<?php

namespace backend\modules\api\models;

class Banner extends \common\models\Banner
{
    public function getApiList()
    {
        return self::find()->select("url")->asArray()->orderBy("sort asc")->all();
    }
}