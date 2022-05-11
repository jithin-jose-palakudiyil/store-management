@extends('master::layouts.master')

@section('content')
    
<div class="panel panel-white">
    <div class="panel-heading">
        <h6 class="panel-title">Activity log</h6>
    </div>
    <table class="table table-bordered mb-5">
            <thead>
                <tr class="table-success">
                    <th scope="col">Activity</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activity_log as $data)
                <tr>
                    
                    <td class="descriptionClass">{!! $data->description !!}</td>
                    <td><?php echo \Carbon\Carbon::parse($data->created_at)->diffForHumans(); ?> </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center pull-right ">
            {!! $activity_log->links("pagination::bootstrap-4") !!}
        </div>
</div>
				 

@endsection

@section('css')  
<style>
.descriptionClass b {color: red}
</style>
@stop