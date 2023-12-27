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
                        <h5> الحملات</h5>




                    </div>

                    <div class="products-search typs1">
                        <div class="row">
                            <div class="col-3">
                                <button class="btn">
                                    <a href="{{ route('campaigns.create') }}"> <span><i
                                                class="fas fa-plus-circle"></i></span>
                                        اضافة حملة

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
                                        <th scope="col">اسم الحملة</th>
                                        <th scope="col"> تاريخ بداية الحملة</th>
                                        <th scope="col"> تاريخ انتهاء الحملة </th>
                                        <th scope="col"> تاريخ انشاء الحملة</th>

                                        <th scope="col" class="text-center">الاعدادات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($campaigns as $campaign)
                                        <tr id="c{{ $campaign->id }}">
                                            <td scope="row">{{ $campaign->title ?? '' }}</td>
                                            <td scope="row">{{ $campaign->start_date ?? '' }}</td>
                                            <td scope="row">{{ $campaign->end_date ?? '' }}</td>
                                            <td scope="row">{{ $campaign->created_at ?? '' }}</td>
                                            <td class="text-center">
                                                {{-- link for editing --}}
                                                <a href="{{ route('campaigns.edit', $campaign->id) }}"> <img
                                                        src="{{ asset('images/pen.svg') }}" id="pen"
                                                        style="cursor: pointer"></a>
                                                {{-- link for result  --}}
                                                <a href="{{ route('campaigns.show', $campaign->id) }}"> <img
                                                        src="{{ asset('images/show.svg') }}" id="pen"
                                                        style="cursor: pointer"></a>
                                                {{-- link for subscription  --}}
                                                @if ($campaign->category_id == config('project_types.system_category_type.category_id_college'))
                                                    <a
                                                        href="{{ route('campaigns.subscribtionsCollege.index', $campaign->id) }}">
                                                        <img src="{{ asset('images/show.svg') }}" id="pen"
                                                            style="cursor: pointer"></a>
                                                @else
                                                    <a
                                                        href="{{ route('campaigns.subscribtionsBasic.index', $campaign->id) }}">
                                                        <img src="{{ asset('images/show.svg') }}" id="pen"
                                                            style="cursor: pointer"></a>
                                                @endif
                                                {{-- link for result file --}}
                                                {{-- <a href=""> <img src="{{ asset('images/pen.svg') }}" id="pen"
                                                        style="cursor: pointer"></a> --}}
                                                {{-- link for deleting  --}}

                                                <img src="{{ asset('images/trash.svg') }}" id="trash"
                                                    onclick="deletetag('{{ $campaign->id }}')" style="cursor:pointer;">

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
                        url: `campaigns/${id}`,
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
