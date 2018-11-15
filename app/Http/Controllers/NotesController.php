<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notes;
use App\NotesCategory;
use App\Personal;
use App\Projects;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
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

    public function showNotes(Request $request)
    {
        $this->authorize('admin-control');
        $order = $request->get('order'); 
        $dir = $request->get('dir'); 
        $page_appends = null;
        $searchText = $request->get('searchText');

        $notes = Notes::whereIn('active', [0, 1]);
            
        if ($searchText>0) {
            $notes = $notes
                ->where('note_category_id', $searchText);
        }

        if ($order && $dir) {
            $notes = $notes->orderBy($order, $dir);
            $page_appends = [
                'order' => $order,
                'dir' => $dir,
            ];
        } else {
            $page_appends = [
                'order' => 'created_at',
                'dir' => 'desc',
            ];
            $notes = $notes->orderBy($page_appends['order'], $page_appends['dir']);
        }

        $notes = $notes->paginate(50)->appends(['searchText' => $searchText]);
        $notesCategory = NotesCategory::all();

        $data['notes'] = $notes;
        $data['dir'] = $dir == 'asc' ? 'desc' : 'asc';
        $data['page_appends'] = $page_appends;
        $data['searchText'] = $searchText;
        $data['notesCategory'] = $notesCategory;

        $user=Auth::user();
        if ($user) {
            $user->prev_login = $user->last_login;
            $user->last_login = \Carbon\Carbon::now();
            $user->save();
        }

        return view('vendor.admin.notes', ['data' => $data]);
    }

    public function destroyNote($id)
    {
        $this->authorize('user-valid');
        $note = Notes::findOrFail($id);
        $backPath = Auth::user()->isAdmin() ? '/admin/notes' : '/userNotes';
        if (Auth::user()->isAdmin() || Auth::user()->id == $note->to_user_id) {     
            $noteName = $note->note_name;
            try {
                $note->delete();
                return redirect($backPath)->with('message', 'Заметка '.$noteName.' удалена');
            } catch (Exception $e) {
                return redirect($backPath)->with('message', 'Невозможно удалить заметку '.$noteName);
            }
        } else {
            return redirect($backPath)->with('message', 'Недостаточно прав для удаления заметки');
        }
    }

    public function showUserNotes(Request $request)
    {
        $this->authorize('user-unconfirmed');
        $order = $request->get('order'); 
        $dir = $request->get('dir'); 
        $page_appends = null;
        $searchText = $request->get('searchText');

        $notes = Notes::whereIn('active', [1])
                        ->where('to_user_id', Auth::user()->id);
        if(Auth::user()->isAdmin()) {
            $notes = $notes->orWhere('to_user_id', null);
        }
            
        if ($searchText>0) {
            $notes = $notes
                ->where('note_category_id', $searchText);
        }

        if ($order && $dir) {
            $notes = $notes->orderBy($order, $dir);
            $page_appends = [
                'order' => $order,
                'dir' => $dir,
            ];
        } else {
            $page_appends = [
                'order' => 'created_at',
                'dir' => 'desc',
            ];
            $notes = $notes->orderBy($page_appends['order'], $page_appends['dir']);
        }

        $notes = $notes->paginate(50)->appends(['searchText' => $searchText]);
        $notesCategory = NotesCategory::all();

        $data['notes'] = $notes;
        $data['dir'] = $dir == 'asc' ? 'desc' : 'asc';
        $data['page_appends'] = $page_appends;
        $data['searchText'] = $searchText;
        $data['notesCategory'] = $notesCategory;

        $user=Auth::user();
        if ($user) {
            $user->prev_login = $user->last_login;
            $user->last_login = \Carbon\Carbon::now();
            $user->save();
        }

        return view('user-notes', ['data' => $data]);
    }
}
