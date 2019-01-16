<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="IE=edge" >
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="favicon.ico">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/bootstrap.min.css?v=3.3.6') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/style.min.css?v=4.1.0') }}style.min.css?v=4.1.0" rel="stylesheet">
    <link href="{{ asset('css/div.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="col-lg-12">

    <div class="col-lg-12 col-lg-offset-3">


        <div class="from-group">

            <label class="col-md-3 control-label">忘记密码了吗？，别着急，请点击以下链接，我们协助你找回密码：</label>
             <br>

            <a href="{{url('login/resetemail',['_token'=>$user['_token']])}}">{{url('login/resetemail/'.$user['_token'])}}</a>

        </div>


        <div class="from-group">

            <label >如果这不是你的邮件请忽略，很抱歉打扰，请原谅。</label>
        </div>

    </div>


</div>
</body>
</html>