@extends('App.dash')
@section('style')
<style>
.selectpicker{
   width: 100% !important;
    display:block !important;
}
.selectpicker button{
     width: 100% !important;  
}
.setting .info button{
    width: 100% !important;
    background-color:white;
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
                <div class="setting">
                    <div class="container">
                        <div class="row def">
                            <img src="{{asset('images/setting.svg')}}">
                            <h5>  اضافه كورس لطالب اساسى  </h5>
                        </div>
                           
                           
                        </div>
                        <div class="info">
                            <div class="row">
                          
                             
                     <div class="form-group col-3">
                                     
                              <label for="pay">اكواد الطلاب</label><br >
                               <select class="form-control qeno-select selectpicker"
                               style="display:block;width:100% !important;" id="student"
                               onchange="gettypecourse(this)" name="id" data-live-search="true">
                                 <option value="0" selected="selected" disabled>اختر كود</option>
                                       @foreach($students as $student)
                                       <option value="{{$student->id}}">{{$student->code}}</option>
                                        @endforeach                                   
                                   </select>
                                   <script>
                                $(function () {
                                    $('.selectpicker').selectpicker();
                                });
                                        </script>
                               </div>
                                 <div class="form-group col-3">
                                     
                              <label for="type"> الكورسات</label><br >
                               <select class="form-control qeno-select selectpicker"
                               style="display:block;width:100% !important;" id="type"
                                data-live-search="true">
                                 <option value="0">اختر كورس</option>
                                       @foreach($types as $type)
                                       <option value="{{$type->id}}">{{$type->name_ar}}</option>
                                        @endforeach                                   
                                   </select>
                                   <script>
                                $(function () {
                                    $('.selectpicker').selectpicker();
                                });
                                        </script>                         
								</div>    
                            </div>
                  <div class="save text-center mt-6">
                            <div class="row save">
                                <div class="col-12 text-center">
                                  <input type="submit" value="حفظ" class="text-center" onclick="addtypecourse()">

                                </div>

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
<script>
function gettypecourse(selected){
let id = selected.value;
console.log(id);
 $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `gettypecourse/${id}`,
   //    contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
          $("#type").empty();
          $("#type").html(result.data);
		   $("#type").selectpicker("refresh");
       }

      });
    }
  function addtypecourse(){
 $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"post",
       url: 'addtypecourse',
       dataType: "Json",
		data:{
		'student_id':$('#student').val(),
		'type_id':$("#type").val()
		},
       success: function(result){
		   if(result.status == true){
			   Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'تم اضافه الطالب للكورس بنجاح',
  showConfirmButton: false,
  timer: 1500
})
		location.reload();
		   }else if(result.status == false){
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
@endsection