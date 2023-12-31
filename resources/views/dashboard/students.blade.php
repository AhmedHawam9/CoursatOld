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
                            <div class="table-responsive">

                                <table id="example" class="table col-12" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th scope="col" class="text-center">الاسم</th>
                                            <th scope="col" class="text-center">الكود</th>
                                            <th scope="col" class="text-center">رقم الهاتف</th>
                                            <th scope="col" class="text-center">الكورسات</th>


                                            <!--  <th scope="col" class="text_center">السنه</th>-->
                                            <th scope="col" class="text-center">الاعدادات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr id="s{{ $student->id }}">

                                                <th>{{ $student->id }}</th>
                                                <td scope="col" class='text-center'><a
                                                        href="{{ route('studentprofile', $student->id) }}">{{ $student->name }}</a>
                                                </td>
                                                <td scope="col" class='text-center'>{{ $student->code }}</td>
                                                <td scope="col" class='text-center'>{{ $student->phone }}</td>
                                                @if ($student->year_id != null)
                                                    <td scope="col" class="text-center">
                                                        <ul>
                                                            @if ($student->stutypes)
                                                                @foreach ($student->stutypes as $type)
                                                                    <li style="font-size:14px;">{{ $type->name_ar }}</li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </td>
                                                @elseif($student->university_id != null)
                                                    <td scope="col" class="text-center">
                                                        <ul>
                                                            @if ($student->stutypescollege)
                                                                @foreach ($student->stutypescollege as $typecollege)
                                                                    <li style="font-size:14px;">{{ $typecollege->name_ar }}
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>

                                                    </td>
                                                @else
                                                    <td scope="col" class="text-center">


                                                    </td>
                                                @endif
                                                <td class="text-center">
                                                    <span class="btn  btn-sm"style="border:1px solid #222; margin-bottom:10px; padding:6px 45px" onclick="student_logout({{ $student->id }})">
                                                        تسجيل الخروج
                                                    </span>
                                                    <span
                                                        class="btn  btn-sm"style="border:1px solid #222; margin-bottom:10px; padding:6px 45px"
                                                        id="btn{{ $student->id }}"
                                                        onclick="activeuser({{ $student->id }})">
                                                        @if ($student->active == 1)
                                                            الغاء التفعيل
                                                        @else
                                                            تفعيل
                                                        @endif
                                                    </span>
                                                    <img src="{{ asset('images/trash.svg') }}" id="trash"
                                                        onclick="deleteuser('{{ $student->id }}')"
                                                        style="cursor:pointer;">
                                                    @if ($student->category_id == 1)
                                                        <a class="btnbtn-sm mt-2"
                                                            style="border:1px solid #222; margin-bottom:10px; font-size:13px; display:block;    padding: 10px 10px; width: 60%; "
                                                            href="{{ route('typeresults_students', $student->id) }}">نتائج
                                                            الامتحانات</a>
                                                    @elseif($student->category_id == 2)
                                                        <a class="btn btn-sm mt-2"
                                                            style="border:1px solid #222; margin-bottom:10px; padding:6px 20px"
                                                            href="{{ route('typecollegeresults_students', $student->id) }}">نتائج
                                                            الامتحانات</a>
                                                    @endif
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

        function student_logout(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `student_logout/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    if (result.status == true) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'تم  تسجيل الخروج ',
                            showConfirmButton: false,
                            timer: 1500
                        });


                    }

                }

            });
        }
    </script>
@endsection
