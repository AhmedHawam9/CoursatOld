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
                            <h5>تعديل حصه </h5>
                        </div>
                            <form method="post" action="{{route('updatelesson',$lesson->id)}}" enctype="multipart/form-data">
                        	@csrf
                         
                                    
                               
                       
                        <div class="info">
                                        <div class="row">
                                         <div class="col-3 text-center mb-5 set-img">
                    <video width="200" height="200" controls >
              <source src="{{url('uploads/'.$lesson->intro)}}" id="video_here">
            Your browser does not support HTML5 video.
          </video>
  <br><br>
                       <input id="kt" type="file" class="form-control ehabtalaat" name="intro">
                                <label for="kt" class="ahmed">اضافة انترو</label>
                                @error('intro')
                                     <p style="color:red;">{{$message}}</p>
                                @enderror
                               </div>
                                <div class="col-3 text-center set-img">
                                   <img src="{{url('uploads/'.$lesson->image)}}" id="realimg">
                        <br>
                       <input id="ad" type="file" class="form-control ehabtalaat" name="image">
                                <label for="ad" class="ahmed">اضافة صوره</label>
                                @error('image')
                                 <p style="color:red;">{{$message}}</p>
                                @enderror
                   </div>    <div class="col-3 text-center set-img">
                                           <img src="{{url('uploads/'.$lesson->notes)}}" id="notes1">
                                <br>
                               <input id="notes" type="file" class="form-control ehabtalaat"  name="notes">
                                        <label for="notes" class="ahmed">اضافة notes</label>
                                        @error('notes')
                                         <p style="color:red;">{{$message}}</p>
                                        @enderror
                           </div>
                                          <div class="col-3 text-center set-img">
                                                   <img src="{{url('uploads/'.$lesson->part_paper)}}" id="realimg1">
                                <br>
                               <input id="ad1" type="file" class="form-control ehabtalaat" name="part_paper">
                                        <label for="ad1" class="ahmed">اضافة مذكره حصه</label>
                                        @error('part_paper')
                                         <p style="color:red;">{{$message}}</p>
                                        @enderror
                                  </div>
                               </div>
                             @if(Auth::user() && Auth::user()->isAdmin == 'admin')
                            <div class="row">
                                <div class="form-group col-3">
                                    <label> اسم الحصه بالعربى </label>
                                    <input class="form-control" type="text" name="name_ar" value="{{$lesson->name_ar}}">
                                </div>
                                 <div class="form-group col-3">
                                    <label> اسم الحصه بالانجليزي </label>
                                    <input class="form-control" type="text" name="name_en" value="{{$lesson->name_en}}">
                                </div>
                                 <div class="form-group col-3">
                                    <label> نقاط الحصه</label>

                               <input id="points" type="number" class="form-control" name="points" value="{{$lesson->points}}"
                               placeholder="النقاط">
                                      
                                       </div>
                                  <div class="form-group col-3">
                                    <label> نقاط المذكره</label>

                               <input id="part_points" type="number" class="form-control" name="part_points" value="{{$lesson->part_points}}"
                               placeholder="النقاط">
                                      
                                       </div>
                                 <div class="col-6">
                                 <label>التاج </label>
                                <select class="form-control selectpicker"  data-live-search="true"  multiple name="tag_id[]">
                                      
                                  @foreach($tags as $tag)
                                  <option value="{{$tag->id}}" @if(in_array($tag->id,$lesson->tags->pluck('id')->toArray())) selected @endif>{{$tag->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                                  <div class="form-group col-3">
                               <label>ترتيب الحصص </label>
                               <input style="height: 36px;" value="{{$lesson->order_number}}" type="number" name="order_number">
                               </div>
                            </div>
                            @elseif(Auth::user() &&Auth::user()->is_student == 5 && Auth::user()->category_id == 2)
                              <div class="row">
                                <div class="form-group col-3">
                                    <label> اسم الحصه بالعربى </label>
                                    <input class="form-control" type="text" name="name_ar" value="{{$lesson->name_ar}}">
                                </div>
                                 <div class="form-group col-3">
                                    <label> اسم الحصه بالانجليزي </label>
                                    <input class="form-control" type="text" name="name_en" value="{{$lesson->name_en}}">
                                </div>
                                <div class="form-group col-3">
                                    <label> نقاط الحصه</label>

                               <input id="points" type="number" class="form-control" name="points" value="{{$lesson->points}}"
                               placeholder="النقاط">
                                      
                                       </div>
                                  <div class="form-group col-3">
                                    <label> نقاط المذكره</label>

                               <input id="part_points" type="number" class="form-control" name="part_points" value="{{$lesson->part_points}}"
                               placeholder="النقاط">
                                      
                                       </div>
                                     <div class="col-6">
                                 <label>التاج </label>
                                <select class="form-control selectpicker"  data-live-search="true"  multiple name="tag_id[]">
                                      
                                  @foreach($tags as $tag)
                                  <option value="{{$tag->id}}" @if(in_array($tag->id,$lesson->tags->pluck('id')->toArray())) selected @endif>{{$tag->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                                 <div class="form-group col-3">
                               <label>ترتيب الحصص </label>
                               <input style="height: 36px;"  min="0"  value="{{$lesson->order_number}}" type="number" name="order_number">
                               </div>
                                  
                               
                            </div>
                               @elseif(Auth::user() &&Auth::user()->is_student == 3)
                               <div class="row">
                                <div class="form-group col-3">
                                    <label> اسم الكورس بالعربى </label>
                                    <input class="form-control" type="text" name="name_ar" value="{{$lesson->name_ar}}">
                                </div>
                                 <div class="form-group col-3">
                                    <label> اسم الكورس بالانجليزي </label>
                                    <input class="form-control" type="text" name="name_en" value="{{$lesson->name_en}}">
                                </div>
                            <div class="form-group col-3">
                                    <label> نقاط الحصه</label>

                               <input id="points" type="number" class="form-control" name="points" value="{{$lesson->points}}"
                               placeholder="النقاط">
                                      
                                       </div>
                                  <div class="form-group col-3">
                                    <label> نقاط المذكره</label>

                               <input id="part_points" type="number" class="form-control" name="part_points" value="{{$lesson->part_points}}"
                               placeholder="النقاط">
                                      
                                       </div>
                                      <div class="col-6">
                                 <label>التاج </label>
                                <select class="form-control selectpicker"  data-live-search="true"  multiple name="tag_id[]">
                                      
                                  @foreach($tags as $tag)
                                  <option value="{{$tag->id}}" @if(in_array($tag->id,$lesson->tags->pluck('id')->toArray())) selected @endif>{{$tag->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                                  <div class="form-group col-3">
                               <label>ترتيب الحصص </label>
                               <input style="height: 36px;"  min="0"  value="{{$lesson->order_number}}" type="number" name="order_number">
                               </div>
                            </div>
                            @endif
                           <div class="form-group col-6">
                                   <label>الوصف </label>
                                   <textarea class="form-control" rows="5" name="description">{{$lesson->description}}</textarea>
                      </div>
                        <div class="save text-center mt-6">
                            <div class="row save">
                                <div class="col-12 text-center">
                                  <input type="submit" value="حفظ" class="text-center">

                                </div>

                            </div>
                        </div>
                           <br><br>
                         <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow=""
                      aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        0%
                      </div>
                    </div>
                    <br />
                    <div id="success">

                    </div>
                    <br />
                    </form>
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
    function getdivision(selected){
      let id = selected.value;
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `../getdivision/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
     $('#division').empty();
    $('#division').html(result);
       }

      });
  }
  function getsection(selected){
      let id = selected.value;
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `../getsection/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
     $('#section').empty();
    $('#section').html(result);
       }

      });
  }
  function getsubcollege(selected){
      let id = selected.value;
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `../getsubcollege/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
     $('#subcollege').empty();
    $('#subcollege').html(result);
       }

      });
  }
   function gettypescollege(selected){
      let id = selected.value;
      console.log(id);
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `../gettypescollege/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
     $('#typescollege').empty();
    $('#typescollege').html(result);
       }

      });
  }  function getdocsection(selected){
      let id = selected.value;
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `../getdocsection/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
     $('#section').empty();
    $('#section').html(result);
       }

      });
  }
   function getdocsubcollege(selected){
      let id = selected.value;
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `../getdocsubcollege/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
     $('#subcollege').empty();
    $('#subcollege').html(result);
       }

      });
  }function getdoctypescollege(selected){
      let id = selected.value;
      console.log(id);
      $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `../getdoctypescollege/${id}`,
         contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
     $('#typescollege').empty();
    $('#typescollege').html(result);
       }

      });
  } function getcolleges(selected){
let id = selected.value;
console.log(id);
 $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `../getcolleges/${id}`,
   //    contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
       $('#college').empty();
       $('#college').html(result.data);
       console.log(result);
       }

      });
    }$(document).on("change", "#kt", function(evt) {
  var $source = $('#video_here');
  $source[0].src = URL.createObjectURL(this.files[0]);
  $source.parent()[0].load();
});function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#realimg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#ad").change(function(){
    readURL(this);
}) 
function getdoctorscollege(selected){
let id = selected.value;
console.log(id);
 $.ajaxSetup({
       headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
       type:"get",
       url: `getdoctorscollege/${id}`,
   //    contentType: "application/json; charset=utf-8",
       dataType: "Json",
       success: function(result){
       $('#doctor').empty();
       $('#doctor').html(result.data);
       console.log(result);
       }

      });
    }  $('form').ajaxForm({
  
      beforeSend:function(){
        
        $('#success').empty();
        
                <?php
$msg = null;
$type = \App\TypesCollege::where('id',$lesson->typescollege_id)->first();
if(auth()->user() && auth()->user()->isAdmin == 'admin'){
      
    $paqauser= \App\Paqa_User::with("paqa")->where("user_id",$type->doctor_id)->first();
    if($paqauser==null){
     $msg='انت غير مشترك في باقه برجاء الاشتراك في باقه';
    //   return response()->json(['status' => false,'errors' => $msg]);
}
   elseif($paqauser->expired_at ==\Carbon\Carbon::now()->format('Y-m-d')){
            $msg = 'انتهت صلاحيه الباقه';
//return response()->json(['status' => false,'errors' => $msg]);

  }
} elseif(Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1){
  $paqauser= \App\Paqa_User::with("paqa")->where("user_id",auth()->user()->id )->first();
  if($paqauser==null){
   $msg='انت غير مشترك في باقه برجاء الاشتراك في باقه';
   //  return response()->json(['status' => false,'errors' => $msg]);
}
 elseif($paqauser->expired_at == \Carbon\Carbon::now()->format('Y-m-d')){
         $msg = 'انتهت صلاحيه الباقه';
//return response()->json(['status' => false,'errors' => $msg]);
}
}if(Auth::user() && Auth::user()->is_student == 2){
             
  $paqauser= \App\Paqa_User::with("paqa")->where("user_id",auth()->user()->id)->first();
  if($paqauser==null){
    $msg='انت غير مشترك في باقه برجاء الاشتراك في باقه';
    // return response()->json(['status' => false,'errors' => $msg]);
}
 elseif($paqauser->expired_at ==\Carbon\Carbon::now()->format('Y-m-d')){
           $msg = 'انتهت صلاحيه الباقه';
//return response()->json(['status' => false,'errors' => $msg]);
}}?>
         /* $('.progress-bar').text('Uploaded');
          $('.progress-bar').css('width', '100%');*/
        var message= '<?php echo $msg;?>';
          $('#success').html('<span class="text-danger"><b>'+message+'</b></span><br /><br />');
      },
  <?php   if($msg){
     }else{ ?>
      uploadProgress:function(event, position, total, percentComplete)
      {
        $('.progress-bar').text(percentComplete + '%');
        $('.progress-bar').css('width', percentComplete + '%');
      },
      success:function(data)
      {
        if(data.errors)
        {
          $('.progress-bar').text('0%');
          $('.progress-bar').css('width', '0%');
          $('#success').html('<span class="text-danger"><b>'+data.errors+'</b></span>');
        }
        if(data.success)
        {
          $('.progress-bar').text('Uploaded');
          $('.progress-bar').css('width', '100%');
          $('#success').html('<span class="text-success"><b>'+data.success+'</b></span><br /><br />');
         location.href ='../lessons/' + data.id;
        }
      }
<?php  }?>
    });function readURL1(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#realimg1').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#ad1").change(function(){
    readURL1(this);
});function writerURL1(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#notes1').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#notes").change(function(){
    writerURL1(this);
})
</script>
@endsection