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

                        <img src="{{ asset('images/all-products.svg') }}">
                        <h5>الحصص</h5>



                    </div>

                    <div class="products-search typs1">
                        <div class="row">
                            <div class="col-3">
                                <button class="btn">
                                    <a href="{{ route('addsubtype', $id) }}"> <span><i class="fas fa-plus-circle"></i></span>
                                        اضافة حصه
                                    </a>
                                </button>
                            </div>




                            <div class="col-4">

                            </div>

                            <div class="col-3">
                                <button class="btn">
                                    <a href="{{ route('addspecialbasic', $id) }}"> <span><i
                                                class="fas fa-plus-circle"></i></span>
                                        اضافة حصه مخصصه
                                    </a>
                                </button>



                            </div>

                        </div>

                    </div>



                    <div class="pt-5">
                        <div class="row">

                            <table id="example" class="table col-12" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th scope="col" class="text-center">الحصه</th>
                                        <th scope="col">الماده</th>
                                        <th scope="col" class="text-center">السنه</th>
                                        <th scope="col" class="text-center">الدوره التعلميه الشهريه</th>
                                        <th scope="col" class="text-center">النوع</th>
                                        <th scope="col" class="text-center">رقم الحصه</th>
                                        <th scope="col" class="text-center">الاعدادات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subtypes as $subtype)
                                        <tr id="subtype{{ $subtype->id }}">
                                            <td class="text-center">{{ $subtype->id }} </td>
                                            <td scope="row">
                                                @if ($subtype->status == 0)
                                                    <a
                                                        href="{{ route('videos', $subtype->id) }}">{{ $subtype->name_ar }}</a>
                                                @else
                                                    {{ $subtype->name_ar }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $subtype->subject->name_ar }} </td>
                                            <td class="text-center">{{ $subtype->year->year_ar }} </td>
                                            <td class="text-center">{{ $subtype->type->name_ar }} </td>
                                            <td>
                                                @if ($subtype->status == 0)
                                                    عاديه
                                                @else
                                                    مخصصه
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $subtype->order_number }} </td>
                                            <td class="text-center">
                                                @if ($subtype->status == 0)
                                                    <a href="{{ route('editsubtype', $subtype->id) }}"> <img
                                                            src="{{ asset('images/pen.svg') }}" id="pen"
                                                            style="cursor: pointer"></a>
                                                @else
                                                    <a href="{{ route('editspecialbasic', $subtype->id) }}"> <img
                                                            src="{{ asset('images/pen.svg') }}" id="pen"
                                                            style="cursor: pointer"></a>
                                                @endif
                                                @if (auth()->user()->hasPermission('subtypes-delete'))
                                                    <img src="{{ asset('images/trash.svg') }}" id="trash"
                                                        onclick="deletesubtype('{{ $subtype->id }}')"
                                                        style="cursor:pointer;">
                                                @endif
                                                <span class="btn btn-success btn-sm" id="btn{{ $subtype->id }}"
                                                    onclick="activesubtype({{ $subtype->id }})">
                                                    @if ($subtype->active == 1)
                                                        الغاء التفعيل
                                                    @else
                                                        تفعيل
                                                    @endif
                                                </span>

                                                <a href="{{ route('subtypeexams', $subtype->id) }}"
                                                    class="btn btn-success btn-sm mt-2">الامتحانات</a>
                                                <a href="{{ route('subtypeattendstudents', $subtype->id) }}"
                                                    class="btn btn-success btn-sm mt-2">الحاضرين</a>
                                                <span class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#myModal{{ $subtype->id }}">create qrcode</span>
                                                <a href="{{ route('subtype.subtype_patches', $subtype->id) }}" title="QrCode History"
                                                    class="text-dark ml-2"><i class="fas fa-cog"></i></a>
                                            </td>
                                        </tr>
                                        <div class="modal" id="myModal{{ $subtype->id }}" tabindex="-1" role="dialog"
                                            aria-hidden="true">
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
                                                                    id="count{{ $subtype->id }}">
                                                            </div>

                                                            <div class="col-12">
                                                                <label>expire date </label>
                                                                <input class="form-control" type="date"
                                                                    id="expire_date{{ $subtype->id }}">
                                                            </div>
                                                            <div class="col-md-6 col-12">
                                                                <div id="qrcodes{{ $subtype->id }}"></div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-4">
                                                            <button type="button" class="btn btn-success mx-auto"
                                                                onclick="store_qrcodes({{ $subtype->id }})">save</button>

                                                            <button class="btn btn-primary waves-effect waves-light mr-12"
                                                                type="button"
                                                                onclick=" printDiv('qrcodes{{ $subtype->id }}');">
                                                                طباعة ال QR
                                                            </button>

                                                        </div>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>

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
        function store_qrcodes(subtype_id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                data: {
                    'type_id': subtype_id,
                    'count': $(`#count${subtype_id}`).val(),
                    'expire_date': $(`#expire_date${subtype_id}`).val(),
                },
                url: `{{ route('store_sub_type_qrcode') }}`,
                dataType: "Json",
                success: function(result) {
                    if (result.status == true) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: result.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $(`#qrcodes${subtype_id}`).html(result.html);

                        // $(`#myModal${subtype_id}`).modal('hide');
                        // table.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: result.message
                        })
                    }
                }
            });
        }
    </script>
    <script>
        function printDiv(divName) {
            var PrintContent = document.getElementById(divName).innerHTML;
            const y = window.top.outerHeight / 2 + window.top.screenY - (530 / 2);
            const x = window.top.outerWidth / 2 + window.top.screenX - (400 / 2);
            var PrintWindow = window.open('', '',
                `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=400, height=530, top=${y}, left=${x}`
            );
            PrintWindow.document.write('<html><head></head><body>');
            PrintWindow.document.write(PrintContent);
            PrintWindow.document.write('</body></html>');
            setTimeout(function() {
                PrintWindow.focus();
                PrintWindow.print();
                PrintWindow.close();
            }, 500);
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "order": [
                    [0, "desc"]
                ], // Order on init. # is the column, starting at 0});
                columnDefs: [{
                    targets: 0,
                    visible: false,


                }, ]

            });
        });

        function activesubtype(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `../activesubtype/${id}`,
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

        function deletesubtype(sel) {
            let id = sel;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "get",
                        url: `../deletesubtype/${id}`,
                        //    contentType: "application/json; charset=utf-8",
                        dataType: "Json",
                        success: function(result) {
                            if (result.status == true) {
                                $(`#subtype${id}`).remove();
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                            }
                        }

                    });
                }


            })
        }
    </script>
@endsection
