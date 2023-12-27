<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TypecollegeJoin;
use App\Student_Typecollege;
use Illuminate\Support\Facades\Auth;
use App\TypesCollege;
use App\User;
use App\Type;

class MainPageController extends Controller
{
    public function main_page_basic()
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $teachers = count(User::where("is_student", 2)->get());
            $types = count(Type::get());
            $students = count(User::where("is_student", 1)->where("year_id", "!=", null)->get());
            $user_types = User::withCount("types")->orderByDesc('types_count')
                ->take('5')->get();
            $types_users = Type::withCount("students")->orderByDesc('students_count')
                ->take('5')->get();
            $students_type = User::withCount("stutypes")->where("name", "!=", null)->orderByDesc('stutypes_count')
                ->take('5')->get();
            $teachers_names = [];
            $types_numbers = [];
            foreach ($user_types as $user_type) {
                $teachers_names[] = $user_type->name;
                $types_numbers[] = $user_type->types_count;
            }
            $teachers2_names = [];
            $types2_numbers = [];
            foreach ($user_types as $user_type) {
                $teachers2_names[] = $user_type->name;
                $types2_numbers[] = $user_type->type_sell_number;
            }

            $types1_names = [];
            $types1_numbers = [];
            foreach ($types_users as $types_user) {
                $types1_names[] = $types_user->name_ar;
                $types1_numbers[] = $types_user->students_count;
            }
            $students1_names = [];
            $students1_numbers = [];
            foreach ($students_type as $student) {
                $students1_names[] = $student->name;
                $students1_numbers[] = $student->stutypes_count;
            }
        } elseif (Auth::user() && Auth::user()->is_student == config('project_types.auth_user_is_student.doctor')) {
            // $teachers = 0;
            // $types = count(Type::where("doctor_id", auth()->id())->get());
            return view('dashboard.mainpage.empty_main');
        } elseif (Auth::user() && Auth::user()->is_student == config('project_types.auth_user_is_student.center') && Auth::user()->category_id == 2) {
            return view('dashboard.mainpage.empty_main');
        }
        return view('dashboard.mainpage.basic', compact(
            "teachers",
            "types",
            "students",
            "teachers_names",
            "types_numbers",
            "teachers2_names",
            "types2_numbers",
            "types1_names",
            "types1_numbers",
            "students1_names",
            "students1_numbers"
        ));
    }
    public function main_page_college()
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $teachers = count(User::where("is_student", 3)->get());
            $types = count(TypesCollege::get());
            $students = count(User::where("is_student", 1)->where("university_id", "!=", null)->get());
            $user_types = User::withCount("typescollege")->orderByDesc('typescollege_count')->take('5')->get();
            $types_users = TypesCollege::withCount("studentscollege")->orderByDesc('studentscollege_count')->take('5')->get();
            $students_type = User::withCount("stutypescollege")->where("name", "!=", null)->orderByDesc('stutypescollege_count')->take('5')->get();
            $teachers_names = [];
            $types_numbers = [];
            foreach ($user_types as $user_type) {
                $teachers_names[] = $user_type->name;
                $types_numbers[] = $user_type->typescollege_count;
            }
            $teachers2_names = [];
            $types2_numbers = [];
            foreach ($user_types as $user_type) {
                $teachers2_names[] = $user_type->name;
                $types2_numbers[] = $user_type->typecollege_sell_number;
            }

            $types1_names = [];
            $types1_numbers = [];
            foreach ($types_users as $types_user) {
                $types1_names[] = $types_user->name_ar;
                $types1_numbers[] = $types_user->studentscollege_count;
            }
            $students1_names = [];
            $students1_numbers = [];
            foreach ($students_type as $student) {
                $students1_names[] = $student->name;
                $students1_numbers[] = $student->stutypescollege_count;
            }
            $joins = TypecollegeJoin::where("status", 0)->get();
        } elseif (Auth::user() && Auth::user()->is_student == config('project_types.auth_user_is_student.doctor')) {
            $teachers = 0;
            $types = count(TypesCollege::where("doctor_id", auth()->id())->get());
            $students = auth()->user()->centerstudents;
            $user_types = User::withCount("typescollege")->orderByDesc('typescollege_count')
                ->take('5')->get();
            $types_users = TypesCollege::withCount("studentscollege")->where("doctor_id", auth()->id())->orderByDesc('studentscollege_count')
                ->take('5')->get();
            $students_type = User::withCount("stutypescollege")->where("name", "!=", null)
                ->orderByDesc('stutypescollege_count')
                ->take('5')->get();
            $teachers_names = [];
            $types_numbers = [];
            foreach ($user_types as $user_type) {
                $teachers_names[] = $user_type->name;
                $types_numbers[] = $user_type->typescollege_count;
            }
            $teachers2_names = [];
            $types2_numbers = [];
            foreach ($user_types as $user_type) {
                $teachers2_names[] = $user_type->name;
                $types2_numbers[] = $user_type->typecollege_sell_number;
            }

            $types1_names = [];
            $types1_numbers = [];
            foreach ($types_users as $types_user) {
                $types1_names[] = $types_user->name_ar;
                $types1_numbers[] = $types_user->studentscollege_count;
            }
            $students1_names = [];
            $students1_numbers = [];
            foreach ($students_type as $student) {
                $students1_names[] = $student->name;
                $students1_numbers[] = count($student->stutypescollege()->where("doctor_id", auth()->id())->get());
            }
            $typescolleges =  TypesCollege::where('doctor_id', Auth::user()->id)
                ->orderBy('created_at', 'Desc')->get();
            $joins = TypecollegeJoin::where("typecollege_id", $typescolleges->pluck("id")->toArray())
                ->where("status", 0)->get();
        } elseif (Auth::user() && Auth::user()->is_student == config('project_types.auth_user_is_student.center') && Auth::user()->category_id == 2) {
            $teachers = count(auth()->user()->doctors);
            $types = count(TypesCollege::where("center_id", auth()->id())->get());
            $students = auth()->user()->centerstudents;
            $user_types = User::withCount("typescollege")->whereIn('id', auth()->user()->doctors->pluck("id")->toArray())
                ->orderByDesc('typescollege_count')
                ->take('5')->get();
            $types_users = TypesCollege::withCount("studentscollege")->where("center_id", auth()->id())->orderByDesc('studentscollege_count')
                ->take('5')->get();
            $students_type = User::withCount("stutypescollege")->where("name", "!=", null)
                ->orderByDesc('stutypescollege_count')->whereIn('id', auth()->user()->doctors->pluck("id")->toArray())
                ->take('5')->get();
            $teachers_names = [];
            $types_numbers = [];
            foreach ($user_types as $user_type) {
                $teachers_names[] = $user_type->name;
                $types_numbers[] = $user_type->typescollege_count;
            }
            $teachers2_names = [];
            $types2_numbers = [];
            foreach ($user_types as $user_type) {
                $teachers2_names[] = $user_type->name;
                $types2_numbers[] = $user_type->typecollege_sell_number;
            }

            $types1_names = [];
            $types1_numbers = [];
            foreach ($types_users as $types_user) {
                $types1_names[] = $types_user->name_ar;
                $types1_numbers[] = $types_user->studentscollege_count;
            }
            $students1_names = [];
            $students1_numbers = [];
            foreach ($students_type as $student) {
                $students1_names[] = $student->name;
                $students1_numbers[] = count($student->stutypescollege()->where("center_id", auth()->id())->get());
            }
            $typescolleges =  TypesCollege::where('center_id', Auth::user()->id)
                ->orderBy('created_at', 'Desc')->get();
            $joins = TypecollegeJoin::where("typecollege_id", $typescolleges->pluck("id")->toArray())
                ->where("status", 0)->get();
        }

        return view('dashboard.mainpage.college', compact(
            "teachers",
            "types",
            "students",
            "teachers_names",
            "types_numbers",
            "teachers2_names",
            "types2_numbers",
            "types1_names",
            "types1_numbers",
            "students1_names",
            "students1_numbers",
            "joins"
        ));
    }
    public function main()
    {
        return view('dashboard.mainpage.index');
    }
}
