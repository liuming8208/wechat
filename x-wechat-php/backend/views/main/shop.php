<?php
use yii\helpers\Url;
?>

<div class="container">
    <table id="tt"></table>
</div>

<div id="shop" class="easyui-dialog" closed="true" title="门店（带 * 必填）" modal="true" shadow="false" style="width:550px; height:auto;top:10px;">
    <form id="shop-form" class="form-horizontal">
        <div class="padding-12">

            <input type="hidden" class="easyui-textbox" id="id" name="id"/>
            <p><label>门店名称：</label> <input type="text" class="easyui-textbox" id="name" name="name"/> <label>*</label>&nbsp;&nbsp;&nbsp;&nbsp;
               <label>门店电话：</label> <input type="text" class="easyui-textbox" name="phone" id="phone"/>
            </p>
            <p><label>所在路址：</label> <input type="text" class="easyui-textbox" id="road" name="road" style="width: 300px;"/> <label>*</label></p>

            <p><label>门店图片：</label>
                <span class="textbox" style="width: 221px;">
                <input id="url" name="url" class="textbox-text" style="margin: 0px; padding-top: 0px; padding-bottom: 0px; height: 28px; line-height: 28px; width: 221px;" readonly="readonly"/></span>
                <button type="button" class="easyui-linkbutton" onclick="showUpload()">上传图片</button> <label>*</label></p>

            <p><label>门店位置：</label> <input type="text" class="easyui-textbox" name="address" id="address" style="width: 300px;"/> <label>*</label></p>

            <p><label>门店介绍：</label> <textarea id="introduce" name="introduce" style="width: 290px;"></textarea></p>
            <p><label>公司介绍：</label> <input type="text" class="easyui-textbox" id="company" name="company" style="width: 300px;"/></p>

            <p><label>位置经度：</label> <input type="text" class="easyui-textbox" id="longitude" name="longitude"/> <label>*</label> &nbsp;&nbsp;&nbsp;&nbsp;
               <label>位置纬度：</label> <input type="text" class="easyui-textbox" name="latitude" id="latitude"/> <label>*</label></p>

            <p><label>门店排序：</label> <input type="text" class="easyui-numberbox" name="sort" id="sort"/></p>

            <p>&nbsp;</p>
            <p style="padding-left: 65px;"><button type="button" class="easyui-linkbutton" icon="icon-ok" onclick="addRow()">确定</button></p>
        </div>
    </form>
</div>

<div id="img" class="easyui-dialog" closed="true" title="图片上传" modal="true" shadow="false" style="width:400px; height:auto;top:150px;">
    <form id="img-form" class="form-horizontal">
        <div class="padding-12">
            <label>上传图片：</label> <input class="easyui-filebox" id="file_name" name="UploadForm[file_name]"/>
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
            url: "<?= Url::to('/main/shop') ?>",
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
                        showRow(0);
             }}],
            frozenColumns:[[
                {field:"id",title:"id"},
                {field:"name",title:"门店名称",width:120},
                {field:"road",title:"门店所在路址",width:140},
                {field:"url",title:"门店图片",formatter:function (value,row) {
                        return "<img src='"+value+"' style='width: 35px;height:35px; padding:4px;'></img>";
                    }},
                {field:"phone",title:"门店电话"},

            ]],
            columns:[[
                {field:"address",title:"门店位置",width:200},
                {field:"longitude",title:"经度"},
                {field:"latitude",title:"纬度"},
                {field:"introduce",title:"门店介绍",width:300},
                {field:"company",title:"公司介绍",width:250},
                {field:"sort",title:"排序位置"},
                {field:"created_time",title:"添加时间",width: 160},
                {
                    title: '操作',field: 'action',halign: 'center',align: 'center',width: 100,
                    formatter: function (value,row) {
                        return "<a class='icon-edit' title='编辑' href='#' onclick='showRow(" + row.id + ");'></a>&nbsp;&nbsp;&nbsp;<a class='icon-remove' title='删除' href='#' onclick='delRow(" + row.id + ");'></a>";
                    }
                }
            ]]
        });
    }

    function showRow(id) {
        $('#shop').dialog({cache:false}).dialog('open');
        $("#shop-form").form("clear");

        if(id!=0){
            $.post({
                loadText: '正在操作，请稍候...',
                url: "<?=Url::to('/main/get-shop-by-id')?>",
                data:{id:id,'_csrf-backend':_csrf_backend},
                success: function (data) {
                    if (data.status == 0) {
                        $("#shop-form").form("load", data.row);
                    }
                    else {
                        $.messager.alert('提示信息', data.msg, 'info');
                    }
                }
            });
        }
    }

    function addRow() {
        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/save-shop')?>",
            data:$("#shop-form").serialize(),
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');

                if(data.status==0){
                    $("#tt").datagrid("reload");
                    $('#shop').dialog({cache:false}).dialog('close');
                    $("#shop-form").form("clear");
                }
            }
        })
    }

    function delRow(id) {

        if(id<=0) { return false;}
        $.post({
            loadText: '正在操作，请稍候...',
            url: "<?=Url::to('/main/del-shop')?>",
            data: {'id':id},
            success: function (data) {
                $.messager.alert('提示信息', data.msg, 'info');
                $("#tt").datagrid("reload");
            }
        })
    }

</script>
