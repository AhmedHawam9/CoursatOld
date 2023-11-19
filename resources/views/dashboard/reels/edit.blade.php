@extends('App.dash')
@section('style')
<style>
    #example_wrapper {
        width: 100% !important;
    }
</style>
@endsection
@section('content')
    <div class="page-body">
        <div class="container">
            <!-- header section -->
            <div class="main_topic">
                <h4>{{__('messages.edit reel')}}</h4>
            </div>

            <form class="form_topic" action="{{route('reels.update',$reel->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- start input -->
                <div class="row">
                    <div class="col-12">
                        <div class="input-group">
                            <label class="form-label"> {{ __('messages.name') }}</label>
                            <input required type="text" name="name" value="{{$reel->name}}" placeholder="{{ __('messages.name') }}" class="form-control">
                        </div>
                    </div>
                </div>
                <!-- finish input -->

                <!-- start input -->
                <div class="row">
                    <div class="input-group pt-4">
                        <div class="file-upload-wrapper"
                             data-text="{{ __('messages.Drag the video you want to add or open it from here') }}">
                            <input name="video" type="file"
                                   class="file-upload-field"  value="{{$reel->video}}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 mx-auto text-center mb-5 set-img">
                    <video width="200" height="200" controls>
                        <source src="{{ $reel->video_link ?? '' }}" id="video_here">
                        Your browser does not support HTML5 video.
                    </video>

                    @error('video')
                        <p style="color:red;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="input-group">
                            <label class="form-label">{{ __('messages.education_stage') }}</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="category_id" id="radio1"
                                        onchange="toggleRow()" value="1">
                                    <label class="form-check-label" for="radio1">{{ __('messages.basic') }}</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="category_id" id="radio2"
                                        onchange="toggleRow()" value="2">
                                    <label class="form-check-label"
                                        for="radio2">{{ __('messages.university education') }}</label>
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
                <div class="main_education" id="mainEducation">
                    <h4>{{ __('messages.basic') }}</h4>
                    <div class="row">
                        {{-- <div class="col-12">
                        <div class="input-group">
                            <label class="form-label"> الصلاحيات</label>
                            <select required class="selectpicker"
                                data-selected-text-format="count > 1" data-icon="bi bi-trash"
                                data-live-search="true">
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="form-group col-lg-3 col-md-6 col-12">
                        <label>المرحله</label>
                        <select class="form-control selectpicker" name="stage_id" onchange="getstage_years(this);" id="stage" title="ادخل المرحله ">
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
                        <select class="form-control selectpicker" name="years_id" required id="year" onchange="getyear_subjects(this);" title="اختر السنه">
                            {{-- <option value="0" selected="selected" disabled="disabled">اختر السنه</option> --}}

                        </select>
                        @error('years_id')
                            <p style="color:red;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-12">
                        <label>الماده </label>
                        <select class="form-control selectpicker" name="subjects_id" required id="subject" onchange="getSubject_teacher(this)" title="اختر الماده">
                            {{-- <option value="0" selected="selected" disabled="disabled">اختر الماده</option> --}}

                        </select>
                        @error('subjects_id')
                            <p style="color:red;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-lg-3 col-md-6 col-12">
                        <label>المدرسين </label>
                        <select class="form-control selectpicker" name="teacher_id" required id="teachers"  onchange="getTeacher_types(this)" title="اختر المدرس">
                            {{-- <option value="0" selected="selected" disabled="disabled">اختر المدرس</option> --}}

                        </select>
                        @error('teacher_id')
                            <p style="color:red;">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>
                </div>
                <!-- main education -->



                <!-- university education -->
                <div class="university_education" id="universityEducation">
                    <h4>{{ __('messages.university education') }}</h4>
                    <div class="row">

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label>اسم الجامعه </label>
                            <select name="university_id" required class="form-control selectpicker" id="university"
                                onchange="getcolleges(this);" title="اختر جامعه">
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
                            <select name="college_id" required class="form-control selectpicker" id="college"
                                onchange="getdivision(this);" title="اختر كليه">
                                {{-- <option value="0" selected="selected" disabled="disabled">اختر كليه</option> --}}

                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label>اسم القسم </label>
                            <select name="division_id" required class="form-control selectpicker" id="division"
                                onchange="getsection(this);" title="اختر قسم">
                                {{-- <option value="0" selected="selected" disabled="disabled">اختر قسم</option> --}}

                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label>اسم الفرقه </label>
                            <select name="section_id" required class="form-control selectpicker" id="section"
                                onchange="getsection_subjectsCollege(this);" title="اختر فرقه">
                                {{-- <option value="0" selected="selected" disabled="disabled">اختر فرقه</option> --}}

                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label>الماده </label>
                            <select class="form-control selectpicker" name="subjects_college_id" title="اختر الماده " required
                                id="subject_college" onchange="getSubject_teacherCollege(this)">
                                {{-- <option value="0" selected="selected" disabled="disabled">اختر الماده</option> --}}

                            </select>
                            @error('subjects_id')
                                <p style="color:red;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-lg-3 col-md-6 col-12">
                            <label>المدرسين </label>
                            <select class="form-control selectpicker" name="teacher_id" requir title="اختر المدرس"ed id="teachers_college"
                                onchange="getTeacher_typescollege(this)">
                                {{-- <option value="0" selected="selected" disabled="disabled">اختر المدرس</option> --}}

                            </select>
                            @error('teacher_id')
                                <p style="color:red;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- finish input -->
                <div class="row">
                    <div class="col-lg-6">
                        <button type="submit" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                class="btn first">{{ __('messages.update') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </div>


    <script src="../js/jquery-3.7.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/bootstrap-select.min.js"></script>
<script src="../js/main.js"></script>
    <script>
        // This function for main education
        function toggleRow() {
            var checkbox = document.getElementById('radio1');
            var hiddenRow = document.getElementById('mainEducation');
            var checkbox2 = document.getElementById('radio2');
            var hiddenRow2 = document.getElementById('universityEducation');
            // var checkbox3 = document.getElementById('radio3');
            // var hiddenRow3 = document.getElementById('freeEducation');

            if (checkbox.checked) {
                // Show the hidden row
                hiddenRow.classList.remove('main_education');
                hiddenRow2.classList.add('university_education');
                hiddenRow3.classList.add('free_education');
            }
            else if(checkbox2.checked) {
                // Show the hidden row
                hiddenRow.classList.add('main_education');
                hiddenRow2.classList.remove('university_education');
                hiddenRow3.classList.add('free_education');

            }
            else  if(checkbox3.checked) {
                // Show the hidden row
                hiddenRow.classList.add('main_education');
                hiddenRow2.classList.add('university_education');
                hiddenRow3.classList.remove('free_education');
            }



        }


        </script>
        <!-- show main education -->

@endsection
