<?php

class Organisation extends \Eloquent {
	protected $fillable = [];

    public function users()
    {
        return $this->hasMany('User');
    }

    public function alerts()
    {
        return $this->hasMany('Alert');
    }
}