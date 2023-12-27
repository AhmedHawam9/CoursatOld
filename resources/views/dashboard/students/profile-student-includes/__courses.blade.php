<div class="header-table">
    <h3>كورسات</h3>
    {{-- <div class="form-group">
        <input type="date" id="course_date" onclick="filter_courses({{ $student->id }})" class="form-control">
    </div> --}}
</div>
<div class="table-responsive">
    <div class="table_details">
        <table id="example" class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">اسم الكورس</th>
                    <th scope="col">تاريخ اشتراك الكورس</th>
                    <th scope="col">نوع الاشتراك</th>
                    <th scope="col">نوع المنصه</th>
                    <th scope="col"></th>


                </tr>
            </thead>
            <tbody id="courses">
                @forelse ($student_courses as $item)
                    <tr>
                        <th scope="row" id="course_row{{ $item->id }}">{{ $item->id }}</th>
                        <td>{{ $item->name_ar }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->pivot->created_at)->format('Y-m-d g:i A') }}</td>
                        <td>--</td>
                        {{-- <td>{{ $item->pivot->getTypeFormatAttribute() ?? '--' }}</td> --}}
                        <td>{{ $item->center_id ? $item->center->name : '--' }}</td>
                        @if ($student->category_id == config('project_types.system_category_type.category_id_college'))
                            <td onclick="deleteuser_from_stutypescollege({{ $student->id }},{{ $item->id }})"
                                title="حذف الطالب من هذا الكورس"><i class="fas fa-trash-alt delet"></i></td>
                        @elseif ($student->category_id == config('project_types.system_category_type.category_id_basic'))
                            <td onclick="deleteuser_from_stutypes({{ $student->id }},{{ $item->id }})"
                                title="حذف الطالب من هذا الكورس"><i class="fas fa-trash-alt delet"></i></td>
                        @endif
                    </tr>
                @empty
                    <tr>الطالب لا يمتلك اي كورس</tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- window.location.href = '{{ route('dashboard.students.show', ':student_id') }}'.replace(':student_id', student_id) + '?course_date=' + course_date; --}}
<script>
    function filter_courses(student_id) {
        var url = `{{ route('students.subscribtions.get_courses') }}`;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: url,
            //   contentType: "application/json; charset=utf-8",
            dataType: "Json",
            data: {
                "student_id": student_id,
                "course_date": $('#course_date :selected').val(),
            },
            success: function(result) {
                if (result.status == true) {
                    $('#example').DataTable().destroy();
                    $("#courses").empty();
                    $("#courses").append(result.data);
                    $('#example').DataTable().draw();
                }

            }

        });
    }
</script>
