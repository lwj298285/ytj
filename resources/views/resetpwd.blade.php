@include('public.header')
<body>
<div>

    <form class="form-horizontal" name="forgetpwd" id="forgetpwd" method="post" action="{{url('login/resetemail',['_token'=>$token->_token])}}">
    {{ csrf_field() }}
    <div class="ibox-content">

    <div class="hr-line-dashed"></div>
    <div class="form-group">
    <label class="col-md-3 control-label">邮箱地址：</label>
    <div class="col-md-3">
    <input type="text" name="email" id="email"   class="form-control" value="{{$token->email}}" readonly/>
    </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-group">
    <label class="col-md-3 control-label">新密码：</label>
    <div class="col-md-3">

    <input type="password" name="password" id="password"  class="form-control"/>
    </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-group">
    <label class="col-md-3 control-label">确认密码：</label>
    <div class="col-md-3">
    <input type="password" name="repassword" id="repassword"  class="form-control"/>
    </div>
    </div>

    <div class="hr-line-dashed"></div>

    <div class="form-group">
    <label class="col-md-4 control-label "></label>
    <div class="col-md-3 ">
    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>重置密码</button>
    </div>
    </div>
    </div>

    </form>
</div>
@include('public.footer')
<script type="text/javascript">

    //清空窗体
    function clearform(){

        $(':input','#forgetpwd')
            .not(':hidden,:button, :submit, :reset, :radio,:checkbox')
            .val('');
        $("input[type=checkbox]").iCheck('check');
        $("label.error").hide();
        $(".error").removeClass("error");
    }

    $(function(){

        //输入验证
        $("#forgetpwd").validate({
            ignore: "", //chose 样式验证必须要开启否则验证无效
            debug: false, //调试模式，即使验证成功也不会跳转到目标页面
            rules: { //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)

                password: {
                    required: true,
                    checkPwd:true
                },
                repassword: {
                    required: true,
                    equalTo: "#password",
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },

            }

        });


        //表单ajax提交
        $('#forgetpwd').ajaxForm({
            success: complete,
            dataType: 'json'
        });
        //回调函数
        function complete(data){

            if(JSON.stringify(data.code)==1){
                layer.msg(JSON.stringify(data.msg), {icon: 6,time:1500,shade: 0.1}, function(index){
                    window.location.href="{{url('/login')}}";
                });
            }else{
                layer.msg(JSON.stringify(data.msg), {icon: 5,time:1500,shade: 0.1});
                return false;
            }
        }

        $('#username').focus();
    });

</script>

</body>
</html>