<?php

namespace backend\modules\api\controllers;

use backend\modules\api\models\AccessToken;
use backend\modules\api\models\Banner;
use backend\modules\api\models\Order;
use backend\modules\api\models\OrderClick;
use backend\modules\api\models\Question;
use backend\modules\api\models\Shop;
use backend\modules\api\models\Task;
use common\models\Customer;
use common\models\MessageType;
use Yii;

class ApiController extends AuthController
{
    public function actionTest()
    {
        echo "open_id=".$this->open_id."，time=".time();
    }

    /**
     * 微信用户信息
     */
    public function actionOpenId()
    {
        $code=Yii::$app->request->get('code','');
        if($code)
        {
            $app_id=Yii::$app->params['appId'];
            $app_secret=Yii::$app->params['appSecret'];
            $request_url="https://api.weixin.qq.com/sns/jscode2session?appid=".$app_id."&secret=".$app_secret."&js_code=".$code."&grant_type=authorization_code";

            $info=$this->getRequest($request_url);
            $info=json_decode($info,true);
            if($info && $info['openid'])
            {
                //查询open_id是否存在数据中(没有就添加)
                $model=new Customer();
                $data=$model->findOneByOpenId($info['openid']);
                if(!$data)
                {
                    $model->open_id=$info['openid'];
                    $model->save();
                }
            }
            return $this->asJson($info['openid']);
        }
    }

