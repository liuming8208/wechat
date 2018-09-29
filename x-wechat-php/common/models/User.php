<?php
namespace common\models;

use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * 数据表名
     */
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            ['id','integer'],
            [['username','password_hash'],'trim'],
            ['username','unique'],
            ['username','required','message'=>'用户不能为空'],
            [['password_hash'],'required'],
            ['password_hash','required','message'=>'密码不能为空'],
            ['password_hash','filter','filter'=>function($value){
                return md5($value); //加密密码
            }],
            ['status','required','message'=>'状态不能为空'],

            [['name_alias'], 'safe'],
            [['last_login_time','created_time','updated_time'],'default','value'=>date("Y-m-d H:i:s")],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * 删除数据 By id
     */
    public function delOneById($id)
    {
        $data=self::find()->where(['id'=>$id])->one();
        if($data)
        {
            $data->delete();
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds user by $username
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username,'status'=>1]);
    }

    /**
     * 获取登录用户列表
     */
    public function getList($page,$rows)
    {
        $query=self::find()->asArray()->orderBy('last_login_time desc');

        $search=new Search($page,$rows);
        return $search->getList($query);
    }

}
