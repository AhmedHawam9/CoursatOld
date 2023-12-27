@extends('App.dash')
@section('content')
    <style>
        .iframe_hide_poop {
            position: relative;
        }

        .iframe_hide_poop iframe {
            position: relative;
        }

        .magic {
            position: absolute;
            width: 37px;
            height: 41px;
            top: 14px;
            right: 145px;
            z-index: 10;
            background-color: #000;
        }
    </style>
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
                    <form method="post" action="{{ route('updatevideoscollege', $video->id) }}"
                        enctype="multipart/form-data">
                        @csrf


                        <div class="row">

                            <div class="col-6 text-center set-img">
                                <video width="200" height="200" controls>
                                    <source src="{{ $video->url_link }}" id="video_here">
                                    Your browser does not support HTML5 video.
                                </video>
                                {{-- <div class="iframe_hide_poop">
                                    <div class="magic"></div>
                                    <iframe src="{{ $video->url_video }}" width="200" height="200"></iframe>
                                </div> --}}
                                <br>
                                <br>
                                <input id="kt" type="file" class="form-control ehabtalaat" name="url">
                                <label for="kt" class="ahmed">اضافة فيديو</label>
                                @error('url')
                                    <div class="alert alert-danger">هذا الحقل مطلوب</div>
                                @enderror
                                <span class="btn btn-danger" onclick="delete_video_college_video({{ $video->id }})">حذف
                                    Video</span>
                            </div>

                            <div class="col-6 text-center set-img">
                                <canvas id="pdfViewer" style="width:200px;height:200px"></canvas>
                                <input id="myPdf" type="file" class="form-control ehabtalaat" name="pdf">

                                <span class="d-block mx-2">
                                    {{ pathinfo($video->pdf, PATHINFO_BASENAME) ?? '' }}
                                </span>

                                {{-- <span class="d-block mx-2">{{ $video->pdf->getClientOriginalName() ?? '' }}
                                </span> --}}
                                <br>
                                <label for="myPdf" class="ahmed">اضافة pdf</label>
                                @error('pdf')
                                    <div class="alert alert-danger">هذا الحقل مطلوب</div>
                                @enderror
                                {{-- <span class="btn btn-danger" onclick="delete_video_college_pdf({{ $video->id }})">حذف
                                    pdf</span> --}}

                            </div>


                            <div class="col-6 text-center set-img">
                                <input id="ad" type="file" class="form-control ehabtalaat" name="image">
                                <img src="{{ asset('uploads/' . $video->image) }}" id="realimg">
                                <br>
                                <label for="ad" class="ahmed">اضافة صوره</label>
                                @error('image')
                                    <div class="alert alert-danger">هذا الحل مطلوب</div>
                                @enderror
                            </div>

                            <div class="col-6 text-center set-img">
                                <img src="{{ asset('uploads/' . $video->board) }}" id="realimg2">
                                <br>
                                <input id="ad2" type="file" class="form-control ehabtalaat" name="board">
                                <label for="ad2" class="ahmed">سبوره الحصه</label>
                                @error('board')
                                    <div class="alert alert-danger">هذا الحقل مطلب</div>
                                @enderror
                                {{-- <span class="btn btn-danger" onclick="delete_video_college_board({{ $video->id }})">حذف
                                    السبوره</span> --}}

                            </div>


                            {{-- youtube --}}

                            <div class="col-12 form-group">
                                <label for="youtube_link" class="ahmed">اضافة لينك youtube</label>
                                <input id="youtube_link" type="text" class="form-control" name="youtube_link"
                                    value="{{ $video->youtube_link }}" placeholder="قم بإضافة لينك يوتيوب اذا أردت">

                                @error('youtube_link')
                                    <div class="alert alert-danger">هذا الحقل مطلوب</div>
                                @enderror
                            </div>
                            <input type="hidden" name="id" value="{{ $video->youtube_link }}">

                        </div>




                        @if (Auth::user() && Auth::user()->isAdmin == 'admin')
                            <div class="info">
                                <div class="row">
                                    <div class="col-3 form-group">
                                        <label>عنوان الفيديو بالعربى</label>
                                        <input id="name_ar" type="text" class="form-control" name="name_ar"
                                            value="{{ $video->name_ar }}" placeholder="الاسم">
                                        @error('name_ar')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-3 form-group">
                                        <label>عنوان الفيديو الانجليزي</label>
                                        <input id="name_en" type="text" class="form-control" name="name_en"
                                            value="{{ $video->name_en }}" placeholder="الاسم">
                                        @error('name_en')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>


                                    <div class="form-group col-3">

                                        <label for="pay">مدفوع</label><br>
                                        <input id="pay" style="width: 13px;" type="checkbox" value="1"
                                            name="pay" @if ($video->paid == 1) checked @endif>

                                    </div>
                                    <div class="form-group col-3">
                                        <label>ترتيب الفيديو </label>
                                        <input style="height: 36px;" type="number" name="order_number"
                                            value="{{ $video->order_number }}">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>اوصف باالعربى</label>
                                        <textarea class="form-control" rows="5" id="description_ar" name="description_ar">{{ $video->description_ar }}</textarea>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>الوصف بالانجليزي</label></label>
                                        <textarea class="form-control" rows="5" id="description_en" name="description_en">{{ $video->description_en }}</textarea>
                                    </div>
                                </div>
                            @elseif(Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2)
                            </div>
                            <div class="info">
                                <div class="row">
                                    <div class="col-3 form-group">
                                        <label>عنوان الفيديو بالعربى</label>
                                        <input id="name_ar" type="text" class="form-control" name="name_ar"
                                            value="{{ $video->name_ar }}" placeholder="الاسم">
                                        @error('name_ar')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-3 form-group">
                                        <label>نوان الفيديو بالنجليزي</label>
                                        <input id="name_en" type="text" class="form-control" name="name_en"
                                            value="{{ $video->name_en }}" placeholder="الاسم">
                                        @error('name_en')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>


                                    <div class="form-group col-3">

                                        <label for="pay">مدفوع</label><br>
                                        <input id="pay" style="width: 13px;" type="checkbox" value="1"
                                            name="pay" @if ($video->paid == 1) checked @endif>

                                    </div>
                                    <div class="form-group col-3">
                                        <label>ترتيب الفيدي </label>
                                        <input style="height: 36px;" type="number" name="order_number"
                                            value="{{ $video->order_number }}">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>الوصف باالعربى</label>
                                        <textarea class="form-control" rows="5" id="description_ar" name="description_ar">{{ $video->description_ar }}</textarea>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>الوصف بالانجليزي</label></label>
                                        <textarea class="form-control" rows="5" id="description_en" name="description_en">{{ $video->description_en }}</textarea>
                                    </div>
                                </div>
                            @elseif(Auth::user() && Auth::user()->is_student == 3)
                            </div>
                            <div class="info">
                                <div class="row">
                                    <div class="col-3 form-group">
                                        <label>عنوان الفديو بالعربى</label>
                                        <input id="name_ar" type="text" class="form-control" name="name_ar"
                                            value="{{ $video->name_ar }}" placeholder="الاسم">
                                        @error('name_ar')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-3 form-group">
                                        <label>عنوان الفيديو بالانجليزي</label>
                                        <input id="name_en" type="text" class="form-control" name="name_en"
                                            value="{{ $video->name_en }}" placeholder="الاسم">
                                        @error('name_en')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group col-3">

                                        <label for="pay">مدفوع</label><br>
                                        <input id="pay" style="width: 13px;" type="checkbox" value="1"
                                            name="pay" @if ($video->paid == 1) checked @endif>

                                    </div>
                                    <div class="form-group col-3">
                                        <label>ترتيب الفيدو </label>
                                        <input style="height: 36px;" type="number" name="order_number"
                                            value="{{ $video->order_number }}">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>الوصف باالعربى</label>
                                        <textarea class="form-control" rows="5" id="description_ar" name="description_ar">{{ $video->description_ar }}</textarea>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>الوصف بالانليزي</label></label>
                                        <textarea class="form-control" rows="5" id="description_en" name="description_en">{{ $video->description_en }}</textarea>
                                    </div>
                                </div>
                        @endif
                        <br><br>
                        <div class="progress">
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
                $type = \App\TypesCollege::where('id', $video->typescollege_id)->first();
                if (auth()->user() && auth()->user()->isAdmin == 'admin') {
                    $paqauser = \App\Paqa_User::with('paqa')
                        ->where('user_id', $type->doctor_id)
                        ->first();
                    if ($paqauser == null) {
                        $msg = 'انت غير مشترك في باقه برجا الاشتراك في باقه';
                        //   return response()->json(['status' => false,'errors' => $msg]);
                    } elseif ($paqauser->expired_at == \Carbon\Carbon::now()->format('Y-m-d')) {
                        $msg = 'انتهت صلاحه الباقه';
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
                        $msg = 'انتهت صلاحيه البقه';
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
                    location.href = '../typescolleges';
                }
            }
            <?php  }?>
        });

        function getdocsection(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getdocsection/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#section').empty();
                    $('#section').html(result);
                }

            });
        }

        function getdocsubcollege(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getdocsubcollege/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#subcollege').empty();
                    $('#subcollege').html(result);
                }

            });
        }

        function getdoctypescollege(selected) {
            let id = selected.value;
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getdoctypescollege/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#typescollege').empty();
                    $('#typescollege').html(result);
                }

            });
        }

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
        // //get subtype

        //   function getsubtype(selected){

        // var id = selected.value;
        //  console.log(id);
        //   $.ajax({
        //       type:"GET",
        //       url: `getsubtype/${id}`,//put y
        //       contentType: "application/json; charset=utf-8",
        //       dataType: "Json",
        //       success: function(result){
        //           console.log(result);
        //         $('#subtype').empty();
        //         $('#subtype').html(result);

        //       }

        //       });
        // }
        function getdivision(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getdivision/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#division').empty();
                    $('#division').html(result);
                }

            });
        }

        function getsection(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getsection/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#section').empty();
                    $('#section').html(result);
                }

            });
        }

        function getsubcollege(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getsubcollege/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#subcollege').empty();
                    $('#subcollege').html(result);
                }

            });
        }

        function gettypescollege2(selected) {
            let id = selected.value;
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `gettypescollege2/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    console.log(result);
                    $('#typescollege').empty();
                    $('#typescollege').html(result[0]);
                    $('#doctor').empty();
                    $('#doctor').html(result[1]);
                }

            });
        }

        function getlesson(selected) {
            let id = selected.value;
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getlesson/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    console.log(result);
                    $('#lesson').empty();
                    $('#lesson').html(result.lesson);
                    $('#doctor').empty();
                    $('#doctor').html(result.doctor);
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
        function delete_video_college_video(sel) {
            let id = sel;
            var url = `{{ route('delete_video_college_video', ':id') }}`;
            url = url.replace(':id', id);
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
                        url: url,
                        //    contentType: "application/json; charset=utf-8",
                        dataType: "Json",
                        success: function(result) {
                            if (result.status == true) {

                                Swal.fire(
                                    'Deleted!',
                                    'Your pdf has been deleted.',
                                    'success'
                                )
                            }
                        }

                    });
                }


            })
        }
    </script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <script>
        // Loaded via <script> tag, create shortcut to access PDF.js exports.
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        // The workerSrc property shall be specified.
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

        $("#myPdf").on("change", function(e) {
            var file = e.target.files[0]
            if (file.type == "application/pdf") {
                var fileReader = new FileReader();
                fileReader.onload = function() {
                    var pdfData = new Uint8Array(this.result);
                    // Using DocumentInitParameters object to load binary data.
                    var loadingTask = pdfjsLib.getDocument({
                        data: pdfData
                    });
                    loadingTask.promise.then(function(pdf) {
                        console.log('PDF loaded');

                        // Fetch the first page
                        var pageNumber = 1;
                        pdf.getPage(pageNumber).then(function(page) {
                            console.log('Page loaded');

                            var scale = 1.5;
                            var viewport = page.getViewport({
                                scale: scale
                            });

                            // Prepare canvas using PDF page dimensions
                            var canvas = $("#pdfViewer")[0];
                            var context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            // Render PDF page into canvas context
                            var renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };
                            var renderTask = page.render(renderContext);
                            renderTask.promise.then(function() {
                                console.log('Page rendered');
                            });
                        });
                    }, function(reason) {
                        // PDF loading error
                        console.error(reason);
                    });
                };
                fileReader.readAsArrayBuffer(file);
            }
        });
        -- >
        <
        script >
            $(document).on("change", "#kt", function(evt) {
                var $source = $('#video_here');
                $source[0].src = URL.createObjectURL(this.files[0]);
                $source.parent()[0].load();
            });

        function getcollege(selected) {
            let id = selected.value;
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getcolleges/${id}`,
                //    contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#college').empty();
                    $('#college').html(result.data);
                    $('#college').selectpicker('refresh');
                    console.log(result);
                }

            });
        }
        $('form').ajaxForm({

            beforeSend: function() {

                $('#success').empty();

                <?php
                $msg = null;
                $type = \App\TypesCollege::where('id', $video->typescollege_id)->first();
                if (auth()->user() && auth()->user()->isAdmin == 'admin') {
                    $paqauser = \App\Paqa_User::with('paqa')
                        ->where('user_id', $type->doctor_id)
                        ->first();
                    if ($paqauser == null) {
                        $msg = 'انت غير مشترك في باقه برجا الاشتراك في باقه';
                        //   return response()->json(['status' => false,'errors' => $msg]);
                    } elseif ($paqauser->expired_at == \Carbon\Carbon::now()->format('Y-m-d')) {
                        $msg = 'انتهت صلاحه الباقه';
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
                        $msg = 'انتهت صلاحيه البقه';
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
                    location.href = '../typescolleges';
                }
            }
            <?php  }?>
        });
    </script>
    <script>
        function delete_video_college_pdf(selected) {
            let id = selected;
            var url = `{{ route('delete_video_college_pdf',':id') }}`;
            url = url.replace(':id',id);
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
                        url: url,
                        //    contentType: "application/json; charset=utf-8",
                        dataType: "Json",
                        success: function(result) {
                            if (result.status == true) {

                                Swal.fire(
                                    'Deleted!',
                                    'Your pdf has been deleted.',
                                    'success'
                                )
                            }
                        }

                    });
                }


            })
        }

        function delete_video_college_board(selected) {
            let id = selected;
            var url = `{{ route('delete_video_college_board',':id') }}`;
            url = url.replace(':id',id);
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
                        url: url,
                        //    contentType: "application/json; charset=utf-8",
                        dataType: "Json",
                        success: function(result) {
                            if (result.status == true) {

                                Swal.fire(
                                    'Deleted!',
                                    'Your board has been deleted.',
                                    'success'
                                )
                            }
                        }

                    });
                }


            })
        }
    </script> --}}
@endsection
