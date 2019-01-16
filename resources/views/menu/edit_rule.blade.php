@include('public.header')
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>编辑菜单</h5>
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
                <div class="ibox-content">
                    <form class="form-horizontal m-t" name="edit_rule" id="edit_rule" method="post" action="{{url('menu/edit_rule')}}">
                        @foreach($data as $v)
                        <input type="hidden" name="id" value="{{$v->id}}">
                            {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">菜单名称：</label>
                            <div class="input-group col-sm-4">
                                <input id="title" type="text" class="form-control" name="title" required="" aria-required="true" value="{{$v->title}}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">节点：</label>
                            <div class="input-group col-sm-4">
                                <input type="text" name="name" id="name" value="{{$v->name}}" placeholder="模块/控制器/方法"  class="form-control" />
                                <span class="help-block m-b-none">如：user/adduser </span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"> 样式名称：</label>
                            <div class="input-group col-sm-4">
                                <input type="text" name="css" id="css" value="{{$v->css}}" placeholder="输入样式名称"  class="form-control" />
                                <span class="help-block m-b-none"> <a href="http://fontawesome.dashgame.com/" target="_black">选择图标</a> 如:fa(样式启用) fa-user(图标名) fa-2x（图标大小）fa-spin （启用动态样式）text-danger（图标颜色）</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"> 排序：</label>
                            <div class="input-group col-sm-5">
                                <input type="text" name="sort" id="sort" value="{{$v->sort}}" placeholder="输入排序"  class="form-control" />
                            </div>
                        </div>
                        @endforeach
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;&nbsp;
                                <a class="btn btn-danger" href="javascript:history.go(-1);"><i class="fa fa-close"></i> 返回</a>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function(){
        $('#edit_rule').ajaxForm({
            beforeSubmit: checkForm,
            success: complete,
            dataType: 'json'
        });

        function checkForm(){
            if( '' == $.trim($('#title').val())){
                layer.msg('请输入菜单名称',{icon:2,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                });
                return false;
            }

            if( '' == $.trim($('#name').val())){
                layer.msg('控制器/方法不能为空',{icon:0,time:1500,shade: 0.1}, function(index){
                    layer.close(index);
                });
                return false;
            }
        }

        function complete(data){

            if(JSON.stringify(data.code)==1){
                layer.msg(JSON.stringify(data.msg), {icon: 6,time:1500,shade: 0.1}, function(index){
                    window.location.href="{{url('menu/index')}}";
                });
            }else{

                layer.msg(JSON.stringify(data.msg), {icon: 5,time:1500,shade: 0.1});
                return false;
            }
        }

    });



    $(document).ready(function(){

        moveEnd(document.getElementById('title'));

    })


    $(":input,select").keydown(function (e) {
        var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
        if (keyCode == 13) {

            for (var i = 0; i < this.form.elements.length; i++) {
                if (this == this.form.elements[i]) break;
            }

            i = (i + 1) % this.form.elements.length;

            if (this.form.elements[i].readOnly || this.form.elements[i].disabled)
                i = (i + 1) % this.form.elements.length;
            //this.form.elements[i].focus();
            moveEnd(this.form.elements[i]);
            return false;
        } else {

            return true;


        }
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