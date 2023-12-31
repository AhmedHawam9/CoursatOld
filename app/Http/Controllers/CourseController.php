<?php

namespace App\Http\Controllers;

use App\Course;
use App\General;
use App\Student_Course;
use App\Student_Type;
use App\Student_Typecollege;
use App\Sub;
use App\Type;
use App\TypesCollege;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Validator;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:course-create'])->only('addcourse');
        $this->middleware(['permission:course-read'])->only('course');
        $this->middleware(['permission:course-update'])->only('editcourse');
        $this->middleware(['permission:course-delete'])->only('deletecourse');
    }
    public function addcourse()
    {
        if (Auth::user() && Auth::user()->is_student == 4) {
            $subs = Sub::where('general_id', auth()->user()->general_id)->get();
            $users = "";
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
            $subs = Sub::orderBy('created_at', 'Desc')->get();
            $users = User::where('id', auth()->user()->id)->first()->lecturers;
        } elseif (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $subs = Sub::orderBy('created_at', 'Desc')->get();
            $users = User::where('is_student', 4)->get();
        }
        return view('dashboard.addcourse')->with('generals', General::orderBy('created_at', 'Desc')->get())
            ->with('subs', $subs)->with('lecturers', $users);
    }
    public function storecourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'sub_id' => 'required',

            'intro' => 'required|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi',
        ], [
            'required' => 'هذا الحقل مطلوب',
            'mimetypes' => 'هذا الحقل يقبل فيديو فقط',
            'mimes' => 'هذا الحقل يقبل صوره فقط',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $course = new Course;
        $course->description = $request->description;
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $user = User::where('id', $request->user_id)->first();
            $course->general_id = $request->general_id;
            $course->user_id = $request->user_id;
        } elseif (Auth::user() && Auth::user()->is_student == 4) {
            $user = User::where('id', auth()->id())->first();
            $course->general_id = auth()->user()->general_id;
            $course->user_id = auth()->user()->id;
            $center_id = $user->belongcenter3()->first()->id ?? null;
            if ($center_id) {
                $course->center_id = $center_id;
            }
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
            $user = User::where('id', $request->user_id)->first();
            $course->general_id = $request->general_id;
            $course->user_id = $request->user_id;
            $course->center_id = auth()->user()->id;
        }
        $course->sub_id = $request->sub_id;
        $course->name_ar = $request->name_ar;
        $course->name_en = $request->name_en;
        if ($request->points) {
            $course->points = $request->points;
        }
        if ($request->hasFile('image')) {

            $image = $request->image;

            $file = $image->getClientOriginalName();
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
            $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
            $course->image = $fileName . '_' . time() . '.' . $fileExtension;
        } else {
            $course->image = $user->image;
        }
        if ($request->hasFile('intro')) {
            $intro = $request->intro;
            $intro->move('uploads', time() . '.' . $intro->getClientOriginalExtension());
            $course->intro = time() . '.' . $intro->getClientOriginalExtension();
        }
        $course->save();
        return response()->json(['success' => 'video uploaded']);
    }
    public function course()
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $courses = Course::orderBy('created_at', 'Desc')->get();
            $subs = Sub::orderBy('created_at', 'Desc')->get();
        } elseif (Auth::user() && Auth::user()->is_student == 4) {

            $courses = Course::where('user_id', auth()->user()->id)->orderBy('created_at', 'Desc')->get();
            $subs = Sub::where('general_id', auth()->user()->general_id)->get();
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
            $subs = Sub::orderBy('created_at', 'Desc')->get();
            $courses = Course::where('center_id', auth()->user()->id)->orderBy('created_at', 'Desc')->get();
        }
        return view('dashboard.course')->with('courses', $courses)->with('subs', $subs);
    }
    public function editcourse($id)
    {
        if (Auth::user() && Auth::user()->is_student == 4) {
            $subs = Sub::where('general_id', auth()->user()->general_id)->get();
            $users = "";
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
            $subs = Sub::orderBy('created_at', 'Desc')->get();
            $users = User::where('id', auth()->user()->id)->first()->lecturers;
        } elseif (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $subs = Sub::orderBy('created_at', 'Desc')->get();
            $users = User::all();
        }
        return view('dashboard.editcourse')->with('course', Course::where('id', $id)->first())->with('generals', General::orderBy('created_at', 'Desc')->get())->with('subs', $subs)->with('users', $users);
    }
    public function deletecourse($id)
    {
        $type = Course::where('id', $id)->first();
        if (public_path() . '/uploads/' . $type->intro) {
            $link1 = public_path() . '/uploads/' . $type->intro;
            File::delete($link1);
        }
        if ($type->videos) {
            foreach ($type->videos as $video) {
                if (public_path() . '/uploads/' . $video->image) {
                    $link1 = public_path() . '/uploads/' . $video->image;
                    File::delete($link1);
                }
                if (public_path() . '/uploads/' . $video->url) {
                    $link1 = public_path() . '/uploads/' . $video->url;
                    File::delete($link1);
                }
            }
        }

        $type->delete();
        return response()->json(['status' => true]);
    }
    public function updatecourse($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            //    'image' => 'required|mimes:jpeg,jpg,png,gif',
            //    'intro' => 'required|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi'
        ], [
            'required' => 'هذا الحقل مطلوب',
            'mimetypes' => 'هذا الحقل يقبل فيديو فقط',
            'mimes' => 'هذا الحقل يقبل صوره فقط',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $course = Course::where('id', $id)->first();
        $course->description = $request->description;
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $course->general_id = $request->general_id;
            $course->user_id = $request->user_id;
        } elseif (Auth::user() && Auth::user()->is_student == 4) {
            $course->general_id = auth()->user()->general_id;
            $course->user_id = auth()->user()->id;
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
            $course->general_id = $request->general_id;
            $course->user_id = $request->user_id;
            $course->center_id = auth()->user()->id;
        }
        $course->sub_id = $request->sub_id;
        $course->name_ar = $request->name_ar;
        $course->name_en = $request->name_en;
        if ($request->points) {
            $course->points = $request->points;
        }
        if ($request->hasFile('image')) {
            if (public_path() . '/uploads/' . $course->image) {
                $link = public_path() . '/uploads/' . $course->image;
                File::delete($link);
            }
            $image = $request->image;

            $file = $image->getClientOriginalName();
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
            $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
            $course->image = $fileName . '_' . time() . '.' . $fileExtension;
        }
        if ($request->hasFile('intro')) {
            if (public_path() . '/uploads/' . $course->intro) {
                $link = public_path() . '/uploads/' . $course->intro;
                File::delete($link);
            }
            $intro = $request->intro;
            $intro->move('uploads', time() . '.' . $intro->getClientOriginalExtension());
            $course->intro = time() . '.' . $intro->getClientOriginalExtension();
        }
        $course->save();
        return response()->json(['success' => 'video uploaded']);
    }
    public function getcourse($id)
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $courses = Course::where('user_id', $id)->get();
        } elseif (Auth::user() && Auth::user()->is_student == 4) {
            $courses = Course::where('user_id', auth()->user()->id)->get();
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
            $courses = Course::where('user_id', $id)->get();
        }

        $text = "";
        $text .= '<option value="0"   disabled="disabled">اختارالكورس</option>';
        foreach ($courses as $course) {
            $text .= '<option value="' . $course->id . '">' . $course->name_ar . '</option>';
        }

        return response()->json($text);
    }
    public function getsubcourses($id)
    {
        $courses = Course::where('sub_id', $id)->get();
        $text = "";
        $text .= '<option value="0"   disabled="disabled">اختارالكورس</option>';
        foreach ($courses as $course) {
            $text .= '<option value="' . $course->id . '">' . $course->name_ar . '</option>';
        }
        return response()->json($text);
    }
    public function activecourse($id)
    {
        $course = Course::where('id', $id)->first();
        if ($course->active == 1) {
            $course->active = 0;
            $course->save();
            return response(['status' => 'deactive']);
        } else if ($course->active == 0) {
            $course->active = 1;
            $course->save();
            return response(['status' => 'active']);
        }
    }
    public function givetypecourse()
    {
        if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1) {
            $types1 = auth()->user()->centertypes->pluck('id')->toArray();
            $types = auth()->user()->centertypes;
            $students_ids = Student_Type::whereIn('type_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 2) {
            $types1 = auth()->user()->types->pluck('id')->toArray();
            $types = auth()->user()->types;
            $students_ids = Student_Type::whereIn('type_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $types1 = auth()->user()->centertypescollege->pluck('id')->toArray();
            $types = auth()->user()->centertypescollege;
            $students_ids = Student_Typecollege::whereIn('typecollege_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 3) {
            $types1 = auth()->user()->typescollege->pluck('id')->toArray();
            $types = auth()->user()->typescollege;
            $students_ids = Student_Typecollege::whereIn('typecollege_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
            $types1 = auth()->user()->centercourses->pluck('id')->toArray();
            $types = auth()->user()->centercourses;
            $students_ids = Student_Course::whereIn('course_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 4) {
            $types1 = auth()->user()->courses->pluck('id')->toArray();
            $types = auth()->user()->courses;
            $students_ids = Student_Course::whereIn('course_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        }
        return view('dashboard.givetypecourse')->with('students', $students)->with('types', $types);
    }
    public function gettypecourse($id)
    {
        $user = User::where('id', $id)->first();
        if (Auth::user() && Auth::user()->is_student == 2) {
            $types = auth()->user()->types()->where('years_id', $user->year_id)->whereNotIn("id", $user->stutypes->pluck("id")->toArray())->get();
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1) {
            $types = auth()->user()->centertypes()->where('years_id', $user->year_id)->whereNotIn("id", $user->stutypes->pluck("id")->toArray())->get();
        }
        $text = '';
        $text .= '<option value="0" disabled="disabled" selected="selected">اختر كورس</option>';
        foreach ($types as $type) {
            $text .= '<option value="' . $type->id . '">' . $type->name_ar . '</option>';
        }
        return response()->json(['data' => $text]);
    }
    public function givecourse()
    {
        if (Auth::user() && Auth::user()->is_student == 4) {
            $students = auth()->user()->centerstudents;
            $courses = auth()->user()->courses;
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
            $students = auth()->user()->centerstudents;
            $types = auth()->user()->centercourses;
        }
        return view('dashboard.givecourse')->with('courses', $courses)->with('students', $students);
    }
    public function addcourses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'student_id' => 'required'
        ], [
            'course_id.required' => 'حقل الكورس مطلوب',
            'student_id.required' => 'حقل الطالب مطلوب',
        ]);
        if ($validator->passes()) {
            $student = User::where('id', $request->student_id)->first();
            $course = Course::where('id', $request->course_id)->first();
            $student->stucourses()->attach($request->course_id);
            return response()->json(['status' => true]);
        } else {
            $msg = $validator->messages()->first();
            return response()->json(['status' => false, 'message' => $msg]);
        }
    }
    public function givetypecollegecourse()
    {
        if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1) {
            $types1 = auth()->user()->centertypes->pluck('id')->toArray();
            $types = auth()->user()->centertypes;
            $students_ids = Student_Type::whereIn('type_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 2) {
            $types1 = auth()->user()->types->pluck('id')->toArray();
            $types = auth()->user()->types;
            $students_ids = Student_Type::whereIn('type_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $types1 = auth()->user()->centertypescollege->pluck('id')->toArray();
            $types = auth()->user()->centertypescollege;
            $students_ids = Student_Typecollege::whereIn('typecollege_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 3) {
            $types1 = auth()->user()->typescollege->pluck('id')->toArray();
            $types = auth()->user()->typescollege;
            $students_ids = Student_Typecollege::whereIn('typecollege_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
            $types1 = auth()->user()->centercourses->pluck('id')->toArray();
            $types = auth()->user()->centercourses;
            $students_ids = Student_Course::whereIn('course_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (Auth::user() && Auth::user()->is_student == 4) {
            $types1 = auth()->user()->courses->pluck('id')->toArray();
            $types = auth()->user()->courses;
            $students_ids = Student_Course::whereIn('course_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        }
        return view('dashboard.givetypecollegecourse')->with('students', $students)->with('types', $types);
    }
    public function gettypecollegecourse($id)
    {
        $user = User::where('id', $id)->first();
        if (Auth::user() && Auth::user()->is_student == 3) {
            $types = auth()->user()->typescollege()->where('section_id', $user->section_id)->whereNotIn("id", $user->stutypescollege->pluck("id")->toArray())->get();
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $types = auth()->user()->centertypescollege()->where('section_id', $user->section_id)->whereNotIn("id", $user->stutypescollege->pluck("id")->toArray())->get();
        }
        $text = '';
        $text .= '<option value="0" disabled="disabled" selected="selected">اختر كورس</option>';
        foreach ($types as $type) {
            $text .= '<option value="' . $type->id . '">' . $type->name_ar . '</option>';
        }
        return response()->json(['data' => $text]);
    }
    public function addtypecollegecourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required',
            'student_id' => 'required'
        ], [
            'type_id.required' => 'حقل الكورس مطلوب',
            'student_id.required' => 'حقل الطالب مطلوب',
        ]);
        if ($validator->passes()) {
            $student = User::where('id', $request->student_id)->first();
            $student->stutypescollege()->attach($request->type_id);
            return response()->json(['status' => true]);
        } else {
            $msg = $validator->messages()->first();
            return response()->json(['status' => false, 'message' => $msg]);
        }
    }
    public function addtypecourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required',
            'student_id' => 'required'
        ], [
            'type_id.required' => 'حقل الكورس مطلوب',
            'student_id.required' => 'حقل الطالب مطلوب',
        ]);
        if ($validator->passes()) {
            $student = User::where('id', $request->student_id)->first();
            $student->stutypes()->attach($request->type_id);
            return response()->json(['status' => true]);
        } else {
            $msg = $validator->messages()->first();
            return response()->json(['status' => false, 'message' => $msg]);
        }
    }
    public function addcoursesstudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'student_id' => 'required'
        ], [
            'course_id.required' => 'حقل الكورس مطلوب',
            'student_id.required' => 'حقل الطالب مطلوب',
        ]);

        if ($validator->passes()) {
            if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1) {
                $type = Type::where('id', $request->course_id)->first();
                $students = [];
                foreach ($request->student_id as $student_id) {
                    if (!in_array($student_id, $type->studentstype->pluck("id")->toArray())) {
                        $students[] = $student_id;
                    }
                }
                $type = $type->studentstype()->attach($students);
                return response()->json(['status' => true]);
            } else if (Auth::user() && Auth::user()->is_student == 2) {
                $type = Type::where('id', $request->course_id)->first();

                $students = [];
                foreach ($request->student_id as $student_id) {
                    if (!in_array($student_id, $type->studentstype->pluck("id")->toArray())) {
                        $students[] = $student_id;
                    }
                }
                $type = $type->studentstype()->attach($students);
                return response()->json(['status' => true]);
            } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
                $type = TypesCollege::where('id', $request->course_id)->first();

                $students = [];
                foreach ($request->student_id as $student_id) {
                    if (!in_array($student_id, $type->studentscollege->pluck("id")->toArray())) {
                        $students[] = $student_id;
                    }
                }
                $type = $type->studentscollege()->attach($students);
                return response()->json(['status' => true]);
            } else if (Auth::user() && Auth::user()->is_student == 3) {
                $type = TypesCollege::where('id', $request->course_id)->first();
                $students = [];
                foreach ($request->student_id as $student_id) {
                    if (!in_array($student_id, $type->studentscollege->pluck("id")->toArray())) {
                        $students[] = $student_id;
                    }
                }
                $type = $type->studentscollege()->attach($students);
                return response()->json(['status' => true]);
            } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 3) {
                $type = Course::where('id', $request->course_id)->first();
                $students = [];
                foreach ($request->student_id as $student_id) {
                    if (!in_array($student_id, $type->studentscourses->pluck("id")->toArray())) {
                        $students[] = $student_id;
                    }
                }
                $type = $type->studentscourses()->attach($students);
                return response()->json(['status' => true]);
            } else if (Auth::user() && Auth::user()->is_student == 4) {
                $type = Course::where('id', $request->course_id)->first();
                $students = [];
                foreach ($request->student_id as $student_id) {
                    if (!in_array($student_id, $type->studentscourses->pluck("id")->toArray())) {
                        $students[] = $student_id;
                    }
                }
                $type = $type->studentscourses()->attach($students);
                return response()->json(['status' => true]);
            }
        } else {
            $message = $validator->messages()->first();
            return response()->json(['status' => false, 'message' => $message]);
        }
    }
}
