
<style>
    .filter-option-inner-inner {
    color: black !important;
}
.all-products button {
    width: 100% !important;
}
:not(.input-group)>.bootstrap-select.form-control:not([class*=col-]) {
    width: 95% !important;
}
div#category_id_college {
    background-color: #f4f4f4;
    margin-top: 13px;
    border-radius: 10px;
    margin-top: 10px;
    padding: 0.5rem;
}
h4.hederre {
    font-family: "reg";
    font-size: 1.2rem;
}
</style>

<div class="form-group col-lg-2 col-md-6 col-12">
    {{-- <label>اسم الجامعه </label> --}}
    <select name="university_id"  class="form-control selectpicker" id="university_id"
        onchange="getcolleges(this);filter_students();" title="اختر جامعه">
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
<div class="form-group col-lg-2 col-md-6 col-12">
    {{-- <label>اسم الكليه </label> --}}
    <select name="college_id"  class="form-control selectpicker" id="college_id"
        onchange="getdivision(this);filter_students();" title="اختر كليه">
        {{-- <option value="0" selected="selected" disabled="disabled">اختر كليه</option> --}}

    </select>
</div>
<div class="form-group col-lg-2 col-md-6 col-12">
    {{-- <label>اسم القسم </label> --}}
    <select name="division_id"  class="form-control selectpicker" id="division_id"
        onchange="getsection(this);filter_students();" title="اختر قسم">
        {{-- <option value="0" selected="selected" disabled="disabled">اختر قسم</option> --}}

    </select>
</div>
<div class="form-group col-lg-2 col-md-6 col-12">
    {{-- <label>اسم الفرقه </label> --}}
    <select name="section_id"  class="form-control selectpicker" id="section"
        onchange="getsection_subjectsCollege(this);filter_students();" title="اختر فرقه">
        {{-- <option value="0" selected="selected" disabled="disabled">اختر فرقه</option> --}}

    </select>
</div>
<div class="form-group col-lg-2 col-md-6 col-12">
    {{-- <label>الماده </label> --}}
    <select class="form-control selectpicker" name="subjects_college_id" title="اختر الماده "
        id="subject_college" onchange="getSubject_teacherCollege(this)">
        {{-- <option value="0" selected="selected" disabled="disabled">اختر الماده</option> --}}

    </select>
    @error('subjects_id')
        <p style="color:red;">{{ $message }}</p>
    @enderror
</div>

<div class="form-group col-lg-3 col-md-6 col-12">
    {{-- <label>المدرسين </label> --}}
    <select class="form-control selectpicker" name="teacher_id" requir title="اختر المدرس"ed id="teachers_college"
        onchange="getTeacher_typescollege(this)">
        {{-- <option value="0" selected="selected" disabled="disabled">اختر المدرس</option> --}}

    </select>
    @error('teacher_id')
        <p style="color:red;">{{ $message }}</p>
    @enderror
</div>

<div class="form-group col-lg-3 col-md-6 col-12">
    {{-- <label>الكورسات </label> --}}
    <select class="form-control selectpicker" name="type_college_id"  id="typescollege"
        onchange="filter_students();" title="اختر الكورسات">
        {{-- <option value="0" selected="selected" disabled="disabled">اختر الكورسات</option> --}}

    </select>
    @error('type_college_id')
        <p style="color:red;">{{ $message }}</p>
    @enderror
</div>



{{-- college filters --}}
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
            url: `getcolleges/${id}`,
            //    contentType: "application/json; charset=utf-8",
            dataType: "Json",
            success: function(result) {
                $('#college_id').empty();
                $('#college_id').html(result.data);
                $('.selectpicker').selectpicker('refresh');
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
            url: `getdivision/${id}`,
            contentType: "application/json; charset=utf-8",
            dataType: "Json",
            success: function(result) {
                $('#division_id').empty();
                $('#division_id').html(result);
                $('.selectpicker').selectpicker('refresh');
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
                $('#section_id').empty();
                $('#section_id').html(result);
                $('.selectpicker').selectpicker('refresh');
            }

        });
    }

    function getsection_subjectsCollege(selected) {
        let id = selected.value;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "get",
            url: `getsection_subjectsCollege/${id}`,
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "get",
            url: `getSubjectTeachercollege/${id}`,
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
