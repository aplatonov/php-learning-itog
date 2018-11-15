<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table="settings";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'settings_name', 'how_it_works_1', 'how_it_works_2', 'how_contact_us', 'address', 'phone', 'email', 'carousel_pics'
    ];
}
