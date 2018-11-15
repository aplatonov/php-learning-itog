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
use Exception;
use Response;
use App\Comments;
use App\Users;

class CommentsController extends Controller
{
    use NotesTrait;

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function showComments(Request $request)
    {
        $this->authorize('admin-control');
        $order = $request->get('order'); 
        $dir = $request->get('dir'); 
        $page_appends = null;
        $searchText = $request->get('searchText');

        $comments = Comments::whereIn('active', [0, 1]);

        //добавляем условия поиска, если они заданы
        if (!empty($searchText)) {
            $comments = $comments
                        ->where('description', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('author_name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('author_position', 'LIKE', '%' . $searchText . '%');
        }

        if ($order && $dir) {
            $comments = $comments->orderBy($order, $dir);
            $page_appends = [
                'order' => $order,
                'dir' => $dir,
            ];
        } else {
            $page_appends = [
                'order' => 'created_at',
                'dir' => 'desc',
            ];
            $comments = $comments->orderBy($page_appends['order'], $page_appends['dir']);
        }

        $comments = $comments->paginate(config('app.objects_on_page_admin'))->appends(['searchText' => $searchText]);
        Session::put('page', $comments->currentPage());

        $data['comments'] = $comments;
        $data['dir'] = $dir == 'asc' ? 'desc' : 'asc';
        $data['page_appends'] = $page_appends;
        $data['searchText'] = $searchText;

        return view('vendor.admin.comments', ['data' => $data]);
    }

	public function blockComment(Request $request)
    {
        $this->authorize('admin-control');
    	$comment = Comments::findOrFail($request->input('comment_id'));
        if (Auth::user()->isAdmin()) {
            $comment->active = $request->input('action');
            $comment->save();
            $data = array( 'text' => 'success' );
        } else {
            $data = array( 'text' => 'fail' . $request->input('action') );
        }
        return Response::json($data);
    }

    public function destroyComment($id)
    {
        $this->authorize('admin-control');
        if (Auth::user()->isAdmin()) {
	        $comment = Comments::findOrFail($id);
            $commentName = $comment->note_name;
            try {
                $comment->delete();
                return redirect()->back()->with('message', 'Комментарий '.$commentName.' удален');
            } catch (Exception $e) {
                return redirect()->back()->with('message', 'Невозможно удалить комментарий '.$commentName);
	            }
        } else {
            return redirect()->back()->with('message', 'Недостаточно прав для удаления комментария');
        }
    }

    public function addComment()
    {
        $this->authorize('user-unconfirmed');
        $companies = Users::where('confirmed', true)
            ->get();

        session(['fromPage' => \URL::previous()]);

        //dd($users);
        return view('vendor.admin.new-comment')->with([
                'companies' => $companies
                ]);
    }

    public function storeComment(Request $request)
    {
        $this->authorize('user-unconfirmed');
        $form = $request->all();

        $this->validate($request, [
            'author_name' => 'required|max:50',
            'author_position' => 'max:50',
            'description' => 'required|max:1000'
        ]); 
        
        //dd($form);

        $form['active'] = isset($form['active']) ? 1 : 0;
        $form['visible_on_main'] = isset($form['visible_on_main']) ? 1 : 0;
        $form['company_id'] = !isset($form['company_id']) || $form['company_id']== 0 ? null : $form['company_id'];

        if ($form['isUpdate'] == 1) {
        	$comment = Comments::find($form['comment_id']);
            $comment->update($form);
        } else {
            $comment = Comments::create($form);
            $this->commentNote($comment->author_position . ' ' . $comment->author_name,
                $comment->description,
                $comment->company_id,
                $comment->user_id);
        }

        if ($form['isUpdate'] == 1) {
            return redirect(session('fromPage'))->with(['message' => 'Отзыв от '.$comment->author_name.' обновлен']);
        } else {
            return redirect(session('fromPage'))->with(['message' => 'Отзыв от '.$comment->author_name.' добавлен. Он ожидает проверки администратора.']);
        }
    }

    public function edit($id)
    {   
        $this->authorize('admin-control');
        $comment = Comments::findOrFail($id);
        $companies = Users::where('valid', true)
            ->get();

        session(['fromPage' => \URL::previous()]);
        if (Auth::user()->isAdmin()) {
            return view('vendor.admin.edit-comment')->with([
                'comment' => $comment,
                'companies' => $companies
            ]);
        } else  {
            return redirect()->back()->with('message', 'Недостаточно прав для редактирования комментария');
        }
    }

    public function showCommentsMain(Request $request)
    {
        $searchText = $request->get('searchText');

        $comments = Comments::where('active', true)
        	->where('visible_on_main', true);

        //добавляем условия поиска, если они заданы
        if (!empty($searchText)) {
            $comments = $comments
                        ->where('description', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('author_name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('author_position', 'LIKE', '%' . $searchText . '%');
        }
		$comments = $comments->orderBy('created_at', 'desc');

        $comments = $comments->paginate(config('app.objects_on_page_admin'))->appends(['searchText' => $searchText]);
        
        $data['comments'] = $comments;
        $data['searchText'] = $searchText;

        return view('comments', ['data' => $data]);
    }

    public function addCommentMain(Request $request)
    {
        $form = $request->all();
        if (isset($form['company_id'])) {
            $form['company_name'] = Users::find($form['company_id'])->name;
        }
        session(['fromPage' => \URL::previous()]);

        //dd($users);
        return view('new-comment-main', ['form' => $form]);
    }
    
}
