<?php
//namespace Classes

//use IIlluminate\Hashing\HasherInterface;


class UserHash extends Hash {
    /**
     * Default crypt cost factor.
     *
     * @var int
     */
    protected $rounds = 10;

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
            //$crypt->setPassword($value, 'pbkdf2', 'Th1s=mYcdf3_$@|+', 10000, 32);
            $crypt->setPassword($value);
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
