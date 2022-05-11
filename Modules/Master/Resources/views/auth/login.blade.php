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
	<script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
        <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>
	<script src="{{asset('public/pages/js/login.js')}}" type="text/javascript"></script>
	
	<script src="{{asset('Modules/Master/Resources/assets/js/login.js')}}"></script>
	<style>  .AbsoluteCenter { margin: auto; position: absolute;  max-height: 480px; top: 0; left: 0; bottom: 0; right: 0; } </style>
        
	<!-- /theme JS files -->

</head>

<body class="login-container">
    <div class="AbsoluteCenter"> 
	<!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <!-- Main content -->
                <div class="content-wrapper">

                    <!-- login -->
                    <form action="{{route('master_login')}}" method="post" autocomplete="off" id="master_login">
                        {{ csrf_field() }}
                        <div class="panel panel-body login-form">
                            <div class="text-center">
                                <h5 class="content-group-lg">Login to your account <small class="display-block">Enter your credentials</small></h5>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" name="username" class="form-control" placeholder="Username">
                                <div class="form-control-feedback"> <i class="icon-user text-muted"></i> </div>
                                <div id="username_error">
                                    @if($errors->has('username'))
                                    <div class="validation-error-label">{{ $errors->first('username') }}</div>
                                    @endif
                                 </div>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <input type="password" class="form-control" placeholder="Password" name="password">
                                <div class="form-control-feedback"> <i class="icon-lock2 text-muted"></i> </div>
                                <div id="password_error">
                                    @if($errors->has('password'))
                                    <div class="validation-error-label">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group login-options">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" class="styled" checked="checked">
                                            Remember
                                        </label>
                                    </div> 
                                </div>
                            </div>
                            @if($errors->has('message'))
                            <div class="form-group">
                                <label class="validation-error-label">
                                    {{ $errors->first('message') }}
                                </label> 
                            </div>    
                            @endif
                            <div class="form-group">
                                <button type="submit" class="btn bg-blue btn-block">Login <i class="icon-arrow-right14 position-right"></i></button>
                            </div>   
                        </div>
                    </form>
                    <!-- /login -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

        </div>
	<!-- /page container -->
    </div>
</body>
</html>


