<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectMark extends Model
{
    protected $table="project_mark";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'name', 'finish_date', 'is_done',
    ];

    public function project()
    {
        return $this->belongsTo('App\Projects', 'project_id', 'id');
    }
}
