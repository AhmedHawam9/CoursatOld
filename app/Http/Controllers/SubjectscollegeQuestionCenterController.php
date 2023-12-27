<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\State;
use FFMpeg;
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
use App\Student_Course;
use Illuminate\Support\Facades\Hash;
use App\GroupType;
use App\SubjectQuestion;
use App\SubjectquestionAnswer;
use App\SubjectPart;
use App\SubjectscollegeQuestion;
use App\SubjectscollegequestionAnswer;
use App\SubjectscollegePart;

class SubjectscollegeQuestionCenterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:subjectscollegequestionscenter-create'])->only('addsubjectscollegequestionscenter');
        $this->middleware(['permission:subjectscollegequestionscenter-read'])->only('subjectscollegequestionscenter');
        $this->middleware(['permission:subjectscollegequestionscenter-update'])->only('editsubjectscollegequestionscenter');
        $this->middleware(['permission:subjectscollegequestionscenter-delete'])->only('deletesubjectscollegequestionscenter');
    }
    public function addsubjectscollegequestionscenter()
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $divisions = Division::all();
            $sections = Section::all();
            $subcolleges = SubjectsCollege::all();
            $users = User::where('is_student', '3')->get();
        } elseif (Auth::user() && Auth::user()->is_student == 3) {
            $dd =   \App\Doctor_Division::where('doctor_id', Auth::user()->id)->pluck('division_id')->toArray();
            $divisions = \App\Division::whereIn('id', $dd)->get();
            $ds =   \App\Doctor_Section::where('doctor_id', Auth::user()->id)->pluck('section_id')->toArray();
            $sections = \App\Section::whereIn('id', $ds)->get();
            $dg =    \App\Doctor_Subcollege::where('doctor_id', Auth::user()->id)->pluck('subcollege_id')->toArray();
            $subcolleges = \App\SubjectsCollege::whereIn('id', $dg)->get();
            $users = "";
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $divisions = Division::all();
            $sections = Section::all();
            $subcolleges = SubjectsCollege::all();
            $users = User::where('id', Auth::user()->id)->first()->doctors;
        }

        return view("dashboard.subjectscollegequestions.subjectscollegequestionscenter.create")
            ->with('colleges', College::all())->with('divisions', $divisions)->with('sections', $sections)->with('subcolleges', $subcolleges)->with('users', $users)
            ->with('universities', University::all());
    }
    public function storesubjectscollegequestionscenter(Request $request)
    {
        // dd($request->all());
        $part = new SubjectscollegePart;

        $part->division_id  = $request->division_id;
        $part->section_id = $request->section_id;
        $part->subjectscollege_id  = $request->subjectscollege_id;
        $part->name = $request->part;
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            $part->university_id = $request->university_id;
            $part->college_id = $request->college_id;
            $part->admin = 1;
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $part->university_id = $request->university_id;
            $part->college_id = $request->college_id;
            $part->doctor_id  = $request->doctor_id;
            $part->center_id  = auth()->id();
        } else if (Auth::user() && Auth::user()->is_student == 3) {
            $part->doctor_id  = auth()->id();
            $part->university_id = auth()->user()->university_id;
            $part->college_id = auth()->user()->college_id;
        }
        $part->save();
        foreach ($request->name as $key1 => $name) {
            $question = new SubjectscollegeQuestion;

            $part->division_id  = $request->division_id;
            $part->section_id = $request->section_id;
            $part->subjectscollege_id  = $request->subjectscollege_id;
            $question->name = $name;
            $question->subjectscollegepart_id  = $part->id;
            $question->score = $request->score[$key1];
            $question->question_level = $request->question_level[$key1];
            if (Auth::user() && Auth::user()->isAdmin == 'admin') {
                $part->university_id = $request->university_id;
                $part->college_id = $request->college_id;
                $question->public = 1;
            } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
                $question->doctor_id  = $request->doctor_id;
                $part->university_id = $request->university_id;
                $part->college_id = $request->college_id;
                $question->public = 0;
                $question->center_id  = auth()->id();
            } else if (Auth::user() && Auth::user()->is_student == 3) {
                $part->doctor_id  = auth()->id();
                $part->university_id = auth()->user()->university_id;
                $part->college_id = auth()->user()->college_id;
                $question->public = 0;
            }
            if ($request->video[$key1]) {
                $getID3 = new \getID3;
                $file = $getID3->analyze($request->file('video')[$key1]);

                $duration =  $file['playtime_seconds'];
                $question->video_sec = $duration;
                $url = $request->video[$key1];
                // $video->size_video= $request->file('video')->getSize()/1024;
                $url->move('uploads', time() . '.' . $url->getClientOriginalExtension());
                $question->video = time() . '.' . $url->getClientOriginalExtension();
            }
            if ($request->question_image[$key1]) {
                $image = $request->question_image[$key1];
                $image->move('uploads', time() . $image->getClientOriginalName());
                $question->question_image = time() . $image->getClientOriginalName();
            }
            if ($request->image[$key1]) {
                $image = $request->image[$key1];
                $image->move('uploads', time() . $image->getClientOriginalName());
                $question->image = time() . $image->getClientOriginalName();
            }
            $question->notes = $request->notes[$key1];
            $question->save();
            foreach ($request->answer[$key1] as $key => $value) {
                $questionanswer1 = new SubjectscollegequestionAnswer;
                $questionanswer1->name = $value;
                if (array_key_exists($key, $request->correct[$key1])) {

                    $questionanswer1->correct = $request->correct[$key1][$key];
                }
                $questionanswer1->subjectscollegequestion_id  = $question->id;
                $questionanswer1->save();
            }
        }
        return response()->json(['success' => 'question uploaded']);
    }
    public function subjectscollegequestionscenter()
    {
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            $questions = SubjectscollegePart::where('admin', 1)->get();
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $questions = SubjectscollegePart::where('center_id', Auth::user()->id)->get();
        } else if (Auth::user() && Auth::user()->is_student == 3) {
            $questions = SubjectscollegePart::where('doctor_id', auth()->user()->id)->get();
        }
        $typescolleges = TypesCollege::orderBy('created_at', 'Desc')->get();
        $divisions = Division::all();
        $sections = Section::all();
        $subcolleges = SubjectsCollege::all();
        return view("dashboard.subjectscollegequestions.subjectscollegequestionscenter.index")->with("questions", $questions)->with('colleges', College::all())->with('divisions', $divisions)->with('sections', $sections)->with('subcolleges', $subcolleges)
            ->with('universities', University::all());
    }
    public function editsubjectscollegequestionscenter($id)
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $divisions = Division::all();
            $sections = Section::all();
            $subcolleges = SubjectsCollege::all();
            $users = User::where('is_student', '3')->get();
            $part = SubjectscollegePart::where('id', $id)->first();
        } elseif (Auth::user() && Auth::user()->is_student == 3) {
            $dd =   \App\Doctor_Division::where('doctor_id', Auth::user()->id)->pluck('division_id')->toArray();
            $divisions = \App\Division::whereIn('id', $dd)->get();
            $ds =   \App\Doctor_Section::where('doctor_id', Auth::user()->id)->pluck('section_id')->toArray();
            $sections = \App\Section::whereIn('id', $ds)->get();
            $dg =    \App\Doctor_Subcollege::where('doctor_id', Auth::user()->id)->pluck('subcollege_id')->toArray();
            $subcolleges = \App\SubjectsCollege::whereIn('id', $dg)->get();
            $users = "";
            $part = SubjectscollegePart::where('id', $id)->first();
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $divisions = Division::all();
            $sections = Section::all();
            $subcolleges = SubjectsCollege::all();
            $users = User::where('id', Auth::user()->id)->first()->doctors;
            $part = SubjectscollegePart::where('id', $id)->first();
        }

        return view("dashboard.subjectscollegequestions.subjectscollegequestionscenter.edit",[
            'part' => $part,
            'colleges' => College::all(),
            'divisions' => $divisions,
            'sections' => $sections,
            'subcolleges' => $subcolleges,
            'users' => $users,
            'universities' => University::all()
        ]);//->with('part', $part)->with('colleges', College::all())->with('divisions', $divisions)->with('sections', $sections)->with('subcolleges', $subcolleges)->with('users', $users)->with('universities', University::all());
    }
    public function updatesubjectscollegequestionscenter(Request $request, $id)
    {

        // dd($request->all());
        $part =  SubjectscollegePart::where('id', $id)->first();


        $part->division_id  = $request->division_id;
        $part->section_id = $request->section_id;
        $part->subjectscollege_id  = $request->subjectscollege_id;
        $part->name = $request->part;
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            $part->university_id = $request->university_id;
            $part->college_id = $request->college_id;
            $part->admin = 1;
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $part->university_id = $request->university_id;
            $part->college_id = $request->college_id;
            $part->doctor_id  = $request->doctor_id;
            $part->center_id  = auth()->id();
        } else if (Auth::user() && Auth::user()->is_student == 3) {
            $part->university_id = auth()->user()->university_id;
            $part->college_id = auth()->user()->college_id;
            $part->doctor_id  = auth()->id();
        }
        $part->save();
        if ($request->name) {
            SubjectscollegeQuestion::where('subjectscollegepart_id', $id)->delete();
            foreach ($request->name as $key1 => $name) {
                $question = new SubjectscollegeQuestion;

                $part->division_id  = $request->division_id;
                $part->section_id = $request->section_id;
                $part->subjectscollege_id  = $request->subjectscollege_id;
                $question->name = $name;
                $question->subjectscollegepart_id  = $part->id;
                $question->score = $request->score[$key1];
                $question->question_level = $request->question_level[$key1];
                if (Auth::user() && Auth::user()->isAdmin == 'admin') {
                    $part->university_id = $request->university_id;
                    $part->college_id = $request->college_id;
                    $question->public = 1;
                } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
                    $part->university_id = $request->university_id;
                    $part->college_id = $request->college_id;
                    $question->doctor_id  = $request->doctor_id;
                    $question->public = 0;
                    $question->center_id  = auth()->id();
                } else if (Auth::user() && Auth::user()->is_student == 3) {
                    $part->university_id = auth()->user()->university_id;
                    $part->college_id = auth()->user()->college_id;
                    $part->doctor_id  = auth()->id();
                    $question->public = 0;
                }

                if ($request->video[$key1]) {
                    $getID3 = new \getID3;
                    $file = $getID3->analyze($request->file('video')[$key1]);

                    $duration =  $file['playtime_seconds'];
                    $question->video_sec = $duration;
                    $url = $request->video[$key1];
                    // $video->size_video= $request->file('video')->getSize()/1024;
                    $url->move('uploads', time() . '.' . $url->getClientOriginalExtension());
                    $question->video = time() . '.' . $url->getClientOriginalExtension();
                }
                if ($request->question_image[$key1]) {
                    $image = $request->question_image[$key1];
                    $image->move('uploads', time() . $image->getClientOriginalName());
                    $question->question_image = time() . $image->getClientOriginalName();
                }
                if ($request->image[$key1]) {
                    $image = $request->image[$key1];
                    $image->move('uploads', time() . $image->getClientOriginalName());
                    $question->image = time() . $image->getClientOriginalName();
                }
                $question->notes = $request->notes[$key1];
                $question->save();
                foreach ($request->answer[$key1] as $key => $value) {
                    $questionanswer1 = new SubjectscollegequestionAnswer;
                    $questionanswer1->name = $value;
                    if (array_key_exists($key, $request->correct[$key1])) {

                        $questionanswer1->correct = $request->correct[$key1][$key];
                    }
                    $questionanswer1->subjectscollegequestion_id  = $question->id;
                    $questionanswer1->save();
                }
            }
        }
        return response()->json(['success' => 'question uploaded']);
    }
    public function deletesubjectscollegequestionscenter($id)
    {
        $part =  SubjectscollegePart::where('id', $id)->first();
        /*  if(public_path() . '/uploads/' . $question->video){
         $link1 = public_path() . '/uploads/' . $question->video;
                File::delete($link1);}
    if(public_path() . '/uploads/' . $question->image){
         $link1 = public_path() . '/uploads/' . $question->image;
                File::delete($link1);}*/
        $part->delete();
        return response()->json(['status' => true]);
    }
    public function filtersubjectscollegequestion(Request $request)
    {
        if ($request->university_id && $request->college_id && $request->division_id && $request->section_id && $request->subjectscollege_id) {
            $questions = SubjectscollegePart::where('admin', 1)->where("subjectscollege_id", $request->subjectscollege_id)->get();
        } elseif ($request->university_id && $request->college_id && $request->division_id && $request->section_id) {
            $questions = SubjectscollegePart::where('admin', 1)->where("section_id", $request->section_id)->get();
        } elseif ($request->university_id && $request->college_id && $request->division_id) {
            $questions = SubjectscollegePart::where('admin', 1)->where("division_id", $request->division_id)->get();
        } elseif ($request->university_id && $request->college_id) {
            $questions = SubjectscollegePart::where('admin', 1)->where("college_id", $request->college_id)->get();
        } elseif ($request->university_id) {
            $questions = SubjectscollegePart::where('admin', 1)->where("university_id", $request->university_id)->get();
        } else {
            $questions = SubjectscollegePart::where('admin', 1)->get();
        }
        $text = '';
        foreach ($questions as $question) {
            $text .= '<tr id="type' . $question->id . '">
						  <td class="text-center">' . $question->id . '</td>
                    <td scope="row" class="text-center">' . $question->name . '</td>
                    <td scope="row" class="text-center">
                   ' . $question->subjectscollege->name_ar . '</td>
                   <td scope="row" class="text-center">
                   ' . $question->section->name_ar . '</td>
                <td scope="row" class="text-center">
                   ' . $question->division->name_ar . '</td>
                    <td class="text-center">';
            if ($question->college) {
                $text .= $question->college->name_ar;;
                $text .= '</td>
                          <td class="text-center">';
                if ($question->university)
                    $text .= $question->university->name_ar;
            }
            $text .= '</td>
                        <td class="text-center">
                          <a href="' . route('editsubjectscollegequestionscenter', $question->id) . '" > <img src="' . asset("images/pen.svg") . '" id="pen"
                         style="cursor: pointer"></a>';
            if (auth()->user()->hasPermission("subjectscollegequestionscenter-delete")) {
                $text .= '<img src="' . asset("images/trash.svg") . '" id="trash" onclick="deletetype(' . $question->id . ')" style="cursor:pointer;">';
            }
            $text .= '</td>
                                        </tr>';
        }
        return response()->json(['status' => true, 'data' => $text]);
    }
}
