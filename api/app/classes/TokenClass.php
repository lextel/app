<?php
//namespace Classes

//use IIlluminate\Hashing\HasherInterface;
//TOKEN的操作
class TokenClass {
    
    //token check检测
    public function check($token=0){
        $apptoken = Apptoken::where('token', '=', $token)->first();
        if (empty($apptoken)) return false;
        $memberInfo = Member::where('id', '=', $apptoken->member_id)->first();
        return $memberInfo;
    }
    //生成新的TOKEN
    public function create($user){
        $raw = $user->username.$user->password;
        $token = $this->code($raw);
        //
        Apptoken::firstOrCreate([
            'member_id' => $user->id,
            'token' => $token,
        ]);
        return $token;
    }    
    //删除TOKEN
    public function delete($token){
        Apptoken::where('token', '=', $token)->delete();
        return true;
    }
    //TOKEN加密方式
    protected function code($input){
        $code = md5($input);
        return $code;
    }
}
