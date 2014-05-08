<?php

class Apptoken extends \Eloquent {

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];
    protected $table = 'apptokens';
    // Don't forget to fill this array
    protected $fillable = ['id', 'member_id', 'token'];
    //
    protected function getDateFormat(){
        return time();
    }
}
