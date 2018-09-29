<?php

namespace common\models;
use yii\db\ActiveRecord;

class Banner extends ActiveRecord
{
    /**
     * 数据表名
     */
    public static function tableName()
    {
        return "banner";
    }

    public function attributeLabels()
    {
        return [
            'title'=>'标题：',
            'sort'=>'排序：'
        ];
    }

    public function rules()
    {
        return [
            ['id','integer'],
            [['title'],'required','message'=>'标题不能为空'],
            [['url'],'required','message'=>'图片不能为空'],
            [['sort'],'number','message'=>'排序需要为数字'],
            [['created_time'],'default','value'=>date("Y-m-d H:i:s")]
        ];
    }

    /**
     * 获取数据列表(全部)
     */
    public function getList($page,$rows)
    {
        $query=self::find()->asArray()->orderBy("id,sort");

        $search=new Search($page,$rows);
        return $search->getList($query);
    }

    /**
     * 通过id获取对应的记录
     */
    public function findOneById($id)
    {
        return self::find()->where(['id'=>$id])->one();
    }

    /**
     * 删除一条数据
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