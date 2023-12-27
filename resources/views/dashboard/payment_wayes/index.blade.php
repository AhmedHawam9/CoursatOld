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
                        <h5>وسائل الدفع</h5>



                    </div>

                    <div class="products-search typs1">
                        <div class="row">
                            <div class="col-3">
                                <button class="btn">
                                    <a href="{{ route('paymentways.create') }}"> <span><i
                                                class="fas fa-plus-circle"></i></span>
                                        اضافة وسيلة دفع
                                    </a>
                                </button>





                                <div class="col-4">

                                </div>





                            </div>

                        </div>

                    </div>



                    <div class="pt-5">
                        <div class="row">

                        <div class="table-responsive">
                            <table id="example" class="table col-12" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">وسيلة الدفع</th>
                                        <th scope="col">رقم وسيلة الدفع</th>
                                        <th scope="col">صاحب وسيلة الدفع</th>
                                        {{-- <th scope="col">المركز المسئول عن الدفع</th> --}}
                                        <th scope="col" class="text-center">الاعدادات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paymentWays as $paymentway)
                                        <tr id="c{{ $paymentway->id }}">
                                            <td scope="row">{{ $paymentway->title ?? '' }}</td>
                                            <td scope="row">{{ $paymentway->number ?? '' }}</td>
                                            <td scope="row">{{ $paymentway->creator->name ?? '' }}</td>
                                            {{-- <td scope="row">{{ implode(',', $paymentway->centers->pluck('name')->toArray()) ?? '' }}</td> --}}
                                            <td class="text-center">
                                                <a href="{{ route('paymentways.edit', $paymentway->id) }}"> <img
                                                        src="{{ asset('images/pen.svg') }}" id="pen"
                                                        style="cursor: pointer"></a>

                                                <img src="{{ asset('images/trash.svg') }}" id="trash"
                                                    onclick="deletetag('{{ $paymentway->id }}')" style="cursor:pointer;">

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
            $('#example').DataTable();
        });

        function deletetag(sel) {
            let id = sel;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            Swal.fire({
                title: 'هل انت متاكد؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: "Post",
                        data: {
                            '_method': 'DELETE',
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: `paymentways/${id}`,
                        //    contentType: "application/json; charset=utf-8",
                        dataType: "Json",
                        success: function(result) {
                            $(`#c${id}`).remove();
                            Swal.fire(
                                'Deleted!',
                                'تم مسح وسيلة الدفع بنجاح',
                                'success'
                            )
                        }

                    });
                }


            })
        }
    </script>
@endsection
