@include('public.header')
<style type="text/css">
    .dropdown-submenu {
        position: relative;
    }
    .dropdown-submenu> .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -6px;
        margin-left: -1px;
        -webkit-border-radius: 0 6px 6px 6px;
        -moz-border-radius: 0 6px 6px;
        border-radius: 0 6px 6px 6px;
    }
    .dropdown-submenu:hover > .dropdown-menu {
        display: block;
    }
    .dropdown-submenu> a:after {
        display: block;
        content:" ";
        float: right;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
        border-width: 5px 0 5px 5px;
        border-left-color: #ccc;
        margin-top: 5px;
        margin-right: -10px;
    }
    .dropdown-submenu:hover > a:after {
        border-left-color: #fff;
    }
    .dropdown-submenu.pull-left {
        float: none;
    }
    .dropdown-submenu.pull-left > .dropdown-menu {
        left: -100%;
        margin-left: 10px;
        -webkit-border-radius: 6px 0 6px 6px;
        -moz-border-radius: 6px 0 6px 6px;
        border-radius: 6px 0 6px 6px;
    }
    .slimScrollDiv > *{
        overflow: visible;
    }
</style>
<body id ="mainbody" class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden" >
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span><img alt="image" class="" src="{{asset('images/logo.png')}}"/></span>
                        <span class="text-muted text-xs block"><strong class="font-bold">工号-{{ session('jobnum') }}</strong></span>
                        <span class="text-muted text-xs block"><strong class="font-bold">{{ session('realname') }}</strong></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                            <span class="clear">

                                <span class="text-muted text-xs block" id="val" value="123">{{ session('username') }}-{{ session('rolename') }}<b class="caret"></b></span>
                            </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInDown m-t-xs"  role="menu" aria-labelledby="dropdownMenu4">
                            <li><a href="javascript:;" id="cache"> 清除缓存</a></li>
                            <li data-toggle="modal" data-target="#editPassword"><a href="javascript:;" id="changePassword">修改密码</a></li>
                            <li><a href="{{url('login/loginOut')}}">安全退出</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">microanaly
                    </div>
                </li>
                @if(!empty($menu))
                    @foreach($menu as $v)
                <li class="menu">
                    <a href="{{$v['name']}}">
                        <i class="{{$v['css']}}"></i>
                        <span class="nav-label">{{$v['title']}} </span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">

                        @if(!empty($v['child']))
                            @foreach($v['child'] as $vo)
                        <li>
                            <a class="J_menuItem" href="{{$vo['href']}}">
                               <i class="{{$vo['css']}}" style="color:green"> </i>
                                <span class="nav-label">{{$vo['title']}} </span>
                                <span class="fa arrow"></span>
                            </a>
                        </li>
                            @endforeach
                        @endif
                    </ul>
                </li>
                    @endforeach
            @endif;
            </ul>
        </div>

    </nav>
    <!--左侧导航结束-->

    <!--中间主内容开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown hidden-xs">
                        <a class="right-sidebar-toggle" aria-expanded="false">
                            <i class="fa fa-tasks"></i> 主题
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="index_v1.html">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">常用操作<span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class=""><a id="fullsreen" href="javascript:void(0)"  onclick="fullScreen(document.getElementById('mainbody'))">全屏</a>
                    </li>
                    <li class=""><a  href="javascript:void(0)"  onclick="exitFullScreen()">退出全屏</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabGo"><a>前进</a>
                    </li>
                    <li class="J_tabBack"><a>后退</a>
                    </li>
                    <li class="J_tabFresh"><a>刷新</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabShowActive"><a>定位当前选项卡</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                    </li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
            <a href="javascript:;" id="logout" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i>
                退出</a>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%"
                    src="{{url('index/index')}}" frameborder="0" data-id="index_v1.html" seamless>
            </iframe>
        </div>


        <div class="footer">
            <div class="pull-right">&copy; 2016-2017 微分基因有限公司 版权所有</div>
        </div>
    </div>
    <!--中间主内容结束-->

    <!--右侧边栏开始-->
    <div id="right-sidebar">
        <div class="sidebar-container">
            <ul class="nav nav-tabs navs-3">
                <li class="active">
                    <a data-toggle="tab" href="#tab-1">
                        <i class="fa fa-gear"></i> 主题
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="sidebar-title">
                        <h3> <i class="fa fa-comments-o"></i> 主题设置</h3>
                        <small><i class="fa fa-tim"></i> 你可以从这里选择和预览主题的布局和样式，这些设置会被保存在本地，下次打开的时候会直接应用这些设置。</small>
                    </div>
                    <div class="skin-setttings">
                        <div class="title">主题设置</div>
                        <div class="setings-item">
                            <span>收起左侧菜单</span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                                    <label class="onoffswitch-label" for="collapsemenu">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>固定顶部</span>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar">
                                    <label class="onoffswitch-label" for="fixednavbar">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                                <span>
                        固定宽度
                    </span>

                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                                    <label class="onoffswitch-label" for="boxedlayout">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="title">皮肤选择</div>
                        <div class="setings-item default-skin nb">
                                <span class="skin-name ">
                         <a href="#" class="s-skin-0">
                             默认皮肤
                         </a>
                    </span>
                        </div>
                        <div class="setings-item blue-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-1">
                            蓝色主题
                        </a>
                    </span>
                        </div>
                        <div class="setings-item yellow-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-3">
                            黄色/紫色主题
                        </a>
                    </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--右侧边栏结束-->

