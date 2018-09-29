<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

\backend\assets\LoginAsset::register($this);
$this->title = '丞心护肤造型 - 用户登录';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="login_center">
    <div id="login_area">
        <div id="login_box">
            <div id="login_form">

            <?php
                $form=ActiveForm::begin([
                    'id'=>'submitForm',
                      'fieldConfig' => [
                        'template' => '<label class="form-label">{label}</label>：{input}<div style="color:#ff6900;font-size: 12px;">{error}</div>',
                    ],
                    'enableClientValidation'=>false,
                ]);
                ?>

                <div id="login_tip"><span id="login_err" class="sty_txt2"></span></div>

                <div>
                    <?=$form->field($model, 'username')->textInput(['class'=>'username','id'=>'username','placeholder'=>'输入您的用户名']) ?>
                </div>

                <div>
                    <?=$form->field($model, 'password')->passwordInput(['class'=>'pwd','id'=>'password','placeholder'=>'输入您的密码']) ?>
                </div>

                <div>
                    <?=$form->field( $model, 'verifyCode')->widget(Captcha::className(),[
                        'captchaAction'=>'site/captcha',
                        'options'=>['class'=>'username','placeholder'=>'输入下方验证码'],
                        'template' => '<span>&nbsp;</span>{input}<div style="padding-left: 52px; margin: 10px;cursor: pointer;">{image}<div style="font-size:12px;color: #666; ">看不清？点击图片换验证码</div></div>',
                        'imageOptions' => ['alt' => '验证码','class'=>'code-img', 'src' => ''],
                    ]); ?>
                </div>

                <div style="font-size: 12px;margin-left:67px;">
                    <?=$form->field($model, 'rememberMe')->checkbox() ?>
                </div>

                <div id="btn_area" style="margin-left:65px;">
                    <button type="submit" class="login_btn">登录</button>
                    <button type="reset" class="login_btn">清空</button>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


