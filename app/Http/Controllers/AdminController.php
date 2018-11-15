<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Exception;
use Response;
use App\Users;
use App\UsersHasTechnology;
use App\Comments;
use App\Notes;
use App\Projects;
use App\ProjectsHasTechnology;
use App\Personal;
use App\PersonalHasTechnology;
use File;

class AdminController extends Controller
{
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
        return redirect('home');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUsers(Request $request)
    {
        $this->authorize('admin-control');
        $order = $request->get('order'); 
        $dir = $request->get('dir'); 
        $page_appends = null;
        $searchText = $request->get('searchText');

        $users = Users::whereIn('valid', [0, 1]);

        //добавляем условия поиска, если они заданы
        if (!empty($searchText)) {
            $users = $users
                        ->where('login', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('contact_person', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchText . '%');
        }

        if ($order && $dir) {
            $users = $users->orderBy($order, $dir);
            $page_appends = [
                'order' => $order,
                'dir' => $dir,
            ];
        } else {
            $page_appends = [
                'order' => 'id',
                'dir' => 'desc',
            ];
            $users = $users->orderBy($page_appends['order'], $page_appends['dir']);
        }

        $users = $users->paginate(config('app.users_on_page_admin'))->appends(['searchText' => $searchText]);
        Session::put('page', $users->currentPage());

        $data['users'] = $users;
        $data['dir'] = $dir == 'asc' ? 'desc' : 'asc';
        $data['page_appends'] = $page_appends;
        $data['searchText'] = $searchText;

        return view('vendor.admin.users', ['data' => $data, 'message'=>'']);
    }

     /**
     * Confirm user registration in DB
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirmUser(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            $user = Users::findOrFail($request->input('user_id'));
            $user->confirmed = 1;
            $user->save();
            $data = array( 'text' => 'success' );
        } else {
            $data = array( 'text' => 'fail' );
        }
        return Response::json($data);
    }
    /**
     * Set in DB block field for user
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function blockUser(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            $user = Users::findOrFail($request->input('user_id'));
            //нельзя заблокировать самого себя и юзера с id = 1
            if (!((Auth::user()->id == $user->id) || ($user->id == 1)))  {
                $user->valid = $request->input('action');
                $user->save();
                $data = array( 'text' => 'success' );
            } else {
                $data = array( 'text' => 'fail' . $request->input('action') );
            }
        } else {
            $data = array( 'text' => 'fail' . $request->input('action') );
        }
        return Response::json($data);
    }         
    /**
     * Grant user administrator rights in DB
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminUser(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            $user = Users::findOrFail($request->input('user_id'));
            //нельзя снять права админа с самого себя и юзера с id = 1
            if (!((Auth::user()->id == $user->id) || ($user->id == 1)))  {
                $user->role_id = $request->input('action');
                $user->save();
                $data = array( 'text' => 'success' );
            } else {
                $data = array( 'text' => 'fail' . $request->input('action') );
            }
        } else {
            $data = array( 'text' => 'fail' . $request->input('action') );
        }
        return Response::json($data);
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('admin-control');
        $user = Users::find($id);

        return view('userEdit', ['user'=>$user]);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Delete a user instance with relation records.
     *
     * @param  integer  $id
     * @return string
     */
    public function deleteUser($id)
    {
        $this->authorize('admin-control');
        $user = Users::findOrFail($id);
        if (Auth::check() && (Auth::user()->isAdmin() && $user->id != 1)) {
            $userName = '#'.$user->id.' '.$user->name .' (' . $user->login . ')';
            try {
                $filesToDelete = [];
                DB::beginTransaction();
                UsersHasTechnology::where('user_id', $user->id)->delete();
                Comments::where('user_id', $user->id)->orWhere('company_id', $user->id)->delete();
                Notes::where('to_user_id', $user->id)->orWhere('from_user_id', $user->id)->delete();
                $userProjects = Projects::where('owner_id', $user->id)->get();
                foreach($userProjects as $userProject) {
                    ProjectsHasTechnology::where('project_id', $userProject->id)->delete();
                    $filesToDelete[] = $userProject->doc;
                    $userProject->delete();
                }
                $userPersonals = Personal::where('user_id', $user->id)->get();
                foreach($userPersonals as $userPersonal) {
                    PersonalHasTechnology::where('person_id', $userPersonal->id)->delete();
                    $filesToDelete[] = $userPersonal->resume;
                    $userPersonal->delete();
                }
                $filesToDelete[] = $user->portfolio;
                $filesToDelete[] = $user->logo;
                $user->delete();
                DB::commit();
                foreach ($filesToDelete as $file) {
                    Storage::delete($file);
                }
                if (dirname($user->logo)) {
                    Storage::deleteDirectory(dirname($user->logo));
                }
                if (dirname($user->portfolio)) {
                    Storage::deleteDirectory(dirname($user->portfolio));
                }
                return redirect()->back()->with('message', 'Пользователь '.$userName.' удален');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('message', 'Невозможно удалить пользователя '.$userName);
            }
        } else {
            return redirect()->back()->with('message', 'Недостаточно прав для удаления пользователя');
        }
    }  
}
