@extends('App.dash')
@section('style')
<style>
    #example_wrapper{
        width: 100% !important;
    }
	.all-products #btn1{
		margin-right: 0 !important;
	}
	.all-products #btn2{
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
                     <h5>  بنك اسئله الماده </h5>



                        </div>

                        <div class="products-search typs1">
                            <div class="row">
                                    <button class="btn" >
                                      <a href="{{route('addsubjectquestionscenter')}}">  <span><i class="fas fa-plus-circle"></i></span>
                                        اضافة   سؤال
                                        </a>
                                    </button>
                                </div>

                            </div>

                        </div>

      <div class="pt-5">
         @if(Auth::user() && Auth::user()->isAdmin == 'admin')
                          <div class="row">
                               <div class="form-group col-lg-4 col-12">
                           <label>المرحله</label>
                            <select class="form-control selectpicker" name="stage_id" onchange="getstage(this)">
                                 <option value="0" selected="selected" required disabled="disabled">ادخل المرحله</option>
                                @foreach($stages as $stage)
                                <option value='{{$stage->id}}'>{{$stage->name_ar ?? ""}}</option>
                                @endforeach
                            </select>
                                 @error('stage_id')
                         <p style="color:red;">{{$message}}</p>
                            @enderror
                             </div>
                       <div class="form-group col-lg-4 col-12">
                           <label>سنه الماده</label>
                            <select class="form-control selectpicker" name="years_id" required id="year" onchange="getyear(this)">
                                <option value="0" selected="selected" disabled="disabled">اختر السنه</option>

                            </select>
                                 @error('years_id')
                         <p style="color:red;">{{$message}}</p>
                            @enderror
                             </div>
                                <div class="form-group col-lg-4 col-12">
                           <label>الماده </label>
                            <select class="form-control selectpicker" name="subjects_id" required id="subject" onchange="getteacher(this)">
                                  <option value="0" selected="selected" disabled="disabled">اختر الماده</option>

                            </select>
                                 @error('subjects_id')
                         <p style="color:red;">{{$message}}</p>
                            @enderror
                             </div>
                          </div>
                          <div class="row">
                            <div class="col-3 mx-auto">


                            <span class="btn btn-primary" onclick="filtersubjectquestions()">بحث</span>    </div>
                          </div>
@endif
                        <div class="pt-5">
                            <div class="row">
                                    <div class="table-responsive ">
         <table id="example" class="tablecol-12" style="width:100%">
   <thead>
                <tr>
					<th>id</th>
                     <th scope="col" class="text-center"> القسم  </th>

                    <th scope="col">الماده</th>
                       <th scope="col" class="text-center">السنه</th>
                    <th scope="col" class="text-center">الاعدادات</th>
                </tr>
                        </thead>
        <tbody id="types">
                    @foreach($questions as $question)
                    <tr id="type{{$question->id}}">
						  <td class="text-center">{{$question->id}}</td>
                    <td scope="row" class="text-center">{{$question->name}}</td>
                        <td class="text-center">
                          @if($question->subject)
                          {{$question->subject->name_ar ?? ""}}
                      @endif</td>
                          <td class="text-center">
                            @if($question->year)
                            {{$question->year->year_ar}}
                      @endif</td>
                        <td class="text-center">
                          <a href="{{route('editsubjectquestionscenter',$question->id)}}" > <img src="{{asset('images/pen.svg')}}" id="pen"
                         style="cursor: pointer"></a>
                          @if(auth()->user()->hasPermission("subjectquestionsscenter-delete"))
                             <img src="{{asset('images/trash.svg')}}" id="trash" onclick="deletetype('{{$question->id}}')" style="cursor:pointer;">
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
    $(document).ready(function() {
   $('#example').DataTable({
	"order": [[ 0, "desc" ]], // Order on init. # is the column, starting at 0});
  columnDefs: [
      {
          targets: 0,
        visible : false,


      },]

});
	});
function activetype(id){
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `activetype/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
    if(result.status == 'deactive'){
        Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'تم الغاء التفعيل ',
  showConfirmButton: false,
  timer: 1500
});
$(`#btn${id}`).html('تفعيل');

    }else if(result.status == 'active'){
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
  } function deletetype(sel){
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
       type:"get",
       url: `../deletesubjectquestionscenter/${id}`,
   //    contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
           if(result.status == true){
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
function getstage(selected){
    let id = selected.value;
 $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `getstage/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
    $('#year').empty();
    $('#year').html(result);
        $('#year').selectpicker('refresh');
       }

      });
    }
    	function getyear(selected){
    let id = selected.value;
 $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `getyear/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
    $('#subject').empty();
    $('#subject').html(result);
        $('#subject').selectpicker('refresh');
       }

      });
    }
    function filtersubjectquestions(){
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"post",
       url: `filtersubjectquestions`,
      //   contentType: "application/json; charset=utf-8",
       dataType: "Json",
      data:{
        "years_id":$("#year").val(),
        "subjects_id":$("#subject").val(),

      },
       success: function(result){
    if(result.status == true){
        $('#example').DataTable().destroy();
$("#types").empty();
      $("#types").append(result.data);
       $('#example').DataTable().draw();
    }

       }

      });
  }
</script>
@endsection
