<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use UserRelations;

    protected $table="users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'email', 'password', 'name', 'contact_person', 'phone', 'tarif_id', 'pay_till', 'role_id', 'valid', 'confirmed', 'confirmation_code', 'portfolio', 'logo', 'www', 'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function projects()
    {
        return $this->hasMany('App\Projects', 'owner_id', 'id');
    }

    public function personal()
    {
        return $this->hasMany('App\Personal', 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comments', 'company_id', 'id');
    }
}
