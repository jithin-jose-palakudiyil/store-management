@extends('master::layouts.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat">
            <!--								<div class="panel-heading">
            <h6 class="panel-title">Browse articles<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>

            </div>-->

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i> Stock Register </h6>
                            <div class="list-group no-border">
                                <a href="{{route('stock_reports','store-wise-register')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Store Wise Register
                                </a>

                                <a href="{{route('stock_reports','category-wise-register')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Category Wise Register
                                </a>

                                <a href="{{route('stock_reports','store-and-category-wise-register')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Store & Category Wise Register
                                </a> 
                                <a href="{{route('stock_reports','price-wise-register')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Price Wise Register
                                </a> 
                                <a href="{{route('stock_reports_download','no-stock-register')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i>No Stock Register
                                </a>
                                <a href="{{route('stock_reports_download','low-stock-register')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i>Low Stock Register
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i>Breakage Reports </h6>
                            <div class="list-group no-border">
                                <a href="{{route('breakage_reports','store-wise-breakage-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Store Wise Breakage Report
                                </a>

                                <a href="{{route('breakage_reports','category-wise-breakage-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Category Wise Breakage Report
                                </a>

                                <a href="{{route('breakage_reports','store-and-category-wise-breakage-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Store & Category Wise Breakage Report
                                </a>  
                            </div>
                        </div>
                    </div>
                    
                   
                      <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i>Breakdown Reports </h6>
                            <div class="list-group no-border">
                                <a href="{{route('breakdown_reports','store-wise-breakdown-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Store Wise Breakdown Report
                                </a>

                                <a href="{{route('breakdown_reports','category-wise-breakdown-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Category Wise Breakdown Report
                                </a>

                                <a href="{{route('breakdown_reports','store-and-category-wise-breakdown-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Store & Category Wise Breakdown Report
                                </a> 
<!--                                <a href="{{route('breakage_reports','item-wise-breakage-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Item Wise Breakage Report
                                </a> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i>Consumption Report </h6>
                            <div class="list-group no-border"> 
                                <a href="{{route('consumption_reports','store-wise-consumption-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Store Wise Consumption Report
                                </a> 
                                <a href="{{route('consumption_reports','item-wise-consumption-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Item Wise Consumption Report
                                </a>
                                 <a href="{{route('consumption_reports','store-and-item-wise-consumption-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i>Store & Item Wise Consumption Report
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i>Maintenance Report </h6>
                            <div class="list-group no-border"> 
                                <a href="{{route('maintenance_reports','maintenance-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Maintenance Report
                                </a> 
                                <a href="{{route('maintenance_reports','item-wise-maintenance-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Item Wise Maintenance Due Report
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i>Calibration Report </h6>
                            <div class="list-group no-border"> 
                                <a href="{{route('calibration_reports','calibration-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Calibration Report
                                </a> 
                                <a href="{{route('calibration_reports','item-wise-calibration-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Item Wise Calibration Due Report
                                </a>
                            </div>
                        </div>
                    </div>
<!--                    <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i>Gate Pass Report </h6>
                            <div class="list-group no-border"> 
                                <a href="#" class="list-group-item">
                                    <i class=" icon-unlink"></i> Item Wise Gate Pass Report
                                </a> 
                                <a href="#" class="list-group-item">
                                    <i class=" icon-unlink"></i> Store Wise Gate Pass Report
                                </a> 
                            </div>
                        </div>
                    </div>-->
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i>Expiry Reports </h6>
                            <div class="list-group no-border">
                                <a href="{{route('expiry_reports_download','expired-items-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Expired Items Report
                                </a>

                                <a href="{{route('expiry_reports','expiring-items-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Expiring Items Report
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i>License Reports </h6>
                            <div class="list-group no-border">
                                <a href="{{route('license_reports_download','license-renewed-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> license Renewed Report
                                </a>

                                <a href="{{route('license_reports','license-renewed-in-range-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> License Renewed In Range Report
                                </a>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="content-group">
                            <h6 class="text-semibold heading-divided"><i class="icon-folder6 position-left"></i>Gate Pass </h6>
                            <div class="list-group no-border">
                                <a href="{{route('gate_pass_reports_download','gate-pass-report')}}" class="list-group-item">
                                    <i class=" icon-unlink"></i> Gate Pass Report
                                </a>

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
