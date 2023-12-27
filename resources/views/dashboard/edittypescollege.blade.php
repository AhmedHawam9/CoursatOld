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
            <div class="setting">
                <div class="container">
                    <div class="row def">
                        <img src="{{ asset('images/setting.svg') }}">
                        <h5>تعديل كورس </h5>
                    </div>
                    <form method="post" action="{{ route('updatetypescollege', $typescollege->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="info">
                            <div class="row">
                                <div class="col-6 text-center mb-5 set-img">
                                    <video width="200" height="200" controls>
                                        <source src="{{ asset('uploads/' . $typescollege->intro) }}" id="video_here">
                                        Your browser does not support HTML5 video.
                                    </video>
                                    <br>
                                    <br>
                                    <input id="kt" type="file" class="form-control ehabtalaat" name="intro">
                                    <label for="kt" class="ahmed">اضافة انترو</label>
                                    @error('intro')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-6 text-center set-img">
                                    <img src="{{ asset('uploads/' . $typescollege->image) }}" id="realimg">
                                    <br>
                                    <input id="ad" type="file" class="form-control ehabtalaat" name="image">
                                    <label for="ad" class="ahmed">اضافة صوره</label>
                                    @error('image')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            @if (Auth::user() && Auth::user()->isAdmin == 'admin')
                                <div class="row">
                                    <div class="form-group col-3">
                                        <label> اسم البارت بالعربى </label>
                                        <input class="form-control" type="text" name="name_ar" required
                                            value="{{ $typescollege->name_ar }}">
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم البارت بالانجليزي</label>
                                        <input class="form-control" type="text" name="name_en" required
                                            value="{{ $typescollege->name_en }}">
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم الجامعه </label>
                                        <select name="university_id" required class="form-control"
                                            onchange="getcolleges(this)">
                                            <option value="0" disabled="disabled" selected="selected">اختر جامعه
                                            </option>
                                            @foreach ($universities as $university)
                                                <option value="{{ $university->id }}"
                                                    @if ($typescollege->university_id == $university->id) selected @endif</option>
                                                    {{ $university->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('university_id')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم الكليه </label>
                                        <select name="college_id" class="form-control" id="college"
                                            onchange="getdivision(this)">
                                            <option value="0" disabled="disabled" selected="selected" required>اختر
                                                كليه</option>
                                            @foreach ($colleges as $college)
                                                <option value="{{ $college->id }}"
                                                    @if ($typescollege->college_id == $college->id) selected @endif>
                                                    {{ $college->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم الفرقه </label>
                                        <select name="division_id" class="form-control" id="division"
                                            onchange="getsection(this)">
                                            <option value="0" disabled="disabled" selected="selected" required>اختر
                                                فرقه</option>
                                            @foreach ($divisions as $division)
                                                <option
                                                    value="{{ $division->id }}"@if ($typescollege->division_id == $division->id) selected @endif>
                                                    {{ $division->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم القسم </label>
                                        <select name="section_id" class="form-control" id="section"
                                            onchange="getsubcollege(this)">
                                            <option value="0" disabled="disabled" selected="selected" required>اختر
                                                قسم</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}"
                                                    @if ($typescollege->section_id == $section->id) selected @endif>
                                                    {{ $section->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم الماده </label>
                                        <select name="subjectscollege_id" class="form-control" id="subcollege"
                                            onchange="getdoctor(this)">
                                            <option value="0" disabled="disabled" required selected="selected">اختر
                                                ماده</option>
                                            @foreach ($subcolleges as $subcollege)
                                                <option value="{{ $subcollege->id }}"
                                                    @if ($typescollege->subjectscollege_id == $subcollege->id) selected @endif>
                                                    {{ $subcollege->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اختار الدكتور</label>
                                        <select name="doctor_id" class="form-control" required id="doctor">
                                            <option value="0" selected="selected" disabled>اختر الدكتور</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    @if ($typescollege->doctor_id == $user->id) selected @endif>{{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label>التاج </label>
                                        <select class="form-control selectpicker" data-live-search="true" multiple
                                            name="tag_id[]">

                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->id }}"
                                                    @if (in_array($tag->id, $typescollege->tags->pluck('id')->toArray())) selected @endif>{{ $tag->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>عدد النقط</label>
                                        <input type="number" name="points" value="{{ $typescollege->points }}">
                                    </div>

                                </div>
                            @elseif(Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2)
                                <div class="row">
                                    <div class="form-group col-3">
                                        <label> اسم البارت بالعربى </label>
                                        <input class="form-control" type="text" required name="name_ar"
                                            value="{{ $typescollege->name_ar }}">
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم البارت بالانجليزي</label>
                                        <input class="form-control" type="text" required name="name_en"
                                            value="{{ $typescollege->name_en }}">
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم الجامعه </label>
                                        <select name="university_id" required class="form-control"
                                            onchange="getcolleges(this)">
                                            <option value="0" disabled="disabled" selected="selected">اختر جامعه
                                            </option>
                                            @foreach ($universities as $university)
                                                <option value="{{ $university->id }}"
                                                    @if ($typescollege->university_id == $university->id) selected @endif</option>
                                                    {{ $university->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('university_id')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم الكليه </label>
                                        <select name="college_id" class="form-control" id="college" required
                                            onchange="getdivision(this)">
                                            <option value="0" disabled="disabled" selected="selected">اختر كليه
                                            </option>
                                            @foreach ($colleges as $college)
                                                <option value="{{ $college->id }}"
                                                    @if ($typescollege->college_id == $college->id) selected @endif>
                                                    {{ $college->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم الفرقه </label>
                                        <select name="division_id" class="form-control" required id="division"
                                            onchange="getsection(this)">
                                            <option value="0" disabled="disabled" selected="selected">اختر فرقه
                                            </option>
                                            @foreach ($divisions as $division)
                                                <option
                                                    value="{{ $division->id }}"@if ($typescollege->division_id == $division->id) selected @endif>
                                                    {{ $division->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم القسم </label>
                                        <select name="section_id" class="form-control" required id="section"
                                            onchange="getsubcollege(this)">
                                            <option value="0" disabled="disabled" selected="selected">اختر قسم
                                            </option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}"
                                                    @if ($typescollege->section_id == $section->id) selected @endif>
                                                    {{ $section->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم الماده </label>
                                        <select name="subjectscollege_id" required class="form-control" id="subcollege"
                                            onchange="getdoctor(this)">
                                            <option value="0" disabled="disabled" selected="selected">اختر ماده
                                            </option>
                                            @foreach ($subcolleges as $subcollege)
                                                <option value="{{ $subcollege->id }}"
                                                    @if ($typescollege->subjectscollege_id == $subcollege->id) selected @endif>
                                                    {{ $subcollege->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اختار الدكتور</label>
                                        <select name="doctor_id" class="form-control" required id="doctor">
                                            <option value="0" selected="selected" disabled>اختر الدكتور</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    @if ($typescollege->doctor_id == $user->id) selected @endif>{{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label>التاج </label>
                                        <select class="form-control selectpicker" data-live-search="true" multiple
                                            name="tag_id[]">

                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->id }}"
                                                    @if (in_array($tag->id, $typescollege->tags->pluck('id')->toArray())) selected @endif>{{ $tag->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>عدد النقط</label>
                                        <input type="number" name="points" value="{{ $typescollege->points }}">
                                    </div>

                                </div>
                            @elseif(Auth::user() && Auth::user()->is_student == 3)
                                <div class="row">
                                    <div class="form-group col-3">
                                        <label> اسم البارت بالعربى </label>
                                        <input class="form-control" type="text" required name="name_ar"
                                            value="{{ $typescollege->name_ar }}">
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم البارت بالانجليزي</label>
                                        <input class="form-control" type="text" required name="name_en"
                                            value="{{ $typescollege->name_en }}">
                                    </div>

                                    <div class="form-group col-3">
                                        <label>اسم الفرقه </label>
                                        <select name="division_id" class="form-control" required id="division"
                                            onchange="getdocsection(this)">
                                            <option value="0" disabled="disabled" selected="selected">اختر فرقه
                                            </option>
                                            @foreach ($divisions as $division)
                                                <option
                                                    value="{{ $division->id }}"@if ($typescollege->division_id == $division->id) selected @endif>
                                                    {{ $division->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم القسم </label>
                                        <select name="section_id" class="form-control" required id="section"
                                            onchange="getdocsubcollege(this)">
                                            <option value="0" disabled="disabled" selected="selected">اختر قسم
                                            </option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}"
                                                    @if ($typescollege->section_id == $section->id) selected @endif>
                                                    {{ $section->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>اسم الماده </label>
                                        <select name="subjectscollege_id" class="form-control" id="subcollege">
                                            <option value="0" disabled="disabled" required selected="selected">اختر
                                                ماده</option>
                                            @foreach ($subcolleges as $subcollege)
                                                <option value="{{ $subcollege->id }}"
                                                    @if ($typescollege->subjectscollege_id == $subcollege->id) selected @endif>
                                                    {{ $subcollege->name_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label>التاج </label>
                                        <select class="form-control selectpicker" data-live-search="true" multiple
                                            name="tag_id[]">

                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->id }}"
                                                    @if (in_array($tag->id, $typescollege->tags->pluck('id')->toArray())) selected @endif>{{ $tag->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-3">
                                        <label>عدد النقط</label>
                                        <input type="number" name="points" value="{{ $typescollege->points }}">
                                    </div>

                                </div>
                            @endif
                            <div class="form-group col-6">
                                <label>الوصف </label>
                                <textarea class="form-control" rows="5" name="description">
                                       {{ $typescollege->description }}</textarea>
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
        function getcolleges(selected) {
            let id = selected.value;
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `../getcolleges/${id}`,
                //    contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#college').empty();
                    $('#college').html(result.data);
                    console.log(result);
                }

            });
        }

        function getdivision(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `../getdivision/${id}`,
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
                url: `../getsection/${id}`,
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
                url: `../getsubcollege/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#subcollege').empty();
                    $('#subcollege').html(result);
                }

            });
        }

        function getdocsection(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `../getdocsection/${id}`,
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
                url: `../getdocsubcollege/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#subcollege').empty();
                    $('#subcollege').html(result);
                }

            });
        }

        function getdoctor(selected) {
            let id = selected.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `../getdoctor/${id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#doctore').empty();
                    $('#doctor').html(result);
                }

            });
        }
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
                    location.href = '../typescolleges';
                }
            }
        });
    </script>
@endsection
