
@extends('App.dash')
@section('style')
<style>
    #example_wrapper {
        width: 100% !important;
    }
    .more {
        margin-top: 76px
    }
    .card-label h3{
     font-family: "reg";
    }
</style>
@endsection
@section('content')
	<!--begin::Card-->
    <div class="container">
     <div class="more">

    <div class="card card-custom gutter-b">

        <div class="card-header flex-wrap py-3">
		<div class="card-title">
		<h3 class="card-label">طلبات النقاط</h3>
		</div>

	 </div>
    </div>


            <!--begin: Datatable-->
<div class="table-responsive">

    {!! $dataTable->table([

        ],true) !!}
</div>

     </div>

            <!--end: Datatable-->


    <!--end::Card-->

    </div>
@endsection
@section('scripts')

<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

    {{ $dataTable->scripts() }}
 @endsection
