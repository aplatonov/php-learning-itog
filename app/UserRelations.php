<?php

namespace App;

trait UserRelations
{
    public function isAdmin()
    {
        return ($this->role_id == 1 && $this->valid && $this->confirmed) ? true : false;
    }

    public function isValidUser()
    {
        return ($this->role_id != 1 && $this->valid && $this->confirmed) ? true : false;
    }

    public function isUnconfirmedUser()
    {
        return (($this->role_id != 1) && ($this->valid != true || $this->confirmed != true)) ? true : false;
    }
}