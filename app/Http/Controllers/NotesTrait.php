<?php

namespace App\Http\Controllers;

use App\Notes;
use App\Personal;
use App\Projects;

trait NotesTrait
{
    public function projectNote($to_user_id, $from_user_id, $projectId)
    {
    	//noteCategory = 4 просмотр проекта
    	if ($to_user_id != $from_user_id) {
	    	$form['note_name'] = str_limit(Projects::find($projectId)->project_name, 30);
	    	$form['to_user_id'] = $to_user_id;
	    	$form['from_user_id'] = $from_user_id;
	    	$form['note_category_id'] = 4;
	    	$form['link'] = '/projects/'.$projectId.'/edit';
	        $note = Notes::create($form);
	    }
	    if (isset($note)) {
	    	return true;
	    } else {
	    	return false;
	    }
    }

    public function companyNote($to_user_id, $from_user_id)
    {
    	//noteCategory = 2 запрос контактов компании
    	if ($to_user_id != $from_user_id) {
	    	$form['note_name'] = '';
	    	$form['to_user_id'] = $to_user_id;
	    	$form['from_user_id'] = $from_user_id;
	    	$form['note_category_id'] = 2;
	        $note = Notes::create($form);
	    }
	    if (isset($note)) {
	    	return true;
	    } else {
	    	return false;
	    }
    }

    public function commentNote($note_name, $description, $to_user_id, $from_user_id)
    {
    	//noteCategory = 5 отзыв о компании, 6 - отзыв о сервисе
    	if ($to_user_id != $from_user_id) {
	    	$form['note_name'] = $note_name;
	    	$form['description'] = $description;
	    	$form['to_user_id'] = $to_user_id;
	    	$form['from_user_id'] = $from_user_id;
	    	if (isset($to_user_id)) {
	    		$form['note_category_id'] = 5;
	    	} else {
	    		$form['note_category_id'] = 6;
	    	}
	        $note = Notes::create($form);
	    }
	    if (isset($note)) {
	    	return true;
	    } else {
	    	return false;
	    }
    }

    public function personalNote($to_user_id, $from_user_id, $personId)
    {
    	//noteCategory = 3 контакты спеца

    }

    public function feedbackNote($note_name, $description, $from_user_id, $link)
    {
    	//noteCategory = 7 отправлена форма обратной связи
    	$form['note_name'] = $note_name;
    	$form['description'] = $description;
    	$form['from_user_id'] = $from_user_id;
    	$form['note_category_id'] = 7;
    	$form['link'] = $link;
        $note = Notes::create($form);
	    if (isset($note)) {
	    	return true;
	    } else {
	    	return false;
	    }
    }
}
