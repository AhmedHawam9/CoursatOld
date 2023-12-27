<style>
    h3.card-label {
    font-family: 'med';
}
.card-toolbar a.btn.first {
    background-color: #243e56;
    color: white;
    font-family: 'reg';

}
.table tbody tr td {
    background: unset !important;
}
.table thead tr th {
    background: unset !important;
}
div#dataTableBuilder_wrapper {
    font-family: 'med';
}
button {
    background-color: #243e56 !important;
    color: white !important;
    border: unset !important;
    font-family: 'reg';
    border-radius: 10px !important;
    padding: 0.5rem 1rem !important;
    margin-right: 27px !important;
    cursor: pointer !important;
    transition: 0.7s
}
button:hover {
    background-color: white;
    color: #243e56;
}
div#dataTableBuilder_length {
    margin-top: 19px;
}
.more {
    margin-top: 76px;
}
@media (min-width: 1024px){
    .container {
        max-width: 839px  !important;
}
}
@media (min-width: 1200px){
    .container {
        max-width: 960px !important;
}
}

</style>



@extends('App.dash')
@section('style')
<style>
    #example_wrapper {
        width: 100% !important;
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
											<h3 class="card-label">الفيديوهات القصيره
</h3>
										</div>
										<div class="card-toolbar">

                                            <a class="btn first" href="{{route('reels.create')}}"> <i class="bi bi-plus"></i>اضافه فيديو قصير +</a>

											<!--end::Button-->
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
@endsection
@section('scripts')

<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

    {{ $dataTable->scripts() }}
 @endsection
