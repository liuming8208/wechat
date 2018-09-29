<?php

namespace common\models;
use yii\db\ActiveRecord;

class Shop extends ActiveRecord
{
    /**
     * 数据表名
     */
    public static function tableName()
    {
        return "shop";
    }

    public function rules()
    {
        return [
            ['id','integer'],
            [['name'],'required','message'=>'门店名称不能为空'],
            [['road'],'required','message'=>'门店所在路口不能为空'],
            [['address'],'required','message'=>'门店位置不能为空'],
            [['longitude'],'required','message'=>'经度不能为空'],
            [['latitude'],'required','message'=>'纬度不能为空'],
            [['url'],'required','message'=>'门店图片不能为空'],
            [['phone','introduce','company','sort'],'safe'],
            [['created_time'],'default','value'=>date("Y-m-d H:i:s")]
        ];
    }

    /**
     * 获取所有店铺
     */
    public function getList($page,$rows)
    {
        $query=self::find()->asArray()->orderBy('sort asc');

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