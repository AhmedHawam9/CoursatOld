@extends('App.dash')
@section('style')
<style>
    #example_wrapper{
        width: 100% !important;
    }
    .all-products button {
    width: 81px !important;
    color: #ffffff;
     font-family: 'light' !important;
    font-size: 19px !important;
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
                            <img src="{{asset('images/profile.svg')}}">
                        </div>
                        <div class="col-6">
                            <h5>{{auth()->user()->name}}</h5>
                            <p>ادمن</p>

                        </div>


                            </div>
                        </div>
                        <div class="flag">

                            <div class="row">
                                <div class="col-4">
                                    <img src="{{asset('images/flag.svg')}}">
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
                                <p>{{ Carbon\Carbon::now()->format('d-m-Y')}}</p>
                            </div>
                        </div>


                    </div>


                </div>
                <!--end heed-->


                <!--start setting-->
                <div class="setting all-products typs">
                    <div class="container">
                        <div class="row def">

                            <img src="{{asset('images/all-products.svg')}}">
                            <h5>  طلبات الانضمام</h5>



                        </div>

                        <div class="products-search typs1">
                            <div class="row">





                                <div class="col-4">

                                </div>



                            </div>

                        </div>



                        <div class="pt-5">
                            <div class="row">

                         <div class="table-responsive">
                            <table id="example" class="table col-12" style="width:100%">
                                <thead>
                                             <tr>
                                                 <th>id</th>
                                                  <th scope="col" class="text-center">اسم الطالب</th>
                                                  <th scope="col" class="text-center">رقم الطالب</th>
                                                  <th scope="col" class="text-center">المنصه </th>
                                                  <th scope="col" class="text-center"> المدرس</th>
                                                 <th scope="col" class="text-center">السنه </th>
                                                 <th scope="col" class="text-center"> الماده</th>
                                                  <th scope="col" class="text-center"> الكورس</th>
                                                  <th scope="col" class="text-center"> تاريخ الانضمام</th>
                                                  <th scope="col" class="text-center"> الادمن</th>
                                                   <th scope="col" class="text-center"> الاعدادات</th>
                                             </tr>
                                                     </thead>
                                       <tbody>
                                           @foreach($joins as $join)
                                     <tr id="join{{$join->id}}">
                                     <td class="text-center">{{$join->id}}</td>
                                     <td class="text-center">{{$join->student->name ?? ""}}</td>
                                     <td class="text-center">{{$join->student->phone ?? ""}}</td>

                                         <td class="text-center">{{$join->type ? ($join->type->center->name ?? "المنصه العامه") : ""}}</td>
                                         <td class="text-center">{{$join->type ? ($join->type->user->name ?? "المنصه العامه") : ""}}</td>
                                         <td class="text-center">{{$join->type ? ($join->type->year->year_ar ?? " ") : ""}}</td>
                                         <td class="text-center">{{$join->type ? ($join->type->subject->name_ar ?? " ") : ""}}</td>
                                     <td class="text-center">{{$join->type->name_ar ?? ""}}</td>
                                     <td class="text-center">{{\Carbon\Carbon::parse($join->created_at)->format('Y-m-d')}}</td>
                                     <td class="text-center">{{$join->user->name ?? ""}}</td>
                                     <td class="tex-center">
                                     <div id="status{{$join->id}}">
                                         @if($join->status == 0)

                                     <button type="button"  class="btn btn-success
                                      btn-light-success w-30" onclick="accept_type_join({{$join->id}})">
                                                        قبول </button>
                                                        <button type="button"  class="btn btn-danger
                                      btn-light-danger w-30 "  onclick="refuse_type_join({{$join->id}})">
                                                        رفض </button>

                                         @elseif($join->status == 1)
                                         <span class="badge badge-success p-2">تم القبول</span>
                                         @elseif($join->status == 2)
                                         <span class="badge badge-danger p-2">تم الرفض</span>
                                                        @endif
                                                        </div>
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
                        <h5>Made With <img src="{{asset('images/red.svg')}}"> By Crazy Idea </h5>
                        <p>Think Out Of The Box</p>
                    </div>
                </div>
            </div>
            <!--end foter-->
        </div>
    </div>
    <!--end page-body-->


@endsection
@section("scripts")
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>


<script>
//   $(document).ready(function() {
    $('#example').DataTable({
	"order": [[ 0, "desc" ]], // Order on init. # is the column, starting at 0});
  columnDefs: [
      {
         targets: 0,
      visible : false,


      },]

});
function accept_type_join(sel){
    let id = sel;

 $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
       type:"get",
       url: `accept_type_join/${id}`,
   //    contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
           if(result.status == true){
     Swal.fire(
      'تم!',
      result.message,
      'success'
         )
         $(`#status${id}`).empty();
         $(`#status${id}`).html('<span class="badge badge-success p-2">تم القبول</span>');
       }
           }

    });
    }function refuse_type_join(sel){
    let id = sel;

 $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.ajax({
       type:"get",
       url: `refuse_type_join/${id}`,
   //    contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
           if(result.status == true){
     Swal.fire(
      'تم!',
         result.message,
      'success'
         )
         $(`#status${id}`).empty();
         $(`#status${id}`).html('<span class="badge badge-danger p-2">تم الرفض</span>');
       }
           }

    });
    }

</script>
@endsection
