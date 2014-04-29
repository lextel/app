<?php
//namespace Classes

//use IIlluminate\Hashing\HasherInterface;

//用户密码加密格式
class UserHash extends Hash {
    /**
     * Default crypt cost factor.
     *
     * @var int
     */
    protected $rounds = 10;
    protected $len = 32;
    protected $salt = 'Th1s=mYcdf3_$@|+';
    protected $count = 10000;
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     *
     * @throws \RuntimeException
     */
    public function hashmake($value, array $options = array())
    {
        $cost = isset($options['rounds']) ? $options['rounds'] : $this->rounds;

        $method = isset($options['method']) ? $options['method'] : '';
        if ($method == 'pbkdf2' ){
            $crypt = new \Crypt_Base();
            $this->salt = Config::get('auth.salt', $this->salt);
            $crypt->setPassword($value, $method, 'sha256', $this->salt, $this->count, $this->len);
            $hash = base64_encode($crypt->key);
        }else{
            $hash = password_hash($value, PASSWORD_BCRYPT, array('cost' => $cost));
        }

        if ($hash === false)
        {
            throw new \RuntimeException("Bcrypt hashing not supported.");
        }

        return $hash;
    }

}
