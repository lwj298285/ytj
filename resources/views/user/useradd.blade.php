@include('public.header')
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight" style="height: 100%">
    <div class="row"  style="height: 100%">
        <div class="col-md-12"  style="height: 100%">
            <div class="ibox float-e-margins" style="height: 100%">
                <div class="ibox-title" >
                    <h5>添加用户</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="height: 95%">
                    <form class="form-horizontal" name="userAdd" id="userAdd" method="post" action="{{url('user/useradd')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-2 control-label text-danger">用户名：</label>
                            <div class=" col-md-3">
                                <input id="username" type="text" class="form-control" name="username" >
                            </div>
                            <label class=" control-label col-md-2">性别：</label>
                            <div class=" col-md-4 i-checks">
                                &nbsp;&nbsp;&nbsp;<input id="sex" type="radio" class="js-switch" name="sex" value="1"  checked> 男

                                &nbsp;&nbsp;&nbsp;<input id="sex1" type="radio" class="js-switch" name="sex" value="0"  >女

                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label text-danger">E-mail：</label>
                            <div class="col-md-3">
                                <input id="email" type="text" class="form-control" name="email" >

                            </div>
                            <label class="col-md-2 control-label text-danger">手机号码：</label>
                            <div class="col-md-3">
                                <input id="mobile" type="text" class="form-control" name="mobile" >

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label text-danger">管理员角色：</label>
                            <div class="col-md-3">
                                <select class="form-control" name="groupid" id="groupid">
                                    <option value="">请选择权限角色</option>
                                    @if(!empty($role))
                                        @foreach($role as $v)
                                            <option value="{{$v->id}}" >{{$v->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <label class="col-md-2 control-label text-danger">真实姓名：</label>
                            <div class=" col-md-3">
                                <input id="real_name" type="text" class="form-control" name="real_name" >

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">职务：</label>
                            <div class=" col-md-3">
                                <input id="job" type="text" class="form-control" name="job" >

                            </div>
                            <label class="col-md-2 control-label">职称：</label>
                            <div class="col-md-3">
                                <input id="jobtitle" type="text" class="form-control" name="jobtitle" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label text-danger">登录密码：</label>
                            <div class=" col-md-3">
                                <input id="password" type="password" class="form-control" name="password" >
                            </div>
                            <label class="col-md-2 control-label text-danger">确认密码：</label>
                            <div class=" col-md-3">
                                <input id="repassword" type="password" class="form-control" name="repassword" >
                            </div>

                        </div>
                        <div class="form-group">

                            <label class="col-md-2 control-label">启用：</label>
                            <div class=" col-md-3 i-checks">
                                &nbsp;&nbsp;&nbsp;&nbsp;<input id="status" type="checkbox" class="form-control" name="status" value="1" checked> 默认启用
                            </div>

                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-5">
                                <button class="btn btn-success btn-outline" type="button" onclick="clearform()"><i class="fa fa-plus-circle"></i> 添加</button>&nbsp;&nbsp;
                                <button class="btn btn-primary btn-outline" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-danger btn-outline" href="javascript:history.go(-1);"><i class="fa fa-close"></i> 返回</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@include('public.footer')
<script type="text/javascript">

    //清空窗体
    function clearform(){

        $(':input','#userAdd')
            .not(':hidden,:button, :submit, :reset, :radio,:checkbox')
            .val('');

        $('select').trigger("chosen:updated");
        $("input[type=checkbox]").iCheck('check');
        $("input[type=radio][name=sex][value='1']").iCheck('check');
        $("input[type=radio][name=usertype][value='0']").iCheck('check');
        $("label.error").hide();
        $(".error").removeClass("error");


        $('#username').focus();

    }

    $(":input,select").keydown(function (e) {
        var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
        if (keyCode == 13) {

            for (var i = 0; i < this.form.elements.length; i++) {
                if (this == this.form.elements[i]) break;
            }

            i = (i + 1) % this.form.elements.length;


            // alert($(this.form.elements[i]).attr('id'));
            if ($(this.form.elements[i]).attr('id')=='groupid') {
                $('#job').focus();
                return false;
            }
            // i = (i + 2) % this.form.elements.length;
            // i = (i + 1) % this.form.elements.length;
            moveEnd(this.form.elements[i]);
            // this.form.elements[i].focus();
            return false;
        } else {

            return true;


        }
    });


    $(function(){

        //输入验证
        $("#userAdd").validate({
            ignore: "", //chose 样式验证必须要开启否则验证无效
            debug: false, //调试模式，即使验证成功也不会跳转到目标页面
            rules: { //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)
                username: {
                    required: true, //必填。如果验证方法不需要参数，则配置为true
                    checkName: true
                },
                password: {
                    required: true,
                    checkPwd:true
                },
                repassword: {
                    required: true,
                    equalTo: "#password",
                },
                job: {
                    maxlength:20
                },
                jobtitle: {
                    maxlength:20
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },
                mobile: {
                    required: true,
                    checkMbile:true

                },

                groupid: {
                    required: true,

                },
                real_name: {
                    required: true,
                    maxlength: 50
                }

            }

        });

        $('select').chosen();
        //角色重置后再验证
        $("select").change(function(){

            $(this).valid();

        });

        //表单ajax提交
        $('#userAdd').ajaxForm({
            success: complete,
            dataType: 'json'
        });
        //回调函数
        function complete(data){

            if(JSON.stringify(data.code)==1){
                layer.msg(JSON.stringify(data.msg), {icon: 6,time:1500,shade: 0.1}, function(index){
                    // window.location.href="{:url('user/index')}";
                });
            }else{
                layer.msg(JSON.stringify(data.msg), {icon: 5,time:1500,shade: 0.1});
                return false;
            }
        }

        $('#username').focus();
    });


    function moveEnd(obj) {
        obj.focus();
        var len = obj.value.length;
        if (document.selection){
            var sel = obj.createTextRange();
            sel.moveStart('character', len);
            sel.collapse();
            sel.select();
        } else if (typeof obj.selectionStart == 'number' && typeof obj.selectionEnd == 'number') {
            obj.selectionStart = obj.selectionEnd = len;
        }
    }

</script>
</body>
</html>