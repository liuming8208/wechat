<div id="left_menu">

    <ul id="TabPage" style="height:200px; margin-top:50px;">
        <li id="left_tab1" class="selected" onclick="switchTab('TabPage','left_tab1','1')">
            <img style="width: 33px;height:31px; color: #fff;" alt="后台管理" title="后台管理" src="/image/1_hover.jpg">
        </li>
    </ul>

    <div id="nav_show" style="position:absolute; bottom:0px; padding:10px;">
        <a href="javascript:;" id="show_hide_btn">
            <img alt="显示/隐藏" title="显示/隐藏" src="/image/nav_hide.png" width="35" height="35">
        </a>
    </div>

</div>

<script type="text/javascript">

    $(function() {
        //首页数据
        $('#rightMain').attr('src', '/site/home-page');

        $('#TabPage li').click(function()
        {
            var index = $(this).index();
            $(this).find('img').attr('src', '/image/'+ (index+1) +'_hover.jpg');
            $(this).css({background:'#fff'});
            $('#nav_module').find('img').attr('src', '/image/module_'+ (index+1) +'.png');
            $('#TabPage li').each(function(i, ele){
                if( i!=index ){
                    $(ele).find('img').attr('src', '/image/'+ (i+1) +'.jpg');
                    $(ele).css({background:'#044599'});
                }
            });
            // 显示侧边栏
            switchSysBar(true);
        });

        // 显示隐藏侧边栏
        $("#show_hide_btn").click(function() {
            switchSysBar();
        });
    });

    /**隐藏或者显示侧边栏**/
    function switchSysBar(flag){
        var side = $('#side');
        var left_menu_cnt = $('#left_menu_cnt');
        if( flag==true ){
            left_menu_cnt.show(500, 'linear');
            side.css({width:'280px'});
            $('#top_nav').css({width:'77%', left:'304px'});
            $('#main').css({left:'280px'});
        }else{
            if ( left_menu_cnt.is(":visible") ) {
                left_menu_cnt.hide(10, 'linear');
                side.css({width:'60px'});
                $('#top_nav').css({width:'100%', left:'60px', 'padding-left':'28px'});
                $('#main').css({left:'60px'});
                $("#show_hide_btn").find('img').attr('src', '/image/nav_show.png');
            } else {
                left_menu_cnt.show(500, 'linear');
                side.css({width:'280px'});
                $('#top_nav').css({width:'77%', left:'304px', 'padding-left':'0px'});
                $('#main').css({left:'280px'});
                $("#show_hide_btn").find('img').attr('src', '/image/nav_hide.png');
            }
        }
    }
</script>