<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title><?php  if (isset($page_title)){ echo $page_title; } ?></title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{asset('public/global_assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('public/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('public/assets/css/core.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('public/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('public/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files --> 
        <script src="{{asset('public/global_assets/js/plugins/loaders/pace.min.js')}}"></script>
	<script src="{{asset('public/global_assets/js/core/libraries/jquery.min.js')}}"></script>
	<script src="{{asset('public/global_assets/js/core/libraries/bootstrap.min.js')}}"></script>
	<script src="{{asset('public/global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
	<script src="{{asset('public/global_assets/js/plugins/ui/nicescroll.min.js')}}"></script>
	<script src="{{asset('public/global_assets/js/plugins/ui/drilldown.js')}}"></script>
        
        
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{asset('public/assets/js/app.js')}}"></script>
	<!-- /theme JS files -->
        <style>
            .alert-dismissable .close, .alert-dismissible .close {
                top: -1px !important;
                right: -6px !important;
            }
        </style>
        
        
        <script type="application/javascript">
            var base_url = "{{url('/')}}";
            var master_prefix = "{{master_prefix}}";
        </script>
        
        <!-- custom stylesheets -->
        @yield('css') 
        <!-- /custom stylesheets -->
        
        <!-- custom style -->
        @stack('style')
        <!-- custom style -->
        
        <!-- custom js top -->
        @yield('js_top') 
        <!-- /custom js top -->
</head>