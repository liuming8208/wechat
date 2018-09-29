<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 *  首页控制器
 */
class SiteController extends Controller
{
    /**
     * 访问判断
     */
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only' => ['index','captcha','main'],//这里一定要加
                'rules'=>[
                    [
                        'actions'=>['index','captcha'],
                        'allow'=>true,
                    ],
                    [
                        'actions' => ['main','logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * 验证码
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'backColor'=>0xfefefe,  //背景颜色
                'maxLength' => 4,       //最大显示个数
                'minLength' => 4,       //最少显示个数
                'padding' => 4,         //间距
                'foreColor'=>0x666666,  //字体颜色
                'offset'=>4,            //设置字符偏移量
                'testLimit'=>1,
                'width'=>75,
                'height'=>30,
               ],
        ];
    }

    /**
     * 登录首页
     */
    public function actionIndex()
    {
        $this->layout=false;
        $model=new LoginForm();

        //登录
        if ($model->load(Yii::$app->request->post()))
        {
            $verifyCode=Yii::$app->request->post($model->formName())['verifyCode'];
            if($this->createAction('captcha')->validate($verifyCode,false) && $model->login())
            {
                return $this->redirect('/site/main');
            }
            else
            {
                $model->addError('verifyCode','验证码不正确');
            }
        }
        return $this->render('index',compact('model'));
    }

    /**
     * 系统欢迎首页
     */
    public function actionHomePage()
    {
        $this->layout=false;
        return $this->render("home-page");
    }

    /**
     * 后台框架页
     */
    public function actionMain()
    {
        return $this->render("main");
    }

    /**
     * 用户退出
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
