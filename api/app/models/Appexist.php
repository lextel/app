<?php

class Appexist extends \Eloquent {
    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];
    protected $table = 'appexists';
    // Don't forget to fill this array
    protected $fillable = ['id', 'imei', 'package'];
    //
    protected function getDateFormat(){
        return time();
    }
}