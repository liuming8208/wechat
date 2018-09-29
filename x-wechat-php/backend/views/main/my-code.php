<?php
use yii\helpers\Url;
?>

<form id="main-form" class="form-horizontal">
    <div class="padding-12">
        <label>微信昵称：</label> <input class="easyui-textbox" id="nick_name" name="nick_name"/> &nbsp;&nbsp;
        <label>兑换码：</label> <input class="easyui-textbox" id="code" name="code" style="width:300px;"/>
        &nbsp;&nbsp;
        <button type="button" class="easyui-linkbutton" icon="icon-ok" onclick="search()">确定</button>
    </div>
</form>
<hr class="line">

<div class="container">
    <table id="tt"></table>
</div>

<script type="text/javascript">

    var params={'_csrf-backend':_csrf_backend};
    $(function () {
        loadData(params);
    });

    function search() {
        var nick_name=$("#nick_name").val();
        var code=$("#code").val();
        params={'_csrf-backend':_csrf_backend,'nick_name':nick_name,'code':code};
        loadData(params);
    }

    function loadData(params) {
        $('#tt').datagrid({
            url: "<?= Url::to('/main/my-code') ?>",
            method: 'post',
            queryParams: params,
            singleSelect: true,
            striped: true,
            rownumbers: true,
            pagination: true,
            nowrap: false,
            pageNumber: 1,
            pageSize: 20,
            pageList: [20,40],
            frozenColumns:[[
                {field:"id",title:"id"},
                {field:"task_name",title:"任务名称",width:200},
                {field:"nick_name",title:"微信昵称"},
                {field:"gender",title:"性别",formatter:function (value,row) {
                        if(value==0){
                            return "未知";
                        }
                        else{
                            return value==1?"男":'女';
                        }
                    }},
                {field:"count",title:"需要次数"},
                {field:"click_number",title:"已点次数"},
                {field:"status",title:"状态",width:60,formatter:function (value,row) {
                        if(value==0){
                            return "未完成";
                        }
                        else {
                            return value==1?"<span style='color: #ff00dd;'>未兑换</span>":"<span  style='color: #ff0000;'>已兑换</span>";
                        }
                    }},
                {field:"code",title:"兑换码"},
            ]],
            columns:[[

                {field:"code_expire",title:"有效期",width: 150},
                {field:"created_time",title:"添加时间",width: 150},
                {
                    title: '操作',field: 'action',halign: 'center',align: 'center',width: 100,
                    formatter: function (value,row) {
                        if(row.status==2)
                        {
                            return "已兑换";
                        }
                        else if(row.status==1){
                            return "<a class='icon-ok' title='确认兑换' href='#' onclick='okBtn(" + row.id + ");'></a>";
                        }
                        else{
                            return " -- "
                        }
                    }
                }
            ]]
        });
    }

    function okBtn(id) {

        if(id<=0){ return false;}

        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/use-my-code')?>",
            data:{id:id,'_csrf-backend':'<?=yii::$app->request->csrfToken ?>'},
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');
                if (data.status == 0)
                {
                   $("#tt").datagrid("reload");
                }
            }
        });
    }

</script>
