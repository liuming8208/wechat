<?php

namespace backend\modules\api\models;

use yii\db\ActiveRecord;

class OrderClick extends ActiveRecord
{
    public static function tableName()
    {
        return "order_click";
    }

    public function rules()
    {
        return [
            [['open_id','task_id','user_id'],'required'],
            ['created_time','default','value'=>date("Y-m-d H:i:s")]
        ];
    }

    /**
     * 获取一条记录
     */
    public function findOneDetail($open_id,$task_id,$user_id)
    {
        return self::findOne(['open_id'=>$open_id,'task_id'=>$task_id,'user_id'=>$user_id]);
    }

}