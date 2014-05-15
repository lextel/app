<?php

class Awardlog extends \Eloquent {

    // Add your validation rules here
    //status 定义
    //6 结算完成
    public static $rules = [
        // 'title' => 'required'
    ];
    protected $table = 'awardlog';
    // Don't forget to fill this array
    protected $fillable = ['id', 'app_id', 'title', 'package', 'award', 'status', 'member_id', 'username', 'imei'];
    //
    protected function getDateFormat(){
        return time();
    }
}
