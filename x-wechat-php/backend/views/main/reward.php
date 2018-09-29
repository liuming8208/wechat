<?php
use yii\helpers\Url;
?>

<div class="container">
    <table id="tt"></table>
</div>

<div id="reward" class="easyui-dialog" closed="true" title="奖励" modal="true" shadow="false" style="width:400px; height:auto;top:100px;">
    <form id="reward-form" class="form-horizontal">
        <div class="padding-12">

            <label>名称：</label> <input type="text" class="easyui-textbox" id="name" name="name"/>
            &nbsp;&nbsp;
            <label>&nbsp;&nbsp;&nbsp;</label> <button type="button" class="easyui-linkbutton" icon="icon-ok" onclick="addRow()">确定</button>
        </div>
    </form>
</div>

<script type="text/javascript">

    $(function () {
        var params={'_csrf-backend':_csrf_backend};
        loadData(params);
    });

    function loadData(params) {
        $('#tt').datagrid({
            url: "<?= Url::to('/main/reward') ?>",
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
            toolbar: [
                {text: '增加',
                    iconCls: 'icon-add',
                    handler: function () {
                       showRow();
                    }}],
            columns:[[
                {field:"id",title:"id"},
                {field:"name",title:"奖励名称",width:200},
                {field:"created_time",title:"添加时间",width: 160},
                {
                    title: '操作',field: 'action',halign: 'center',align: 'center',width: 100,
                    formatter: function (value,row) {
                        return "<a class='icon-remove' title='删除' href='#' onclick='delRow(" + row.id + ");'></a>";
                    }
                }
            ]]
        });
    }

    function showRow() {
        $('#reward').dialog({cache:false}).dialog('open');
        $("#reward-form").form("clear");
    }

    function closeRow() {
        $('#reward').dialog({cache:false}).dialog('close');
        $("#reward-form").form("clear");
    }

    function addRow() {
        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/save-reward')?>",
            data:$("#reward-form").serialize(),
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');
                if(data.status==0){
                    $("#tt").datagrid("reload");
                    closeRow();
                }
            }
        })
    }

    function delRow(id) {

        if(id<=0) { return false;}
        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/del-reward')?>",
            data: {'id':id},
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');
                $("#tt").datagrid("reload");
            }
        })
    }

</script>
