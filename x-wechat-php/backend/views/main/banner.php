<?php
use yii\helpers\Url;
?>

<div class="container">
    <table id="tt"></table>
</div>

<div id="banner" class="easyui-dialog" closed="true" title="首页滚动图" modal="true" shadow="false" style="width:400px; height:auto;top:100px;">
    <form id="banner-form" class="form-horizontal">
        <div class="padding-12">

            <p><label>名称：</label> <input type="text" class="easyui-textbox" id="title" name="title"/></p>
            <p><label>上传：</label>
                <span class="textbox" style="width: 135.4px;">
                <input type="text" id="url" name="url" class="textbox-text validatebox-text textbox-prompt" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 28px; line-height: 28px; width: 127.4px;" readonly="readonly" /></span>
                <button type="button" class="easyui-linkbutton" onclick="showUpload()">上传图片</button></p>
            <p><label>排序：</label> <input type="text" class="easyui-numberbox" id="sort" name="sort"/><span class="color-999">(填数字)</span></p>
            &nbsp;&nbsp;
            <label>&nbsp;&nbsp;&nbsp;</label> <button type="button" class="easyui-linkbutton" icon="icon-ok" onclick="addRow()">确定</button>
        </div>
    </form>
</div>

<div id="img" class="easyui-dialog" closed="true" title="图片上传" modal="true" shadow="false" style="width:400px; height:auto;top:150px;">
    <form id="img-form" class="form-horizontal">
        <div class="padding-12">
            <label>上传图片：</label>
            <input  class="easyui-filebox" id="file_name" name="UploadForm[file_name]"/>
            &nbsp;&nbsp;
            <button type="button" class="easyui-linkbutton" icon="icon-ok" onclick='imageUpload("<?=Url::to('/common/upload-img')?>")'>确定</button>
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
            url: "<?= Url::to('/main/banner') ?>",
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
                {field:"title",title:"标题",width:140},
                {field:"url",title:"图片",formatter:function (value,row) {
                        return "<img src='"+value+"' style='width: 25px;height:25px; padding:4px;' ></img>";
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
        $('#banner').dialog({cache:false}).dialog('open');
        $("#banner-form").form("clear");
    }

    function closeRow() {
        $('#banner').dialog({cache:false}).dialog('close');
        $("#banner-form").form("clear");
    }

    function addRow() {
        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/save-banner')?>",
            data:$("#banner-form").serialize(),
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
            url: "<?=Url::to('/main/del-banner')?>",
            data: {'id':id},
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');
                $("#tt").datagrid("reload");
            }
        })
    }

</script>
