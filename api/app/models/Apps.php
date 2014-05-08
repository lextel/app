<?php

class Apps extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];
    protected $table = 'apps';
    // Don't forget to fill this array
     protected $fillable = ['id', 'package', 'title', 'icon', 'award', 'size', 'images', 'summary', 'link'];
     //
     protected function getDateFormat(){
        return time();
    }
}