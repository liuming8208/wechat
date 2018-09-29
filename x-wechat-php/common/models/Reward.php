<?php

namespace common\models;
use yii\db\ActiveRecord;

class Reward extends ActiveRecord
{
    /**
     * 数据表名
     */
    public static function tableName()
    {
        return "reward";
    }

    public function rules()
    {
        return [
            ['id','integer'],
            [['name'],'required','message'=>'名称不能为空。'],
            [['created_time'],'default','value'=>date("Y-m-d H:i:s")]
        ];
    }

    /**
     * 获取所有奖励
     */
    public function getList($page,$rows)
    {
        $query=self::find()->asArray()->orderBy("id desc");

        $search=new Search($page,$rows);
        return $search->getList($query);
    }

    /**
     * 获取对应记录 By id
     */
    public function findOneById($id)
    {
        return self::find()->where(['id'=>$id])->one();
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
     * 下拉框数据
     */
    public function forCheckBox()
    {
        return self::find()->select("id,name")->asArray()->all();
    }

}