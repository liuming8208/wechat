<?php

namespace common\models;
use yii\db\ActiveRecord;

class Task extends ActiveRecord
{
    /**
     * 数据表名
     */
    public static function tableName()
    {
        return "task";
    }

    public function rules()
    {
        return [
            ['id','integer'],
            [['name'],'required','message'=>'名称不能为空'],
            [['count'],'number','message'=>'点赞次数只能为数字'],
            [['rate'],'number','message'=>'中奖概率只能为数字'],
            [['sort'],'number','message'=>'排序只能为数字'],

            [['reward_id'],'required','message'=>'奖励不能为空'],
            [['type'],'required','message'=>'任务类型不能为空'],

            [['created_time'],'default','value'=>date("Y-m-d H:i:s")]
        ];
    }

    /**
     * 获取热门任务
     */
    public function getList($page,$rows,$type=null)
    {
        $query=self::find()->alias('t')
            ->select('t.id,t.name,t.count,t.rate,t.type,t.created_time,r.name reward_name')
            ->leftJoin(['r'=>Reward::tableName()],'t.reward_id = r.id')
            ->andFilterWhere(['=','t.type',$type])
            ->asArray()
            ->orderBy('t.type,t.sort asc');

        $search=new Search($page,$rows);
        return $search->getList($query);
    }

    /**
     * 获取对应记录 By id
     */
    public function findOneById($id)
    {
        return self::findOne(['id'=>$id]);
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

}