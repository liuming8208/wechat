<?php

namespace backend\modules\api\models;

use yii\db\ActiveRecord;

class AccessToken extends ActiveRecord
{
    public static function tableName()
    {
        return "access_token";
    }

    public function rules()
    {
        return [
            [['token','expire_time'],'required'],
            ['created_time','default','value'=>date('Y-m-d H:i:s')],
            ['type','default','value'=>0]
        ];
    }

    /**
     * 获取没有过期的token
     */
    public function findOneLastRecord()
    {
        return self::find()
                    ->where(['=','type',0])
                    ->andWhere(['>','expire_time',date("Y-m-d H:i:s")])->one();
    }

    /**
     * 获取是否存在的token
     */
    public function findOneByToken($token)
    {
        return self::find()->where(['=','token',$token])
                            ->andWhere(['=','type',0])
                            ->andWhere(['>','expire_time',date("Y-m-d H:i:s")])->one();
    }


}