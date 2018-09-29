<?php

namespace backend\modules\api\models;

use common\models\Customer;
use common\models\Order;
use common\models\Reward;

class Task extends \common\models\Task
{
    public function getApiList($open_id)
    {
        $order=Order::find()->alias("o")->select("o.id order_id,o.task_id,o.click_number,o.status,o.code,o.code_expire")
             ->leftJoin(['c'=>Customer::tableName()],"c.id=o.user_id")
             ->where(['=',"c.open_id",$open_id])
             ->asArray()
             ->orderBy("o.id desc")
             ->all();

        $task=Task::find()->alias("t")->select("t.id,t.name task_name,t.count,r.name reward_name")
             ->leftJoin(['r'=>Reward::tableName()],'t.reward_id=r.id')
            ->where("t.type=0")
            ->asArray()
            ->all();

        return $this->arrayMerge($task,$order);
    }

    /**
     * 把订单合并到任务列表上
     */
    private function arrayMerge($task,$order)
    {
        if(!$task) {  return null; }
        $arr=[];
        foreach ($task as $key=>$value)
        {
            $_arr['task_id']=$value['id'];
            $_arr['task_name']=$value['task_name'];
            $_arr['count']=$value['count'];
            $_arr['reward_name']=$value['reward_name'];

            $_arr['order_id']="";
            $_arr['click_number']=0;
            $_arr['status']=0;
            $_arr['code']="";
            $_arr['code_expire']="";
            if($order){
                foreach ($order as $k=>$v)
                {
                    if($value['id']==$v["task_id"])
                    {
                        $_arr['order_id']=$v["order_id"];
                        $_arr['click_number']=$v["click_number"];
                        $_arr['status']=$v["status"];;
                        $_arr['code']=$v["code"];;
                        $_arr['code_expire']=$v["code_expire"];;
                    }
                }
            }
            $arr[]=$_arr;
        }
        return $arr;
    }

    /**
     * 获取type=1的所有数据
     */
    public function getApiTypeIsOne()
    {
        return self::find()->where("type=1")->asArray()->orderBy("sort asc")->all();
    }

    /**
     * 获取任务
     */
    public function getApiTaskNameByName($name)
    {
        return self::findOne(['name'=>$name]);
    }

}