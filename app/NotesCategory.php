<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotesCategory extends Model
{
    protected $table="notes_category";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'active',
    ];
}
