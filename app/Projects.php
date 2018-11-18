<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $table="projects";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_name', 'description', 'speciality_id', 'doc', 'start_date', 'finish_date', 'owner_id', 'customer_id', 'active',
    ];

    public function speciality()
    {
        return $this->belongsTo('App\Speciality', 'speciality_id', 'id');
    }

    public function projectTechnologies()
    {
        return $this->belongsToMany('App\Technology', 'projects_has_technology', 'project_id', 'technology_id');
    }

    public function user()
    {
    	return $this->belongsTo('App\Users', 'owner_id', 'id');
    }

    public function projectMarks()
    {
        return $this->hasMany('App\ProjectMark', 'project_id', 'id');
    }
}
