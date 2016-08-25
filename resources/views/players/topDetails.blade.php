@extends('layouts.master')

@section('main-content')
 <div class="col-md-12">
 	<div class="x_panel">
 		<div class="x_title">
	      <h2>{{ $user->username }} translated words</h2>
	      <div class="clearfix"></div>
	    </div>
	    <div class="x_content">
		   	<table class="table table-striped table-bordered" id="topdetails-table">
		        <thead>
		            <tr>
		                <th>Base Id</th>
                    <th>Dialect Id</th>
                    <th>Translated</th>
		                <th>Sentiment</th>
		                <th>Date</th>
		            </tr>
		        </thead>
		    </table>
		</div>    
	</div>    
 </div>   
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" ></script>
<script>
 $(function() {
    $('#topdetails-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url("heymen/players/top-data/".Request::segment(4)) }}',
        bLengthChange: false,
        pageLength: 10
    });
});
</script>
@endpush