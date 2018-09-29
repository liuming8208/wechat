<?php

namespace backend\modules\api\models;

class Question extends \common\models\Question
{
    public function getApiList()
    {
        return self::find()->orderBy("sort")->asArray()->all();
    }
}