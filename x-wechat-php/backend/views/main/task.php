<?php
use yii\helpers\Url;
?>

<div class="container">
    <table id="tt"></table>
</div>

<div id="task" class="easyui-dialog" closed="true" title="任务" modal="true" shadow="false" style="width:400px; height:auto;top:100px;">
    <form id="task-form" class="form-horizontal">
        <div class="padding-12">

            <p><label>任务名称：</label> <input type="text" class="easyui-textbox" id="name" name="name"/></p>
            <p><label>点赞次数：</label> <input type="text" class="easyui-numberbox" id="count" name="count"/><span class="color-999">(填数字)</span></p>
            <p><label>中奖概率：</label> <input type="text" class="easyui-numberbox" id="rate" name="rate"/><span class="color-999">(填数字)</span></p>
            <p><label>奖励名称：</label> <input type="text" class="easyui-combobox" name="reward_id" id="reward_id"/></p>
            <p><label>任务类型：</label> <input type="text" class="easyui-combobox" name="type" id="type"/></p>
            <p><label>排序数字：</label> <input type="text" class="easyui-numberbox" id="sort" name="sort"/><span class="color-999">(填数字)</span></p>
            <p>&nbsp;</p>
            <p style="padding-left: 65px;"><button type="button" class="easyui-linkbutton" icon="icon-ok" onclick="addRow()">确定</button></p>
        </div>
    </form>
</div>

<script type="text/javascript">

    $(function () {
        var params={'_csrf-backend':_csrf_backend};
        loadData(params);

        $('#reward_id').combobox({
            url:'<?=Url::to("/common/get-reward")?>',
            valueField:'id',
            textField:'name',
            panelHeight: 'auto',
        });

        $('#type').combobox("loadData",Array({value:"0", text:"任务"},{value:"1", text:"大转盘"}));
        $('#type').combobox({
            panelHeight: 'auto',
        });
    });

    function loadData(params) {
        $('#tt').datagrid({
            url: "<?= Url::to('/main/task') ?>",
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
                {field:"name",title:"任务名称",width:200},
                {field:"count",title:"点赞次数"},
                {field:"rate",title:"中奖概率"},
                {field:"reward_name",title:"奖励"},
                {field:"type",title:"任务类型",formatter:function (value,row) {
                        return value==0 ? "<span style='color:#ff00dd'>任务</span>":"<span style='color:#ff0000'>大转盘</span>";
                }},
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
        $('#task').dialog({cache:false}).dialog('open');
        $("#task-form").form("clear");
    }

    function closeRow() {
        $('#task').dialog({cache:false}).dialog('close');
        $("#task-form").form("clear");
    }


    function addRow() {
        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/save-task')?>",
            data:$("#task-form").serialize(),
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
            url: "<?=Url::to('/main/del-task')?>",
            data: {'id':id},
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');
                $("#tt").datagrid("reload");
            }
        })
    }

</script>
