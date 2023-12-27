<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\State;
use App\Point;
use App\City;
use App\Year;
use App\Subject;
use App\Type;
use App\Subtype;
use App\Video;
use Illuminate\Support\Facades\File;
use App\User;
use App\Offer;
use App\User_Year;
use Illuminate\Validation\Rule;
use App\Category;
use App\Stage;
use App\College;
use App\Division;
use App\Section;
use App\SubjectsCollege;
use App\TypesCollege;
use App\Lesson;
use App\VideosCollege;
use App\General;
use App\Sub;
use App\Course;
use App\Sub_User;
use App\Stage_User;
use App\College_User;
use App\VideosGeneral;
use App\Bouquet;
use App\Notification;
use Session;
use Validator;
use App\Doctor_Subcollege;
use App\Doctor_Division;
use App\Doctor_Section;
use App\University;
use App\Specialbasic;
use App\Specialcollege;
use App\User_Subject;
use App\Center_Teacher;
use App\Center_Doctor;
use App\Center_Lecturer;
use App\Student_Typecollege;
use Illuminate\Support\Facades\Auth;
use Owenoj\LaravelGetId3\GetId3;
use App\Http\Resources\CityResource1;
use QrCode;
use App\Message;
use Carbon\Carbon;
use App\Student_Type;
use App\Paqa;
use App\Paqa_User;
use App\Services\AuthDataService;
use App\Student_Course;
use Illuminate\Support\Facades\Hash;
use App\TypeexamResult;
use App\TypescollegeexamResult;
use App\TypeJoin;
use App\TypecollegeJoin;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function basicstudents()
    {
        $students = User::where('is_student', 1)->whereNotNull("name")->whereNotNull("year_id")->get();
        $auth_service = new AuthDataService();
        $types = $auth_service->getAuthType();
        return view('dashboard.students.basicstudents', compact('students', "types"))->with('stages', Stage::all());
    }
    public function filterbasicstudents(Request $request)
    {

        $students = User::where('is_student', 1)->whereNotNull("name")->where('year_id', $request->years_id)->get();
        $text = "";
        foreach ($students as $student) {
            $text .= '<tr id="s' . $student->id . '">


                      <td scope="col" class="text-center"><a href="' . route("studentprofile", $student->id) . '">' . $student->name . '</a></td>
				<td scope="col" class="text-center">' . $student->code . '</td>
             <td scope="col" class="text-center">';
            if ($student->state) {
                $text .=  $student->state['state'];
            }
            $text .= '</td>
						<td scope="col" class="text-center">';
            if ($student->city) {
                $text .= $student->city['city'];
            }
            $text .= '</td>
						<td class="text-center">
                               <span class="btn btn-success btn-sm" id="btn' . $student->id . '" onclick="activeuser(' . $student->id . ')">';
            if ($student->active == 1) {
                $text .= 'الغاء التفعيل';
            } else {
                $text .= 'تفعيل';
            }
            $text .= '</span>
                             <img src="' . asset("images/trash.svg") . '" id="trash" onclick="deleteuser(' . $student->id . ')" style="cursor:pointer;">
                                            </td>

                                        </tr>';
        }
        return response()->json(['status' => true, 'data' => $text]);
    }
    public function collegestudents()
    {
        $students = User::where('is_student', 1)->whereNotNull("name")->whereNotNull("section_id")->get();
        $divisions = Division::all();
        $sections = Section::all();
        return view('dashboard.students.collegestudents', compact('students'))->with('colleges', College::all())->with('divisions', $divisions)->with('sections', $sections)
            ->with('universities', University::all());
    }
    public function filtercollegestudents(Request $request)
    {

        if ($request->section_id  && $request->division_id && $request->college_id && $request->university_id) {
            $students = User::where('is_student', 1)->whereNotNull("name")->where("section_id", $request->section_id)->get();
        } elseif ($request->division_id && $request->college_id && $request->university_id) {
            $students = User::where('is_student', 1)->whereNotNull("name")->where("division_id", $request->division_id)->get();
        } elseif ($request->college_id && $request->university_id) {
            $students = User::where('is_student', 1)->whereNotNull("name")->where("college_id", $request->college_id)->get();
        } elseif ($request->university_id) {
            $students = User::where('is_student', 1)->whereNotNull("name")->where("university_id", $request->university_id)->get();
        }
        $text = "";
        foreach ($students as $student) {
            $text .= '<tr id="s' . $student->id . '">


                      <td scope="col" class="text-center"><a href="' . route("studentprofile", $student->id) . '">' . $student->name . '</a></td>
				<td scope="col" class="text-center">' . $student->code . '</td>
             <td scope="col" class="text-center">';

            $text .= '<ul>';
            if ($student->stutypescollege) {
                foreach ($student->stutypescollege as $typecollege) {
                    $text .= '<li style="font-size:14px;">' . $typecollege->name_ar . '</li>';
                }
            }
            $text .= '</ul>';

            $text .= '</td>

						<td class="text-center">
                               <span class="btn btn-success btn-sm" id="btn' . $student->id . '" onclick="activeuser(' . $student->id . ')">';
            if ($student->active == 1) {
                $text .= 'الغاء التفعيل';
            } else {
                $text .= 'تفعيل';
            }
            $text .= '</span>
                             <img src="' . asset("images/trash.svg") . '" id="trash" onclick="deleteuser(' . $student->id . ')" style="cursor:pointer;">
                                            </td>

                                        </tr>';
        }
        return response()->json(['status' => true, 'data' => $text]);
    }
    public function students()
    {
        $students = User::where('is_student', 1)->where('name', '<>', NULL)->select("name", "id", "code", "phone", "year_id")->get();
        return view('dashboard.students')->with('students', $students);
    }
    public function mytypestudents()
    {
        if (
            Auth::user() &&
            Auth::user()->is_student == config('project_types.auth_user_is_student.center') &&
            Auth::user()->category_id == config('project_types.system_category_type.category_id_basic')
        ) {
            $types1 = auth()->user()->centertypes->pluck('id')->toArray();
            $types = auth()->user()->centertypes;
            $students_ids = Student_Type::whereIn('type_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (
            Auth::user() &&
            Auth::user()->is_student == config('project_types.auth_user_is_student.basic_lecturer')
        ) {
            $types1 = auth()->user()->types->pluck('id')->toArray();
            $types = auth()->user()->types;
            $students_ids = Student_Type::whereIn('type_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (
            Auth::user() && Auth::user()->is_student == config('project_types.auth_user_is_student.center') &&
            Auth::user()->category_id == config('project_types.system_category_type.category_id_college')
        ) {
            $types1 = auth()->user()->centertypescollege->pluck('id')->toArray();
            $types = auth()->user()->centertypescollege;
            $students_ids = Student_Typecollege::whereIn('typecollege_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (
            Auth::user() &&
            Auth::user()->is_student == config('project_types.auth_user_is_student.doctor')
        ) {
            $types1 = auth()->user()->typescollege->pluck('id')->toArray();
            $types = auth()->user()->typescollege;
            $students_ids = Student_Typecollege::whereIn('typecollege_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (
            Auth::user() && Auth::user()->is_student == config('project_types.auth_user_is_student.center') &&
            Auth::user()->category_id == 3
        ) {
            $types1 = auth()->user()->centercourses->pluck('id')->toArray();
            $types = auth()->user()->centercourses;
            $students_ids = Student_Course::whereIn('course_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        } else if (
            Auth::user() &&
            Auth::user()->is_student == config('project_types.auth_user_is_student.private_course_lecturer')
        ) {
            $types1 = auth()->user()->courses->pluck('id')->toArray();
            $types = auth()->user()->courses;
            $students_ids = Student_Course::whereIn('course_id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge(auth()->user()->centerstudents);
        }
        return view("dashboard.mytypestudents")->with('students', $students)->with('types', $types);
    }
    public function typeresults_students($id)
    {
        $examresults =  TypeexamResult::where('student_id', $id)->get();
        return view("dashboard.students.typeresults_students")->with('examresults', $examresults);
    }
    public function typecollegeresults_students($id)
    {
        $examresults =  TypescollegeexamResult::where('student_id', $id)->get();
        return view("dashboard.students.typecollegeresults_students")->with('examresults', $examresults);
    }
    public function addtypesollegestudent(Request $request, $id)
    {
        $arr = [];
        array_push($arr, $id);
        $type =  TypesCollege::where('id', $request->course_id)->first();
        $type->studentscollege()->attach($arr);
        return response()->json(['status' => true]);
    }
    public function addtypestudent($id)
    {
        $arr = [];
        array_push($arr, $id);

        $type =  Type::where('id', $request->course_id)->first();
        $type->studentstype()->attach($arr);
        return response()->json(['status' => true]);
    }
    public function studentstype($id)
    {
        $students = Type::where("id", $id)->first()->studentstype;
        $status = 0;
        return view("dashboard.courses_students.studentstype", compact("students", "id", "status"));
    }

    public function bannedStudentstype($id)
    {
        $students = [];
        $students_joins = Student_Type::where("type_id", $id)->onlyTrashed()->get();
        foreach ($students_joins as $join) {
            $students[] = $join->student;
        }
        // $students = Type::where("id", $id)->first()->studentstype()->onlyTrashed()->get();
        $status = 0;
        return view("dashboard.courses_students.bannedStudentstype", compact("students", "id", "status"));
    }
    public function studentstypecollege($id)
    {
        $students = TypesCollege::where("id", $id)->first()->studentscollege;
        $status = 1;
        return view("dashboard.courses_students.studentstype", compact("students", "id", "status"));
    }
    public function bannedStudentstypecollege($id)
    {

        $students = [];
        $students_joins = Student_Typecollege::where("typecollege_id", $id)->onlyTrashed()->get();
        $students = $students_joins->map(function ($item) {
            return $item->student;
        });
        // foreach ($students_joins as $join) {
        //     $students[] = $join->student;
        // }
        // $students = TypesCollege::with(['studentscollege' => function ($query) {
        //     $query->onlyTrashed();
        // }])->find($id)->studentscollege;
        $status = 1;
        return view("dashboard.courses_students.bannedStudentstype", compact("students", "id", "status"));
    }
    public function studentscourse($id)
    {

        $students = Course::where("id", $id)->first()->studentscourses;
        $status = 2;
        return view("dashboard.courses_students.studentstype", compact("students", "id", "status"));;
    }
    public function userstudents()
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
        $stages = Stage::all();
        $colleges = College::all();
        $divisions = Division::all();
        $sections = Section::all();
        $universities = University::all();
        return view("dashboard.courses_students.students", compact(
            "students",
            "stages",
            "universities",
            "colleges",
            "divisions",
            "sections"
        ));
    }
    public function teacherstudents($id)
    {
        $user = User::where("id", $id)->first();
        if ($user && $user->is_student == 5 && $user->category_id == 1) {
            $types1 = $user->centertypes->pluck('id')->toArray();
            $types = $user->centertypes;
            $students_ids = Student_Type::whereIn('id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge($user->centerstudents);
        } else if ($user && $user->is_student == 2) {
            $types1 = $user->types->pluck('id')->toArray();
            $types = $user->types;
            $students_ids = Student_Type::whereIn('id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge($user->centerstudents);
        } else if ($user && $user->is_student == 5 && $user->category_id == 2) {
            $types1 = $user->centertypescollege->pluck('id')->toArray();
            $types = $user->centertypescollege;
            $students_ids = Student_Typecollege::whereIn('id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge($user->centerstudents);
        } else if ($user && $user->is_student == 3) {
            $types1 = $user->typescollege->pluck('id')->toArray();
            $types = $user->typescollege;
            $students_ids = Student_Typecollege::whereIn('id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge($user->centerstudents);
        } else if ($user && $user->is_student == 5 && $user->category_id == 3) {
            $types1 = $user->centercourses->pluck('id')->toArray();
            $types = $user->centercourses;
            $students_ids = Student_Course::whereIn('id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge($user->centerstudents);
        } else if ($user && $user->is_student == 4) {
            $types1 = $user->courses->pluck('id')->toArray();
            $types = $user->courses;
            $students_ids = Student_Course::whereIn('id', $types1)->get()->pluck('student_id')->unique();
            $students1 = User::whereIn('id', $students_ids)->get();
            $students = $students1->merge($user->centerstudents);
        }
        return view("dashboard.courses_students.students", compact("students"));
    }
    public function deletestudentcourse(Request $request)
    {
        if ($request->status == 0) {
            $studenttype = Student_Type::where([['student_id', '=', $request->student_id], ['type_id', '=', $request->course_id]])->first();
            TypeJoin::where([['student_id', '=', $request->student_id], ['type_id', '=', $request->course_id]])->delete();
        } elseif ($request->status == 1) {
            $studenttype = Student_Typecollege::where([['student_id', '=', $request->student_id], ['typecollege_id', '=', $request->course_id]])->first();
            TypecollegeJoin::where([['student_id', '=', $request->student_id], ['typecollege_id', '=', $request->course_id]])->delete();
        } elseif ($request->status == 2) {
            $studenttype = Student_Course::where([['student_id', '=', $request->student_id], ['course_id', '=', $request->course_id]])->first();
        }
        $studenttype->delete();
        return response()->json(['status' => true]);
    }
    public function activestudentcourse(Request $request)
    {
        if ($request->status == 0) {
            $studenttype = Student_Type::where([['student_id', '=', $request->student_id], ['type_id', '=', $request->course_id]])->first();
        } elseif ($request->status == 1) {
            $studenttype = Student_Typecollege::where([['student_id', '=', $request->student_id], ['typecollege_id', '=', $request->course_id]])->first();
        } elseif ($request->status == 2) {
            $studenttype = Student_Course::where([['student_id', '=', $request->student_id], ['course_id', '=', $request->course_id]])->first();
        }
        if ($studenttype->active == 1) {
            $studenttype->active = 0;
            $studenttype->save();
            return response(['status' => 'deactive']);
        } else if ($studenttype->active == 0) {
            $studenttype->active = 1;
            $studenttype->save();
            return response(['status' => 'active']);
        }
    }
    public function student_logout($id)
    {
        DB::beginTransaction();
        $user = User::where("id", $id)->first();
        $user->update([
            "device_id" => null,
            "device_token" => null,
            "api_token" => Hash::make(rand(0, 999999) . time()),
        ]);
        DB::commit();
        return response()->json(["status" => true]);
    }



    public function unverified_students()
    {
        $students = User::where('phone_verify', 0)->whereIn("is_student", [1, 2])->where("is_visitor", 0)->whereNotNull("name")->get();
        return view('dashboard.students.unverified_students', compact('students'));
    }

    public function verify_all_students()
    {
        $students = User::where('phone_verify', 0)->whereIn("is_student", [1, 2])->where("is_visitor", 0)->whereNotNull("name")->get();
        foreach ($students as $student) {
            $student->update([
                "phone_verify" => 1
            ]);
        }
        return redirect()->route('unverified_students');
    }
}
