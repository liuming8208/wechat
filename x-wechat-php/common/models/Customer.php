<?php

namespace common\models;

use yii\db\ActiveRecord;

class Customer extends ActiveRecord
{
    public static function tableName()
    {
        return "customer";
    }

    public function rules()
    {
        return [
            ['open_id','required'],
            [['nick_name','gender','avatar_url','city','province','country'],'safe'],
            ['created_time','default','value'=>date('Y-m-d H:i:s')]
        ];
    }

    /**
     * 通过Open_id查找，是否存在用户
     */
    public function findOneByOpenId($open_id)
    {
        return self::findOne(['open_id'=>$open_id]);
    }


    /**
     * 获取客户列表
     */
    public function getList($page,$rows,$params)
    {
        $query=self::find()
            ->andFilterWhere(['like','nick_name',$params])
            ->asArray()
            ->orderBy("created_time desc");

        $search=new Search($page,$rows);
        return $search->getList($query);
    }


}