</div>

@include('public.footer')

<div class="modal  fade" id="editPassword" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title">修改密码</h3>
            </div>
            <form class="form-horizontal" name="change_password" id="change_password" method="post" action="{{url('index/change')}}">
                {{csrf_field()}}
                <div class="ibox-content">
                    <div class="form-group">
                        <label class="col-md-3 control-label">原有密码：</label>
                        <div class="col-md-6">
                            <input type="password" name="password" id="password"  class="form-control"/>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">新密码：</label>
                        <div class="col-md-6">
                            <input type="password" name="newpassword" id="newpassword"  class="form-control"/>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">确认密码：</label>
                        <div class="col-md-6">
                            <input type="password" name="confirmpassword" id="confirmpassword"  class="form-control"/>

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-md-4 control-label  "></label>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> 关闭</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
<script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{ asset('js/contabs.js')}}"></script>
<script src="{{ asset('js/plugins/pace/pace.min.js')}}"></script>
<script src="{{ asset('js/hplus.min.js?v=4.1.0')}}"></script>
<script type="text/javascript">


    $(function(){

        $("#change_password").validate({
            debug: false, //调试模式，即使验证成功也不会跳转到目标页面
            rules: { //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)
                password: {
                    required: true //必填。如果验证方法不需要参数，则配置为true

                },
                newpassword: {
                    required: true,
                    checkPwd:true
                },


                confirmpassword: {
                    required: true,
                    equalTo: "#newpassword",
                }

            }

        });

        $('#change_password').ajaxForm({
            //beforeSubmit: checkForm,
            success: complete,
            dataType: 'json'
        });


        function complete(res){
            if(res.code==-2){
                layer.msg(res.msg, {icon: 1,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                    return false;
                });
            }
            if(res.code==1){
                layer.msg(res.msg, {icon: 6,time:1500,shade: 0.1}, function(index){
                    $(':input','#change_password').val('');
                    layer.close(index);
                    $("#editPassword").modal('toggle');
                });
            }else{
                layer.msg(res.msg, {icon: 2,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                    return false;
                });
            }
        }

    });


    function fullScreen(toFullScreenElement)


    {


        var docElm =toFullScreenElement ;

        //W3C
        if (docElm.requestFullscreen) { //不能用document.requestFullscreen判断
            docElm.requestFullscreen();
            document.addEventListener("fullscreenchange", removeFullScreenEle, false);//监听全屏改变事件
        }
        //FireFox
        else if (docElm.mozRequestFullScreen) {

            docElm.mozRequestFullScreen();
            document.addEventListener("mozfullscreenchange", removeFullScreenEle, false);//监听事件方法不能加参数，如果按esc退出的话则无法传递参数
        }
        //Chrome等
        else if (docElm.webkitRequestFullScreen) {
            docElm.webkitRequestFullScreen();
            document.addEventListener("webkitfullscreenchange",removeFullScreenEle, false);
        }
        //IE11
        else if (docElm.msRequestFullscreen) {

            docElm.msRequestFullscreen();
            document.addEventListener("MSFullscreenChange", removeFullScreenEle, false);//注意IE全屏状态改变事件名不能用小写mfsfullscreenchange,否则无法监听到,其他浏览器可以用小写
        };

    }
    /*
     * 浏览器退出全屏
     */
    function exitFullScreen()
    {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
        else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        }
        else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
        else if (document.msExitFullscreen) {
            document.msExitFullscreen();

        };

    }

    //退出登录
    $(document).ready(function(){


        $("#logout").click(function(){
            layer.confirm('你确定要退出吗？', {icon: 3}, function(index){
                layer.close(index);
                window.location.href="{{url('login/loginOut')}}";
            });
        });
    });

    //清除缓存
    $(function() {
        $("#cache").click(function () {
            layer.confirm('你确定要清除缓存吗？', {icon: 3}, function (index) {
                layer.close(index);
                $.getJSON('{{url("index/clear_cache")}}', {'type': 'get'}, function (data) {

                    if (data.code == 1)
                        layer.msg(data.msg, {icon: 1, time: 1500});
                    else
                        layer.msg(data.msg, {icon: 0, time: 1500});
                    //return true
                });
            });
        });



        //  fullScreen(document.getElementById('mainbody'))
    });


</script>
</body>

</html>
