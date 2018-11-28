<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['home',
                        'welcome',
                        'projects',
                        'new-project',
                        'companies',
                        'new-comment-main',
                        'comments',
                        'contacts',
                        'about',
                        'vendor.admin.*',
                        'userEdit',
                        'specialities.*',
                        'technologies.*',
                        'notecategories.*',
                        'user-notes',
                        'edit-project',
            ], function($view)
        {
            if (Auth::check()) {
                if (isset(Auth::user()->prev_login)) {
                    $prev_login = Auth::user()->prev_login;
                } else {
                    $prev_login = '1970-01-01';
                }
                if (Auth::user()->isAdmin()) {
                    $notes['notif'] = DB::table('notes')
                        ->where('notes.created_at','>',$prev_login)
                        ->whereIn('notes.note_category_id', [1,2,3,4])
                        ->join('notes_category', 'notes.note_category_id', '=', 'notes_category.id')
                        ->select(DB::raw('count(*) as notes_count, max(notes.created_at) as note_last, notes.note_category_id, notes_category.name'))
                        ->groupBy('notes.note_category_id', 'notes_category.name')
                        ->get();

                    $notes['forms'] = DB::table('notes')
                        ->where('notes.created_at','>',$prev_login)
                        ->whereIn('notes.note_category_id', [5,6,7])
                        ->join('notes_category', 'notes.note_category_id', '=', 'notes_category.id')
                        ->select(DB::raw('count(*) as notes_count, notes.note_category_id, notes_category.name'))
                        ->groupBy('notes.note_category_id', 'notes_category.name')
                        ->get();

                    $notes['newProjects'] = DB::table('projects')
                        ->where('created_at','>',$prev_login)
                        ->where('active', true)
                        ->where('owner_id','!=',Auth::user()->id)
                        ->count();
                    $notes['newUsers'] = DB::table('users')
                        ->where('created_at','>',$prev_login)
                        ->where('id','!=',Auth::user()->id)
                        ->count();

                        //dd($notes['forms']);
                    $notes['sumNotif'] = $notes['notif']->sum('notes_count') + $notes['newProjects'] + $notes['newUsers'];
                } else {
                    $notes['notif'] = DB::table('notes')
                        ->where('notes.created_at','>',$prev_login)
                        ->where('notes.to_user_id','=',Auth::user()->id)
                        ->whereIn('notes.note_category_id', [1,2,3,4])
                        ->join('notes_category', 'notes.note_category_id', '=', 'notes_category.id')
                        ->select(DB::raw('count(*) as notes_count, notes.note_category_id, notes_category.name'))
                        ->groupBy('notes.note_category_id', 'notes_category.name')
                        ->get();

                    $notes['forms'] = DB::table('notes')
                        ->where('notes.created_at','>',$prev_login)
                        ->where('notes.to_user_id','=',Auth::user()->id)
                        ->whereIn('notes.note_category_id', [5])
                        ->join('notes_category', 'notes.note_category_id', '=', 'notes_category.id')
                        ->select(DB::raw('count(*) as notes_count, max(notes.created_at) as note_last, notes.note_category_id, notes_category.name'))
                        ->groupBy('notes.note_category_id', 'notes_category.name')
                        ->get();
                    $notes['sumNotif'] = $notes['notif']->sum('notes_count') ;
                }
                //если прошла авторизация
                $notes['userProjects'] = DB::table('projects')
                        ->where('owner_id', Auth::user()->id)
                        ->count();
            } else {
                $notes['notif'] = [];
                $notes['forms'] = [];
                $notes['userProjects'] = null;
            }
            $notes['allProjects'] = DB::table('projects')
                        ->where('active', true)
                        ->count();
            $notes['allUsers'] = DB::table('users')
                ->where('valid', true)
                ->where('confirmed', true)
                ->count();

            $eventsProj = DB::table('projects')
                        ->where('active', true)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get(['owner_id as user_id', 'project_name as name', 'created_at', DB::raw("'добавил проект: ' as title")])
                        ->toArray();
            $eventsUsers = DB::table('users')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get(['id as user_id', 'name', 'created_at', DB::raw("'зарегистрировался в системе ' as title")])
                        ->toArray();
            $eventsComm = DB::table('notes')
                        ->where('active', true)
                        ->whereIn('note_category_id', [5, 6, 7])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get(['from_user_id as user_id', 'description as name', 'created_at', DB::raw("'оставил заметку: ' as title")])
                        ->toArray();
            $res = array_merge($eventsProj, $eventsUsers, $eventsComm);
            $res = array_values(array_sort($res, function ($value) {
                return $value->created_at;
            }));
            $res = array_slice(array_reverse($res),0,10);
            foreach ($res as &$value) {
                $value->logo = \App\Users::find($value->user_id) ? \App\Users::find($value->user_id)->logo : null;
                $value->user_name = \App\Users::find($value->user_id) ? \App\Users::find($value->user_id)->name : null;
                $value->position = rand(0, 1);
            }
       
            $notes['events'] = $res;

            $view->with(['notes' => $notes]);
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
