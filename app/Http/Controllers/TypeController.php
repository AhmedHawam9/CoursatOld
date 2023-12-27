<?php

namespace App\Http\Controllers;

use App\DataTables\Admin\SystemContent\TypesDataTable;
use App\Stage;
use App\Subject;
use App\Tag;
use App\Type;
use App\User;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Validator;

class TypeController extends Controller
{
    protected $view = "dashboard.types-basic_courses.";

    public function __construct()
    {
        $this->middleware(['permission:types-create'])->only('addtype');
        $this->middleware(['permission:types-read'])->only('types');
        $this->middleware(['permission:types-update'])->only('edittype');
        $this->middleware(['permission:types-delete'])->only('deletetype');
    }
    public function types(TypesDataTable $dataTable)
    {
        return $dataTable->render($this->view . 'index', [
            'stages' => Stage::all(),
        ]);
        /**if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            $types = Type::orderBy('created_at', 'desc')->get();
        } else if (Auth::user() && Auth::user()->is_student == 2) {
            $types = Type::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->get();
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id = 1) {
            $types = Type::orderBy('created_at', 'desc')->where('center_id', Auth::user()->id)->get();
        }
        return view('dashboard.types', compact('types'))
            ->with('stages', Stage::all());*/
    }
    public function deletetype($id)
    {
        $type = Type::where('id', $id)->first();
        if ($type->subtypes) {
            foreach ($type->subtypes as $subtype) {
                if (public_path() . '/uploads/' . $subtype->intro) {
                    $link1 = public_path() . '/uploads/' . $subtype->intro;
                    File::delete($link1);
                }
                if ($subtype->videos) {
                    foreach ($subtype->videos as $video) {
                        if (public_path() . '/uploads/' . $video->url) {
                            $link = public_path() . '/uploads/' . $video->url;
                            File::delete($link);
                        }
                    }
                }
            }
        }

        $type->delete();
        return response()->json(['status' => true]);
    }
    public function addtype()
    {
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            $users = User::where('is_student', 2)->get();
            $subjects = Subject::all();
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1) {
            $users = User::where('center_id', Auth::user()->id)->where('is_student', 2)->get();
            $subjects = Subject::all();
        } else if (Auth::user() && Auth::user()->is_student == 2) {
            $users = "";
            $subjects = User::where('id', auth()->user()->id)->first()->subjects;
        }
        $tags = Tag::all();
        return view('dashboard.addtype')->with('years', Year::all())->with('subjects', $subjects)
            ->with('stages', Stage::all())->with('users', $users)->with('tags', $tags);
    }
    public function storetype(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'years_id' => 'required',
            'subjects_id' => 'required',
        ], [
            'required' => 'هذا الحقل مطلوب',
            'mimetypes' => 'هذا الحقل يقبل فيديو فقط',
            'mimes' => 'هذا الحقل يقبل صوره فقط',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            $user = User::where('id', $request->user_id)->first();
            $type = new Type;
            $type->name_ar = $request->name_ar;
            $type->name_en = $request->name_en;
            $type->years_id = $request->years_id;
            $type->description = $request->description;
            $type->subjects_id = $request->subjects_id;
            $type->stage_id = $request->stage_id;
            if ($request->points) {
                $type->points = $request->points;
            }
            if ($request->hasFile('intro')) {
                $intro = $request->intro;
                $intro->move('uploads', time() . '.' . $intro->getClientOriginalExtension());
                $type->intro = time() . '.' . $intro->getClientOriginalExtension();
            }
            if ($request->hasFile('image')) {
                $image = $request->image;
                $file = $image->getClientOriginalName();
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
                $type->image = $fileName . '_' . time() . '.' . $fileExtension;
            } else {
                $type->image = $user->image;
            }
            $type->user_id = $request->user_id;
            $type->save();
            if ($request->tag_id) {
                $type->tags()->attach($request->tag_id);
            }
        } elseif (Auth::user() && Auth::user()->is_student == 2) {
            $user = User::where('id', auth()->id())->first();
            $year = Year::where('id', $request->years_id)->first();
            $type = new Type;
            $type->name_ar = $request->name_ar;
            $type->name_en = $request->name_en;
            $type->years_id = $request->years_id;
            $type->description = $request->description;
            $type->subjects_id = $request->subjects_id;
            $type->stage_id = $year->stage_id;
            $center_id = $user->belongcenter1()->first()->id ?? null;
            if ($center_id) {
                $type->center_id = $center_id;
            }
            if ($request->points) {
                $type->points = $request->points;
            }
            if ($request->hasFile('intro')) {
                $intro = $request->intro;
                $intro->move('uploads', time() . '.' . $intro->getClientOriginalExtension());
                $type->intro = time() . '.' . $intro->getClientOriginalExtension();
            }
            if ($request->hasFile('image')) {
                $image = $request->image;

                $file = $image->getClientOriginalName();
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
                $type->image = $fileName . '_' . time() . '.' . $fileExtension;
            } else {
                $type->image = $user->image;
            }
            $type->user_id = auth()->user()->id;
            $type->save();
            if ($request->tag_id) {
                $type->tags()->attach($request->tag_id);
            }
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1) {
            $user = User::where('id', $request->user_id)->first();
            $year = Year::where('id', $request->years_id)->first();
            $type = new Type;
            $type->name_ar = $request->name_ar;
            $type->name_en = $request->name_en;
            $type->years_id = $request->years_id;
            $type->description = $request->description;
            $type->subjects_id = $request->subjects_id;
            $type->stage_id = $year->stage_id;
            if ($request->points) {
                $type->points = $request->points;
            }
            if ($request->hasFile('image')) {

                $image = $request->image;

                $file = $image->getClientOriginalName();
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
                $type->image = $fileName . '_' . time() . '.' . $fileExtension;
            } else {
                $type->image = $user->image;
            }
            if ($request->hasFile('intro')) {
                $intro = $request->intro;
                $intro->move('uploads', time() . '.' . $intro->getClientOriginalExtension());
                $type->intro = time() . '.' . $intro->getClientOriginalExtension();
            }
            $type->center_id = auth()->user()->id;
            $type->user_id = $request->user_id;
            $type->save();
            if ($request->tag_id) {
                $type->tags()->attach($request->tag_id);
            }
        }
        return response()->json(['success' => 'course uploaded']);
    }
    public function edittype($id)
    {
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            $users = User::where('is_student', 2)->get();
            $subjects = Subject::all();
        } else if (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1) {
            $users = User::where('center_id', Auth::user()->id)->where('is_student', 2)->get();
            $subjects = Subject::all();
        } else if (Auth::user() && Auth::user()->is_student == 2) {
            $users = "";
            $subjects = User::where('id', auth()->user()->id)->first()->subjects;
        }
        $tags = Tag::all();
        return view('dashboard.edittype')->with('years', Year::all())->with('subjects', $subjects)->with('type', Type::where('id', $id)->first())->with('stages', Stage::all())->with('users', $users)->with('tags', $tags);
    }
    public function updatetype($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',

            //    'image' => '|mimes:jpeg,jpg,png,gif',
            //     'intro' => 'required|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi'
        ], [
            'required' => 'هذا الحقل مطلوب',
            'mimetypes' => 'هذا الحقل يقبل فيديو فقط',
            'mimes' => 'هذا الحقل يقبل صوره فقط',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $type = Type::where('id', $id)->first();
        if ($request->tag_id) {
            $type->tags()->sync($request->tag_id);
        }
        $type->description = $request->description;
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            if ($request->hasFile('intro')) {
                if ($link = public_path() . '/uploads/' . $type->intro) {
                    $link = public_path() . '/uploads/' . $type->intro;
                    File::delete($link);
                }
                $intro = $request->intro;
                $intro->move('uploads', time() . '.' . $intro->getClientOriginalExtension());
                $type->intro = time() . '.' . $intro->getClientOriginalExtension();
            }
            if ($request->hasFile('image')) {
                if ($link = public_path() . '/uploads/' . $type->image) {
                    $link = public_path() . '/uploads/' . $type->image;
                    File::delete($link);
                }
                $image = $request->image;

                $file = $image->getClientOriginalName();
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                $image->move('uploads', $fileName . '_' . time() . '.' . $fileExtension);
                $type->image = $fileName . '_' . time() . '.' . $fileExtension;
            }
            $type->name_ar = $request->name_ar;
            $type->name_en = $request->name_en;
            $type->years_id = $request->years_id;
            $type->subjects_id = $request->subjects_id;
            $type->stage_id = $request->stage_id;
            if ($request->points) {
                $type->points = $request->points;
            }
            $type->user_id = $request->user_id;
            $type->save();
        } elseif (Auth::user() && Auth::user()->is_student == 2) {
            $year = Year::where('id', $request->years_id)->first();

            $type->name_ar = $request->name_ar;
            $type->name_en = $request->name_en;
            $type->years_id = $request->years_id;
            $type->subjects_id = $request->subjects_id;
            $type->stage_id = $year->stage_id;
            if ($request->points) {
                $type->points = $request->points;
            }
            $type->user_id = auth()->user()->id;
            $type->save();
        } elseif (Auth::user() && Auth::user()->is_student == 5 && Auth::user()->category_id == 1) {
            $year = Year::where('id', $request->years_id)->first();

            $type->name_ar = $request->name_ar;
            $type->name_en = $request->name_en;
            $type->years_id = $request->years_id;
            $type->subjects_id = $request->subjects_id;
            $type->stage_id = $year->stage_id;
            if ($request->points) {
                $type->points = $request->points;
            }
            $type->center_id = auth()->user()->id;
            $type->user_id = $request->user_id;
            $type->save();
        }
        return response()->json(['success' => 'course uploaded']);
    }
    public function gettype($id, $value)
    {

        $types = Type::where('subjects_id', $value)->where('user_id', $id)->get();
        $text1 = "";
        $text1 .= '<option value="0"  selected="selected" disabled>اختر دوره تعلميه  </option>';
        foreach ($types as $type) {
            $text1 .= '<option value="' . $type->id . '">' . $type->name_ar . '</option>';
        }

        return response()->json([$text1]);
    }


    public function activetype($id)
    {
        $type = Type::where('id', $id)->first();
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



}
