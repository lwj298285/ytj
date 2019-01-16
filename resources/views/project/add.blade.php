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
                    <form class="form-horizontal" name="cateAdd" id="cateAdd" method="post" action="{{url('admin/project/add')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-3 control-label text-danger">栏目名称：</label>
                            <div class=" col-md-5">
                                <input id="title" type="text" class="form-control" name="title" >
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label text-danger">排序：</label>
                            <div class=" col-md-5">
                                <input id="sort" type="number" class="form-control" name="sort" min="1" max="100" value="50">

                            </div>
                        </div>

                        <div class="form-group">

                            <label class="col-md-3 control-label">启用：</label>
                            <div class=" col-md-5 i-checks">
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

        $(':input','#cateAdd')
            .not(':hidden,:button, :submit, :reset, :radio,:checkbox')
            .val('');

        $('select').trigger("chosen:updated");
        $("input[type=checkbox]").iCheck('check');
        $("input[type=radio][name=sex][value='1']").iCheck('check');
        $("input[type=radio][name=usertype][value='0']").iCheck('check');
        $("label.error").hide();
        $(".error").removeClass("error");


        $('#title').focus();

    }


    $(function(){

        //输入验证
        $("#cateAdd").validate({
            ignore: "", //chose 样式验证必须要开启否则验证无效
            debug: false, //调试模式，即使验证成功也不会跳转到目标页面
            rules: { //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)
                title: {
                    required: true, //必填。如果验证方法不需要参数，则配置为true
                    checkName: true
                },

            }

        });

        $('select').chosen();
        //角色重置后再验证
        $("select").change(function(){

            $(this).valid();

        });

        //表单ajax提交
        $('#cateAdd').ajaxForm({
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

        $('#title').focus();
    });

</script>
</body>
</html>