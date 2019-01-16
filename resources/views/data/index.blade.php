@include('public.header')
<link href="{{asset('css/plugins/iCheck/custom.css')}}" rel="stylesheet">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>数据库备份</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="table_basic.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-9 m-b-xs i-checks">
                        <button class="btn btn-outline btn-primary" href="javascript:;" id="export">立即备份</button>
                        <button id="optimize" class="btn btn-outline btn-info " url="{{url('admin/data/optimize')}}" >优化表</button>
                        <button id="repair" class="btn btn-outline btn-danger" url="{{url('admin/data/repair')}}" >修复表</button>
                        &nbsp;&nbsp;
                        &nbsp;<input  type="radio" class="js-switch" name="back[]" value="0"  checked>结构
                        &nbsp;&nbsp;&nbsp;<input  type="radio" class="js-switch" name="back[]" value="1"  >数据和结构
                    </div>
                </div>
                <form id="export-form" method="post" action="{{url('admin/data/export')}}">
                    {{csrf_field()}}
                    <table class="table table-bordered">
                        <thead >
                        <tr>
                            <th><input class="i-checks checkbox check-all" checked="chedked" type="checkbox"></th>
                            <th>表名</th>
                            <th>数据量</th>
                            <th>使用空间</th>
                            {{--<th>保留空间</th>--}}
                            <th>备份状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--{{dd($data)}}--}}
                      @if(!empty($data))

                       @foreach($data as $v)
                        <tr>
                            <td><input class="ids i-checks" checked="chedked" type="checkbox" name="ids[]" value="{{$v->Name}}"></td>
                            <td>{{$v->Name}}</td>
                            <td>【{{$v->Rows}}】 条记录</td>
                            <td>{{round(($v->Data_length+$v->Index_length)/1024/1024,2)}}MB</td>
                            {{--<td>{$vo.resrve_length}</td>--}}
                            <td id="info">等待备份...</td>
                            <td>
                                <a class="btn btn-primary btn-xs btn-outline btns" href="javascript:void(0)" onclick="optimize('{{$v->Name}}')">优化表</a>
                                <a class="btn btn-danger btn-xs btn-outline" href="javascript:void(0)" onclick="repair('{{$v->Name}}')">修复表</a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <td colspan="7" class="text-center"> 暂未发现数据库表! </td>
                        @endif
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Panel Other -->
</div>


@include('public.footer')
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    //全选的实现
    $('.check-all').on('ifChecked', function (event) {
        $('input[name="ids[]"]').iCheck('check');
    });
    $('.check-all').on('ifUnchecked', function (event) {
        $('input[name="ids[]"]').iCheck('uncheck');
    });
    $(function () {

        (function ($) {
            var ids= new Array();
            $("input[name='ids[]']:checked").each(function () {
                ids.push($(this).val());
            })
            var $form = $("#export-form"), $export = $("#export"), tables, $optimize = $("#optimize"), $repair = $("#repair");
            $optimize.add($repair).click(function () {

                $.post($(this).attr('url'), {ids:ids}, function (data) {
                    if (data.code) {
                        layer.msg(data.msg,{icon:1,time:1500,shade: 0.1,});
                    } else {
                        layer.msg(data.msg,{icon:2,time:1500,shade: 0.1,});
                    }
                });
                return false;
            });

            $export.click(function () {
                $export.parent().children().prop('disabled', true);
                $export.html("正在发送备份请求...");
                $.post(
                    $form.attr("action"),
                    {ids:ids,type:$("input[name='back[]']:checked").val()},
                    function (data) {
                        if (data.code) {
                            $export.html("开始备份，请不要关闭本页面！");
                            backup(data);
                            window.onbeforeunload = function () {
                                return "正在备份数据库，请不要关闭！";
                            };
                        } else {
                            layer.msg(data.msg,{icon:2,time:2000,shade: 0.1,});
                            $export.html("立即备份");
                            setTimeout(function () {
                                $export.parent().children().prop('disabled', false);
                            }, 1500);
                        }
                    });
                return false;
            });

            function backup(tab) {

              if(tab.code==1){

                  layer.msg(tab.msg,{icon:1,time:1500,shade: 0.1,});
                  $("#export").text('立即备份');
                  $("button").attr('disabled',false);

              }else{

                  layer.msg(tab.msg,{icon:0,time:1500,shade: 0.1,});
              }

            }


        })(jQuery);


    });

    /**
     * 数据库表优化
     * @param ids
     */
    function optimize(ids) {

        $.post("{{url('admin/data/optimize')}}",{'ids':ids},function (res) {

         if(res.code==1){

             layer.msg(res.msg,{icon:1,time:1500,shade: 0.1,});
         }else{

             layer.msg(res.msg,{icon:0,time:1500,shade: 0.1,});
         }

        });
    }

    /**
     * 数据库表修复
     * @param ids
     */
    function repair(ids) {

        $.post("{{url('admin/data/repair')}}",{'ids':ids},function (res) {

            if(res.code==1){

                layer.msg(res.msg,{icon:1,time:1500,shade: 0.1,});
            }else{

                layer.msg(res.msg,{icon:0,time:1500,shade: 0.1,});
            }

        });
    }

</script>
</body>
</html>