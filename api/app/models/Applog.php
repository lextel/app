<?php

class Applog extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];
    protected $table = 'applogs';
    // Don't forget to fill this array
    protected $fillable = ['id', 'app_id', 'title', 'package', 'award', 'status', 'member_id', 'username', 'imei'];
    //
    protected function getDateFormat(){
        return time();
    }
}