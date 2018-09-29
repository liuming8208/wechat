<?php
namespace common\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;

    private $_user;

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
              [ ['username', 'password','verifyCode'], 'required'],
                ['rememberMe', 'boolean'],
                ['password', 'validatePassword'],
                ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'verifyCode'=>'验证码',
            'rememberMe'=>'记住登录名和密码',
        ];
    }

    /**
     * 验证密码
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || $user->password_hash !==md5($this->password)) {
                $error=$this->attributeLabels()['username']."或".$this->attributeLabels()['password'].'错误，登录失败';
                $this->addError($attribute,$error);
            }
        }
    }

    /**
     * 登录验证
     */
    public function login()
    {
        if ($this->validate(['username','password'])) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 3 : 0);
        }
        return false;
    }

    /**
     * 获取用户
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username,'');
        }
        return $this->_user;
    }
}
