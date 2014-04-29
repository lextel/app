<?php


class Member extends Eloquent  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'members';

    protected $fillable = ['id', 'username', 'password', 'nickname', 
                            'bio', 'points', 'email', 'mobile', 'created_at', 
                            'updated_at'];

    public $timestamps = false;
    
    
    protected $messages = ['username.required' => '用户名是必须的',                                            
                            'username.unique' => '您注册的用户名已经存在',
                            'password.required' => '用户密码是必须的', 
                            'password.min' => '密码最小6位长度',
                            'password.max' => '密码最大20位长度',
                            'nickname.required' => '用户昵称是必须的',
                            'nickname.unique' => '您注册的昵称已经存在',
                            'nickname.min' => '昵称最小2位长度',
                            'nickname.max' => '昵称最大8位长度',
                            ];

    /**
     * 获取用户头像 姓名 IP
     *
     * @param $ids array ID数组
     *
     * @return array
     */
    public function byIds($ids) {
        $ids = array_unique($ids);
        $ids[] = 0;
        $members = Member::whereRaw('id in (' . implode(',', $ids) . ')')
                           ->get(['id', 'avatar', 'nickname', 'ip'])
                           ->toArray();

        $data = [];
        foreach($members as $member) {
            $data[$member['id']] = $member;
        }

        return $data;
    }
    
    //检测登录字段
    public function validateSignIn($username, $password) {
        return Validator::make(
        [
            'username' => $username,
            'password' => $password,
        ],[
            'username' => 'required',
            'password' => 'required|min:6|max:20',
        ], $this->messages);        
    }
   
    //检测注册字段
    public function validateSignUp($username, $password, $nickname) {
        return Validator::make(
        [
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname,
        ],[
            'username' => 'required|unique:members',
            'password' => 'required|min:6|max:20',
            'nickname' => 'required|min:2|max:8|unique:members',
        ], $this->messages);
    }
    
    public function setPassword($password) {
        $userhash = new UserHash();
        return $userhash->hashmake($password, ['method'=>'pbkdf2']);
    }
    
    /*
    * @desc:添加新用户
    * @param: $username string 用户名
    * @param: $password string 用户密码
    * @param: $nickname string 用户昵称
    * @return void
    */
    public function createMember($username, $password, $nickname){
        //email 判断
        Member::create([
          'username' => $username,
          'password' => $this->setPassword($password),   // 生成密码.
          'email' => $username,
          'nickname' => $nickname,
          'created_at' => time(),
          'updated_at' => time(),
        ]);
        
        //手机判断
        
        return ;
    }
    
    
}
