@include('public.header')
<link href="{{ asset('css/jquery.mCustomScrollbar.css')}}" rel="stylesheet" type="text/css">

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight allhgt">
    <!-- Panel Other -->
    <div class="ibox float-e-margins allhgt">
        <div class="ibox-title">
            <h5>栏目类型</h5>
        </div>
        <div class="ibox-content pecenthgt">
            <div class="row allhgt ">

                <div class="col-md-4 allhgt">
                    <div class="panel panel-info allhgt">
                        <div class="panel-heading">
                            <i class="fa fa-cogs"></i>栏目类型维护
                        </div>
                        <div class="panel-body pecenthgt">
                            <div class=" mCustomScrollbar ztree-wrap  col-md-6" data-mcs-theme="dark">

                                <ul id="treeCate" class="ztree"></ul>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-8">
                    <form class="form-horizontal" name="cate" id="cate" method="post" action="{{url('admin/project/add')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-2 control-label" >编号：</label>
                            <div class="col-md-2">
                                <input id="id" type="text" class="form-control" name="id" readonly>
                                <input id="sort" type="hidden" class="form-control" name="sort" >

                            </div>
                            <label class="col-md-2 control-label text-danger">栏目类型：</label>
                            <div class="col-md-5">
                                <input id="title" type="text" class="form-control" name="title" >

                            </div>

                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">

                            <label class="col-md-2 control-label">启用：</label>
                            <div class=" col-md-5 i-checks">
                                &nbsp;&nbsp;&nbsp;&nbsp;<input id="status" type="checkbox" class="form-control" name="status" value="1" checked> 默认启用
                            </div>

                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">

                            <div class="col-md-9 col-md-offset-4">
                                <button class="btn btn-success  btn-outline" type="button" onclick="clearform()"><i class="fa fa-plus-circle" ></i> 添加</button>&nbsp;&nbsp;&nbsp;
                                <button class="btn btn-primary  btn-outline" type="submit"><i class="fa fa-save"></i> 保存</button>

                            </div>
                        </div>
                    </form>
                </div>


            </div><!-- End Example Pagination -->

        </div>
    </div>
</div>
<!-- End Panel Other -->



@include('public.footer')
<script src="{{ asset('js/jquery.mCustomScrollbar.js')}}"></script>
<script type="text/javascript">


    //获取临床类型json类型数据
    function giveCate(){
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            view: {
                // showicon:false,
                // showtitle:false,
                selectedMulti: false
                // addHoverDom: addHoverDom,
                // removeHoverDom: removeHoverDom

            },

            edit: {
                enable: true,
                showRenameBtn:false,
                showRemoveBtn: setRemoveBtn,
                removeTitle: "删除栏目类型",
                drag: {
                    iscopy:false,
                    // ismove:true,
                    prev: true,
                    next: true,
                    inner:false
                },
                editNameSelectAll:false
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback:{
                onDblClick: zTreeOnDblClick,
                beforeRemove: zTreeBeforeRemove,
                beforeDrag:beforeonDrag,
                beforeDrop: zTreeBeforeDrop
                //onDrop: zTreeOnDrop
            }

        };

        $.getJSON('{{url("admin/project/giveCate")}}', function(res){

            var zNodes =JSON.parse(res.data);  //将字符串转换成obj
            zTreeObj = $.fn.zTree.init($("#treeCate"), setting, zNodes);

        });


    }


    //单击节点获取节点数据
    function zTreeOnDblClick(event, treeId, treeNode){
        $("label.error").hide();
        $(".error").removeClass("error");
        $("#title").focus();
        if(treeNode.level == 0) {

            return false;
        }

        $.getJSON('{{url("admin/project/Info")}}',{id:treeNode.id,status:$("#status").val()},function(data)
            {
                if(data){
                    $('#id').val(data.id);
                    $('#sort').val(data.sort);
                    $('#title').val(data.title);
                    if(data.status==1)
                        $("#status").iCheck('check')
                    else
                        $("#status").iCheck('uncheck');

                }else{

                    layer.msg("选择你需要更新的数据", {icon:2, time: 1500});
                }
            }
        );

    }


    //删除节点
    function zTreeBeforeRemove(treeId, treeNode) {

        layer.confirm('确认删除栏目类型('+treeNode.name+')?', {icon: 3, title:'提示'}, function(index) {

            $.getJSON('{{url("admin/project/del")}}', {'id': treeNode.id}, function (res) {
                if (res.code == 1) {
                    var zTree = $.fn.zTree.getZTreeObj("treeCate");
                    var parnode=treeNode.getParentNode();
                    zTree.removeNode(treeNode);
                    parnode.isParent=true;
                    zTree.updateNode(parnode);
                    clearform();
                    layer.msg(res.msg, {icon: 1, time: 1500});

                    //return true;

                } else {
                    layer.msg(res.msg, {icon: 0, time: 1500});
                    // return false;
                }
            });

        })
        return false;
    }


    //拖拽节点前
    //dragId 根节点
    var dragId;
    function beforeonDrag(treeId, treeNodes) {
        for (var i=0,l=treeNodes.length; i<l; i++) {
            dragId = treeNodes[i].pId;
            if (treeNodes[i].drag === false) {
                return false;
            }
        }
        return true;
    }


    //拖拽结束前判断如果拖出父窗口之外停止操作
    function zTreeBeforeDrop( treeId, treeNodes, targetNode, moveType,iscopy) {


        if(targetNode.pId == dragId){


            $.getJSON('{{url("admin/project/softEdit")}}', {'id': treeNodes[0].id, 'type': moveType ,'targetid':targetNode.id}, function (res) {

                if (res.code == 1) {

                    layer.msg(res.msg, {icon: 1, time: 1500});
                    return true;

                } else {

                    layer.msg(res.msg, {icon: 0, time: 1500});
                    return false;
                }
            });

        } else{
            layer.msg('只能同级操作！', {icon: 0, time: 1500});
            return false;
        }



    }

    //根节点不显示编辑按钮
    function setRemoveBtn(treeId, treeNode) {
        //判断为顶级节点则不显示删除按钮
        if(treeNode.level == 0)
            return false;
        else
            return true;

    }

    //清空窗体
    function clearform(){

        $(':input','#cate')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');

        $("label.error").hide();
        $(".error").removeClass("error");
        $("#title").focus();

    }



    //加载树状临床类型  ----临床类型输入验证
    $(document).ready(function(){

        $(".content").mCustomScrollbar({
            axis:"yx" // vertical and horizontal scrollbar
        });
        giveCate();

        $("#cate").validate({

            debug: false, //调试模式，即使验证成功也不会跳转到目标页面
            rules: { //配置验证规则，key就是被验证的dom对象，value就是调用验证的方法(也是json格式)
                title: {
                    required: true, //必填。如果验证方法不需要参数，则配置为true
                    maxlength: 255
                }
            }
        });


//临床类型新增 更新提交
        $('#cate').ajaxForm({
            success: complete,
            dataType: 'json'
        });


//新增 更新回调
        function complete(data){

            if(data.code==1){

                layer.msg(data.msg, {icon: 6,time:1500,shade: 0.1 });
                giveCate();
            }else{
                layer.msg(data.msg, {icon: 5,time:1500,shade: 0.1});
                return false;
            }
        }

    });


</script>
</body>
</html>








