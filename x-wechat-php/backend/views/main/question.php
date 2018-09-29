<?php
use yii\helpers\Url;
?>

<div class="container">
    <table id="tt"></table>
</div>

<div id="question" class="easyui-dialog" closed="true" title="常见问题" modal="true" shadow="false" style="width:400px; height:auto;top:100px;">
    <form id="question-form" class="form-horizontal">
        <div class="padding-12">

            <p><label>&nbsp;问：</label> <input class="easyui-textbox" id="question" name="question" style="width:300px;"/></p>
            <p> <label>&nbsp;答：</label> <textarea id="answer" name="answer" style="width: 290px;"></textarea></p>
            <p><label>排序：</label> <input class="easyui-numberbox" id="sort" name="sort"/><span class="color-999">(填数字)</span></p>
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
            url: "<?= Url::to('/main/question') ?>",
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
                {field:"question",title:"问"},
                {field:"answer",title:"答"},
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
        $('#question').dialog({cache:false}).dialog('open');
        $("#question-form").form("clear");
    }

    function closeRow() {
        $('#question').dialog({cache:false}).dialog('close');
        $("#question-form").form("clear");
    }

    function addRow() {
        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/save-question')?>",
            data:$("#question-form").serialize(),
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');
                if(data.status==0){
                    $('#question-form').form("clear");
                    $("#tt").datagrid("reload");
                    closeRow();
                }
            }
        });
    }
    
    function delRow(id) {

        if(id<=0) { return false;}
        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/del-question')?>",
            data: {'id':id},
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');
                $("#tt").datagrid("reload");
            }
        })
    }

</script>
