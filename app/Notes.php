<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table="notes";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'note_name', 'description', 'to_user_id', 'from_user_id', 'note_category_id', 'active', 'link',
    ];

    public function to()
    {
        return $this->belongsTo('App\Users', 'to_user_id', 'id');
    }

	public function from()
    {
        return $this->belongsTo('App\Users', 'from_user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\NotesCategory', 'note_category_id', 'id');
    }
}
