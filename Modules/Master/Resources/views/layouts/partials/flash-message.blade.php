
@if(Session::has('flash-success-message'))
<div class="alert bg-success text-white alert-styled-left alert-dismissible" style="background-color: #009688 !important;">
    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
    <span class="font-weight-semibold">Well done!</span> {!! Session::get('flash-success-message')!!}
</div> 
@endif
 
@if(Session::has('flash-error-message')) 
<div class="alert bg-danger text-white alert-styled-left alert-dismissible">
    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
    <span class="font-weight-semibold">Oh snap!</span> {!! Session::get('flash-error-message') !!}.
</div>
@endif

