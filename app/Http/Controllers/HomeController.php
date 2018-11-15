<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Settings;
use Illuminate\Support\Facades\Mail;
use App\Users;


class HomeController extends Controller
{
    use NotesTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::select('how_it_works_1', 'how_it_works_2')->first();
        return view('home', ['settings'=>$settings]);
    }

    public function getContacts()
    {
        $settings = Settings::select('how_contact_us', 'address', 'phone', 'email')->first();
        return view('contacts', ['settings'=>$settings]);
    }

    public function sendFeedback(Request $request)
    {
        $form = $request->all();
        
        $this->validate($request, [
            'person_name' => 'required|min:2|max:150',
            'description' => 'required|min:10',
            'email' => 'required|email'
            ], [
            'person_name.required' => 'Имя обязательно к заполненнию',
            'description.required' => 'Пустое сообщение недопустимо',
            'description.min' => 'Напишите подробнее',
        ]); 
        //$this->feedbackNote($note_name, $description, $from_user_id, $link)
        $from_user_id = Auth::guest() ? null : Auth::user()->id;
        if ($this->feedbackNote('Feedback от '.$form['person_name'], 
                                $form['description'], 
                                $from_user_id, 
                                $form['email'])) 
        {
            $form['user_message'] = explode("\n", $form['description']);
            Mail::send('layouts.feedback', $form, function ($message) use ($form)
            {
                $message->to(env('MAIL_FROM_ADDRESS'))
                        ->subject('Feedback from ' . $form['person_name'])
                        ->replyTo($form['email']);
            });
            return redirect('/contacts')->with(['message' => 'Сообщение успешно отправлено']);
        } else {
            return redirect('/contacts')->with(['message' => 'Не удалось отправить сообщение']);
        }

    }
    /**
     * Set in DB block field for user
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unblockUserByMail($confirmationCode)
    {
        $user = Users::where('confirmation_code', $confirmationCode)->first();
        if ($user) {
            $user->confirmed = true;
            $user->confirmation_code = null;
            $user->save();
            return redirect('/users/editprofile')->with(['message' => 'E-mail подтвержден.']);
        } else {
            return redirect('/home')->with(['message' => 'Ошибочная ссылка для подтверждения e-mail.']);
        }
    }    
}
 