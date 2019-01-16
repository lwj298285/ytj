@include('public.header')
<link type="text/css" href="{{ asset('js/plugins/bootstrap_table/bootstrap-table.min.css')}}"  rel="stylesheet">
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap_table/extensions/editable/bootstrap-editable.css')}}" type="text/css">
<link type="text/css" href="{{ asset('css/dialog/dialog.css')}}"  rel="stylesheet">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight allhgt" >
    <!-- Panel Other -->
    <div class="ibox float-e-margins allhgt" >
        <div class="ibox-title">
            <h5>用户列表</h5>
        </div>
        <div class="ibox-content pecenthgt " id="mainbox" >
            <!--搜索框开始-->
            <div id="toolbar" >
                <form class="form-inline">
                    {{ csrf_field() }}

                    <div class=" from-group ">

                        <div class="input-group">
                            <select class="form-control " name="role" id="role">
                                <option value="">选择所属角色</option>
                                @foreach($role as $v)
                                    <option value="{{$v->id}}" >{{$v->title}}</option>
                                @endforeach
                            </select>


                            &nbsp;<a href="{{url('user/useradd')}}"><button type="button" class=" input-group-addon btn btn-primary  btn-outline " title="新增" >
                                    <i class="fa fa-plus">新增</i>
                                </button>
                            </a>

                        </div>

                    </div>

                </form>

            </div>

            <table id="userlist"></table>
            <!-------  编辑窗口------->
        </div>
    </div>
</div>
<!-- End Panel Other -->
@include('public.footer')
<script src="{{ asset('js/plugins/bootstrap_table/bootstrap-table.min.js')}}"></script>
<script src="{{ asset('js/plugins/bootstrap_table/extensions/export/bootstrap-table-export.js')}}"></script>
<script src="{{ asset('js/plugins/tableExport/jQuery.base64.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/plugins/bootstrap_table/locale/bootstrap-table-zh-CN.min.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/plugins/bootstrap_table/extensions/editable/bootstrap-table-editable.min.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/plugins/bootstrap_table/extensions/editable/bootstrap-editable.js')}}"></script>
<script src="{{ asset('js/plugins/tableExport/tableExport.js')}}"></script>
<script src="{{ asset('js/dialog/dialog.js')}}" ></script>
<div  id="formdiv" hidden >

    <form class="form-horizontal" name="userform" id="userfrom" method="post" action="{{url('user/userEdit')}}">
        <div class="row col-md-12  ">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="col-md-2 control-label text-danger">用户名：</label>
                <div class=" col-md-4">
                    <input id="jobnum" type="hidden" class="form-control" name="jobnum">
                    <input id="id" type="hidden" class="form-control" name="id">
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
                <div class="col-md-4">
                    <input id="email" type="text" class="form-control" name="email" >

                </div>
                <label class="col-md-2 control-label text-danger">手机号码：</label>
                <div class="col-md-4">
                    <input id="mobile" type="text" class="form-control" name="mobile" >

                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label text-danger">真实姓名：</label>
                <div class=" col-md-4">
                    <input id="real_name" type="text" class="form-control" name="real_name" >

                </div>

                <label class="col-md-2 control-label  text-danger">管理员角色：</label>
                <div class="col-md-4">
                    <select class="form-control" name="groupid" id="groupid">
                        <option value="">请选择权限角色</option>
                       @if(!empty($role))
                          @foreach($role as $v)
                              <option value="{{$v->id}}" >{{$v->title}}</option>
                            @endforeach
                        {/foreach}
                       @endif
                    </select>
                </div>

            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">职务：</label>
                <div class=" col-md-4">
                    <input id="job" type="text" class="form-control" name="job" >

                </div>
                <label class="col-md-2 control-label">职称：</label>
                <div class="col-md-4">
                    <input id="jobtitle" type="text" class="form-control" name="jobtitle" >
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label text-danger" id="lbpwd">登录密码：</label>
                <div class=" col-md-4">
                    <input id="password" type="password" class="form-control" name="password" >
                </div>
                <label class="col-md-2 control-label text-danger" id="lbrpwd">确认密码：</label>
                <div class=" col-md-4">
                    <input id="repassword" type="password" class="form-control" name="repassword" >
                </div>

            </div>
            <div class="form-group">

                <label class="col-md-2 control-label">启用：</label>
                <div class=" col-md-4 i-checks">
                    &nbsp;&nbsp;&nbsp;&nbsp;<input id="status" type="checkbox" class="form-control" name="status" value="1" checked> 默认启用
                </div>

            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-7 col-md-offset-4">
                    <button class="btn btn-success btn-outline" type="button" onclick="clearform()"><i class="fa fa-plus-circle"></i> 添加</button>&nbsp;&nbsp;
                    <button class="btn btn-primary btn-outline" type="submit"><i class="fa fa-save"></i> 保存</button>&nbsp;&nbsp;
                    <button class="btn btn-danger btn-outline" type="button" onclick="dialog.getCurrent().close()"><i class="fa fa-close"></i> 关闭</button>
                </div>
            </div>

        </div>
    </form>
