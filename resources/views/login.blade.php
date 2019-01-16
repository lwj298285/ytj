<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <title>微分基因云平台</title>
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css?v=4.4.0')}}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/style.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/login.min.css')}}" rel="stylesheet">
    <!--极验验证需要引入的两个JS-->
    <script charset="utf-8" src="{{ asset('js/jquery1.9.js')}}"></script>
    <script src="{{ asset('js/gt.js')}}"></script>

    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>

</head>

<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7" style="color:#fff">
            <div class="logopanel m-b"  >
            </div>
            <div class="signin-info">

                <div class="m-b"></div>
                <h1>欢迎使用 <strong>微分基因管理系统</strong></h1>
                <ul class="m-b">
                    <!-- 加载动画 -->
                    <div id="logining" style="display:none ;background:rgba(255,00,255,0);text-align: center">
                        <img src='{{asset('images/logining.gif')}}' />
                    </div>

                </ul>
            </div>
        </div>
        <div class="col-sm-5" style="color:#fff">


            <form id="doLogin" name="doLogin" method="post" action="{{url('login/doLogin')}}">
                {{csrf_field()}}
                <p class="m-t-md" id="err_msg">登录到系统</p>
                <input type="text" class="form-control uname" placeholder="用户名" id="username" name="username"/>
                <input type="password" class="form-control pword m-b" placeholder="密码" id="password" name="password" />
                @if(config('verify_type')=='true')
                <div style="margin-bottom:70px">
                    <input type="text" class="form-control" placeholder="验证码" style="color:black;width:100px;float:left;margin:0px 0px;" name="code" id="code"/>
                    <img src="{{url('login/getVerify')}}" onclick="javascript:this.src='{{url('login/getVerify')}}?tm='+Math.random();" style="float:right;cursor: pointer"/>
                    {{--<img src="{{url('login/getVerify')}}" style="float:right;cursor: pointer"/>--}}
                </div>
               @else
                <div id="embed-captcha"></div>
                <p id="wait">正在加载验证码......</p>
                @endif
                <button type="submit" class="btn btn-primary btn-block" id="login">登　录</button>
                <button  data-toggle="modal" data-target="#forgetpassword" type="button" class="btn btn-danger btn-block">忘记密码</button>
                {{--<button  data-toggle="modal" data-target="#resitger" type="button" class="btn btn-success btn-block">注册</button>--}}

            </form>


        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left" style="color:#fff">
            &copy; 2018 All Rights Reserved.
        </div>
    </div>
</div>
{{--忘记密码--}}
<div class="modal  fade" id="forgetpassword" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title">重置密码</h3>
            </div>
            <form class="form-horizontal" name="forgetpwd" id="forgetpwd" method="post" action="{{url('login/resetpwd')}}">
                {{ csrf_field() }}
                <div class="ibox-content">
                    <div class="form-group">
                        <label class="col-md-3 control-label">用户名：</label>
                        <div class="col-md-6">
                            <input type="text" name="resetpwdusername" id="resetpwdusername"  class="form-control"/>
                        </div>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">邮箱地址：</label>
                        <div class="col-md-6">
                            <input type="text" name="resetpwdemail" id="resetpwdemail"  class="form-control"/>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-md-4 control-label  "></label>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>重置</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> 关闭</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<script charset="utf-8" src="{{ asset('js/jquery.min.js?v=2.1.4')}}"></script>
<script src="{{ asset('js/plugins/validate/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/plugins/validate/messages_zh.min.js')}}"></script>
<script src="{{ asset('js/checkvaildate.js')}}"></script>
<script src="{{ asset('js/bootstrap.min.js?v=3.3.6')}}"></script>
<script src="{{ asset('js/jquery.form.js')}}"></script>
<script src="{{ asset('js/layer/layer.js')}}"></script>


<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    var handlerEmbed = function (captchaObj) {
        $("#embed-submit").click(function (e) {
            var validate = captchaObj.getValidate();
            if (!validate) {
                $("#notice")[0].className = "show";
                setTimeout(function () {
                    $("#notice")[0].className = "hide";
                }, 2000);
                e.preventDefault();
            }
        });
        // 将验证码加到id为captcha的元素里
        captchaObj.appendTo("#embed-captcha");
        captchaObj.onReady(function () {
            $("#wait")[0].className = "hide";
        });
        // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
    };
    $.ajax({
        // 获取id，challenge，success（是否启用failback）
        url: "{{url('login/getVerify',array('t'=>time()))}}", // 加随机数防止缓存
        type: "get",
        dataType: "json",
        success: function (data) {
            // 使用initGeetest接口
            // 参数1：配置参数
            // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                product: "float", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
            }, handlerEmbed);
        }
    });


    $(function(){


        $('#doLogin').ajaxForm({
            beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
            success: complete, // 这是提交后的方法
            error:error,
            dataType: 'json'
        });

        function checkForm(){
            if( '' == $.trim($('#username').val())){
                layer.msg('请输入登录用户名', {icon: 5,time:1000}, function(index){
                    layer.close(index);
                });
                return false;
            }

            if( '' == $.trim($('#password').val())){
                layer.msg('请输入登录密码', {icon: 5,time:1000}, function(index){
                    layer.close(index);
                });
                return false;
            }

            //$("#login").removeClass('btn-primary').addClass('btn-danger').text("登录中...");
            $("#login").attr('disabled',true).text("登录中...");
            $("#logining").css('display','block'); //加载动画
        }


        function error(){
            $("#logining").css('display','none'); //关闭动画
            layer.msg('请求错误,请联系管理员', {icon: 5,time:1000});
            // $("#login").removeClass('btn-danger').addClass('btn-primary').text("登　录");
            $("#login").attr('disabled',false).text("登录");
            return false;
        }


        function complete(data){
            $("#logining").css('display','none'); //关闭动画

            if(data.code==1){
                layer.msg(data.msg, {icon: 6,time:1000}, function(index){
                    layer.close(index);
                    window.location.href=data.data;
                });
            }else{

                layer.msg(data.msg, {icon: 5,time:1000});
                //  $("#login").removeClass('btn-danger').addClass('btn-primary').text("登　录");
                $("#login").attr('disabled',false).text("登录");
                return false;
            }

        }

        $('#forgetpwd').validate({
            debug: false, //调试模式，即使验证成功也不会跳转到目标页面
            rules: { //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)
                resetpwdusername: {
                    required: true,
                    checkName: true
                },
                resetpwdemail: {
                    required: true,
                    email: true,
                    maxlength: 100
                }
            }
        });

        $('#forgetpwd').ajaxForm({
            // beforeSubmit: checkForm,
            success: finished,
            dataType: 'json'
        });

        //回调函数
        function finished(data){


            if(JSON.stringify(data.code)==1){

                layer.msg(JSON.stringify(data.msg), {icon: 1,time:1000,shade: 0.1});
                $('#forgetpwd').modal('hide');
            }else{

                layer.msg(JSON.stringify(data.msg), {icon: 2,time:10000,shade: 0.1});
                return false;
            }
        }

    });


</script>
</body>
</html>