    /**
     * 添加客户信息
     */
    public function actionAddCustomer()
    {
        $model=new Customer();

        if($model->load(Yii::$app->request->get(),''))
        {
            $open_id=$model->attributes['open_id'];
            if($open_id)
            {
                $_model=clone $model;
                $data=$_model->findOneByOpenId($open_id);
                if($data && $data->load(Yii::$app->request->get(),''))
                {
                    $data->save();
                    return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
                }
            }
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS),$model);
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));

    }

    /**
     * (首页)广告列表
     */
    public function actionBanner()
    {
        $model=new Banner();
        $data=$model->getApiList();
        return $this->asJson($data);
    }

    /**
     * (首页)任务订单列表
     */
    public function actionTaskOrder()
    {
        $model=new Task();
        $data=$model->getApiList($this->open_id);
        return $this->asJson($data);
    }

    /**
     * 添加分享任务(领取任务)
     */
    public function actionAddShareTask()
    {
        $data=$this->vailShareTask();
        if(!$data['data'])
        {
            $model=new Order();
            $model->task_id=$data['task_id'];
            $model->user_id=$data['customer']['id'];
            $model->save();
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 获取分享任务(点赞页面)
     */
    public function actionGetShareTask()
    {
        $model=new Task();
        $data=$this->vailShareTask();
        if($data && $data['data'])
        {
            $data['task']=$model->findOneById($data['task_id']);
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS,$data));
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 验证判断分享任务
     */
    private function vailShareTask()
    {
        $task_id=Yii::$app->request->get("task_id",'');
        if(!$task_id){ return null; }

        $customer_model=new Customer();
        $customer=$customer_model->findOneByOpenId($this->open_id);

        if(!$customer) { return null; }

        $model=new Order();
        $data['task_id']=$task_id;
        $data['customer']=$customer;
        $data['data']=$model->findOneByTaskId($task_id,$customer->id);

        return $data;
    }

    /**
     * 点击分享任务(为他/她点赞)
     */
    public function actionClickShareTask()
    {
        $task_id=Yii::$app->request->get('task_id',0);
        $user_id=Yii::$app->request->get('user_id',0);

        if(!$this->open_id || !$task_id || !$user_id){  return null; }

        $model=new OrderClick();
        $data=$model->findOneDetail($this->open_id,$task_id,$user_id);

        if(!$data)
        {
            //取出任务信息
            $task_model=new Task();
            $task_data=$task_model->findOneById($task_id);

            //取出分享的任务订单
            $order_model=new Order();
            $order_data=$order_model->findOneByTaskId($task_id,$user_id);

            if($task_data && $order_data && $order_data->status==0)
            {
                $order_data->click_number +=1;
                //判断点赞数是否达到要求的数(达到的话生成code和过期时间)
                if($order_data->click_number >= $task_data->count)
                {
                    $order_data->status=1;
                    $order_data->code=md5($task_id.$user_id);
                    $order_data->code_expire=date("Y-m-d", strtotime('+3 days'));
                    $order_data->code_url=$this->qrcode($order_data->code);
                }
                $order_data->save();

                $model->open_id=$this->open_id;
                $model->task_id=$task_id;
                $model->user_id=$user_id;
                $model->save();

                return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
            }
        }
        return $this->asJson($this->isFail(MessageType::$EXIST_CLICK_OPEN_ID));
    }

    /**
     * 生成满足点赞数的二维码图片
     */
    private function qrcode($code=null)
    {
        if($code)
        {
            require dirname(__DIR__) . '/common/phpqrcode/phpqrcode.php';
            $value =Yii::$app->request->hostInfo.'/api/api/use-qrcode?code='.$code;	 //二维码内容
            $errorCorrectionLevel = 'L';	//容错级别
            $matrixPointSize = 5;			//生成图片大小

            //生成二维码图片
            $file_name = 'upload/'.$code.'.png';
            \QRcode::png($value,$file_name , $errorCorrectionLevel, $matrixPointSize, 2);

            return $file_name;
        }
        return null;
    }

    /**
     * 使用二维吗(兑换二维吗/展示优惠码)
     */
    public function actionUseQrcode()
    {
        $code=Yii::$app->request->get("code",'');
        $str="<h1 style='font-size: 56px; text-align: center'>";
        $str.="<p>优惠码</p>";
        $str.="<p>".$code."</p>";
        $str.="</h1>";
        echo $str;
    }

    /**
     * 保存朋友圈图片
     */
    public function actionSaveFriendImage()
    {
        set_time_limit(0);
        header('content-type:image/png');

        $task_id=Yii::$app->request->get("task_id","");

        //$token_model=new AccessToken();
        //$token_data=$token_model->findOneLastRecord();
        //if(!$token_data){
        //    $access_token=$this->accessToken();
        //}
        //else{
        //    $access_token=$token_data->token;
        //}

        //获取用户头像图片
        $model=new Customer();
        $customer_data=$model->findOneByOpenId($this->open_id);
        if(!$customer_data){ return null;}

        $access_token=$this->accessToken(); //先不读库里面的access_token

        if(!$access_token){
            return $this->asJson($this->isFail(MessageType::$ACCESS_TOKEN_NULL));
        }

        $access_token=urldecode($access_token);
        $request_url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;

        $data['page']=""; //page/api/pages/zan/zan
        $data['scene']=urldecode("task_id=".$task_id."&user_id=".$customer_data->id);
        $data['width']=205;
        $data=json_encode($data);

        //合并图片
        $temp = "image/friendqrcode.jpg";
        $code=md5($task_id.$customer_data->id.'friend');

        $customer_head=$this->getRequest($customer_data->avatar_url);
        $file_name_head="upload/".$customer_data->open_id.".png";
        file_put_contents($file_name_head, $customer_head);

        $temp=$this->mergeQrcode($temp,$file_name_head,20,500);

        $qrcode_stream=$this->postJsonRequest($request_url,$data);
        $qrcode_file_name="upload/".$code.".png";
        file_put_contents($qrcode_file_name, $qrcode_stream);

        $temp=$this->mergeQrcode($temp,$qrcode_file_name,350,355);

        //返回图片存放路径
        return $this->asJson($this->isSuccess(MessageType::$SUCCESS,$temp));
    }

    /**
     * 合并小程序二维码和底图，成一张新的二维码
     */
    private function mergeQrcode($temp,$url,$x,$y)
    {
        if(!$temp || !$url){  return null;  }

        $big_img = imagecreatefromstring(file_get_contents($temp));
        $qrcode_img = imagecreatefromstring(file_get_contents($url));

        list($qr_code_width, $qr_code_hight, $qCodeType) = getimagesize($url);
        imagecopymerge($big_img, $qrcode_img, $x, $y, 0, 0, $qr_code_width, $qr_code_hight, 100);
        list($bigWidth, $bigHight, $bigType) = getimagesize($temp);
        imagejpeg($big_img,$url);
        ob_end_clean();

        return $url;
    }

    /**
     * 返回 access_token
     */
    private function accessToken()
    {
        $app_id=Yii::$app->params['appId'];
        $app_secret=Yii::$app->params['appSecret'];
        $request_url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$app_id."&secret=".$app_secret;

        $info=$this->getRequest($request_url);
        $info=json_decode($info,true);

        $access_token=$info['access_token'];

        //把access_token写入到数据库
        $model=new AccessToken();
        $data=$model->findOneByToken($access_token);
        if(!$data)
        {
            $data=clone $model;
            $data->token=$access_token;
            $data->expire_time=date("Y-m-d H:i:s",time()+7000);
            $data->save();
        }
        return $access_token;
    }

    /**
     * 常见问题
     */
    public function actionQuestion()
    {
        $model=new Question();
        $data=$model->getApiList();
        return $this->asJson($data);
    }

    /**
     * 商铺列表
     */
    public function actionShop()
    {
        $model=new Shop();
        $data=$model->getApiList();
        return $this->asJson($data);
    }

    /**
     * 商铺详情
     */
    public function actionShopDetail()
    {
        $id=Yii::$app->request->get("id",0);
        $model=new Shop();
        if($id==0){
            $data=$model->getApiDetailBySort();
        }
        else{
            $data=$model->findOneById($id);
        }
        return $this->asJson($data);
    }

    /**
     * 订单兑换码
     */
    public function actionOrderCode()
    {
        $status=Yii::$app->request->get("status",0);
        $mode=new Order();
        $data=$mode->getApiList($this->open_id,$status);
        return $this->asJson($data);
    }

    /**
     * 获取任务名称(领取任务/奖品任务)
     */
    public function actionGetTaskName()
    {
        $model=new Task();
        $data=$model->getApiTypeIsOne();
        return $this->asJson($this->isSuccess(MessageType::$SUCCESS,$data));
    }

    /**
     * 保存抽奖获得的奖项
     */
    public function actionSaveLuckTask()
    {
        $task_name=Yii::$app->request->get('task_name','');
        if(!$task_name || !$this->open_id){
            return $this->asJson($this->isFail(MessageType::$PARAMS_ERROR));
        }

        $task_model=new Task();
        $task=$task_model->getApiTaskNameByName($task_name);
        if(!$task) {
            return $this->asJson($this->isFail(MessageType::$DATA_NULL));
        }

        $customer_model=new Customer();
        $customer=$customer_model->findOneByOpenId($this->open_id);

        if(!$customer) {
            return $this->asJson($this->isFail(MessageType::$DATA_NULL));
        }

        $model=new Order();
        $data=$model->findOneByTaskId($task['id'],$customer['id']);
        if(!$data){
            $model->task_id=$task['id'];
            $model->user_id=$customer['id'];
            $model->status=1;
            if($task_name==MessageType::$THANKS)
            {
                $model->status=2;
            }
            $model->code=md5($task['id'].$customer['id']);
            $model->code_expire=date("Y-m-d", strtotime('+4 days'));
            $model->code_url=$this->qrcode($model->code);
            $model->save();

            return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 获取最近获奖名单(获取最近5条获奖名单记录)
     */
    public function actionGetLastLuckTask(){

        $model=new Order();
        $data=$model->getApiLuckList($this->open_id);
        return $this->asJson($this->isSuccess(MessageType::$SUCCESS,$data));
    }

}