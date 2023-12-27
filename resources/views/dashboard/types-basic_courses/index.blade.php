@extends('App.dash')
@section('style')
    <style>
        #example_wrapper {
            width: 100% !important;
        }

        .all-products #btn1 {
            margin-right: 0 !important;
        }

        .all-products #btn2 {
            margin-right: 0 !important;
        }
    </style>
@endsection
@section('content')
    <!--start page-body-->
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
                    <div class="row def">

                        <img src="images/all-products.svg">
                        <h5>الدورات التعلميه الشهريه </h5>



                    </div>

                    <div class="products-search typs1">
                        <div class="row">
                            <button class="btn" style="width: fit-content">
                                <a href="{{ route('addtype') }}"> <span><i class="fas fa-plus-circle"></i></span>
                                    اضافة دوره تعلميه شهريه
                                </a>
                            </button>

                        </div>

                    </div>



                    <div class="pt-5">
                        <div class="row">
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label>المرحله</label>
                                <select class="form-control selectpicker new" name="stage_id" id="stage_id"
                                    onchange="getstage(this); filtertypes()" title="ادخل المرحله">
                                    {{-- <option value="0" selected="selected" required disabled="disabled">ادخل المرحله</option> --}}
                                    @foreach ($stages as $stage)
                                        <option value='{{ $stage->id }}'>{{ $stage->name_ar }}</option>
                                    @endforeach
                                </select>
                                @error('stage_id')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label>سنه الماده</label>
                                <select class="form-control selectpicker new" name="years_id" required id="years_id"
                                    onchange="getyear(this); filtertypes()" title="اختر السنه">
                                    {{-- <option value="0" selected="selected" disabled="disabled">اختر السنه</option> --}}

                                </select>
                                @error('years_id')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label>الماده </label>
                                <select class="form-control selectpicker new" name="subjects_id" required id="subjects_id"
                                    onchange="getteacher(this); filtertypes()" title="اختر الماده">
                                    {{-- <option value="0" selected="selected" disabled="disabled">اختر الماده</option> --}}

                                </select>
                                @error('subjects_id')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-lg-3 col-md-6 col-12">
                                <label>الشهر </label>
                                <input type="month" class="form-control" id="month" name="month"
                                    {{-- value="{{ \Carbon\Carbon::now()->format('Y-m') }}" --}} />
                                @error('month')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- <div
                                class="form-group col-lg-3 col-md-6 col-12 d-flex justify-content-center align-items-center flex-column">
                                <label style="opacity: 0" class="w-100 d-block">الماده </label>
                                <span class="btn btn-primary d-block" style="width: 75%; display: block; margin: 0 auto;"
                                    onclick="filtertypes()">بحث</span>
                            </div> --}}
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

                            {{-- <table id="example" class="table col-12 table-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th scope="col" class="text-center">الدوره االتعلميه الشهريه</th>
                                        <th scope="col" class="text-center"> المدرس</th>
                                        <th scope="col" class="text-center"> نوع المنصة</th>

                                        <th scope="col">الماده</th>
                                        <th scope="col" class="text-center">السنه</th>
                                        <th scope="col" class="text-center">تاريخ إنشاء الكورس</th>
                                        <th scope="col" class="text-center">الاعدادات</th>
                                    </tr>
                                </thead>
                                <tbody id="types">
                                    @foreach ($types as $type)
                                        <tr id="type{{ $type->id }}">
                                            <td class="text-center">{{ $type->id }}</td>
                                            <td scope="row" class="text-center"><a
                                                    href="{{ route('subtypes', $type->id) }}">{{ $type->name_ar }}</a>
                                            </td>
                                            <td class="text-center">
                                                @if ($type->user)
                                                    {{ $type->user->name }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $type->center ? $type->center->name ?? 'المنصه العامه' : 'المنصه العامه' }}
                                            </td>
                                            <td class="text-center">
                                                @if ($type->subject)
                                                    {{ $type->subject->name_ar }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($type->year)
                                                    {{ $type->year->year_ar }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $type->created_at->format('Y-m-d') }}</td>
                                            <td class="text-center">
                                                <div class="opitions">
                                                    <a href="{{ route('edittype', $type->id) }}"> <img
                                                        src="{{ asset('images/pen.svg') }}" id="pen"
                                                        style="cursor: pointer"></a>
                                                    @if (auth()->user()->hasPermission('types-delete'))
                                                        <img src="{{ asset('images/trash.svg') }}" id="trash"
                                                            onclick="deletetype('{{ $type->id }}')"
                                                            style="cursor:pointer;">
                                                    @endif
                                                    <span class="btn bg-success btn-success text-white btn-sm"
                                                        id="btn{{ $type->id }}"
                                                        onclick="activetype({{ $type->id }})">
                                                        @if ($type->active == 1)
                                                            الغاء التفعيل
                                                        @else
                                                            تفعيل
                                                        @endif

                                                    </span>
                                                    <a href="{{ route('grouptypes', $type->id) }}"
                                                        class="btn btn-success btn-sm">المجموعات</a>
                                                    <a href="{{ route('studentstype', $type->id) }}"
                                                        class="btn btn-success btn-sm">الطلاب</a>
                                                    <a href="{{ route('bannedStudentstype', $type->id) }}"
                                                        class="btn btn-danger btn-sm"> الطلاب المحذوفين </a>
                                                    <a href="{{ route('typeexams', $type->id) }}"
                                                        class="btn btn-success btn-sm">الامتحانات</a>
                                                    <span class="btn btn-success btn-sm" data-toggle="modal"
                                                        data-target="#myModal{{ $type->id }}">create qrcode</span>
                                                    <a href="{{ route('types.patches', $type->id) }}" title="QrCode History"
                                                        class="text-dark ml-2"><i class="fas fa-cog"></i></a>
                                                    <a href="{{ route('security', $type->id) }}" title="اعدادات الامان"
                                                        class="text-dark ml-2"><i class="fas fa-cog"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal" id="myModal{{ $type->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">create qrcode
                                                        </h4>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label>count </label>
                                                                <input class="form-control" value="1"
                                                                    id="count{{ $type->id }}">
                                                            </div>

                                                            <div class="col-12">
                                                                <label>expire date </label>
                                                                <input class="form-control" type="date"
                                                                    id="expire_date{{ $type->id }}">
                                                            </div>
                                                            <div class="col-md-6 col-12">
                                                                <div id="qrcodes{{ $type->id }}"></div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-4">
                                                            <button type="button" class="btn btn-success mx-auto"
                                                                onclick="store_qrcodes({{ $type->id }})">save</button>

                                                            <button class="btn btn-primary waves-effect waves-light mr-12"
                                                                type="button"
                                                                onclick=" printDiv('qrcodes{{ $type->id }}');">
                                                                طباعة ال QR
                                                            </button>

                                                        </div>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table> --}}

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
    <!--end page-body-->
@endsection
@section('scripts')
    {{ $dataTable->scripts() }}

    <script>
        function getstage(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getstage/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#years_id').empty();
                    $('#years_id').html(result);
                    $('#years_id').selectpicker('refresh');
                    $('#subjects_id').selectpicker('refresh');
                }

            });
        }

        function getyear(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getyear/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#subjects_id').empty();
                    $('#subjects_id').html(result);
                    $('#subjects_id').selectpicker('refresh');
                }

            });
        }
    </script>

    <script>
        function filtertypes() {
            $('#dataTableBuilder').on('preXhr.dt', function(e, settings, data) {
                //basic filters
                data.stage_id = $("#stage_id").val();
                data.years_id = $("#years_id").val();
                data.subjects_id = $("#subjects_id").val();
                data.month = $("#month").val();

            });
            $('#dataTableBuilder').DataTable().ajax.reload();
        }
    </script>
@endsection
