@include('public.header')
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight" style="height: 100%">
    <div class="row" style="height: 100%">
        <div class="col-sm-12" style="height: 100%">
            <div class="ibox float-e-margins" style="height: 100%">
                <div class="ibox-title">
                    <h5>编辑角色</h5>
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
                    <form class="form-horizontal" name="roleEdit" id="roleEdit" method="post" action="{{url('role/edit')}}">
                        @foreach($data as $v)
                            {{ csrf_field()}}
                        <input type="hidden" value="{{$v->id}}" name="id"/>
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-danger">角色名称：</label>
                            <div class="input-group col-sm-4">
                                <input id="title" type="text" class="form-control" name="title" value="{{trim($v->title)}}">

                            </div>

                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">状&nbsp;态：</label>
                            <div class="col-sm-6 ">
                                <div >
                                    <input type="checkbox" name='status' value="1"  class="js-switch" @if($v->status==1) checked @endif>&nbsp;&nbsp;默认开启
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-3">
                                <button class="btn btn-primary btn-outline" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-danger btn-outline" href="javascript:history.go(-1);"><i class="fa fa-close"></i> 返回</a>
                            </div>
                        </div>
                           @endforeach
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@include('public.footer')
<script type="text/javascript">
   $(document).ready(function () {
       $("#roleEdit").validate({
           debug: false, //调试模式，即使验证成功也不会跳转到目标页面
           rules: { //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)
               title: {
                   required: true, //必填。如果验证方法不需要参数，则配置为true
                   chinese: true,
                   maxlength:100
               }
           }
       });

       $('#roleEdit').ajaxForm({
           beforeSubmit: checkForm,
           success: complete,
           dataType: 'json'
       });

       function checkForm(){
           if( '' == $.trim($('#title').val())){
               layer.msg('请输入角色名称',{icon:2,time:1500,shade: 0.1}, function(index){
                   layer.close(index);
               });
               return false;
           }
       }

       function complete(data){
           if(JSON.stringify(data.code)==1){
               layer.msg(JSON.stringify(data.msg), {icon: 6,time:1500,shade: 0.1}, function(index){
                   window.location.href="{{url('role/index')}}";
               });
           }else{
               layer.msg(JSON.stringify(data.msg), {icon: 5,time:1500,shade: 0.1});
               return false;
           }
       }

       var title=$('#title').val();
       $('#title').val('').focus().val(title);
   })

    //IOS开关样式配置
    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, {
        color: '#1AB394'
    });
    var config = {
        '.chosen-select': {},
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

</script>
</body>
</html>