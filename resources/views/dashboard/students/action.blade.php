<style>
    td.text-center {
        display: flex !important;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        min-width: 200px;
    }

    td.text-center {
        width: 30%;
    }

    .table-bordered.dataTable tbody th,
    table.table-bordered.dataTable tbody td {
        border-bottom-width: 0;
        font-size: 0.8rem;
        padding: 0.5rem !important;
    }

    .width_30 {
        text-align: center;
        margin-bottom: 0.5rem;
        width: 30%;
    }

    .width_30 img {
        width: 25px;
    }
</style>

@php
    $student = \App\User::find($id);
@endphp
<td class="text-center">
    <div class="width_30">
        <img src="{{ asset('images/sex.svg') }}" alt="" title="تسجيل الخروج"
            onclick="student_logout({{ $id }})">
    </div>


    {{-- تفعيل المستخدم --}}
    <div class="width_30">
        @if ($active == 1)
            <img src="{{ asset('images/five.svg') }}" id="btn{{ $id }}" onclick="activeuser({{ $id }})"
                alt="" title="الغاء التفعيل">
        @else
            <img src="{{ asset('images/seven.svg') }}" id="btn{{ $id }}"
                onclick="activeuser({{ $id }})" alt="" title="تفعيل">
        @endif
    </div>
    {{-- تفعيل المستخدم --}}

    {{-- حذف المستخدم --}}
    <div class="width_30">
        <img src="{{ asset('images/trash.svg') }}" id="trash" onclick="deleteuser('{{ $id }}')"
            style="cursor:pointer;">
    </div>
    {{-- حذف المستخدم --}}

    <div class="width_30">
        @if ($category_id == 1)
            <a class="resl" href="{{ route('typeresults_students', $id) }}">
                <img src="{{ asset('images/eat.svg') }}" alt="" title="نتائج

            الامتحانات">

            </a>
        @elseif($category_id == 2)
            <a class="resl" href="{{ route('typecollegeresults_students', $id) }}"><img
                    src="{{ asset('images/eat.svg') }}" alt="" title="نتائج الامتحانات "></a>
        @endif
    </div>

    <div class="width_30">
        <img src="{{ asset('images/three.svg') }}" alt="" title=" ريسيت باسورد الطالب " data-toggle="modal"
            data-target="#passwordModal{{ $id }}">
    </div>


    <div class="width_30">
        <img src="{{ asset('images/two.svg') }}" alt="" title="كورسات الطالب" data-toggle="modal"
            data-target="#cousesModal{{ $id }}">
    </div>
    <div class="width_30">
        <a href="{{ route('studentprofile', $id) }}" alt="" title="بروفيل الطالب" class="resl">
            <img src="{{ asset('images/one.svg') }}" alt="" title="بروفيل الطالب">
        </a>
    </div>


</td>
<!-- The Modal -->
<div class="modal" id="passwordModal{{ $id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">ريسيت الباسورد للطالب </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-8">
                        <label for="password{{ $id }}"> كلمة المرور</label>
                        <input id="password{{ $id }}" type="password" name="password" class="form-control"
                            placeholder="كلمة المرور">

                    </div>
                    <div class="col-8">
                        <label for="password_confirmation{{ $id }}">تأكيد كلمة المرور</label>
                        <input id="password_confirmation{{ $id }}"type="password"
                            name="password_confirmation" class="form-control" placeholder="تاكيد كلمة المرور">
                    </div>
                </div>
                <div class="row  mt-4">
                    <button type="button text-center" onclick="resetstudentPassword({{ $id }})"
                        class="btn btn-success">
                        حفظ
                    </button>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="cousesModal{{ $id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">كورسات الطالب </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    @if ($student->stutypescollege)
                        <ul>
                            @foreach ($student->stutypescollege as $typecollege)
                                <img src="{{ asset('images/trash.svg') }}" id="trash{{ $typecollege->id }}"
                                    onclick="deleteuser_from_stutypescollege({{ $student->id }},{{ $typecollege->id }})"
                                    style="cursor:pointer;">
                                <li style="font-size:14px;">{{ $typecollege->name_ar }} </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($student->stutypes)
                        <ul>
                            @foreach ($student->stutypes as $type)
                                <img src="{{ asset('images/trash.svg') }}" id="trash{{ $type->id }}"
                                    onclick="deleteuser_from_stutypes({{ $student->id }},{{ $type->id }})"
                                    style="cursor:pointer;">
                                <li style="font-size:14px;">{{ $type->name_ar }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
{{-- var student_id = $(".student_id:checked").map(function() {
            return $(this).val();
        }).get() --}}
<script>
    //
    function resetstudentPassword(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.table').DataTable();
        $.ajax({
            type: "post",
            url: `resetStudentPassword/${id}`,
            //  contentType: "application/json; charset=utf-8",
            dataType: "Json",
            data: {
                'password': $(`#password${id}`).val(),
                'password_confirmation': $(`#password_confirmation${id}`).val(),
            },
            success: function(result) {
                if (result.status == true) {

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'تم تعديل الباسورد للطلاب بنجاح',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    table.ajax().reload()

                    //location.reload();
                } else if (result.status == false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: result.message,
                    })

                }
                $(`#myModal${id}`).modal('hide');



            }

        });
    }
</script>


<script>
    function deleteuser_from_stutypes(student_id, type_id) {

        console.log("type student ",student_id);
        console.log("type id",type_id);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        Swal.fire({
            title: ' هل انت متاكد من حذف الطالب من هذا المحتوي ؟',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {


                var url = "{{ route('stutypes.deleteuser_from_stutypes') }}";

                var table = $('.table').DataTable();

                $.ajax({
                    type: "post",
                    url: url,

                    //  contentType: "application/json; charset=utf-8",
                    dataType: "Json",
                    data: {
                        'student_id': student_id,
                        'type_id': type_id,
                    },
                    //    contentType: "application/json; charset=utf-8",
                    dataType: "Json",
                    success: function(result) {
                        if (result.status == true) {
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
</script>


<script>
    function deleteuser_from_stutypescollege(student_id, typecollege_id) {
        console.log("typecolege student ",student_id);
        console.log("typecolege id",typecollege_id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        Swal.fire({
            title: ' هل انت متاكد من حذف الطالب من هذا المحتوي ؟',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {


                var url = "{{ route('stutypescollege.deleteuser_from_stutypescollege') }}";

                var table = $('.table').DataTable();

                $.ajax({
                    type: "post",
                    url: url,

                    //  contentType: "application/json; charset=utf-8",
                    dataType: "Json",
                    data: {
                        'student_id': student_id,
                        'typecollege_id': typecollege_id,
                    },
                    //    contentType: "application/json; charset=utf-8",
                    dataType: "Json",
                    success: function(result) {
                        if (result.status == true) {
                            Swal.fire(
                                'Deleted!',
                                result.message,
                                'success'
                            )
                        }else{
                            Swal.fire(
                                'Error!',
                                result.message,
                                'error'
                            )
                        }
                    }

                });
            }
        })
    }
</script>
