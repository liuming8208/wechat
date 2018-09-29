<?php

namespace backend\controllers;

use common\models\Banner;
use common\models\Customer;
use common\models\MessageType;
use common\models\Order;
use common\models\Question;
use common\models\Reward;
use common\models\Shop;
use common\models\Task;
use common\models\User;
use Yii;

class MainController extends BaseController
{

    /**
     * 微信用户
     */
    public function actionWechatUser()
    {
        if(Yii::$app->request->isPost)
        {
            $model=new Customer();
            $params=trim(Yii::$app->request->post("nick_name",''));
            $data=$model->getList($this->page,$this->rows,$params);
            return $this->asJson($data);
        }
        return $this->render("wechat-user");
    }

    /**
     * 完成优惠券
     */
    public function actionMyCode()
    {
        if(Yii::$app->request->isPost)
        {
            $params['nick_name']=trim(Yii::$app->request->post("nick_name",''));
            $params['code']=trim(Yii::$app->request->post("code",''));

            $model=new Order();
            $data=$model->getList($this->page,$this->rows,$params);
            return $this->asJson($data);
        }
        return $this->render("my-code");
    }

    /**
     * 使用我的优惠码
     */
    public function actionUseMyCode()
    {
        $id=Yii::$app->request->post("id",'');
        if($id)
        {
            $model=new Order();
            $data=$model->findOneById($id);
            if($data){
                $data->status=2;
                $data->save();
                return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
            }
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 常见问题
     */
    public function actionQuestion()
    {
        if(Yii::$app->request->isPost)
        {
            $model=new Question();
            $data=$model->getList($this->page,$this->rows);
            return $this->asJson($data);
        }
        return $this->render("question");
    }

    /**
     * 添加常见问题
     */
    public function actionSaveQuestion()
    {
        $model=new Question();
        if($model->load(Yii::$app->request->post(),'') && $model->save())
        {
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
        }
        return $this->asJson($this->isFail($this->getError($model)));
    }

    /**
     * 删除常见问题
     */
    public function actionDelQuestion()
    {
        $id=Yii::$app->request->post("id",'');
        if($id){
            $model=new Question();
            $result=$model->delOneById($id);
            if($result){
                return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
            }
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 首页滚动图
     */
    public function actionBanner()
    {
        if(Yii::$app->request->isPost)
        {
            $model=new Banner();
            $data=$model->getList($this->page,$this->rows);
            return $this->asJson($data);
        }
        return $this->render("banner");
    }

    /**
     * 添加首页滚动图
     */
    public function actionSaveBanner()
    {
        $model=new Banner();
        if($model->load(Yii::$app->request->post(),'') && $model->save())
        {
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
        }
        return $this->asJson($this->isFail($this->getError($model)));
    }

    /**
     * 删除首页滚动图
     */
    public function actionDelBanner()
    {
        $id=Yii::$app->request->post("id",'');
        if($id){
            $model=new Banner();
            $result=$model->delOneById($id);
            if($result){
                return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
            }
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 奖励
     */
    public function actionReward()
    {
        if(Yii::$app->request->isPost)
        {
            $model=new Reward();
            $data=$model->getList($this->page,$this->rows);
            return $this->asJson($data);
        }
        return $this->render("reward");
    }

    /**
     * 添加奖励
     */
    public function actionSaveReward()
    {
        $model=new Reward();
        if($model->load(Yii::$app->request->post(),'') && $model->save())
        {
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
        }
        return $this->asJson($this->isFail($this->getError($model)));
    }

    /**
     * 删除奖励
     */
    public function actionDelReward()
    {
        $id=Yii::$app->request->post("id",'');
        if($id){
            $model=new Reward();
            $result=$model->delOneById($id);
            if($result){
                return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
            }
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 任务
     */
    public function actionTask()
    {
        if(Yii::$app->request->isPost)
        {
            $model=new Task();
            $data=$model->getList($this->page,$this->rows);
            return $this->asJson($data);
        }
        return $this->render("task");
    }

    /**
     * 添加任务
     */
    public function actionSaveTask()
    {
        $model=new Task();
        if($model->load(Yii::$app->request->post(),'') && $model->save())
        {
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
        }
        return $this->asJson($this->isFail($this->getError($model)));
    }

    /**
     * 删除任务
     */
    public function actionDelTask()
    {
        $id=Yii::$app->request->post("id",'');
        if($id){
            $model=new Task();
            $result=$model->delOneById($id);
            if($result){
                return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
            }
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 门店
     */
    public function actionShop()
    {
        if(Yii::$app->request->isPost)
        {
            $model=new Shop();
            $data=$model->getList($this->page,$this->rows);
            return $this->asJson($data);
        }
        return $this->render("shop");
    }

    /**
     * 获取门店信息
     */
    public function actionGetShopById()
    {
        $id=Yii::$app->request->post("id",'');
        if($id){
            $model=new Shop();
            $data=$model->findOneById($id);
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS,$data));
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 添加/更新门店
     */
    public function actionSaveShop()
    {
        $model=new Shop();
        $_model=clone $model;
        if($model->load(Yii::$app->request->post(),'') && $model->validate())
        {
            $id=$model->attributes['id'];
            if($id)
            {
                $data=$_model->findOneById($id);
                if($data && $data->load(Yii::$app->request->post(),''))
                {
                    $data->save();
                    return $this->asJson($this->isSuccess(MessageType::$UPDATE_SUCCESS));
                }
            }
            else
            {
                $model->save();
                return $this->asJson($this->isSuccess(MessageType::$INSERT_SUCCESS));
            }
        }

        return $this->asJson($this->isFail($this->getError($model)));
    }

    /**
     * 删除门店
     */
    public function actionDelShop()
    {
        $id=Yii::$app->request->post("id",'');
        if($id){
            $model=new Shop();
            $result=$model->delOneById($id);
            if($result){
                return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
            }
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

    /**
     * 登录用户
     */
    public function actionUser()
    {
        if(Yii::$app->request->isPost)
        {
            $model=new User();
            $data=$model->getList($this->page,$this->rows);
            return $this->asJson($data);
        }
        return $this->render("user");
    }

    /**
     * 添加用户
     */
    public function actionSaveUser()
    {
        $model=new User();
        if($model->load(Yii::$app->request->post(),'') && $model->save())
        {
            return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
        }
        return $this->asJson($this->isFail($this->getError($model)));
    }

    /**
     * 删除用户
     */
    public function actionDelUser()
    {
        $id=Yii::$app->request->post("id",'');
        if($id){
            $model=new User();
            $result=$model->delOneById($id);
            if($result){
                return $this->asJson($this->isSuccess(MessageType::$SUCCESS));
            }
        }
        return $this->asJson($this->isFail(MessageType::$FAIL));
    }

}