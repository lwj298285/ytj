@include('public.header')
<link href="{{ asset('css/jquery.mCustomScrollbar.css')}}" rel="stylesheet" type="text/css">
<link type="text/css" href="{{ asset('js/plugins/bootstrap_table/bootstrap-table.min.css')}}"  rel="stylesheet">
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap_table/extensions/editable/bootstrap-editable.css')}}" type="text/css">
<link type="text/css" href="{{ asset('css/dialog/dialog.css')}}"  rel="stylesheet">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>日志列表</h5>
        </div>
        <div class="ibox-content">
            <!--搜索框开始-->
            <div class="row">
                <div class="form-group">
                    <form name="admin_list_sea" class="form-search" method="post" id="logs" action="{{url('admin/log/operate_log')}}">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="col-md-2">
                                <input type="text" id="admin_name" name="admin_name"  class="form-control" placeholder="输入用户名搜素">
                            </div>

                            <div class="col-md-2">
                                <input type="text"  id="startdate" name="startdate" class="form-control laydate-icon" placeholder="开始日期">
                            </div>

                            <div class="col-md-2">
                                <input type="text"  id="enddate" name="enddate" class=" form-control laydate-icon" placeholder="结束日期">
                            </div>
                            <div class="col-md-4 btn-group" style="text-align:right">

                                <button type="submit" class="btn btn-primary btn-outline"><i class="fa fa-search"></i> 搜索</button>


                                <a href="javascript:;" onclick="del_curr_log()" class="btn btn-danger btn-outline "><i class="fa fa-trash-o"></i> 清除当前</a>


                                <a href="javascript:;" onclick="del_all_log()" class="btn btn-danger btn-outline "><i class="fa fa-trash-o"></i> 清除所有</a>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!--搜索框结束-->
            <div class="hr-line-dashed"></div>

            <div class="example-wrap">

                <div class="example">

                    <table id="log">
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Panel Other -->
</div>

