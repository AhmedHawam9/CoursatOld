@extends('App.dash')
@section('style')
    <style>
        #example_wrapper {
            width: 100% !important;
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
                        <h5>الطلاب</h5>



                    </div>

                    <div class="products-search typs1">


                    </div>



                    <div class="pt-5">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>المرحله</label>
                                <select class="form-control selectpicker" name="stage_id" onchange="getstage(this)">
                                    <option value="0" selected="selected" required disabled="disabled">ادخل المرحله
                                    </option>
                                    @foreach ($stages as $stage)
                                        <option value='{{ $stage->id }}'>{{ $stage->name_ar }}</option>
                                    @endforeach
                                </select>
                                @error('stage_id')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <label>سنه الماده</label>
                                <select class="form-control selectpicker" name="years_id" required id="year"
                                    onchange="getyear(this)">
                                    <option value="0" selected="selected" disabled="disabled">اختر السنه</option>

                                </select>
                                @error('years_id')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- <div class="form-group col-4">
                                <label>الماده </label>
                                <select class="form-control selectpicker" name="subjects_id" required id="subject"
                                    onchange="getteacher(this)">
                                    <option value="0" selected="selected" disabled="disabled">اختر الماده</option>

                                </select>
                                @error('subjects_id')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-3 mx-auto">


                                <span class="btn btn-primary" onclick="filterbasicstudents()">بحث</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">

                                <table id="example" class="table col-12" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th scope="col" class="text-center">الاسم</th>
                                            <th scope="col" class="text-center">الكود</th>
                                            <th scope="col" class="text-center">الكورسات</th>
                                            <!--	<th scope="col" class="text-center">المدينه</th>-->

                                            <!--  <th scope="col" class="text_center">السنه</th>-->
                                            <th scope="col" class="text-center">الاعدادات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="students">
                                        @foreach ($students as $student)
                                            <tr id="s{{ $student->id }}">

                                                <th>{{ $student->id }}</th>
                                                <td scope="col" class='text-center'><a
                                                        href="{{ route('studentprofile', $student->id) }}">{{ $student->name }}</a>
                                                </td>
                                                <td scope="col" class='text-center'>{{ $student->code }}</td>
                                                <td scope="col" class="text-center">
                                                    <ul>
                                                        @if ($student->stutypes)
                                                            @foreach ($student->stutypes as $type)
                                                                <li style="font-size:14px;">{{ $type->name_ar }}</li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </td>

                                                @php
                                                    $types = \App\Type::where('years_id', $student->years_id)
                                                    // ->whereNotIn('id', $student->stutypes->pluck('id'))
                                                        ->get();
                                                @endphp
                                                <td class="text-center">
                                                    <span class="btn btn-success btn-sm" id="btn{{ $student->id }}"
                                                        onclick="activeuser({{ $student->id }})">
                                                        @if ($student->active == 1)
                                                            الغاء التفعيل
                                                        @else
                                                            تفعيل
                                                        @endif
                                                    </span>
                                                    @if ($student->phone_verify != 1)
                                                        <span
                                                            class="btn btn-success btn-sm phone_verify{{ $student->id }}"
                                                            onclick="phone_verify({{ $student->id }})">
                                                            phone verify
                                                        </span>
                                                    @endif
                                                    <!-- Button to Open the Modal -->
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        style="font-size:13px;width:52px;" data-toggle="modal"
                                                        data-target="#myModal{{ $student->id }}">
                                                        كورس
                                                    </button>

                                                    <!-- The Modal -->
                                                    <div class="modal" id="myModal{{ $student->id }}">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">

                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">ادخال الطالب لكورس </h4>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">&times;</button>
                                                                </div>

                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-8">


                                                                            <select name="type_id"
                                                                                class="form-control selectpicker"
                                                                                id="course{{ $student->id }}">
                                                                                @foreach ($types as $type)
                                                                                    <option value="{{ $type->id }}">
                                                                                        {{ $type->name_ar }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-4">
                                                                        <button type="button text-center"
                                                                            onclick="addtypestudent({{ $student->id }})"
                                                                            class="btn btn-success">
                                                                            حفظ
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <img src="{{ asset('images/trash.svg') }}" id="trash"
                                                        onclick="deleteuser('{{ $student->id }}')"
                                                        style="cursor:pointer;">

                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

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
    <!--end page-body-->
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>


    <script>
        $(document).ready(function() {

            $('#example').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                }, ] // Order on init. # is the column, starting at 0

            });
        });

        function activeuser(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `activeuser/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    if (result.status == 'deactive') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'تم الغاء التفعيل ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $(`#btn${id}`).html('تفعيل');

                    } else if (result.status == 'active') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'تم التفعيل  ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $(`#btn${id}`).html('الغاء التفعيل');

                    }

                }

            });
        }

        function deleteuser(sel) {
            let id = sel;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            Swal.fire({
                title: 'هل انت متاكد',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({

                        url: `deleteuser/${id}`,
                        //    contentType: "application/json; charset=utf-8",
                        dataType: "Json",
                        success: function(result) {
                            if (result.status == true) {
                                $(`#s${id}`).remove();
                                Swal.fire(
                                    'Deleted!',
                                    'تم مسح المستخدم بنجاح',
                                    'success'
                                )
                            }
                        }

                    });
                }


            })
        }

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
                    $('#year').empty();
                    $('#year').html(result);
                    $('#year').selectpicker('refresh');
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
                    $('#subject').empty();
                    $('#subject').html(result);
                    $('#subject').selectpicker('refresh');
                }

            });
        }

        function filterbasicstudents() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: `filterbasicstudents`,
                //   contentType: "application/json; charset=utf-8",
                dataType: "Json",
                data: {
                    "years_id": $("#year").val(),


                },
                success: function(result) {
                    if (result.status == true) {

                        $('#example').DataTable().destroy();
                        $("#students").empty();
                        $("#students").append(result.data);
                        $('#example').DataTable().draw();
                    }

                }

            });
        }

        function addtypestudent(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var student_id = $(".student_id:checked").map(function() {
                return $(this).val();
            }).get()
            $.ajax({
                type: "post",
                url: `addtypestudent/${id}`,
                //  contentType: "application/json; charset=utf-8",
                dataType: "Json",
                data: {
                    'course_id': $(`#course${id}`).val()
                },
                success: function(result) {
                    if (result.status == true) {

                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'تم اضافه الكورس للطلاب بنجاح',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $(`#myModal${id}`).modal('hide');

                        //location.reload();
                    } else if (result.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: result.message,
                        })
                        //$().reload()

                    }

                }

            });
        }


        function phone_verify(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `phone_verify/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    if (result.status == true) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'تم  بنجاح ',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        $(`.phone_verify${id}`).remove();
                    }

                }

            });
        }
    </script>
@endsection
