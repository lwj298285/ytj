<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="IE=edge" >
    <meta name="renderer" content="webkit">
    <link rel="shortcut icon" href="favicon.ico">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/bootstrap.min.css?v=3.3.6') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/font-awesome.min.css?v=4.4.0') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/plugins/chosen/chosen.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/plugins/switchery/switchery.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/style.min.css?v=4.1.0') }}style.min.css?v=4.1.0" rel="stylesheet">
    <link href="{{ asset('css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/div.css') }}" rel="stylesheet" type="text/css">
    <link  href="{{ asset('js/plugins/zTree/zTreeStyle.css') }}" rel="stylesheet" type="text/css">
    {{--<link  href="{{ asset('js/layer/skin/layer.ext.css')}}" rel="stylesheet" type="text/css">--}}
    <style type="text/css">
        .long-tr th{
            text-align: center
        }
        .long-td td{
            text-align: center
        }
    </style>
</head>