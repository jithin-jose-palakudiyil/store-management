@include('master::layouts.partials.header')

<body class="navbar-top-md-md"> 
	<!-- Fixed navbars wrapper -->
	<div class="navbar-fixed-top"> 
            <!-- Main navbar -->
            @include('master::layouts.partials.main-navbar')
            <!-- /main navbar -->

            <!-- Second navbar -->
            @include('master::layouts.partials.second-navbar')
            <!-- /second navbar --> 
	</div>
	<!-- /fixed navbars wrapper -->
 
	<!-- Page header -->
	@include('master::layouts.partials.breadcrumb')
	<!-- /page header -->
 
	<!-- Page container -->
	<div class="page-container"> 
            <!-- Page content -->
            <div class="page-content"> 
                <!-- Main content -->
                <div class="content-wrapper">
                    @include('master::layouts.partials.flash-message')    
                    @yield('content')	 
                </div>
                <!-- /main content --> 
            </div>
            <!-- /page content --> 
	</div>
	<!-- /page container -->
 
	<!-- Footer -->
	@include('master::layouts.partials.footer')
	<!-- /footer --> 
</body>
</html>
