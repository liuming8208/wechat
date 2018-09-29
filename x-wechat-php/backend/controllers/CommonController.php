<?php

namespace backend\controllers;
use common\models\Result;
use common\models\Reward;
use common\models\UploadForm;
use yii;
use yii\web\UploadedFile;
use common\models\MessageType;

/**
 * 通用方法
 */
class CommonController extends BaseController
{

    /**
     * 获取奖励
     */
    public function actionGetReward()
    {
        $model=new Reward();
        $data=$model->forCheckBox();
        if($data){
            return $this->asJson($data);
        }
        return null;
    }

    /**
     * 图片上传
     */
    public function actionUploadImg()
    {
        $upload=new UploadForm();
        $upload->file_name= UploadedFile::getInstance($upload, 'file_name');
        $url=$upload->uploadImg();

        if($url){
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS,"/".$url));
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

}