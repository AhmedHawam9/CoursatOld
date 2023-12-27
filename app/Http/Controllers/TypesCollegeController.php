<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use App\User;
use App\College;
use App\Division;
use App\Section;
use App\SubjectsCollege;
use App\TypesCollege;

use Validator;
use App\Doctor_Subcollege;

use App\University;

use Illuminate\Support\Facades\Auth;

use App\Tag;

class TypesCollegeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:typescolleges-create'])->only('addtypescollege');
        $this->middleware(['permission:typescolleges-read'])->only('typescolleges');
        $this->middleware(['permission:typescolleges-update'])->only('edittypescollege');
        $this->middleware(['permission:typescolleges-delete'])->only('deletetypescollege');
    }
    public function addtypescollege()
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
        $tags = Tag::all();
        return view('dashboard.addtypescollege')->with('colleges', College::all())->with('divisions', $divisions)->with('sections', $sections)->with('subcolleges', $subcolleges)->with('users', $users)->with('tags', $tags)
            ->with('universities', University::all());
    }
    public function storetypescollege(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            //     'intro' => 'required'
            //|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi'
        ], [
            'required.name_ar' => 'حقل الاسم مطلوب  ',
            'required.name_en' => 'حقل الاسم مطلوب  ',
            'required.image' => 'حقل الصوره مطلوب  ',
            'required.intro' => 'حقل الانترو مطلوب  ',
            'mimetypes' => 'هذا الحقل يقبل فيديو فقط',
            'mimes' =>  'هذا الحقل يقبل صوره فقط'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $typescollege = new TypesCollege;
        $typescollege->description = $request->description;
        $typescollege->division_id = $request->division_id;
        $typescollege->section_id = $request->section_id;
        $typescollege->subjectscollege_id = $request->subjectscollege_id;
        $typescollege->name_ar = $request->name_ar;
        $typescollege->name_en = $request->name_en;
        if ($request->points) {
            $typescollege->points = $request->points;
        }

        if ($request->hasFile('intro')) {
            $intro = $request->intro;
            $intro->move('uploads', time() . '.' . $intro->getClientOriginalExtension());
            $typescollege->intro = time() . '.' . $intro->getClientOriginalExtension();
        }
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {

            $doctor = User::where('id', $request->doctor_id)->first();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $file = $image->getClientOriginalName();
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
                $typescollege->image = $fileName . '_' . time() . '.' . $fileExtension;
            } else {
                $typescollege->image = $doctor->image;
            }
            $typescollege->doctor_id = $request->doctor_id;
            $typescollege->college_id = $request->college_id;

            $typescollege->university_id = $request->university_id;
        } else if (Auth::user() && Auth::user()->is_student == 3) {
            $doctor = User::where('id', auth()->user()->id)->first();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $file = $image->getClientOriginalName();
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
                $typescollege->image = $fileName . '_' . time() . '.' . $fileExtension;
            } else {
                $typescollege->image = $doctor->image;
            }
            $center_id = $doctor->belongcenter2()->first()->id ?? null;
            if ($center_id) {
                $typescollege->center_id = $center_id;
            }
            $typescollege->doctor_id = auth()->user()->id;
            $typescollege->university_id = auth()->user()->university_id;
            $typescollege->college_id = auth()->user()->college_id;
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $doctor = User::where('id', $request->doctor_id)->first();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $file = $image->getClientOriginalName();
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
                $typescollege->image = $fileName . '_' . time() . '.' . $fileExtension;
            } else {
                $typescollege->image = $doctor->image;
            }
            $typescollege->doctor_id = $request->doctor_id;
            $typescollege->college_id = $request->college_id;
            $typescollege->university_id = $request->university_id;
            $typescollege->center_id = auth()->user()->id;
        }
        $typescollege->save();
        if ($request->tag_id) {
            $typescollege->tags()->attach($request->tag_id);
        }
        return response()->json(['success' => 'course uploaded']);
        // return redirect()->route('typescolleges');

    }
    public function typescolleges()
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $typescolleges = TypesCollege::orderBy('created_at', 'Desc')->get();
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
            $typescolleges =  TypesCollege::where('doctor_id', Auth::user()->id)->orderBy('created_at', 'Desc')->get();
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $typescolleges =  TypesCollege::where('center_id', Auth::user()->id)->orderBy('created_at', 'Desc')->get();
            $divisions = Division::all();
            $sections = Section::all();
            $subcolleges = SubjectsCollege::all();
            $users = User::where('id', Auth::user()->id)->first()->doctors;
        }
        return view('dashboard.typescolleges')->with('typescolleges', $typescolleges)->with('colleges', College::all())->with('divisions', $divisions)->with('sections', $sections)->with('subcolleges', $subcolleges)
            ->with('universities', University::all());
    }
    public function edittypescollege($id)
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $divisions = Division::all();
            $sections = Section::all();
            $users = User::where('is_student', '3')->get();
            $subcolleges = SubjectsCollege::all();
        } elseif (Auth::user() && Auth::user()->is_student == 3) {
            $dd =   \App\Doctor_Division::where('doctor_id', Auth::user()->id)->pluck('division_id')->toArray();
            $divisions = \App\Division::whereIn('id', $dd)->get();
            $ds =   \App\Doctor_Section::where('doctor_id', Auth::user()->id)->pluck('section_id')->toArray();
            $sections = \App\Section::whereIn('id', $ds)->get();
            $dg =    \App\Doctor_Subcollege::where('doctor_id', Auth::user()->id)->pluck('subcollege_id')->toArray();
            $subcolleges = \App\SubjectsCollege::whereIn('id', $dg)->get();
            $users = User::all();
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $divisions = Division::all();
            $sections = Section::all();
            $subcolleges = SubjectsCollege::all();
            $users = User::where('id', Auth::user()->id)->first()->doctors;
        }
        $tags = Tag::all();
        return view('dashboard.edittypescollege')->with('typescollege', TypesCollege::where('id', $id)->first())
            ->with('colleges', College::all())->with('divisions', $divisions)->with('sections', $sections)->with('subcolleges', $subcolleges)->with('users', $users)->with('universities', University::all())->with('tags', $tags);
    }
    public function updatetypescollege($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            //    'image' => 'mimes:jpeg,jpg,png,gif',
            ///    'intro' => 'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi'
        ], [
            'required.name_ar' => 'حقل الاسم مطلوب  ',
            'required.name_en' => 'حقل الاسم مطلوب  ',
            'required.image' => 'حقل الصوره مطلوب  ',
            'required.intro' => 'حقل الانترو مطلوب  ',
            'mimetypes' => 'هذا الحقل يقبل فيديو فقط',
            'mimes' =>  'هذا الحقل يقبل صوره فقط'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $typescollege = TypesCollege::where('id', $id)->first();
        $typescollege->description = $request->description;
        $typescollege->division_id = $request->division_id;
        $typescollege->section_id = $request->section_id;
        $typescollege->subjectscollege_id = $request->subjectscollege_id;
        $typescollege->name_ar = $request->name_ar;
        $typescollege->name_en = $request->name_en;
        if ($request->points) {
            $typescollege->points = $request->points;
        }
        if ($request->hasFile('image')) {
            if (public_path() . '/uploads/' . $typescollege->image) {
                $link = public_path() . '/uploads/' . $typescollege->image;
                File::delete($link);
            }

            /**$image = $request->image;
            $file = $image->getClientOriginalName();
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
            $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
            $typescollege->image = $fileName . '_' . time() . '.' . $fileExtension;*/

            $image = $request->image;
            $image->move('uploads', time() . '.' . $image->getClientOriginalExtension());
            $typescollege->image = time() . '.' . $image->getClientOriginalExtension();

        }
        if ($request->hasFile('intro')) {
            if (public_path() . '/uploads/' . $typescollege->intro) {
                $link = public_path() . '/uploads/' . $typescollege->intro;
                File::delete($link);
            }
            $intro = $request->intro;
            $intro->move('uploads', time() . '.' . $intro->getClientOriginalExtension());
            $typescollege->intro = time() . '.' . $intro->getClientOriginalExtension();
        }
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            $typescollege->doctor_id = $request->doctor_id;
            $typescollege->college_id = $request->college_id;
            $typescollege->university_id = $request->university_id;
        } else if (Auth::user() && Auth::user()->is_student == 3) {
            $typescollege->doctor_id = auth()->user()->id;
            $typescollege->university_id = auth()->user()->university_id;
            $typescollege->college_id = auth()->user()->college_id;
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $typescollege->doctor_id = $request->doctor_id;
            $typescollege->college_id = $request->college_id;
            $typescollege->center_id = auth()->user()->id;
            $typescollege->university_id = $request->university_id;
        }
        $typescollege->save();
        if ($request->tag_id) {
            $typescollege->tags()->sync($request->tag_id);
        }
        return response()->json(['success' => 'video uploaded']);
    }
    public function deletetypescollege($id)
    {
        $type =  TypesCollege::where('id', $id)->first();
        if ($type->lessons) {
            foreach ($type->lessons as $lesson) {
                if (public_path() . '/uploads/' . $lesson->intro) {
                    $link1 = public_path() . '/uploads/' . $lesson->intro;
                    File::delete($link1);
                }
                if ($lesson->videos) {
                    foreach ($lesson->videos as $video) {
                        if (public_path() . '/uploads/' . $video->url) {
                            $link = public_path() . '/uploads/' . $video->url;
                            File::delete($link);
                        }
                    }
                }
            }
        }
        //
        $type->delete();
        return response()->json(['status' => true]);
    }
    public function gettypescollege($id)
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $typescolleges = TypesCollege::where('subjectscollege_id', $id)->get();
        } elseif (Auth::user() && Auth::user()->is_student == 3) {
            $typescolleges = TypesCollege::where('subjectscollege_id', $id)->where('doctor_id', auth()->user()->id)->get();
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $typescolleges = TypesCollege::where('subjectscollege_id', $id)->where('center_id', auth()->user()->id)->get();
        }
        $text = "";
        $text .= '<option value="0"   disabled="disabled"  selected="selected">ادخل الوحده</option>';
        foreach ($typescolleges as $typescollege) {
            $text .= '<option value="' . $typescollege->id . '">' . $typescollege->name_ar . '</option>';
        }
        return response()->json($text);
    }
    public function gettypescollege2($id)
    {
        if (
            Auth::user() && Auth::user()->is_student == 5 &&
            Auth::user()->category_id == 2
        ) {
            $typescolleges = TypesCollege::where('subjectscollege_id', $id)->where('center_id', auth()->user()->id)
                ->get();
            $doctors_ids = Doctor_Subcollege::where('subcollege_id', $id)->get()
                ->pluck('doctor_id')->toArray();
            $doctors1 = User::whereIn('id', $doctors_ids)->get();
            $doctors2 = User::where('id', auth()->user()->id)->first()->doctors;
            $users = $doctors1->intersect($doctors2);
        } else {
            $typescolleges = TypesCollege::where('subjectscollege_id', $id)
                ->get();
            $users = User::where('is_student', 3)
                ->where('subjectscollege_id', $id)
                ->get();
        }
        $text = "";
        $text .= '<option value="0" selected="selected"  disabled="disabled">ادخل الكورس</option>';
        foreach ($typescolleges as $typescollege) {
            $text .= '<option value="' . $typescollege->id . '">' . $typescollege->name_ar . '</option>';
        }
        $text2 = "";
        $text2 .= '<option value="0" selected="selected" disabled="disabled">ادخل الدكتور</option>';
        foreach ($users as $user) {
            $text2 .= '<option value="' . $user->id . '">' . $user->name . '</option>';
        }
        return response()->json([$text, $text2]);
    }
    public function getdoctypescollege($id)
    {
        $typescolleges = TypesCollege::where('subjectscollege_id', $id)->where('doctor_id', auth()->user()->id)->get();
        $text = "";
        $text .= '<option value="0"  selected="selected" disabled="disabled">ادخل كورس</option>';
        foreach ($typescolleges as $typescollege) {
            $text .= '<option value="' . $typescollege->id . '">' . $typescollege->name_ar . '</option>';
        }
        return response()->json($text);
    }
    public function activetypecollege($id)
    {
        $type = TypesCollege::where('id', $id)->first();
        if ($type->active == 1) {
            $type->active = 0;
            $type->save();
            return response(['status' => 'deactive']);
        } else if ($type->active == 0) {
            $type->active = 1;
            $type->save();
            return response(['status' => 'active']);
        }
    }
    public function getdoctorscollege($id)
    {
        if (auth()->user() && auth()->user()->isAdmin == 'admin') {
            $doctor = TypesCollege::where('id', $id)->first()->doctor;
        } elseif (Auth::user() && Auth::user()->is_student == 3) {
            $doctor = TypesCollege::where('id', $id)->first()->doctor;
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 2) {
            $doctor = TypesCollege::where('id', $id)->first()->doctor;
        }
        $text = '';
        $text .= '<option value="' . $doctor->id . '" selected="selected">' . $doctor->name . '</option>';
        return response()->json(['data' => $text]);
    }



}//End of controller
