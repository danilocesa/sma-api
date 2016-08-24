@extends('layouts.master')
@push('scripts')
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" ></script>
    <script>
    	$(document).ready(function(){
		    // $('#myTable').DataTable();

		    $('#myTable').DataTable({
	            processing: true,
	            serverSide: true,
	            ajax: 'http://localhost/halohalo-api/public/logsData'
	        });
		});


    </script>
@endpush


@section('content')
   <table id="myTable"></table>
@endsection