@include('public.footer')
<script src="{{ asset('js/plugins/bootstrap_table/bootstrap-table.min.js')}}"></script>
<script src="{{ asset('js/plugins/bootstrap_table/extensions/export/bootstrap-table-export.js')}}"></script>
<script src="{{ asset('js/plugins/tableExport/jQuery.base64.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/plugins/bootstrap_table/locale/bootstrap-table-zh-CN.min.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/plugins/bootstrap_table/extensions/editable/bootstrap-table-editable.min.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/plugins/bootstrap_table/extensions/editable/bootstrap-editable.js')}}"></script>
<script src="{{ asset('js/plugins/tableExport/tableExport.js')}}"></script>
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    /* laydate({
     elem: '#startdate', //目标元素。由于laydate.js封装了一个轻量级的选择器引擎，因此elem还允许你传入class、tag但必须按照这种方式 '#id .class'
     event: 'focus' //响应事件。如果没有传入event，则按照默认的click
     });*/
    //日期格式
    Date.prototype.Format = function(fmt){ //author: meizz
        var o = {
            "M+" : this.getMonth()+1,                 //月份
            "d+" : this.getDate(),                    //日
            "h+" : this.getHours(),                   //小时
            "m+" : this.getMinutes(),                 //分
            "s+" : this.getSeconds(),                 //秒
            "q+" : Math.floor((this.getMonth()+3)/3), //季度
            "S"  : this.getMilliseconds()             //毫秒
        };
        if(/(y+)/.test(fmt))
            fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
        for(var k in o)
            if(new RegExp("("+ k +")").test(fmt))
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
        return fmt;
    }
    /**日期格式***/
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

    var start = {
        elem: '#startdate',
        format: 'YYYY-MM-DD',
        // min: laydate.now(), //设定最小日期为当前日期
        max: laydate.now(), //最大日期
        istime: true,
        istoday: true,
        choose: function(datas){
            end.min = datas; //开始日选好后，重置结束日的最小日期
            end.start = datas; //将结束日的初始值设定为开始日

        }
    };

    var end = {
        elem: '#enddate',
        format: 'YYYY-MM-DD ',
        // min: laydate.now(),
        max: laydate.now(),
        istime: true,
        istoday: true,
        choose: function(datas){
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };


    laydate(start);
    laydate(end);

    var date = new Date();
    $('#startdate').val(date.Format('yyyy-MM-dd'));
    $('#enddate').val(date.Format('yyyy-MM-dd'));
    $(document).ready( function () {
        //初始化日志记录
        $('#log').bootstrapTable({
            classes: 'table table-striped table-hover table-responsive ',
            method: 'get',
            url: '{{url("admin/log/operate_log")}}',
            editable: true,//开启编辑模式
            clickToSelect: false, //点击选中
            cache: false,   //缓存
            idField: "id",
            sortname: "id",
            sortOrder: "desc",
            sortStable: true,
            pageList: [10, 20, 'all'],
            pageSize: 10,
            pageNumber: 1,
            //contentType: "application/x-www-form-urlencoded",//必须要有！！！！(post)
            // ajaxOptions:'',
            sortorder: "desc",
            sorttable: false,

            silentSort:false,
            queryParamsType: 'limit',
            pagination:true,
            // showPaginationSwitch:true, //显示分页切换按钮
            uniqueId: 'index', //将index列设为唯一索引
            striped: true,
            iconSize: 'outline',
             // showRefresh: true, //是否显示刷新按钮
            searchAlign: 'left',
            sortable: true,  //所有列是否排
            minimumCountColumns: 2, //最少列数
            smartDisplay: true,      //
            detailView: false,  //是否显示父子表
            //dataField:"rows",
            undefinedText: "",
            totalField:"total",
            checkboxHeader: false, //表头是否显示全选heckbox
            paginationHAlign: 'right',
            buttonsClass: "parmary btn-outline", //button 样式
            rowStyle: function (row, index) {  //行样式 待用
                return {classes: 'text-nowrap '};//单元格不换行
            },

            columns: [
                {
                    field: "index",
                    title: "序号",
                    align: "center",
                    valign: "center",
                    order: "asc",
                    sortable: false,
                    editable: false,
                    width: "5%",
                    formatter: function (value, row, index) {
                        return row.index = index; //返回行号
                    }
                },
                {field: "id", title: "ID", editable: false, visible: false, searchable: false, width: "5%"},
                {field: "admin_id", title: "编号", editable: false, visible: false, searchable: false, width: "5%"},
                {
                    field: "admin_name",
                    title: "操作用户",
                    visible: true,
                    align: "center",
                    valign: "center",
                    order: "asc",
                    sortable: false,
                    editable: false
                },
                {
                    field: "description",
                    title: "描述",
                    visible: true,
                    align: "center",
                    valign: "center",
                    order: "asc",
                    sortable: false,
                    editable: false
                },
                {
                    field: "ip",
                    title: "操作IP",
                    visible: true,
                    align: "center",
                    valign: "center",
                    order: "asc",
                    sortable: false,
                    editable: false
                },
                {
                    field: "status", title: "状态", align: "center", formatter: function (value, row, index) {
                        if (Number(value) == 1) {
                            return [
                                '<span id="open" type="button" class="btn-xs  btn-outline label label-info">成功</span>'
                            ];
                        } else {

                            return [
                                '<span  type="button" class="btn-xs  btn-outline label label-danger">失败</span>'
                            ];
                        }

                    }, events: 'operateEvents'
                },
                {
                    field: "add_time",
                    title: "操作时间",
                    visible: true,
                    align: "center",
                    valign: "center",
                    order: "asc",
                    sortable: false,
                    formatter: function (value, row, index) {
                        return value;
                    }
                },
                {
                    field: "action", title: "操作", align: "center", formatter: function (value, row, index) {
                        return [
                            '<button id="remove" type="button"  class="btn-xs btn-danger btn-outline  "   title="删除"  >',
                            '<i class="glyphicon glyphicon-remove">删除</i>',
                            '</button>'
                        ].join('');
                    }, events: 'operateEvents'
                },
            ]
        });

        window.operateEvents = {

            //删除当前行日志
            'click #remove': function (e, value, row, index) {

                layer.confirm('确认删除记录?(' + row.admin_name + ')', {icon: 3, title: '提示'}, function (index) {

                    //-----------ajax提交删除-------------------//
                    $.getJSON('{{url("admin/log/del")}}', {'id': row.id}, function (res) {      //data是后台返回过来的JSON数据
                        if (res.code == 1) {
                            layer.msg(res.msg, {icon: 1, time: 1500});

                            //------回调成功删除当前表数据--------------------//
                            $('#log').bootstrapTable('remove', {field: 'index', values: [row.index]});

                        } else {

                            layer.msg(res.msg, {icon: 0, time: 1500});

                        }

                    });

                    layer.close(index);
                })

            }

        };

        $("#logs").ajaxForm({
            beforeSubmit: function () {
                layer.load();
            },
            success: complete,
            error: function () {
                layer.closeAll('loading');
                layer.msg('请求错误,请联系管理员', {icon: 5, time: 1500});
            },
            dataType: 'json'

        });

        //查询返回渲染表数据
        function complete(res) {

            if (res.code == 1) {
                $('#log').bootstrapTable('load', res.data);
                $('#log').bootstrapTable('resetView');

            } else {

                layer.msg(res.msg, {icon: 0, time: 1500});
            }

            layer.closeAll('loading');
        }
    });

    /**
     * [del 删除当前日志]
     * @Author[李勇 peis999]
     * @param   {[type]}    log_id[log_id]
     */
    function del_curr_log() {
        layer.confirm('确认删除当前日志吗?', {icon: 3, title: '提示'}, function (index) {
            layer.load();
            var username=$('#admin_name').val();
            var startdate=$('#startdate').val();
            var enddate=$('#enddate').val();

            $.getJSON('{{url("admin/log/curdel")}}', {
                'adminname':username,
                'startdate': startdate,
                'enddate':enddate
            }, function (res) {
                if (res.code == 1) {
                    layer.msg(res.msg, {icon: 1, time: 1500});
                   $("#log").bootstrapTable('refresh',true);
                    layer.closeAll('loading');
                } else {
                    layer.closeAll('loading');
                    layer.msg(res.msg, {icon: 0, time: 1500});
                }
            });

            layer.close(index);
        })

    }
    /**
     * [del 删除所有日志]
     * @Author[李勇 peis999]
     * @param   {[type]}    log_id[log_id]
     */
    function del_all_log(){
        layer.confirm('确认清除所有日志吗?', {icon: 3, title:'提示'}, function(index){
            //do something
            // $(".spiner-example").css('display','block'); //加载动画
            layer.load();
            $.getJSON('{{url("admin/log/Alldel")}}',  function(res){
                if(res.code == 1){
                    layer.closeAll('loading');
                    layer.msg(res.msg,{icon:1,time:1500});
                    Ajaxpage();
                }else{
                    layer.closeAll('loading');
                    layer.msg(res.msg,{icon:0,time:1500});
                }
            });
            layer.close(index);
        })

    }

</script>
</body>
</html>