<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectsHasTechnology extends Model
{
    protected $table="projects_has_technology";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'technology_id',
    ];
}
