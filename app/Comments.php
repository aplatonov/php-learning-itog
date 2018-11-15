<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table="comments";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'user_id', 'company_id', 'author_name', 'author_position', 'active', 'visible_on_main', 'raiting',
    ];

    public function owner()
    {
        return $this->belongsTo('App\Users', 'user_id', 'id');
    }

    public function aboutUser()
    {
        return $this->belongsTo('App\Users', 'company_id', 'id');
    }
}
