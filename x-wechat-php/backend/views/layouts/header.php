<div id="top">
    <div id="top_logo">
        <span style="color: #fff;font-size: 30px;">美容微信小程序后台</span>
    </div>
    <div id="top_links">
        <div id="top_op">
            <ul>
                <li>
                    <img alt="当前用户" src="/image/user.jpg">：
                    <span><?php
                         if(yii::$app->user)
                         {
                             echo yii::$app->user->identity['name_alias'];
                         }
                         ?></span>
                </li>
                <li>
                    <img alt="今天是" src="/image/date.jpg">：
                    <span id="day_day"></span>
                </li>
            </ul>
        </div>
        <div id="top_close">
            <a href="#" target="_parent" title="退出系统" onclick="logout()">
            <img alt="退出系统" title="退出系统" src="/image/close.jpg" style="position: relative; left: 25px;top: 10px;">
            </a>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        /**获得当前日期**/
        var time = new Date();
        var myYear = time.getFullYear();
        var myMonth = time.getMonth()+1;
        var myDay = time.getDate();
        if(myMonth < 10){
            myMonth = "0" + myMonth;
        }
        $("#day_day").html(myYear + "." + myMonth + "." + myDay);
    })

    function logout() {
        if(confirm("确定退出系统吗？")){
           window.location.href="/site/logout";
        }
    }

</script>