.@extends('App.dash')
@section('style')
<style>
    #example_wrapper{
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
                            <h5>الطلاب</h5>

                           
                    
                        </div>

                        <div class="products-search typs1">
                   

                        </div>
                   
            <div class="row">
                                <div class="col-lg-3 col-md-6 col-12">
                                    <button class="btn w-100 mx-auto" >
                                      <a href="{{route('addgroupcoursestudent',$id)}}">  <span><i class="fas fa-plus-circle"></i></span>
                                        اضافه طالب لمجموعه  
                                        </a>
                                    </button>

                     
                              </div>
                      </div>


                        <div class="pt-5">
                            <div class="row">
                                       <div class="table-responsive">
                                                 
         <table id="example" class="table col-12" style="width:100%">
   <thead>
                <tr>
                  
                     <th scope="col" class="text-center">الاسم</th>
					<th scope="col" class="text-center">الكود</th>
					<th scope="col" class="text-center">المحافظه</th>
					<th scope="col" class="text-center">المدينه</th>
                    <th scope="col" class="text-center">المكان</th>
                <!--  <th scope="col" class="text_center">السنه</th>-->
                <!--     <th scope="col" class="text-center">الاعدادات</th>-->
                </tr>
                        </thead>
        <tbody>
                    @foreach($students as $student)
                    <tr>
                    

                        
              <td scope="col" class='text-center'><a href="{{route('studentprofile',$student->id)}}">{{$student->name}}</td>
				<td scope="col" class='text-center'>{{$student->code}}</td>
             <td scope="col" class="text-center">
                @if($student->state)     
                   {{$student->state['state']}}
				 @endif
                  </td>   
						<td scope="col" class="text-center">
                     @if($student->city)
                   {{$student->city['city']}}
						@endif
                  </td>     
						<td scope="col" class="text-center">
                     
                   {{$student->address}}
                  </td>
					<!--	<td class="text-center">
                               <span class="btn btn-success btn-sm" id="btn{{$student->id}}" onclick="activeuser({{$student->id}})">
                             @if($student->active == 1)
                             الغاء التفعيل
                             @else
                             تفعيل
                             @endif
                         </span>
                                            </td>-->

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
    $('#example').DataTable();
} );function addcoursesstudents(){
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
       var student_id = $(".student_id:checked").map(function(){
      return $(this).val();
    }).get()
    $.ajax({
       type:"post",
       url: `addcoursesstudents`,
       //  contentType: "application/json; charset=utf-8",
       dataType: "Json",
      data:{
        'course_id':$("#type_id").val(),
        'student_id':student_id
      },
       success: function(result){
    if(result.status == true){
 
Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'تم اضافه الكوسات للطلاب بنجاح',
  showConfirmButton: false,
  timer: 1500
})
location.reload();
    }else if(result.status == false){
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
</script>
@endsection