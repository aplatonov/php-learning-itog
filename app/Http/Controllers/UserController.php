<?php

namespace App\Http\Controllers;

use App\Users;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\NotReadableException;
use Illuminate\Support\Facades\Session;
use Image;
use App\Technology;
use App\UsersHasTechnology;
use Response;


class UserController extends Controller
{
    use NotesTrait;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function showCompanies(Request $request)
    {
        $this->authorize('user-unconfirmed');
        $searchText = $request->get('searchText');

        $companies = Users::where('valid', 1)
            ->where('confirmed', 1);
            

        if (!empty($searchText)) {
            $companies = $companies
                ->where('name', 'LIKE', '%' . $searchText . '%')
                ->orWhere('description', 'LIKE', '%' . $searchText . '%');
        }

        $companies = $companies->paginate(config('app.objects_on_page'))->appends(['searchText' => $searchText]);

        $data['companies'] = $companies;
        $data['searchText'] = $searchText;

        return view('companies', ['data' => $data]);
    }

    public function showCompanyInfo(Request $request)
    {
        if (Auth::user()->confirmed == 1 && Auth::user()->valid == 1) {
            $company = Users::findOrFail($request->input('company_id'));
            $company_info = '<small>' . $company->contact_person . '<br>' . $company->phone . '<br><a href="mailto:' . $company->email . '">'.$company->email.'</a>' . '<br><a href="//' . $company->www . '" target="_blank">' . $company->www . '</a><br>'.
                '<a href="' . '/users/edit/'.$company->id.'">' . 'Карточка компании' . '</a><small>';
            $data = array( 'text' => 'success', 'company_info' => $company_info);
            $this->companyNote($request->input('company_id'), Auth::user()->id);
        } else {
            $data = array( 'text' => 'fail' . $request->input('action') );
        }
        return Response::json($data);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id = null, Request $request)
    {
        $this->authorize('user-unconfirmed');
        //dd ($user_id);
        if (isset($user_id)) {
            $user = Users::findOrFail($user_id);
        } else {
            $user = Users::find(Auth::user()->id);
        }
        $technologies = Technology::where('active', true)->get();

        return view('userEdit', ['user'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \$user_id
     * @return \Illuminate\Http\Response
     */
    public function update($user_id, Request $request)
    {
        $this->authorize('user-unconfirmed');
        $user = Users::find($user_id);
        $userlogin = $user->login;
        $oldPortfolio = $user->portfolio;
        $oldLogo = $user->logo;

        $form = $request->all();
        $this->validate($request, [
            'email' => 'required|email|max:120',
            'name' => 'required|min:2|max:190',
            'contact_person' => 'required|min:2|max:80',
            'phone' => 'required|max:60',
            'www' => 'max:150|nullable',
            'portfolio' => 'file|max:1000|mimes:pdf,doc,docx,rtf',
            'logo' => 'file|max:500|mimes:jpg,jpeg,png,gif'
        ]);

        $form['valid'] = isset($form['valid']) ? 1 : 0;
        $form['confirmed'] = isset($form['confirmed']) ? 1 : 0;
        $path = $oldLogo;
        if ($request->file('logo')) {
            try {
                $file = $request->file('logo');
                //картинка для логотипа
                Image::make($file->getRealPath())->fit(220, 220)->save();
                $path = $file->store('userdata'.DIRECTORY_SEPARATOR.$user_id, 'public');
                Storage::delete($oldLogo);
            } catch (Exception $e) {
                //возврат с сообщ об ошибке
            }
        }
        $form['logo'] = $path;

        $path = $oldPortfolio;
        if ($request->file('portfolio')) {
            try {
                $path = $request->file('portfolio')->store('userdata'.DIRECTORY_SEPARATOR.$user_id, 'public');
                Storage::delete($oldPortfolio);
            } catch (Exception $e) {
                //возврат с сообщ об ошибке
            }
        }
        $form['portfolio'] = $path; 

        $user->update($form);

        if (Auth::user()->isAdmin()) {
            return redirect('/admin/users?page=' . Session::get('page',1))->with('message','Данные пользователя ' . $userlogin . ' обновлены успешно.');
        }
        else
        {
            return redirect('home')->with('message','Ваши данные обновлены успешно.');       
        }
        //dd($form);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
