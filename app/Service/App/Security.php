<?php
namespace R\App\Service\App;
use R\Lib\Core\Security as RlibSecurity;

class Security extends RlibSecurity
{
    protected $password_salt = "toadkk";

    /**
     * パスワード用ハッシュ値の検証
     */
    public function passwordHash ($strPassword, $numAlgo = 1, $arrOptions = array())
    {
        return sha1($this->password_salt.$strPassword);
    }
    /**
     * パスワード用ハッシュ値の検証
     */
    public function passwordVerify ($strPassword, $strHash)
    {
        return (bool)($this->passwordHash($strPassword) == $strHash);
    }
}