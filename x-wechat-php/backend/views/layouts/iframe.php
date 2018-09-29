<?php
use backend\assets\IframeAsset;
use yii\helpers\Html;
IframeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript">

        var _csrf_backend='<?=yii::$app->request->csrfToken ?>';

        function showUpload() {
            $('#img').dialog({cache:false}).dialog('open');
        }

        function imageUpload(requestUrl) {
            $.ajaxFileUpload({
                type:'POST',
                url: requestUrl,
                secureuri: false,
                fileElementId: 'filebox_file_id_1',
                dataType: 'json',
                success : function(data) {
                    $.messager.alert('提示信息', data.msg, 'info');
                    if(data.status==0)
                    {
                        $('#url').val(data.row);
                        $('#img').dialog({cache:false}).dialog('close');
                    }
                },
                error : function(data) {
                    $.messager.alert('提示信息','服务器异常，请稍后再试！');
                }
            });
        }

    </script>
</head>
<body>
<?php $this->beginBody() ?>

<div>
    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>