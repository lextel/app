<?php

class Applog extends \Eloquent {

    // Add your validation rules here
    //status 定义
    //0 要下载
    //1 下载中
    //2 下载OK
    //3 安装OK
    //4 运行OK
    //5 记录奖励OK
    //6 结算完成
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
