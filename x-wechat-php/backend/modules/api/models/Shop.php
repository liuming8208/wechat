<?php

namespace backend\modules\api\models;

class Shop extends \common\models\Shop
{
     public function getApiList()
     {
         return self::find()->select("id,road")->asArray()->all();
     }

     public function getApiDetailBySort(){
         return self::find()->orderBy("sort asc")->one();
     }

}