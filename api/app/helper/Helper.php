<?php

class Helper{
    /*
    * url 补全
    */
    public static function urlPro($url, $type='link')
    {
        //判断是否带http, https
        preg_match("/^(http[s]*:\/\/)?/i", $url, $matches);
        if ($matches[0]){
            return $url;
        }
        //读取配置
        switch ($type){
            case 'link':
                $host = Config::get('common.apkHost', '');
                break;
            case 'img':
                $host = Config::get('common.imgHost', '');
                break;
            default:
                $host = '';
                break;
        }
        $url = $host.$url;
        return $url;
    }
}

?>
