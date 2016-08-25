@extends('layouts.master')

@section('main-content')
 <div class="col-md-6">
  <div class="x_panel">
    <div class="x_title">
      <h2>Top 7 Players</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
    <table class="table">
      <thead>
        <tr>
          <th>Username</th>
          <th>Score</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      	@foreach($top_players as $top_player)
        <tr>
          <td>{{ $top_player->username }}</td>
          <td>{{ $top_player->total_score }}</td>
          <td><a href="{{ url('heymen/players/top-details/'.$top_player->user_id) }}" class="btn btn-info">View Details</a></td>
        </tr>
         @endforeach
      </tbody>
    </table>
    </div>
  </div>
</div>

 <div class="col-md-6">
 	<div class="x_panel">
 		<div class="x_title">
	      <h2>List of Players</h2>
	      <div class="clearfix"></div>
	    </div>
	    <div class="x_content">
		   	<table class="table table-striped table-bordered" id="users-table">
		        <thead>
		            <tr>
		                <th>Name</th>
		                <th>Email</th>
		                <th>Created At</th>
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
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url("heymen/players/basic-data") }}',
        columns: [
            {data: 0, name: 'username'},
            {data: 1, name: 'email'},
            {data: 2, name: 'created_at'}
        ],
        bLengthChange: false,
		pageLength: 7
    });
});
</script>
@endpush