<?php

namespace backend\modules\api\controllers;

use common\models\Customer;
use yii\web\Controller;
use Yii;


class AuthController extends Controller
{
    public $open_id;
    public $page=1;
    public $rows=10;

    public function init()
    {
       $this->open_id=Yii::$app->request->get("open_id","");
       $this->page=Yii::$app->request->get("page",1);
       $this->rows=Yii::$app->request->get("rows",10);
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

    /**
     * Get请求方法
     */
    public function getRequest($url)
    {
        $curl =curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT,1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    /**
     * Post方法请求
     */
    public function postRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array($return_code, $response);
    }

    /**
     * Post JSON 方法请求
     */
    public function postJsonRequest($url,$data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $info = curl_exec($ch);
        curl_close($ch);
        return $info;
    }

}