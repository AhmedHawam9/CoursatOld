@extends('App.dash')
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
                            <h5> تعديل البروفايل </h5>
                        </div>
               
                         
                                    
                             
                        <div class="info">
                            <div class="row">
                                <div class="form-group col-md-4 col-12">
                                    <label>الاسم</label>
                                    <input class="form-control" id="name" type="text" value="{{auth()->user()->name}}" name="name">
                                     @error('name')
                                 <p style="color:red;">{{$message}}</p>
                                 @enderror
                                </div>
                                <div class="form-group col-md-4 col-12">
                                    <label>الايميل</label>
                                    <input class="form-control" id="email" value="{{auth()->user()->email}}"  type="text" name="email">
                                     @error('email')
                                 <p style="color:red;">{{$message}}</p>
                                 @enderror
                                </div>
                               
                                  <div class="form-group col-md-4 col-12">
                                    <label>الهاتف</label>
                                    <input class="form-control" id="phone" value="{{auth()->user()->phone}}" type="text" name="phone">
                                     @error('phone')
                                 <p style="color:red;">{{$message}}</p>
                                 @enderror
                                </div>
                            </div>
                            
                       
                          
                      
                 
                        
                           <div class="row">
                               <div class="form-group col-12">
                                   <label>الوصف</label>
                                   <textarea class="form-control" rows="5" 
                                   id="description"  name="description">{{auth()->user()->description}}</textarea>
                               </div>
                           </div>

                        <div class="save text-center mt-6">
                            <div class="row save">
                                <div class="col-12 text-center">
                                  <input type="button" onclick="updateprofile()" value="تعديل" class="text-center">

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

<script>
    function updateprofile(){
 $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
 
    let data = new FormData();
    data.append('name', $('#name').val());
    data.append('email', $('#email').val());
    data.append('phone', $('#phone').val());
    data.append('description', $('#description').val());
    $.ajax({
       type:"post",
       url: `updateprofile`,
       enctype: 'multipart/form-data',
       contentType: false,
        processData: false,
       dataType: "Json",
       data:data,
       success: function(result){
      if(result.status == true){
          Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'تم  تعديل البروفايل بنجاح',
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
      }
       }

      });
    }
</script>
@endsection