<?php
    use yii\helpers\Url;
?>
<form id="main-form" class="form-horizontal">
    <div class="padding-12">
        <label>微信昵称：</label> <input class="easyui-textbox" id="nick_name" name="nick_name"/>
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
        params={'_csrf-backend':_csrf_backend,'nick_name':nick_name};
        loadData(params);
    }
    
    function loadData(params) {
        $('#tt').datagrid({
            url: "<?= Url::to('/main/wechat-user') ?>",
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
            columns:[[
                {field:"id",title:"id"},
                {field:"open_id",title:"微信OpenId",width: 280},
                {field:"nick_name",title:"微信昵称"},
                {field:"gender",title:"性别",formatter:function (value,row) {
                        if(value==0){
                            return "未知";
                        }
                        else{
                            return value==1?"男":'女';
                        }
                    }},
                {field:"avatar_url",title:"微信头像",formatter:function (value,row) {
                        return "<img src='"+value+"' style='width: 25px;height:25px; padding:4px;'></img>";
                    }},
                {field:"city",title:"城市"},
                {field:"province",title:"省份"},
                {field:"country",title:"国家"},
                {field:"created_time",title:"添加时间",width: 160},
            ]]
        });
    }
    
    

</script>
