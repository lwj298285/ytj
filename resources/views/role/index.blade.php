@include('public.header')
<link href="{{ asset('css/jquery.mCustomScrollbar.css')}}" rel="stylesheet" type="text/css">
<link type="text/css" href="{{ asset('js/plugins/bootstrap_table/bootstrap-table.min.css')}}"  rel="stylesheet">
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap_table/extensions/editable/bootstrap-editable.css')}}" type="text/css">
<link type="text/css" href="{{ asset('css/dialog/dialog.css')}}"  rel="stylesheet">
<style type="text/css">
    .ztree-wrap {width:450px; height:340px; overflow:auto;}
</style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight" style="height: 100%">
    <!-- Panel Other -->
    <div class="ibox float-e-margins" style="height: 100%">
        <div class="ibox-title">
            <h5>角色列表</h5>
        </div>
        <div class="ibox-content" style="height: 95%">
            <!--搜索框开始-->
            <div class="row">
                <div class="col-sm-12">
                    <div  class="col-sm-2" style="width: 100px">
                        <div class="input-group" >
                            <a href="{{url('role/roleadd')}}"><button class="btn btn-outline btn-primary" type="button">添加角色</button></a>
                        </div>
                    </div>
                    <form name="list" id="list" class="form-search" method="get" action="{{url('role/getRole')}}">

                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" id="rule" class="form-control" name="rule"  placeholder="输入需查询的角色名" />
                                {{ csrf_field() }}
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
                                </span>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!--搜索框结束-->
            <div class="hr-line-dashed"></div>

            <div class="example-wrap">
                <div class="example">

                    <table id="roles">

                    </table>
                </div>
            </div>
            <!-- End Example Pagination -->
        </div>
    </div>
</div>
<!-- End Panel Other -->
</div>

<!-- 角色分配 -->
<div class="zTreeDemoBackground left" style="display: none" id="role">
    <input type="hidden" id="nodeid">
    <div class="form-group ">
        <div class=" mCustomScrollbar ztree-wrap">
            <ul id="treeType" class="ztree"></ul>
        </div>
    </div>
    <div class="form-group">
        <div class=" col-sm-offset-5" style="margin-bottom: 15px">
            <input type="button" value="确认分配" class="btn btn-primary" id="postform"/>
        </div>
    </div>
</div>

@include('public.footer')
<script src="{{ asset('js/plugins/bootstrap_table/bootstrap-table.min.js')}}"></script>
<script src="{{ asset('js/plugins/bootstrap_table/extensions/export/bootstrap-table-export.js')}}"></script>
<script src="{{ asset('js/plugins/tableExport/jQuery.base64.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/plugins/bootstrap_table/locale/bootstrap-table-zh-CN.min.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/plugins/bootstrap_table/extensions/editable/bootstrap-table-editable.min.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/plugins/bootstrap_table/extensions/editable/bootstrap-editable.js')}}"></script>
<script src="{{ asset('js/plugins/tableExport/tableExport.js')}}"></script>
<script src="{{ asset('js/dialog/dialog.js')}}" ></script>
<script type="text/javascript">

