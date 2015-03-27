<?php

class Alert extends \Eloquent {
	protected $fillable = [];

    public function organisation()
    {
        return $this->belongsTo('Organisation');
    }

    public function users()
    {
        return $this->belongsToMany('User');
    }
}