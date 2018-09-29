<?php

namespace common\models;

use yii\db\ActiveRecord;

class Question extends ActiveRecord
{
    public static function tableName()
    {
        return "question";
    }

    public function rules()
    {
        return [
            ['question','required','message'=>"问，不能为空"],
            ['answer','required','message'=>"答，不能为空"],
            ['question','unique','message'=>"问，只能有一个"],
            ['sort','safe']
        ];
    }

    public function getList($page,$rows)
    {
        $query=self::find()->orderBy("sort")->asArray();

        $search=new Search($page,$rows);
        return $search->getList($query);
    }

    public function delOneById($id){

        $data=self::find()->where(['id'=>$id])->one();
        if($data)
        {
            $data->delete();
            return true;
        }
        return false;
    }

}