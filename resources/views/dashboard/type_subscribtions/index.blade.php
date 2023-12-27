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

    <div class="page-body">
        <div class="container">
            <!--start heed-->
            <div class="heed">

                <div class="row">
                    <div class="profile">
                        <div class="row">
                            <div class="col-3">
                                <img src="{{ asset('images/profile.svg') }}">
                            </div>
                            <div class="col-6">
                                <h5>{{ auth()->user()->name }}</h5>
                                <p>ادمن</p>

                            </div>


                        </div>
                    </div>
                    <div class="flag">

                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset('images/flag.svg') }}">
                            </div>
                            <div class="col-4">
                                <h5>العربية</h5>


                            </div>



                        </div>

                    </div>


                    <div class="noti text-center">
                        <span><i class="far fa-bell"></i></span>
                    </div>



                    <div class="search">

                        <input type="text" name="search">
                        <span class="srch"><i class="fas fa-search"></i></span>

                    </div>

                    <div class="datee">
                        <div class="row">
                            <span><i class="far fa-calendar-alt"></i></span>
                            <p>{{ Carbon\Carbon::now()->format('d-m-Y') }}</p>
                        </div>
                    </div>


                </div>


            </div>
            <!--end heed-->


            <!--start setting-->
            <div class="setting all-products typs">
                <div class="container">

                    <div class="all-infor">
                        <div class="row" id="category_id_basic">
                            <h4 class="hederre">
                                المرحله الأساسيه
                            </h4>
                            {{-- انا عملت ملف جديد هنا عشان افصل كل حاجة لوحدها عشان متبوظش حاجة هنا  ^_^ --}}
                            @include('dashboard.type_subscribtions.includes.__basic_filter_sections')
                        </div>


                        <div class="row" id="subscription_type">
                            <h4 class="hederre">
                                نوع الاشتراك
                            </h4>
                            {{-- انا عملت ملف جديد هنا عشان افصل كل حاجة لوحدها عشان متبوظش حاجة هنا  ^_^ --}}
                            @include('dashboard.type_subscribtions.includes.__subscription_type')

                        </div>



                        <div class="row">
                            <div class="table-responsive">

                                {!! $dataTable->table(
                                    [
                                        'class' => 'table_expenses table_topic table table-striped table-bordered',
                                    ],
                                    true,
                                ) !!}

                            </div>
                        </div>

                    </div>

                </div>

            </div>
            <!--end setting-->


            <!--start foter-->
            <div class="foter">
                <div class="row">
                    <div class="col-12 text-center">
                        <h5>Made With <img src="{{ asset('images/red.svg') }}"> By Crazy Idea </h5>
                        <p>Think Out Of The Box</p>
                    </div>
                </div>
            </div>
            <!--end foter-->
        </div>
    </div>
    <!--end::Card-->
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

    {{ $dataTable->scripts() }}
    <script>
        function filter_students() {
            // console.log("filtering",$("#subscription_type :selected").val());
            // console.log("filtering_2",$("#stage_id").val());
            $('#dataTableBuilder').on('preXhr.dt', function(e, settings, data) {
                //basic filters
                data.stage_id = $("#stage_id").val();
                data.years_id = $("#years_id").val();
                data.subjects_id = $("#subjects_id").val();
                data.subscription_type = $("#subscription_type :selected").val();
                data.type_id = $("#type_id").val();

            });

            $('#dataTableBuilder').DataTable().ajax.reload();
        }
    </script>
@endsection
