<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <script type="text/javascript">

        /* zTree插件加载目录的处理  */
        var zTree;
        var setting = {
            view: {
                dblClickExpand: false,
                showLine: false,
                expandSpeed: "",
            },
            data: {
                key: {
                    name: "resourceName"
                },
                simpleData: {
                    enable:true,
                    idKey: "resourceID",
                    pIdKey: "parentID",
                    rootPId: ""
                }
            },
            callback: {
                onClick: zTreeOnClick
            }
        };

        var curExpandNode = null;
        function beforeExpand(treeId, treeNode) {
            var pNode = curExpandNode ? curExpandNode.getParentNode():null;
            var treeNodeP = treeNode.parentTId ? treeNode.getParentNode():null;
            for(var i=0, l=!treeNodeP ? 0:treeNodeP.children.length; i<l; i++ ) {
                if (treeNode !== treeNodeP.children[i]) {
                    zTree.expandNode(treeNodeP.children[i], false);
                }
            }
            while (pNode) {
                if (pNode === treeNode) {
                    break;
                }
                pNode = pNode.getParentNode();
            }
            if (!pNode) {
                singlePath(treeNode);
            }

        }

        function singlePath(newNode) {
            if (newNode === curExpandNode) return;
            if (curExpandNode && curExpandNode.open==true) {
                if (newNode.parentTId === curExpandNode.parentTId) {
                    zTree.expandNode(curExpandNode, false);
                } else {
                    var newParents = [];
                    while (newNode) {
                        newNode = newNode.getParentNode();
                        if (newNode === curExpandNode) {
                            newParents = null;
                            break;
                        } else if (newNode) {
                            newParents.push(newNode);
                        }
                    }
                    if (newParents!=null) {
                        var oldNode = curExpandNode;
                        var oldParents = [];
                        while (oldNode) {
                            oldNode = oldNode.getParentNode();
                            if (oldNode) {
                                oldParents.push(oldNode);
                            }
                        }
                        if (newParents.length>0) {
                            for (var i = Math.min(newParents.length, oldParents.length)-1; i>=0; i--) {
                                if (newParents[i] !== oldParents[i]) {
                                    zTree.expandNode(oldParents[i], false);
                                    break;
                                }
                            }
                        }else {
                            zTree.expandNode(oldParents[oldParents.length-1], false);
                        }
                    }
                }
            }
            curExpandNode = newNode;
        }

        function onExpand(event, treeId, treeNode) {
            curExpandNode = treeNode;
        }

        /** 用于捕获节点被点击的事件回调函数  **/
        function zTreeOnClick(event, treeId, treeNode) {

            var zTree = $.fn.zTree.getZTreeObj("main_tab");
            zTree.expandNode(treeNode, null, null, null, true);
            // 规定：如果是父类节点，不允许单击操作
            if(treeNode.isParent){
                return false;
            }
            // 如果节点路径为空或者为"#"，不允许单击操作
            if(treeNode.accessPath=="" || treeNode.accessPath=="#"){
                return false;
            }

            rightMain(treeNode.accessPath);

            if( treeNode.isParent ){
                $('#here_area').html('当前位置：'+treeNode.getParentNode().resourceName+'&nbsp;>&nbsp;<span style="color:#1A5CC6">'+treeNode.resourceName+'</span>');
            }else{
                $('#here_area').html('当前位置：系统&nbsp;>&nbsp;<span style="color:#1A5CC6">'+treeNode.resourceName+'</span>');
            }
        };

        /* 上方菜单 */
        function switchTab(tabpage,tabid,id){
            var oItem = document.getElementById(tabpage).getElementsByTagName("li");
            for(var i=0; i<oItem.length; i++){
                var x = oItem[i];
                x.className = "";
            }

            $(document).ajaxStart(onStart).ajaxSuccess(onStop);
            loadMenu(id, 'main_tab');
        }

        $(document).ready(function(){
            $(document).ajaxStart(onStart).ajaxSuccess(onStop);
            loadMenu(1, "main_tab");//默认加载
            if( zTree ){
                zTree.expandAll(true);
            }
        });

        function loadMenu(parent_id, treeObj){

           var data=[
               { "accessPath": "", "checked": false, "parentID": 1, "resourceID": 2, "resourceName": "用户管理"},
                   { "accessPath": "/main/wechat-user", "checked": false, "parentID": 2, "resourceID": 3, "resourceName": "微信用户"},
                   { "accessPath": "/main/my-code", "checked": false, "parentID": 2, "resourceID": 4, "resourceName": "完成优惠券" },

               { "accessPath": "", "checked": false, "parentID": 1, "resourceID": 5, "resourceName": "后台配置" },
                   { "accessPath": "/main/question", "checked": false, "parentID": 5, "resourceID": 6, "resourceName": "常见问题"},
                   { "accessPath": "/main/banner", "checked": false,  "parentID": 5, "resourceID": 7, "resourceName": "首页滚动图" },
                   { "accessPath": "/main/reward", "checked": false, "parentID": 5,  "resourceID": 8, "resourceName": "奖励"},
                   { "accessPath": "/main/task", "checked": false, "parentID": 5, "resourceID": 9, "resourceName": "任务"},
                   { "accessPath": "/main/shop", "checked": false, "parentID": 5, "resourceID": 10, "resourceName": "门店"},

               { "accessPath": "", "checked": false, "parentID": 1, "resourceID": 11, "resourceName": "系统管理"},
                { "accessPath": "/main/user", "checked": false, "parentID": 11, "resourceID": 12, "resourceName": "登录用户"},

            ];

            $.fn.zTree.init($("#"+treeObj), setting, data);
            zTree = $.fn.zTree.getZTreeObj(treeObj);
            if( zTree )
            {
               zTree.expandAll(true);
             }
        }

        function onStart(){
            $("#ajaxDialog").show();
        }

        function onStop(){
            $("#ajaxDialog").hide();
        }

    </script>

</head>
<body>
<?php $this->beginBody() ?>
<?php include_once 'header.php'; ?>

<div id="side">
    <?php include_once 'menu.php'; ?>

    <div id="left_menu_cnt">
        <div id="nav_module">
            <img src="/image/module_1.png" width="210" height="58"/>
        </div>
        <div id="nav_resource">
            <ul id="main_tab" class="ztree"></ul>
        </div>
    </div>
</div>

<div id="top_nav">
    <span id="here_area">当前位置：系统&nbsp;>&nbsp;系统介绍</span>
</div>
<div id="main">
    <iframe name="right" id="rightMain" frameborder="no" scrolling="auto" width="100%" height="100%" allowtransparency="true"/>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

