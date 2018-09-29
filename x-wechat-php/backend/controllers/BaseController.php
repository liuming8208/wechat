<?php

namespace backend\controllers;
use yii\web\Controller;
use yii;

class BaseController extends Controller
{
    //使用自定义母版视图
    public $layout="iframe";

    //关闭csrf验证 （后面再Todo）
    public $enableCsrfValidation = false;

    public $rows; //行数
    public $page; //页码

    public function init()
    {
        $this->page=Yii::$app->request->post('page',1);
        $this->rows=Yii::$app->request->post('rows',20);
    }

    //默认管理员操作
    public function beforeAction($action)
    {
        if(yii::$app->user->isGuest)
        {
           return $this->goHome();
        }
        return true;
    }

    /**
     * 返回成功json数据
     */
    public function isSuccess($msg,$data=null)
    {
        //$msg里面的消息，使用(models文件夹)MessageType类里面定义的消息
        return ['row'=>$data,'status'=>0,'msg'=>$msg];
    }

    /**
     * 返回失败的消息
     */
    public function isFail($msg)
    {
        //$msg里面的消息，使用(models文件夹)MessageType类里面定义的消息
        return ['row'=>'','status'=>1,'msg'=>$msg];
    }

    /**
     * 获取验证错误信息
     */
    public function getError($model)
    {
        $errors=$model->getErrors();
        if($errors)
        {
            $error=array_shift($errors);
            return "错误：".implode(',',$error);
        }
        return null;
    }

}