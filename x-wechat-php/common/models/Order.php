<?php

namespace common\models;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    /**
     * 数据表名
     */
    public static function tableName()
    {
        return "cosmetology.order";
    }

    public function rules()
    {
        return [
            [['task_id','user_id'],'integer'],
            [['code','code_expire','code_url'],'safe'],
            ['click_number','default','value'=>0],
            ['status','default','value'=>0],
            ['created_time','default','value'=>date("Y-m-d H:i:s")],
        ];
    }

   /**
     * 通过code查找记录
     */
   public function findOneByCode($code)
   {
       if($code){
          return self::findOne(['code'=>$code]);
       }
       return null;
   }

    /**
     * 通过id查找记录
     */
    public function findOneById($id)
    {
        if($id){
            return self::findOne(['id'=>$id]);
        }
        return null;
    }

    /**
     * 获取订单(生成优惠码)列表
     */
    public function getList($page,$rows,$params)
    {
        $query=self::find()->alias("o")
            ->select("o.id,o.click_number,o.status,o.code,o.code_expire,o.code_url,o.created_time,t.name task_name,t.count,c.nick_name,c.gender")
            ->leftJoin(['t'=>Task::tableName()],"o.task_id=t.id")
            ->leftJoin(['c'=>Customer::tableName()],'o.user_id=c.id')
            ->andFilterWhere(['like','c.nick_name',$params['nick_name']])
            ->andFilterWhere(['like','o.code',$params['code']])
            ->asArray()
            ->orderBy("o.created_time desc");

        $search=new Search($page,$rows);
        return $search->getList($query);
    }



}