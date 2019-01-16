@include('public.header')
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>菜单列表</h5>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-sm-12">
                    <div  class="col-sm-2">
                        <div class="input-group" >
                            <button type="button" class="btn btn-primary btn-outline" data-toggle="modal" data-target="#myModal">添加菜单</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="example-wrap">
                <div class="example">
                    <form id="ruleorder" name="ruleorder" method="post" action="{{url('menu/softEdit')}}" >
                        {{ csrf_field() }}
                        <table class="table table-bordered table-hover table-responsive table-striped">
                            <thead>
                            <tr class="long-tr">
                                <th width="5%">ID</th>
                                <th width="15%">权限名称</th>
                                <th width="15%">节点</th>
                                <th width="10%">菜单状态</th>
                                <th width="15%">添加时间</th>
                                <th width="15%">更新时间</th>
                                <th width="10%">排序</th>
                                <th width="15%">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($menu as $v)
                                <tr class="long-td ">
                                    <td>{{$v['id']}}</td>
                                    <td style='text-align:left;padding-left: @if ($v['leftpin'] != 0){{$v['leftpin']}} @endif'>{{$v['lefthtml']}}{{$v['title']}}</td>
                                    <td>{{$v['name']}}</td>
                                    <td>

                                        @if($v['status']==1)
                                            <a class="red" href="javascript:;" onclick="rule_state({{$v['id']}});">
                                                <div id="zt{{$v['id']}}"><span class="label label-info">开启</span></div>
                                            </a>
                                        @else
                                            <a class="red" href="javascript:;" onclick="rule_state({{$v['id']}});">
                                                <div id="zt{{$v['id']}}"><span class="label label-danger">禁用</span></div>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ date('Y-m-d',$v['created_at'])}}</td>
                                    <td>{{ date('Y-m-d',$v['updated_at'])}}</td>
                                    <td style="padding: 3px" >
                                        <div >
                                            <input name="{{$v['id']}}" value=" {{$v['sort']}}" width="50%" style="text-align:center;" class="form-control">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{url('menu/edit_rule',['id'=>$v['id']])}}" class="btn btn-primary btn-xs">
                                            <i class="fa fa-paste"></i> 编辑</a>&nbsp;&nbsp;
                                        <a href="javascript:;" onclick="del_rule({{$v['id']}})" class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash-o"></i> 删除</a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8" align="right">
                                    <button type="submit"  id="btnorder" class="btn btn-info">更新排序</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

@include('public.footer')

<div class="modal  fade" id="myModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title">添加菜单</h3>
            </div>
            <form class="form-horizontal" name="add_rule" id="add_rule" method="post" action="{{url('menu/create')}}">
                {{ csrf_field() }}
                <div class="ibox-content">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">所属父级</label>
                        <div class="col-sm-8">
                            <select name="pid" class="form-control">
                                <option value="0">--默认顶级--</option>
                                @foreach($menu as $v)

                                    <option value="{{$v['id']}}" style="margin-left:55px;">{{$v['lefthtml']}}{{$v['title']}}</option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">菜单名称</label>
                        <div class="col-sm-8">
                            <input type="text" name="title" id="title" placeholder="输入菜单名称" class="form-control"/>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">节点</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" id="name" placeholder="模块/控制器/方法" class="form-control"/>
                            <span class="help-block m-b-none">如：admin/user/adduser (一级节点添加“#”即可)</span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">CSS样式</label>
                        <div class="col-sm-8">
                            <input type="text" name="css" id="css" placeholder="输入菜单名称前显示的CSS样式" class="form-control"/>
                            <span class="help-block m-b-none"> <a href="http://www.fontawesome.com.cn/" target="_black">选择图标</a> 如fa fa-user </span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">排&nbsp;序</label>
                        <div class="col-sm-8">
                            <input type="text" name="sort" id="sort" value="50" placeholder="输入排序" class="form-control"/>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">状&nbsp;态</label>
                        <div class="col-sm-6">
                            <div class="radio ">
                                <input type="checkbox" name='status' value="1" class="js-switch" checked />&nbsp;&nbsp;默认开启
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary  bb"><i class="fa fa-save"></i> 保存</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> 关闭</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){


        $("#add_rule").validate({

            debug: false, //调试模式，即使验证成功也不会跳转到目标页面
            rules: { //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)
                title:{
                    required: true,
                    maxlength:20
                },
                name: {
                    required: true, //必填。如果验证方法不需要参数，则配置为true
                    maxlength: 80
                }
            }
        });

        $('#add_rule').ajaxForm({
            success: complete,
            dataType: 'json'
        });

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

    //更新排序
    $(document).ready(function(){

        $('#ruleorder').ajaxForm({
            beforeSubmit:function(){
                layer.load();
            },
            success: complete,
            dataType: 'json'
        });

        function complete(data){
            if(JSON.stringify(data.code)==1){
                layer.msg(JSON.stringify(data.msg), {icon: 1,time:1500,shade: 0.1}, function(index){
                    window.location.href="{{url('menu/index')}}";
                    layer.closeAll('loading');
                });
            }else{
                layer.msg(JSON.stringify(data.msg), {icon: 2,time:1500,shade: 0.1}, function(index){
                    layer.closeAll('loading');
                    layer.close(index);
                });
            }
        }
    });


    /**
     * [del_rule 删除菜单]
     * @Author[李文俊]
     * @param   {[type]}    id [用户id]
     */
    function del_rule(id){

        layer.confirm('确认删除此菜单?', {icon: 3, title:'提示'}, function(index){
            //do something
            $.getJSON('{{url('menu/del')}}', {'id' : id}, function(res){
                if(JSON.stringify(res.code )== 1){
                    layer.msg(JSON.stringify(res.msg),{icon:1,time:1500,shade: 0.1},function(index){
                        layer.close(index);
                        window.location.href="{{url('menu/index')}}";
                    });

                }else{
                    layer.msg(JSON.stringify(res.msg),{icon:0,time:1500,shade: 0.1});
                }
            });

            layer.close(index);
        })

    }

    /**
     * [rule_state 菜单状态]
     * @param  {[type]} val [description]
     * @Author[李文俊]
     */
    function rule_state(val){
        $.getJSON('{{url("menu/status")}}',
            {id:val},
            function(data){
                if(JSON.stringify(data.data)==0){
                    var a='<span class="label label-danger">禁用</span>'
                    $('#zt'+val).html(a);
                    layer.msg(JSON.stringify(data.msg),{icon:2,time:1500,shade: 0.1,});
                    return false;
                }else{
                    var b='<span class="label label-info">开启</span>'
                    $('#zt'+val).html(b);
                    layer.msg(JSON.stringify(data.msg),{icon:1,time:1500,shade: 0.1,});
                    return false;
                }

            });
        return false;
    }

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