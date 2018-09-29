<?php

namespace backend\modules\api\models;

use common\models\Customer;
use common\models\Reward;

class Order extends \common\models\Order
{
    /**
     * 通过task_id and user_id获取对应记录
     */
    public function findOneByTaskId($task_id,$user_id)
    {
        return self::findOne(['task_id'=>$task_id,'user_id'=>$user_id]);
    }

    /**
     * 获取用户未兑换和已经兑换的优惠券
     */
    public function getApiList($open_id,$stauts)
    {
        return self::find()->alias("o")->select("o.id,o.status,o.code,o.code_expire,o.code_url,t.name task_name,r.name reward_name")
                            ->leftJoin(['c'=>Customer::tableName()],'o.user_id=c.id')
                            ->leftJoin(['t'=>Task::tableName()],'o.task_id=t.id')
                            ->leftJoin(['r'=>Reward::tableName()],"t.reward_id=r.id")
                            ->where("ifnull(o.status,0)!=0")
                            ->andWhere(['=',"c.open_id",$open_id])
                            ->andWhere(['=',"o.status",$stauts])
                            ->asArray()
                            ->all();
    }

    /**
     * 获取最近抽奖名单数
     */
    public function getApiLuckList($open_id)
    {
        return self::find()->alias("o")->select("c.nick_name,t.name task_name")
                            ->leftJoin(['t'=>Task::tableName()],'o.task_id=t.id')
                            ->leftJoin(['c'=>Customer::tableName()],"o.user_id=c.id")
                            ->where("t.type=1")
                            ->andFilterWhere(['=','c.open_id',$open_id])
                            ->asArray()
                            ->orderBy("o.id desc")
                            ->limit(5)
                            ->all();
    }

}