</div>
<script type="text/javascript">
    /**
     * [user_state 用户状态]
     * @param  {[type]} val [description]
     * @Author[李文俊 ]
     */
    function user_state(jobnum,status){

        $.post("{{url('user/user_state')}}", {'jobnum':jobnum,'status':status},function(res){

            if(res.code==1){

                $('#userlist').bootstrapTable('updateByUniqueId',{id:jobnum,row:{'status':status}});
                var a=b='<i class="fa fa-check">启用</i>';
                $('#'+jobnum).html(a).removeClass('btn-danger').addClass('btn-info').unbind('click').on('click',function(){user_state(jobnum,0)});
                layer.msg(res.msg,{icon:1,time:1500,shade: 0.1,});

            }else if (res.code==0){

                $('#userlist').bootstrapTable('updateByUniqueId',{id:jobnum,row:{'status':status}})
                var b='<i class="fa fa-close">禁用</i>';
                $('#'+jobnum).html(b).removeClass('btn-info').addClass('btn-danger').unbind('click').on('click',function(){user_state(jobnum,1)});

                layer.msg(res.msg,{icon:0,time:1500,shade: 0.1,});

            }else{

                layer.msg(res.msg,{icon:2,time:1500,shade: 0.1,});

            }

        });
    }
    /**
     * [Openwin 打开用户维护窗口]
     * @Author[李文俊 ]
     */
    function Openwin(){
        dialog({
            onclose: function () {
                clearform();
                $('#userlist').bootstrapTable('refresh',{ url:'{{url("user/getUser")}}'});
            },
            id: 'id-edituser',
            title: '用户信息维护',
            width:800,
            content:  document.getElementById('formdiv'),
        }).showModal();

        $('.chosen_select','#formdiv').chosen();
        $(".chosen-container",'#formdiv').css("width","100%");
    }

    /**
     * 根据角色选择人员信息
     */
    $("#role").change(function () {

        $.getJSON("{{url('user/getUser')}}",{'groupid':$("#role").val()},function (res) {
            if(JSON.stringify(res.code)==1){
                // layer.msg(JSON.stringify(res.msg),{icon:1,time:1000});
                $("#userlist").bootstrapTable('load',res.data);
                $('#userlist').bootstrapTable('resetView');
                layer.closeAll('loading');
            }else{

                layer.msg(JSON.stringify(res.msg),{icon:0,time:1500});
            }


        })

    })
    /**
     * 初始化人员信息
     * */
    $(document).ready( function () {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $('select').chosen();
        //初始化用户列表
        $('#userlist').bootstrapTable({
            classes: ' table table-striped  table-no-bordered table-hover  table-responsive',
            toolbar: "#toolbar",
            method: 'post',
            url:'{{url("user/getUser")}}',
            //contentType: "application/x-www-form-urlencoded",//必须要有！！！！(post)
            // ajaxOptions:'',
            pageList: [10, 20, 'all'],
            pageSize: 10,
            pageNumber: 1,
            cache: false,   //缓存
            sortname: "jobnum",
            sortorder: "desc",
            sorttable: false,
            sortStable: true,
            silentSort:false,
            queryParamsType: 'limit',
            queryParams: function (params) {

                params['hospitalid']=$('#hospital').val();
                params['groupid']=$('#role').val();
                return params
            },
            responseHandler:function(res){
                if(res.code == 0){
                    layer.msg(res.msg,{icon:2,time:1500});
                    return;
                }
                //如果没有错误则返回数据，渲染表格
                return res;
            },
            pagination:true,
//            sidePagination: 'server',//'client',
            editable: true,//开启编辑模式
            clickToSelect: false, //点击选中
            showToggle: true, //显示切换按钮来切换表/卡片视图。
            uniqueId: 'jobnum', //将index列设为唯一索引
            search: true,  //是否显示搜索栏
            iconSize: 'outline',
            showRefresh: true, //是否显示刷新按钮
            searchAlign: 'right',
            minimumCountColumns: 2, //最少列数
            smartDisplay: true,      //
            undefinedText: "",
            // height: $('#mainibox').height() - $('#toolbar').height(),
            //buttonsClass:"parmary btn-outline", //button 样式
            /*  rowAttributes:function(row,index){

             },*/
            rowStyle: function (row, index) {  //行样式 待用

                return {classes: 'text-nowrap '};
            },
            columns: [
                {field: "jobnum", title: "工号", valign: "middle", align: "center",order:"asc"},
                {field:"username",title:"用户名称",visible:true,align:"center",valign:"center",order:"asc",sortable:false,editable:false},
                {field:"title",title:"用户角色",visible:true,align:"center",valign:"center",order:"asc",sortable:false,editable:false},
                {field:"last_login_ip",title:"上次登录IP",visible:true,align:"center",valign:"center",order:"asc",sortable:false,editable:false},
                {field:"real_name",title:"真实姓名",visible:true,align:"center",valign:"center",order:"asc",sortable:false,editable:false},
                {field: "status", title: "状态", valign: "middle", align: "center", formatter: function (value, row, index) {
                        if (value==1)
                            if (row.groupid==1)
                                return;
                            else
                                return [
                                    '<button id="'+row.jobnum+'" class="btn-xs btn-info" onclick=user_state("'+row.jobnum+'",0)><i class="fa fa-check">开启</i></button>'
                                ].join('');

                        else
                        if (row.groupid==1)
                            return;
                        else
                            return [
                                '<button id="'+row.jobnum+'" class="btn-xs btn-danger" onclick=user_state("'+row.jobnum+'",1)><i class="fa fa-close">禁用</i></button>'
                            ].join('');


                    }},
                {field:"last_login_time",title:"上次登录时间",visible:true,align:"center",valign:"center",order:"asc",sortable:false,formatter: function (value, row, index){
                        var date = formatDateTime(value);
                        return date;
                    }},
                {field: "loginnum", title: "登录次数",valign: "middle", align: "center" },
                {field: "sex", title: "性别",valign: "middle", align: "center",visible:false},
                {field: "groupid", title: "角色",valign: "middle", align: "center",visible:false},
                {field:"action",title:"操作",align:"center",formatter:function(value,row,index){
                        return [
                            '<button id="edit" type="button"  class="btn-xs btn-info btn-outline"  title="编辑" >',
                            '<i class="fa fa fa-pencil">编辑</i>',
                            '</button>',
                            '&nbsp;<button id="remove" type="button"  class="btn-xs btn-danger btn-outline "   title="删除"  >',
                            '<i class="fa fa-trash-o">删除</i></i>',
                            '</button>'
                        ].join('');
                    },events: 'operateEvents' },
            ]
        });

        window.operateEvents = {

            //编辑当前行用户信息
            'click #edit': function (e, value, row, index) {
                Openwin();
                $("#id").val(row.id);
                $("#jobnum").val(row.jobnum);
                $("#username").val(row.username);
                if(Number(row.sex)==1)
                    $("#sex").iCheck('check');
                else
                    $("#sex1").iCheck('check');

                $("#email").val(row.email);
                $("#mobile").val(row.mobile);
                $("#real_name").val(row.real_name);
                $("#job").val(row.job);
                $("#jobtitle").val(row.jobtitle);

                $("#groupid").find("option[value='2']").attr("selected",true);

                if(Number(row.status)==1)
                    $("#status").iCheck('check')
                else
                    $("#status").iCheck('uncheck');

                moveEnd(document.getElementById('username'));
            },

            //删除当前行用户人员
            'click #remove': function (e, value, row, index) {

                layer.confirm('确认删除('+row.username+')', {icon: 3, title:'提示'}, function(index){

                    //-----------ajax提交删除-------------------//
                    $.getJSON('{{url("user/del")}}', {'id':row.id}, function(res){      //data是后台返回过来的JSON数据
                        if (JSON.stringify(res.code)==1){
                            layer.msg(JSON.stringify(res.msg),{icon:1,time:1500});
                            //------回调成功删除当前表数据--------------------//
                            $('#userlist').bootstrapTable('refresh');
                        }else{

                            layer.msg(JSON.stringify(res.msg),{icon:0,time:1500});

                        }

                    });

                    layer.close(index);
                })

            }

        };

        //表单ajax提交
        $('#userfrom').ajaxForm({
            success: complete,
            dataType: 'json'
        });

        //回调函数
        function complete(res){

            if(res.code==1){
                layer.msg(res.msg, {icon: 6,time:1500,shade: 0.1});
                window.location.href="{{url('user/index')}}";

            }else{
                layer.msg(res.msg, {icon: 5,time:15000,shade: 0.1});

            }

        }

    });
    //验证录入
    $("#userfrom").validate({
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

    //清空窗体
    function clearform(){

        $(':input[name!=hospitalid]','#userfrom')
            .not(':button, :submit, :reset, :radio,:checkbox,:disabled')
            .val('');
        $('select').trigger("chosen:updated");
        $("input[type=checkbox]").iCheck('check');
        $("#sfzjys").iCheck('uncheck');
        $("label.error").hide();
        $(".error").removeClass("error");
        $('#username').focus();
    }

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
    /**
     * 时间戳转为日期格式
     * @param timeStamp
     * @returns {string}
     */
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