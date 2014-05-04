<?php

class Applog extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = ['id', 'app_id', 'title', 'package', 'award', 'status', 'member_id', 'imei', ];

}