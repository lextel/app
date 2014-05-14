<?php

class Helper{
    /*
    * url 补全
    */
    public static function urlPro($url, $type = 'link')
    {
        //判断是否带http, https
        if (preg_match("/^http[s]*:\/\/[\w_]+\.[\w]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/", $url)){
            return $url;
        }
        //读取配置
        switch ($type){
            case 'link':
                $host = Config::get('common.urlHost', '');
            case 'img':
                $host = Config::get('common.imgHost', '');
            default:
                $host = '';
        }
        $url = $host + $url;
        return $url;
    }
}

?>
