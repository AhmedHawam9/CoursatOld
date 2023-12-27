<style>
    .form-group.nasra {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 35px;
    }
    .form-group.nasra label {
        margin-bottom: 0 !important;
    }

    .hed h5 {
        font-family: "reg"
    }

    .university_education h4 {
        font-family: "reg"
    }

    .main_education h4 {
        font-family: "reg"
    }
    .mansa {
    display: flex;
    gap: 18px;
}
    @media only screen and (max-width: 600px) {
        .form-group.nasra {
            flex-wrap: wrap;
        }
    }
</style>

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
                        <h5>اضافة حملة </h5>

                    </div>
                    <form method="post" action="{{ route('campaigns.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="info">
                            <div class="row">
                                {{-- input for campaign name --}}
                                <div class="form-group col-lg-3 col-md-12 col-12">
                                    <label>اسم الحملة </label>

                                    <input type="text" class="form-control" placeholder="ادخل اسم " name="title"
                                        value="{{ old('title') }}"required>
                                    @error('title')
                                        <div style="color:red;">{{ $message }} </div>
                                    @enderror
                                </div>
                                {{-- input for campaign description --}}
                                <div class="form-group col-lg-3 col-md-12 col-12">
                                    <label>تفاصيل الحملة </label>

                                    <input type="text" class="form-control" placeholder="ادخل الرقم " name="description"
                                        value="{{ old('description') }}"required>
                                    @error('description')
                                        <div style="color:red;">{{ $message }} </div>
                                    @enderror
                                </div>
                                {{-- input for campaign start date --}}
                                <div class="form-group col-lg-3 col-md-12 col-12">
                                    <label>بداية الحملة</label>
                                    <input type="date" class="form-control" placeholder="ادخل تاريخ البدء"
                                        name="start_date" value="{{ old('start_date') }}"required>

                                    @error('start_date')
                                        <div style="color:red;">{{ $message }} </div>
                                    @enderror
                                </div>
                                {{-- input for campaign end date --}}
                                <div class="form-group col-lg-3 col-md-12 col-12">
                                    <label> نهاية الحملة </label>
                                    <input type="date" class="form-control" placeholder=" ادخل تاريخ الانتهاء "
                                        name="end_date" value="{{ old('end_date') }}"required>

                                    @error('end_date')
                                        <div style="color:red;">{{ $message }} </div>
                                    @enderror
                                </div>
                            </div>


                            {{-- input for campaign platform type --}}
                            <div class="row">
                               <div class="col-lg-12 col-md-12 col-12">
                                <div class="hed">
                                    <h5> نوع المنصه </h5>

                                </div>
                               </div>
                                <div class="col-lg-6 col-md-12 col-12">
                                    <div class="form-group nasra">
                                        @foreach ($platforms as $platform)
                                           <div class="mansa">
                                            <input type="checkbox" name="platform[]" value="{{ $platform->id }}">
                                            <label>{{ $platform->title }}</label>
                                           </div>
                                        @endforeach
                                        @error('center_id')
                                            <div style="color:red;">{{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            {{-- begin of filter --}}

                            {{-- input for campaign type --}}
                            <label class="form-label">نوع التعليم</label>

                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group">

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category_id"
                                                id="radioMainEducation" value="1">
                                            <label class="form-check-label" for="radio1">اساسي</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category_id"
                                                id="radioUniversityEducation" value="2">
                                            <label class="form-check-label" for="radio2">جامعي</label>
                                        </div>

                                        {{-- <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="educationType" id="radio3"
                                                    onchange="toggleRow()" value="option3">
                                                <label class="form-check-label" for="radio3">تعليم حر</label>
                                            </div> --}}
                                    </div>
                                </div>
                            </div>
                            <!-- finish input -->

                            <!-- main education -->
                            <div class="main_education" id="mainEducation" style="display: none">
                                <h4>{{ __('messages.basic') }}</h4>
                                <div class="row">
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label>المرحله</label>
                                        <select class="form-control selectpicker" name="stage_id"
                                            onchange="getstage_years(this)" id="stage" title="ادخل المرحله ">
                                            {{-- <option value="0" selected="selected" required disabled="disabled">ادخل المرحله </option> --}}
                                            @foreach ($stages as $stage)
                                                <option value='{{ $stage->id }}'>{{ $stage->name_ar }}</option>
                                            @endforeach
                                        </select>
                                        @error('stage_id')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label>سنه الماده</label>
                                        <select class="form-control selectpicker" name="year_id" id="year"
                                            onchange="getyear_subjects(this)" title="اختر السنه">
                                            {{-- <option value="0" selected="selected" disabled="disabled">اختر السنه</option> --}}

                                        </select>
                                        @error('years_id')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label>الماده </label>
                                        <select class="form-control selectpicker" name="subject_id" id="subject"
                                            onchange="getSubject_teacher(this)" title="اختر الماده">
                                            {{-- <option value="0" selected="selected" disabled="disabled">اختر الماده</option> --}}

                                        </select>
                                        @error('subjects_id')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- main education -->
                            <script>
                                let radioMainEducation = document.getElementById("radioMainEducation");
                                let divMainEducation = document.getElementById("mainEducation");
                                radioMainEducation.onclick = function() {
                                    divMainEducation.style.display = "block";
                                    divuniversityEducation.style.display = "none";
                                }
                            </script>



                            <!-- university education -->
                            <div class="university_education" id="universityEducation" style="display: none">
                                <h4>{{ __('messages.university education') }}</h4>
                                <div class="row">

                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label>اسم الجامعه </label>
                                        <select name="university_id" class="form-control selectpicker" id="university"
                                            onchange="getcolleges(this)" title="اختر جامعه">
                                            {{-- <option value="0" selected="selected" disabled="disabled">اختر جامعه</option> --}}
                                            @foreach ($universities as $university)
                                                <option value="{{ $university->id }}">
                                                    {{ $university->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('university_id')
                                            <p style="color:red;">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-lg-3 col-md-6 col-12">
                                        <label>اسم الكليه </label>
                                        <select name="college_id" class="form-control selectpicker" id="college"
                                            onchange="getdivision(this)" title="اختر كليه">
                                            {{-- <option value="0" selected="selected" disabled="disabled">اختر كليه</option> --}}

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <script>
                                let radioUniversityEducation = document.getElementById("radioUniversityEducation");
                                let divuniversityEducation = document.getElementById("universityEducation");
                                radioUniversityEducation.onclick = function() {
                                    divuniversityEducation.style.display = "block";
                                    divMainEducation.style.display = "none";
                                }
                            </script>
                            {{-- end of filter  --}}


                        </div>
                </div>
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

    <!--end foter-->
    </div>
    </div>
    <div class="foter">
        <div class="row">
            <div class="col-12 text-center">
                <h5>Made With <img src="{{ asset('images/red.svg') }}"> By Crazy Idea </h5>
                <p>Think Out Of The Box</p>
            </div>
        </div>
    </div>
    <!--end page-body-->
@endsection
@section('scripts')
    <script>
        function getstage_years(selected) {
            let id = selected.value;
            var url = `{{ route('getstage', ':id') }}`;
            url = url.replace(':id', id);
            console.log('getstage_years');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: url,
                data: {
                    "id": id
                },
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#year').empty();
                    $('#year').html(result);
                    $('#year').selectpicker('refresh');
                }

            });
        }

        function getyear_subjects(selected) {
            let id = selected.value;
            var url = `{{ route('getyear', ':id') }}`;
            url = url.replace(':id', id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#subject').empty();
                    $('#subject').html(result);
                    $('#subject').selectpicker('refresh');
                }

            });
        }



        function getSubject_teacher(selected) {
            let id = selected.value;
            var url = `{{ route('getSubject_teacher', ':id') }}`;
            url = url.replace(':id', id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#teachers').empty();
                    $('#teachers').html(result);
                    $('#teachers').selectpicker('refresh');
                }
            });
        }

        function getTeacher_types(teacherId) {
            let subjectId = $('#subject').val();
            let teacher_id = $('#teachers').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getteacher_type/${subjectId}/${teacher_id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#types').empty();
                    $('#types').html(result);
                    $('#types').selectpicker('refresh');
                }

            });
        }
    </script>
    <script>
        function getcolleges(selected) {
            let id = selected.value;
            var url = `{{ route('getcolleges', ':id') }}`;
            url = url.replace(':id', id);
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: url,
                //    contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#college').empty();
                    $('#college').html(result.data);
                    $('.selectpicker').selectpicker('refresh');
                    console.log(result);
                }

            });
        }

        function getdivision(selected) {
            let id = selected.value;
            var url = `{{ route('getdivision', ':id') }}`;
            url = url.replace(':id', id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#division').empty();
                    $('#division').html(result);
                    $('.selectpicker').selectpicker('refresh');
                }

            });
        }

        function getsection(selected) {
            let id = selected.value;
            var url = `{{ route('getsection', ':id') }}`;
            url = url.replace(':id', id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#section').empty();
                    $('#section').html(result);
                    $('.selectpicker').selectpicker('refresh');
                }

            });
        }

        function getsection_subjectsCollege(selected) {
            let id = selected.value;
            var url = `{{ route('getsection_subjectsCollege', ':id') }}`;
            url = url.replace(':id', id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#subject_college').empty();
                    $('#subject_college').html(result);
                    $('#subject_college').selectpicker('refresh');
                }

            });
        }



        function getSubject_teacherCollege(selected) {
            let id = selected.value;
            var url = `{{ route('getSubject_teachercollege', ':id') }}`;
            url = url.replace(':id', id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#teachers_college').empty();
                    $('#teachers_college').html(result);
                    $('#teachers_college').selectpicker('refresh');
                }
            });
        }


        function getTeacher_typescollege(teacherId) {
            let subjectId = $('#subject_college').val();
            let teacher_id = $('#teachers_college').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: `getTeacher_typescollege/${subjectId}/${teacher_id}`,
                contentType: "application/json; charset=utf-8",
                dataType: "Json",
                success: function(result) {
                    $('#typescollege').empty();
                    $('#typescollege').html(result);
                    $('#typescollege').selectpicker('refresh');
                }

            });
        }
    </script>
@endsection