$(document).ready(function () {
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    zNodes = '';
    var index = '';
    var index2 = '';
    //初始化角色信息
    $('#roles').bootstrapTable({
        classes: ' table table-striped  table-no-bordered table-hover  table-responsive',
        url:'{{url("role/getRole")}}',
        method: 'get',
        pageList: [10, 20, 'all'],
        pageSize: 10,
        pageNumber: 1,
        cache: false,   //缓存
        sortname: "id",
        sortorder: "desc",
        sorttable: false,
        sortStable: true,
        silentSort:false,
        queryParamsType: 'limit',
        pagination:true,
//            sidePagination: 'server',//'client',
        editable: true,//开启编辑模式
        clickToSelect: false, //点击选中
        // showToggle: true, //显示切换按钮来切换表/卡片视图。
        uniqueId: 'id', //将index列设为唯一索引
        // search: true,  //是否显示搜索栏
        iconSize: 'outline',
        // showRefresh: true, //是否显示刷新按钮
        searchAlign: 'right',
        minimumCountColumns: 2, //最少列数
        smartDisplay: true,      //
        undefinedText: "",

        rowStyle: function (row, index) {  //行样式 待用

            return {classes: 'text-nowrap '};
        },

        columns: [
            {field:"index",title:"序号",align:"center",valign:"center",order:"asc",sortable:false,editable:false,width:"5%",formatter:function(value, row, index){
                    return row.index=index ; //返回行号
                }},
            {field:"id",title:"编号",editable:false,visible:false,searchable:false,width:"5%"},
            {field:"title",title:"角色名称",visible:true,align:"center",valign:"center",order:"asc",sortable:false,editable:false},
            {field:"status",title:"状态",align:"center",formatter:function(value,row,index){
                  if(row.title!='超级管理员'){

                     if(Number(value)==1){
                         return [
                             '<button id="open" type="button" class="btn-xs  btn-outline label label-info">开启</button>'
                         ].join('');
                     }else{

                         return [
                             '<button id="close" type="button" class="btn-xs  btn-outline label label-danger">禁用</button>'
                         ].join('');
                     }
                  }
                },events: 'operateEvents' },
            {field:"create_time",title:"添加时间",visible:true,align:"center",valign:"center",order:"asc",sortable:false,formatter: function (value, row, index){
                    var date = formatDateTime(value);
                    return date;
                }},
            {field:"update_time",title:"更新时间",visible:true,align:"center",valign:"center",order:"asc",sortable:false,formatter: function (value, row, index){
                    var date = formatDateTime(value);
                    return date;
                }},
            {field:"action",title:"操作",align:"center",formatter:function(value,row,index){

                if(row.title=='超级管理员'){

                    return [
                        '<button id="edit" type="button"  class="btn-xs btn-info btn-outline"  title="编辑" >',
                        '<i class="glyphicon glyphicon-edit" >编辑</i>',
                        '</button>',
                        '&nbsp;&nbsp;<button id="remove" type="button"  class="btn-xs btn-danger btn-outline "   title="删除"  >',
                        '<i class="glyphicon glyphicon-remove">删除</i>',
                        '</button>'
                    ].join('');
                }else{

                    return [
                        '<button id="qxfb" type="button" class="btn-xs  btn-outline label label-info">权限分配</button>',
                        '</button>  ',
                        '<button id="edit" type="button"  class="btn-xs btn-info btn-outline"  title="编辑" >',
                        '<i class="glyphicon glyphicon-edit" ></i>',
                        '</button>',
                        '&nbsp;<button id="remove" type="button"  class="btn-xs btn-danger btn-outline "   title="删除"  >',
                        '<i class="glyphicon glyphicon-remove"></i>',
                        '</button>'
                    ].join('');
                }
                },events: 'operateEvents' },
        ]});

        window.operateEvents = {

            //修改当前行状态
            'click #open': function (e, value, row, index) {

                  //-----------ajax提交-------------------//
                    $.getJSON('{{url("role/status")}}', {'id':row.id,status:0}, function(res){      //data是后台返回过来的JSON数据
                        if (JSON.stringify(res.code)==1){
                            layer.msg(res.msg,{icon:1,time:1500});
                            $('#roles').bootstrapTable('load',res.data);
                            $('#roles').bootstrapTable('resetView');
                            layer.closeAll('loading');

                        }else{

                            layer.msg(JSON.stringify(res.msg),{icon:0,time:150});
                        }
                    });
                    layer.close(index);

            },
            //修改当前行状态
            'click #close': function (e, value, row, index) {
                //-----------ajax提交-------------------//
                $.getJSON('{{url("role/status")}}', {'id':row.id,status:1}, function(res){      //data是后台返回过来的JSON数据

                    if (JSON.stringify(res.code)==1){
                        layer.msg(JSON.stringify(res.msg),{icon:1,time:1500});
                        $('#roles').bootstrapTable('load',res.data);
                        $('#roles').bootstrapTable('resetView');
                        layer.closeAll('loading');

                    }else{

                        layer.msg(JSON.stringify(res.msg),{icon:0,time:1500});
                    }
                });
                layer.close(index);

            },

              'click #qxfb':function (e,value,row,index) {


                    //分配权限
                      $("#nodeid").val(row.id);
                      //加载层
                      index2 = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
                      //获取权限信息
                      $.getJSON("{{url('role/giveAccess')}}", {'type' : 'get', 'id' : row.id}, function(res){
                          layer.close(index2);
                          if(res.code == 1){

                              zNodes = JSON.parse(res.data);  //将字符串转换成obj

                              //页面层
                              index = layer.open({
                                  type: 1,
                                  area:['450px', '450px'],
                                  title:'权限分配',
                                  skin: 'layui-layer-demo', //加上边框
                                  content: $('#role')
                              });
                              //设置位置
                              layer.style(index, {
                                  top: '10px',

                              });

                              //设置zetree
                              var setting = {
                                  check:{
                                      enable:true
                                  },
                                  data: {
                                      simpleData: {
                                          enable: true
                                      }
                                  }
                              };
                              $.fn.zTree.init($("#treeType"), setting, zNodes);
                              var zTree = $.fn.zTree.getZTreeObj("treeType");
                              zTree.expandAll(true);

                          }else{
                              layer.alert(res.msg);
                          }
                      });

                 //确认分配权限
                  $("#postform").click(function(){
                      var zTree = $.fn.zTree.getZTreeObj("treeType");
                      var nodes = zTree.getCheckedNodes(true);
                      var NodeString = '';
                      $.each(nodes, function (n, value) {
                          if(n>0){
                              NodeString += ',';
                          }
                          NodeString += value.id;
                      });
                      var id = $("#nodeid").val();
                      //写入库
                      $.post("{{url('role/giveAccess')}}", {'type' : 'give', 'id' : id, 'rule' : NodeString}, function(res){
                          layer.close(index);
                          if(res.code == 1){
                              layer.msg(res.msg,{icon:1,time:1500,shade: 0.1}, function(){
                                 $("#roles").bootstrapTable('refresh',true);
                              });
                          }else{
                              layer.msg(res.msg);
                          }

                      }, 'json')
                  })
              },

            //编辑当前行角色
            'click #edit': function (e, value, row, index) {

                window.location.href="{{url('role/edit')}}/"+row.id;
            },

            //删除当前行角色
            'click #remove': function (e, value, row, index) {

                layer.confirm('确认删除角色?('+row.title+')', {icon: 3, title:'提示'}, function(index){

                    //-----------ajax提交删除-------------------//
                    $.getJSON('{{url("role/del")}}', {'id':row.id}, function(res){      //data是后台返回过来的JSON数据

                        if (JSON.stringify(res.code)==1){
                            layer.msg(JSON.stringify(res.msg),{icon:1,time:1500});

                            //------回调成功删除当前表数据--------------------//
                            $('#roles').bootstrapTable('remove', {field: 'index', values:[row.index]});

                        }else{

                            layer.msg(JSON.stringify(res.msg),{icon:0,time:1500});

                        }

                    });

                    layer.close(index);
                })

            }

    };
    $("#list").validate({

        debug: false, //调试模式，即使验证成功也不会跳转到目标页面
        rules: { //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)
            rule:{
                required: true,
            },
        }
    });

    $("#list").ajaxForm({
        beforeSubmit:function(){
            layer.load();

        },
        success: complete,
        error:function(){
            layer.closeAll('loading');
            layer.msg('请求错误,请联系管理员', {icon: 5,time:1500});
        },
        dataType: 'json'

    });
    //查询返回渲染表数据
    function complete(data){

        $('#roles').bootstrapTable('load',data);
        $('#roles').bootstrapTable('resetView');
        layer.closeAll('loading');
    }
})


function formatDateTime(timeStamp) {
    var date = new Date();
    date.setTime(timeStamp * 1000);
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    m = m < 10 ? ('0' + m) : m;
    var d = date.getDate();
    d = d < 10 ? ('0' + d) : d;
    return y + '-' + m + '-' + d;
};

</script>
</body>
</html>
