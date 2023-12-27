{{-- <style>
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
</style> --}}

<div class="text-center w-25 p-3">

    <!-- Edit -->
    <div >
        <a href="{{ route('edittype', $id) }}" title="Edit">
            <img src="{{ asset('images/pen.svg') }}" id="pen" style="cursor: pointer">
        </a>
    </div>

    <!-- Delete (if user has permission) -->
    @if (auth()->user()->hasPermission('types-delete'))
        <div >
            <img src="{{ asset('images/trash.svg') }}" id="trash" onclick="deletetype('{{ $id }}')" style="cursor:pointer;" title="Delete">
        </div>
    @endif

    <!-- Activate/Deactivate -->
    <div >
        <span class="btn bg-success btn-success text-white btn-sm" id="btn{{ $id }}" onclick="activetype({{ $id }})" title="{{ $active == 1 ? 'Deactivate' : 'Activate' }}">
            @if ($active == 1)
                الغاء التفعيل
            @else
                تفعيل
            @endif
        </span>
    </div>

    <!-- Groups, Students, Deleted Students, Exams -->
    <div >
        <a href="{{ route('grouptypes', $id) }}" class="btn btn-success btn-sm" title="المجموعات">المجموعات</a>
    </div>
    <div >
        <a href="{{ route('studentstype', $id) }}" class="btn btn-success btn-sm" title="الطلاب">الطلاب</a>
    </div>
    <div >
        <a href="{{ route('bannedStudentstype', $id) }}" class="btn btn-danger btn-sm" title="الطلاب المحذوفين"> الطلاب المحذوفين </a>
    </div>
    <div >
        <a href="{{ route('typeexams', $id) }}" class="btn btn-success btn-sm" title="الامتحانات">الامتحانات</a>
    </div>

    <!-- Create QR Code -->
    <div >
        <span class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal{{ $id }}" title="Create QR Code">Create QR Code</span>
    </div>

    <!-- QrCode History -->
    <div >
        <a href="{{ route('types.patches', $id) }}" title="QrCode History" class="text-dark ml-2">
            <i class="fas fa-cog"></i>
        </a>
    </div>

    <!-- Security Settings -->
    <div >
        <a href="{{ route('security', $id) }}" title="اعدادات الامان" class="text-dark ml-2">
            <i class="fas fa-cog"></i>
        </a>
    </div>

</div>



<div class="modal" id="myModal{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">create qrcode
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <label>count </label>
                        <input class="form-control" value="1" id="count{{ $id }}">
                    </div>

                    <div class="col-12">
                        <label>expire date </label>
                        <input class="form-control" type="date" id="expire_date{{ $id }}">
                    </div>
                    <div class="col-md-6 col-12">
                        <div id="qrcodes{{ $id }}"></div>
                    </div>
                </div>
                <div class="row mt-4">
                    <button type="button" class="btn btn-success mx-auto"
                        onclick="store_qrcodes({{ $id }})">save</button>

                    <button class="btn btn-primary waves-effect waves-light mr-12" type="button"
                        onclick=" printDiv('qrcodes{{ $id }}');">
                        طباعة ال QR
                    </button>

                </div>



            </div>
        </div>
    </div>
</div>


<script>
    function store_qrcodes(type_id) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            data: {
                'type_id': type_id,
                'count': $(`#count${type_id}`).val(),
                'expire_date': $(`#expire_date${type_id}`).val(),
            },
            url: `{{ route('store_qrcode') }}`,
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
                    $(`#qrcodes${type_id}`).html(result.html);

                    // $(`#myModal${type_id}`).modal('hide');
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
    function activetype(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "get",
            url: `activetype/${id}`,
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

    function deletetype(sel) {
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
                    url: `deletetype/${id}`,
                    //    contentType: "application/json; charset=utf-8",
                    dataType: "Json",
                    success: function(result) {
                        if (result.status == true) {
                            $(`#type${id}`).remove();
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
