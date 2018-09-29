<?php
use yii\helpers\Url;
?>

<div class="container">
    <table id="tt"></table>
</div>

<div id="user" class="easyui-dialog" closed="true" title="登录用户" modal="true" shadow="false" style="width:400px; height:auto;top:50px;">
    <form id="user-form" class="form-horizontal">
        <div class="padding-12">

            <p><label>用户：</label> <input class="easyui-textbox" id="username" name="username"/></p>
            <p><label>密码：</label> <input class="easyui-textbox" id="password_hash" name="password_hash"/></p>
            <p><label>姓名：</label> <input class="easyui-textbox" id="name_alias" name="name_alias"/></p>
            <p><label>状态：</label> <input class="easyui-combobox" id="status" name="status"/></p>
            &nbsp;&nbsp;
            <p style="padding-left: 42px;"><button type="button" class="easyui-linkbutton" icon="icon-ok" onclick="addRow()">确定</button></p>
        </div>
    </form>
</div>

<script type="text/javascript">

    $(function () {
        var params={'_csrf-backend':_csrf_backend};
        loadData(params);

        $('#status').combobox("loadData",Array({value:"0", text:"停用"},{value:"1", text:"启用"}));
        $('#status').combobox({
            panelHeight: 'auto',
        });
    });

    function loadData(params) {
        $('#tt').datagrid({
            url: "<?= Url::to('/main/user') ?>",
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
                {field:"username",title:"用户名"},

                {field:"name_alias",title:"姓名"},
                {field:"password_hash",title:"密码"},
                {field:"status",title:"用户状态",formatter:function (value,row) {
                        return value==0 ? "停用":"启用";
                }},
                {field:"last_login_time",title:"登陆时间",width: 160},
                {
                    title: '操作',field: 'action',halign: 'center',align: 'center',width: 100,
                    formatter: function (value,row) {
                        if(row.username!="admin"){
                            return "<a class='icon-remove' title='删除' href='#' onclick='delRow(" + row.id + ");'></a>";
                        }
                    }
                }
            ]]
        });
    }

    function showRow() {
        $('#user').dialog({cache:false}).dialog('open');
        $("#user-form").form("clear");
    }
    
    function closeRow() {
        $('#user').dialog({cache:false}).dialog('close');
        $("#user-form").form("clear");
    }

    function addRow() {
        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/save-user')?>",
            data:$("#user-form").serialize(),
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
            url: "<?=Url::to('/main/del-user')?>",
            data: {'id':id},
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');
                $("#tt").datagrid("reload");
            }
        })
    }

</script>
