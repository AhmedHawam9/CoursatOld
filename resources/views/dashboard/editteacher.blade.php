@extends('App.dash')
@section('content')
    <style>
        .setting .info button {
            width: 100% !important;
            background-color: #fff;
            border: 1px solid #ced4da;
        }

        .bootstrap-select .dropdown-toggle .filter-option {
            text-align: start !important;
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
            <div class="setting">
                <div class="container">
                    <div class="row def">
                        <img src="{{ asset('images/setting.svg') }}">
                        <h5>تعديل مدرس </h5>
                    </div>
                    <form method="post" action="{{ route('updateteacher', $teacher->id) }}" enctype="multipart/form-data">
                        @csrf


                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-12 text-center set-img">
                                <img src="{{ asset('uploads/' . $teacher->image) }}" id="realimg">
                                <br>
                                <input id="ad" type="file" class="form-control ehabtalaat" name="image">
                                <label for="ad" class="ahmed">اضافة صوره</label>
                                @error('image')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 text-center set-img">
                                <img src="{{ asset('uploads/' . $teacher->cover_image) }}" id="realimg3">
                                <br>
                                <input id="ad3" type="file" class="form-control ehabtalaat" name="cover_image">
                                <label for="ad3" class="ahmed">اضافة cover </label>
                                @error('cover_image')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 text-center set-img">
                                <img src="{{ asset('uploads/' . $teacher->printsplash) }}" id="realimg2">
                                <br>
                                <input id="ad2" type="file" class="form-control ehabtalaat" name="printsplash">
                                <label for="ad2" class="ahmed">اضافة print splash</label>
                                @error('printsplash')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 text-center mb-5 set-img">
                                <video width="200" height="200" controls>
                                    <source src="{{ asset('uploads/' . $teacher->intro) }}" id="video_here">
                                    Your browser does not support HTML5 video.
                                </video>
                                <br>
                                <br>
                                <input id="kt" type="file" class="form-control ehabtalaat" name="intro">
                                <label for="kt" class="ahmed">اضافة فيديو</label>
                                @error('intro')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="info">
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>الاسم</label>
                                    <input class="form-control" type="text" name="name" value="{{ $teacher->name }}">
                                    @error('name')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>الايميل</label>
                                    <input class="form-control" type="text" name="email"
                                        value="{{ $teacher->email }}">
                                    @error('email')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>كلمه السر</label>
                                    <input class="form-control" type="password" name="password">
                                    @error('password')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>الهاتف</label>
                                    <input class="form-control" type="text" required name="phone"
                                        value="{{ $teacher->phone }}">
                                    @error('phone')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>المحافظة</label>
                                    <select name="state_id" class="form-control" onchange="getcity(this)">
                                        <option value="state_id" selected="selected" disabled>اختر محافظه</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}"
                                                @if ($teacher->state_id == $state->id) selected @endif>{{ $state->state }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>المدينة</label>
                                    <select name="city_id" class="form-control" id="city">
                                        <option value="city_id" selected="selected" disabled>اختر مدينه</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                @if ($teacher->city_id == $city->id) selected @endif>{{ $city->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>العنوان</label>
                                    <input type="text" name="address" class="form-control"
                                        value="{{ $teacher->address }}">
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>المرحله</label>
                                    <select name="state_id" class="form-control" onchange="getstage(this)" required>
                                        <option value="stage_id" selected="selected" disabled>اختر مرحله</option>
                                        @foreach ($stages as $stage)
                                            <option value="{{ $stage->id }}"
                                                @if ($teacher->stage_id == $stage->id) selected @endif>{{ $stage->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>السنه الدراسيه :</label>
                                    <select name="year_id[]" class="form-control selectpicker" id="year" required
                                        onchange="getmanysubs()" multiple="multiple">
                                        <option value="0" selected="selected" disabled>اختر السنه الدراسيه</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->id }}"
                                                @if ($teacher->years->pluck('id')->contains($year->id)) selected @endif>{{ $year->year_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <?php
                                $usersubject = \App\User_Subject::where('user_id', $teacher->id)->pluck('subject_id');
                                $subjects = \App\Subject::whereIn('id', $usersubject)->get(); ?>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>المادة</label>
                                    <select name="subject_id[]" class="form-control selectpicker" required
                                        multiple="multiple" id="subject">
                                        <option value="0" selected="selected" disabled>اختر الماده</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}"
                                                @if ($usersubject->contains($subject->id)) selected @endif>{{ $subject->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>


                            </div>
                            <div class="row">

                            </div>

                            <div class="row">
                                <div class="form-group col-12">
                                    <label>الوصف</label>
                                    <textarea class="form-control" rows="5" name="description">{{ $teacher->description }}</textarea>
                                </div>
                            </div>
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
            },
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
                    location.href = '../teachers';
                }
            }
        });

        function getyear(selected) {

            var id = selected.value;
            console.log(id);
            $.ajax({
                type: "GET",
                url: `../getyear/${id}`, //put y
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
                url: `../getteacher/${id}`, //put y
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#teacher').empty();
                    $('#teacher').html(result);

                }

            });
        }

        function getcity(selected) {
            let id = selected.value;
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `../getcity/${id}`,
                //    contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#city').empty();
                    $('#city').html(result);
                    console.log(result);
                }

            });
        }

        function getstage(selected) {
            let id = selected.value;
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `../getstage/${id}`,
                //    contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#year').empty();
                    $('#year').html(result);
                    $('#year').selectpicker("refresh");
                    console.log(result);
                }

            });
        }

        function getmanysubs() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: `../getmanysubs`,
                data: {
                    'year_id': $('#year').val()
                },
                //    contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#subject').empty();
                    $('#subject').html(result.data);
                    $("#subject").selectpicker("refresh");
                    console.log(result);
                }

            });
        }
        $(document).on("change", "#kt", function(evt) {
            var $source = $('#video_here');
            $source[0].src = URL.createObjectURL(this.files[0]);
            $source.parent()[0].load();
        });

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

        function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#realimg3').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#ad3").change(function() {
            readURL3(this);
        });
    </script>
@endsection
