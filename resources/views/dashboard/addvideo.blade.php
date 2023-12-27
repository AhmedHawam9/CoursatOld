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
            @error('name')
                <p
                    style="margin: 33px auto;
    background: #dc354559;
    color: #dc3545;
    font-weight: bold;
    text-align: center;
    border-radius: 5px;
    padding: 10px 20px;
    box-shadow: 0px 3px 6px #dc35454d;
     }">
                    {{ $message }}</p>
            @enderror
            <div class="setting">
                <div class="container">
                    <div class="row def">
                        <img src="{{ asset('images/setting.svg') }}">
                        <h5>اضافه فيديو </h5>
                    </div>
                    <form method="post" action="{{ route('storevideo', $id) }}" enctype="multipart/form-data">
                        @csrf


                        <div class="row">
                            <div class="col-6 text-center mb-5 set-img">
                                <video width="200" height="200" controls>
                                    <source src="mov_bbb.mp4" id="video_here">
                                    Your browser does not support HTML5 video.
                                </video>
                                <br>
                                <br>
                                <input id="kt" type="file" class="form-control ehabtalaat" 
                                    name="url">
                                <label for="kt" class="ahmed">اضافة فيديو</label>
                                @error('url')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-6 text-center set-img">
                                <canvas id="pdfViewer" style="width:200px;height:200px"></canvas>
                                <input id="myPdf" type="file" class="form-control ehabtalaat" name="pdf">
                                <br>
                                <br>
                                <label for="myPdf" class="ahmed">اضافة pdf</label>
                                @error('pdf')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-6 text-center set-img">
                                <img src="{{ asset('images/set-img.svg') }}" id="realimg">
                                <br>
                                <input id="ad" type="file" class="form-control ehabtalaat" name="image">
                                <label for="ad" class="ahmed">اضافة صوره</label>
                                @error('image')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-6 text-center set-img">
                                <img src="{{ asset('images/set-img.svg') }}" id="realimg2">
                                <br>
                                <input id="ad2" type="file" class="form-control ehabtalaat" name="board">
                                <label for="ad2" class="ahmed">سبوره الحصه</label>
                                @error('board')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- youtube --}}
                            <div class="col-12 form-group">
                                <label for="youtube_link" class="ahmed">اضافة لينك youtube</label>
                                <input id="youtube_link" type="text" class="form-control" name="youtube_link">

                                @error('youtube_link')
                                    <div class="alert alert-danger">هذا الحقل مطلوب</div>
                                @enderror
                            </div>
                        </div>

                </div>
                <div class="info">

                    @if (Auth::user() && Auth::user()->isAdmin == 'admin')
                        <div class="row">
                            <div class="form-group col-4">
                                <input id="name_ar" type="text" required class="form-control" name="name_ar"
                                    placeholder="عنوان الفيديو بالعربى ">
                            </div>
                            <div class="form-group col-4">
                                <input id="name_en" type="text" required class="form-control" name="name_en"
                                    placeholder="عنوان الفيديو بالانجليزي ">
                            </div>
                            <div class="form-group col-4">
                                <label for="pay">مدفوع</label>
                                <input id="pay" style="width: 13px;" type="checkbox" value="1"
                                    name="pay">
                                <br>

                            </div>
                            <div class="form-group col-3">
                                <label>ترتيب الفيديو </label>
                                <input style="height: 36px;" min="0" type="number" name="order_number">
                            </div>
                        </div>
                    @elseif(Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1)
                        <div class="row">
                            <div class="form-group col-4">
                                <input id="name_ar" type="text" required class="form-control" name="name_ar"
                                    placeholder="عنوان الفيديو بالعربى ">
                            </div>
                            <div class="form-group col-4">
                                <input id="name_en" type="text" required class="form-control" name="name_en"
                                    placeholder="عنوان الفيديو بالانجليزي ">
                            </div>

                            <div class="form-group col-4">
                                <label for="pay">مدفوع</label>
                                <input id="pay" style="width: 13px;" type="checkbox" value="1"
                                    name="pay">
                                <br>

                            </div>
                        </div>
                    @elseif(Auth::user() && Auth::user()->is_student == 2)
                        <?php
                        $iduser = Auth::user()->id;
                        $useryear = \App\User_Year::where('user_id', $iduser)->pluck('year_id');
                        $yearr = \App\Year::whereIn('id', $useryear)->get();
                        ?>


                        <div class="row">
                            <div class="form-group col-4">
                                <input id="name_ar" type="text" required class="form-control" name="name_ar"
                                    placeholder="عنوان الفيديو بالعربى ">
                            </div>
                            <div class="form-group col-4">
                                <input id="name_en" type="text" required class="form-control" name="name_en"
                                    placeholder="عنوان الفيديو بالانجليزي ">
                            </div>
                            <div class="form-group col-4">
                                <label for="pay">مدفوع</label>
                                <input id="pay" style="width: 13px;" type="checkbox" value="1"
                                    name="pay">
                                <br>

                            </div>
                            <div class="form-group col-3">
                                <label>ترتيب الفيديو </label>
                                <input style="height: 36px;" min="0" type="number" name="order_number">
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="form-group col-6">
                            <label>الوصف بالعربى</label>
                            <textarea class="form-control" rows="5" id="description_ar" name="description_ar"></textarea>
                        </div>
                        <div class="form-group col-6">
                            <label>الوصف باالانجليزي</label>
                            <textarea class="form-control" rows="5" id="description_en" name="description_en"></textarea>
                        </div>
                    </div>
                    <br><br>
                    <div class="progress px-3">
                        <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0"
                            aria-valuemax="100" style="width: 0%">
                            0%
                        </div>
                    </div>
                    <br />
                    <div id="success">

                    </div>
                    <br />
                    <div class="save text-center mt-6">
                        <div class="row save">
                            <div class="col-12 text-center">
                                <input type="submit" value="حفظ" class="text-center">

                            </div>

                        </div>
                    </div>
                    </form>
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
        $('form').ajaxForm({

            beforeSend: function() {

                $('#success').empty();

                <?php
                $msg = null;
                $subtype = \App\Subtype::where('id', $id)->first();
                $type = \App\Type::where('id', $subtype->type_id)->first();
                if (auth()->user() && auth()->user()->isAdmin == 'admin') {
                    $paqauser = \App\Paqa_User::with('paqa')
                        ->where('user_id', $type->user_id)
                        ->first();
                    if ($paqauser == null) {
                        $msg = 'انت غير مشترك في باقه برجاء الاشتراك في باقه';
                        //   return response()->json(['status' => false,'errors' => $msg]);
                    } elseif ($paqauser->expired_at == \Carbon\Carbon::now()->format('Y-m-d')) {
                        $msg = 'انتهت صلاحيه الباقه';
                        //return response()->json(['status' => false,'errors' => $msg]);
                    }
                } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1) {
                    $paqauser = \App\Paqa_User::with('paqa')
                        ->where('user_id', auth()->user()->id)
                        ->first();
                    if ($paqauser == null) {
                        $msg = 'انت غير مشترك في باقه برجاء الاشتراك في باقه';
                        //  return response()->json(['status' => false,'errors' => $msg]);
                    } elseif ($paqauser->expired_at == \Carbon\Carbon::now()->format('Y-m-d')) {
                        $msg = 'انتهت صلاحيه الباقه';
                        //return response()->json(['status' => false,'errors' => $msg]);
                    }
                }
                if (Auth::user() && Auth::user()->is_student == 2) {
                    $paqauser = \App\Paqa_User::with('paqa')
                        ->where('user_id', auth()->user()->id)
                        ->first();
                    if ($paqauser == null) {
                        $msg = 'انت غير مشترك في باقه برجاء الاشتراك في باقه';
                        // return response()->json(['status' => false,'errors' => $msg]);
                    } elseif ($paqauser->expired_at == \Carbon\Carbon::now()->format('Y-m-d')) {
                        $msg = 'انتهت صلاحيه الباقه';
                        //return response()->json(['status' => false,'errors' => $msg]);
                    }
                } ?>
                /* $('.progress-bar').text('Uploaded');
                 $('.progress-bar').css('width', '100%');*/
                var message = '<?php echo $msg; ?>';
                $('#success').html('<span class="text-danger"><b>' + message + '</b></span><br /><br />');
            },
            <?php   if($msg){
     }else{ ?>
            uploadProgress: function(event, position, total, percentComplete) {
                $('.progress-bar').text(percentComplete + '%');
                $('.progress-bar').css('width', percentComplete + '%');
            },
            success: function(data) {
                if (data.errors) {
                    $('.progress-bar').text('0%');
                    $('.progress-bar').css('width', '0%');
                    $('#success').html('<span class="text-danger"><b>' + data.errors + '</b></span>');
                }
                if (data.success) {
                    $('.progress-bar').text('Uploaded');
                    $('.progress-bar').css('width', '100%');
                    $('#success').html('<span class="text-success"><b>' + data.success +
                        '</b></span><br /><br />');
                    location.href = '../types';
                }
            }
            <?php  }?>
        });

        function getyear(selected) {

            var id = selected.value;
            console.log(id);
            $.ajax({
                type: "GET",
                url: `getyear/${id}`, //put y
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#subject').empty();
                    $('#subject').html(result);

                }

            });
        }


        //   $(function () {
        //         $("#pay").click(function () {
        //             if ($(this).is(":checked")) {
        //              document.getElementById("pay2").style.display='';

        //             } else {
        //              document.getElementById("pay2").style.display='none';

        //             }
        //         });
        //     });
        function getteacher(selected) {

            var id = selected.value;
            console.log(id);
            $.ajax({
                type: "GET",
                url: `getteacher/${id}`, //put y
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#teacher').empty();
                    $('#teacher').html(result[0]);
                    $('#type').empty();
                    $('#type').html(result[1]);

                }

            });
        }

        function getteacher2(selected) {

            var id = selected.value;
            console.log(id);
            $.ajax({
                type: "GET",
                url: `getteacher/${id}`, //put y
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {

                    $('#type').empty();
                    $('#type').html(result[1]);

                }

            });
        }
        // //get subtype

        function getsubtype(selected) {

            var id = selected.value;
            console.log(id);
            $.ajax({
                type: "GET",
                url: `getsubtype/${id}`, //put y
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    console.log(result);
                    $('#subtype').empty();
                    $('#subtype').html(result);

                }

            });
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#realimg').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#ad").change(function() {
            readURL(this);
        });

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#realimg2').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#ad2").change(function() {
            readURL2(this);
        });
    </script>
    <script>
        $(document).on("change", "#kt", function(evt) {
            var $source = $('#video_here');
            $source[0].src = URL.createObjectURL(this.files[0]);
            $source.parent()[0].load();
        });
    </script>
@endsection
