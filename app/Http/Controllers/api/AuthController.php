<?php

namespace App\Http\Controllers\api;

use App\Course;
use App\Course_Rate;
use App\General;
use App\Http\Controllers\Controller;
use App\Http\Resources\CenterResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\HomeCategory;
use App\Http\Resources\LecturerResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\LevelResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\RateResource;
use App\Http\Resources\ResourceUser;
use App\Http\Resources\StateResource;
use App\Http\Resources\SubResource;
use App\Http\Resources\SubtypeResource;
use App\Http\Resources\TypecollegeResource;
use App\Http\Resources\TypeResource;
use App\Http\Resources\UniversityResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VideogeneralResource;
use App\Lesson;
use App\Message;
use App\Center_Teacher;
use App\Center_Doctor;
use App\Http\Resources\AppResource;
use App\Http\Services\WhatsappService;
use App\Notification;
use App\Offer;
use App\Stage;
use App\Sub;
use App\Subject;
use App\SubjectsCollege;
use App\Subtype;
use App\Traits\ApiTrait;
use App\Type;
use App\Typecollege_Rate;
use App\TypesCollege;
use App\Type_Rate;
use App\User_Owner;
use App\University;
use App\User;
use App\VideosGeneral;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    use ApiTrait;

    protected $whatsappService;
    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }
    public function mynotifications()
    {
        $user = auth()->user();
        $nots = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        //     dd($nots);
        $alldata = [];
        if (count($nots) > 0) {
            foreach ($nots as $not) {
                $to = $user->device_token;
                Carbon::setLocale('ar');
                $time = Carbon::parse($not->created_at);
                $data = [
                    "to" => $to,
                    "data" => [
                        "title" => $not->title,
                        'body' => $not->text,
                        'time' => $time->diffForHumans(),
                    ],
                ];
                $dataString = $data;
                $alldata[] = ['data' => $dataString];
            }
        }
        return response()->json([
            'status' => true, 'message' => 'كل اشعارتك',
            'notifications' => $alldata,
        ]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'phone' => 'required|min:7|unique:users',
            //    'email'=>'unique:users',
            'password' => 'required|min:6',
        ], [
            'required' => 'مطلوب ادخال الحقل',
            'phone.unique' => 'ها الرقم تم تسجيله من قبل',
            'email.unique' => 'هذا الحقل موجود من قبل',
            'password.min' => 'حقل كلمه السر لا يجب ان يقل عن 6 احرف',
            'email' => 'اكب الايميل بشكل صحيح',
            'name.unique' => 'هذا الاسم موجود من قبل',
        ]);
        if ($validator->passes()) {
            if (isset($request->email)) {
                $validator = Validator::make($request->all(), [

                    'email' => 'required|unique:users|email',

                ], [
                    'email.unique' => 'هذا الايميل مستخدم ن قبل ',
                    'email.email' => 'هذا الايميل موجود من قبل',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message_ar' => $validator->messages()->first()]);
                }
            }
            $verification_code = rand(100000, 999999);
            $data["verification_code"] = $verification_code;
            $data = [];
            $data['api_token'] = Hash::make(rand(0, 999999) . time());
            $data['password'] = Hash::make($request->password);
            $data['name'] = $request->name;
            $data['phone'] = $request->phone;
            $data['state_id'] = $request->state_id;
            $data['city_id'] = $request->city_id;
            $data['is_student'] = 1;
            $data['email'] = $request->email;
            $data['code'] = $request->phone;
            $data['device_type'] = $request->device_type ?? null;
            $data["ios_version"] = $request->ios_version ?? null;
            $data["android_version"] = $request->android_version ?? null;
            $data['device_token'] = $request->device_token ?? null;
            $data['device_id'] = $request->device_id ?? null;

            $user = User::create($data);

            $this->whatsappService->send_whatsapp(
                $request->phone,
                "رمز التفعيل الخاص بك هو : " . $verification_code,
            );
            //         if($request->hasFile('image'))
            //         {
            //             $image = $request->image;
            //             $image->move('uploads' , $image->getClientOriginalName());
            //             $user->image = $request->image->getClientOriginalName();
            //         }

            if ($request->code) {
                $u = User::where('code', $request->code)->first();
                $u->centerstudents()->attach($user->id);
            }
            return response()->json([
                'status' => 'true',
                'message' => 'تم تسجيل المستخدم',
                'data' => new UserResource($user),

            ]);
        } else {
            $msg = $validator->messages()->first();

            return response()->json(['status' => "false", 'message_ar' => $msg], 401);
        }
    }
    public function phone_verify()
    {
        $user = auth()->user();
        $user->phone_verify = 1;
        $user->save();
        return response()->json(['status' => true, 'data' => new UserResource($user)]);
    }
    public function register_info(Request $request)
    {
        //         $validator = Validator::make($request->all(), [

        //             'state_id'=>'required|',
        //             'city_id'=>'required|',

        //     ]);
        if (auth()->guard('api')->check()) {
            $user = User::where('id', auth()->guard('api')->id())->first();
            if ($request->is_primary == 1) {
                $user->category_id = 1;

                $user->stage_id = $request->stage_id;
                $user->year_id = $request->year_id;
                $user->is_scientific = $request->is_scientific;
                $user->info_compelete = 1;
            } elseif ($request->is_primary == 0) {
                $user->category_id = 2;

                $user->university_id = $request->university_id;
                $user->college_id = $request->college_id;
                $user->division_id = $request->department_id;
                $user->section_id = $request->college_year_id;
                $user->info_compelete = 1;
            }
            $user->save();
        } else {

            $user = new User;
            $user->api_token = Hash::make(rand(0, 999999) . time());
            $user->is_visitor = 1;
            $user->save();
            if ($request->is_primary == 1) {
                $user->category_id = 1;

                $user->stage_id = $request->stage_id;
                $user->year_id = $request->year_id;
                $user->is_scientific = $request->is_scientific;
                $user->info_compelete = 1;
            } elseif ($request->is_primary == 0) {
                $user->category_id = 2;

                $user->university_id = $request->university_id;
                $user->college_id = $request->college_id;
                $user->division_id = $request->department_id;
                $user->section_id = $request->college_year_id;
                $user->info_compelete = 1;
            }
            $user->save();
        }
        return Response()->json([
            'status' => 'true',

            'data' => new UserResource($user),

        ]);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'name' => 'required',
            'phone' => 'required',
            'password' => 'min:6|required',
        ], [
            'phone.required' => 'حقل الهاتف مطلوب',
            'password.required' => 'حقل كمه السر مطلوب',
            'password.min' => 'كلمه السر لايجب ان تقل عن 6 احرف',
        ]);

        if ($validator->passes()) {
            //    $credentials = $request->only(['name', 'password']);
            // $user=User::where('name',$request->name)->first();

            $credentials = $request->only(['phone', 'password']);
            $user = User::where('phone', $request->phone)->first();
            if ($user == null) {
                return response()->json(['message_en' => 'The phone field is not right.', 'message_ar' => 'الهاتف غير صحيح  '], 401);
            }
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['message_en' => 'The password field is not right.', 'message_ar' => 'كلمه المرور غير صحيح'], 401);
            } else if ($user->active == 0) {
                return response()->json(['status' => false, 'message_ar' => 'هذا
                المستخدم ليس مفعل'], 401);
            } else {
                DB::beginTransaction();

                // comment 1 line for device id
                if ($user->device_id == null || $user->device_id == $request->device_id) {
                    if ($request->code) {
                        if ($center = User::where('code', $request->code)->first()) {
                            $center->centerstudents()->attach($user->id);
                        } else {
                            return response()->json(['status' => false, 'message' => 'لا يوجد سنتر هذا الكود']);
                        }
                    }
                    $data = [
                        "device_type" => $request->device_type ?? null,
                        "device_token" => $request->device_token,
                        "device_id" => $request->device_id,
                        "ios_version" => $request->ios_version ?? null,
                        "android_version" => $request->android_version ?? null,
                    ];
                    $user->update($data);

                    DB::commit();
                    return [
                        'status' => 'true',
                        'data' => new UserResource($user),

                    ];
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message_ar' => 'هذا المستخدم ليس له حق الدخول',
                    ], 401);
                }
            }
        } else {
            $msg = $validator->messages()->first();

            return response()->json([
                'status' => "false", 'message_ar' => $msg,
            ], 401);

            // return response()->json(['status'=>false,'message'=>$msg], 300);
        }
    }
    public function logoutnow()
    {
        $user = auth()->user();
        $user->device_token = null;
        $user->save();
        return response()->json(['status' => true]);
    }
    public function change_password(Request $request)
    {
        $user_id = \auth()->user()->id;
        $password = Hash::check($request->old_password, auth()->user()->password);
        if ($password == true) {
            $user = User::where('id', $user_id)->first();
        } else {
            return Response()->json([
                'status' => 'false',
                'data' => 'كلمه السر خطا',
            ], 401);
        }
        if ($user !== null) {
            $user = User::where('id', $user_id)->first();
            $user->password = Hash::make($request->new_password);
            $user->save();
            return Response()->json([
                'status' => 'true',
                'message' => 'تم تغيير كلمه السر بنجاح',
            ]);
        } else {
            return Response()->json([
                'status' => 'false',
                'data' => 'لا يوجد مستخدم',
            ]);
        }
    }
    public function education_stages()
    {
        return response()->json([
            'status' => true,
            'message' => 'كل الراحل',
            'primary' => LevelResource::collection(Stage::all()),
            'collectors' => UniversityResource::collection(University::all()),
        ]);
    }
    public function check_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ], [
            'required' => 'هذا الحق مطلب',
        ]);
        try {
            if ($validator->passes()) {
                $user = User::where('phone', $request->phone)->first();
                if ($user) {
                    $msg = ' العثور عى مستخدم بهذا السم';
                    return response()->json(['status' => true, 'message' => $msg]);
                } else {
                    $msg = 'لا يوج مستخد ذا الرقم';
                    return response()->json(['status' => false, 'message' => $msg]);
                }
            } else {
                $msg = $validator->messages()->first();
                return response()->json(['status' => false, 'message' => $msg]);
            }
        } catch (\Exception $e) {
            $msg = 'حدث خطا ما حاول التسجيل لاا';
            return response()->json(['status' => false, 'message' => $msg]);
        }
    }
    public function forget_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ], [
            'required' => 'هذا الحقل مطلوب',
        ]);
        try {
            if ($validator->passes()) {
                $user = User::where('phone', $request->phone)->first();
                if ($user) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return response()->json(['status' => true, 'message' => 'تم تغيير كم السر بنجح ']);
                } else {
                    $msg = 'ا يو مستخدم بهذ الرقم';
                    return response()->json(['status' => false, 'message' => $msg]);
                }
            } else {
                $msg = $validator->messages()->first();
                return response()->json(['status' => false, 'message' => $msg]);
            }
        } catch (\Exception $e) {
            $msg = 'حدث طا ما حول التسجل لاحا';
            return response()->json(['status' => false, 'message' => $msg]);
        }
    }
    public function addcenter(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code' => 'required',
            ],
            ['required' => 'ذا لحل مطلوب']
        );
        if ($validator->passes()) {
            if ($user = User::where('code', $request->code)->first()) {
                $user->centerstudents()->attach(auth()->id());
                //   $u = new User_Owner;
                //   $u->user_id = auth()->id();
                //   $u->owner_id = $user->id;
                //   $u->save();
                return response()->json([
                    'status' => true, 'message' => 'تم التاد م صحه الكود',
                    'data' => new CenterResource($user),
                ]);
            } else {
                return response()->json(['status' => false, 'message' => 'لا وجد مستخم هذا الكود']);
            }
        } else {
            return response()->json(['status' => false, 'message' => $validator->messages()->first()]);
        }
    }

    public function home_categories()
    {
        // $user_ids = auth()->user()->centerstudents->pluck("id");
        $users = auth()->user()->stdcenters;
        if (count($users) > 0) {

            $offers = Offer::whereIn("center_id", $users->pluck("id"))->get();
        } else {

            $offers = Offer::where('center_id', null)->where("category_id", auth()->user()->category_id)->get();
        }
        if (auth()->user()->category_id == 1) {
            if (count($users) > 0) {
                $result = [];
                $subject_ids = [];
                foreach ($users as $user) {
                    $subject_ids[] = $user->centertypes->pluck("subjects_id")->toArray();
                }
                $result = call_user_func_array("array_merge", $subject_ids);
                $subjects = Subject::where('years_id', auth()->user()->year_id)->whereIn('id', $result)->where("active", 1)->get();
            } else {
                $subjects = Subject::where('years_id', auth()->user()->year_id)->where("active", 1)->get();
            }

            /**
                //Convert the collection to an array
                $subjectsArray = $subjects->toArray();

                // Initialize the special subject
                $allSubjectTab = [
                    'id' => 0,
                    'title' => 'الكل',
                    'courses' => [],
                    'latest_courses' => [],
                    'lecturers' => [],
                ];

                // Add the special subject to the beginning of the array
                array_unshift($subjectsArray, $allSubjectTab);

                // If you need to convert it back to a collection, you can use collect()
                $subjects = collect($subjectsArray);

                 // Create a resource object for the special subject
                $allSubjectResource = new HomeCategory([
                    'id' => 0,
                    'title' => 'الكل',
                    'courses' => [],
                    'latest_courses' => [],
                    'lecturers' => [],
                ]);

                // Transform the collection using the resource
                $subjectsResource = HomeCategory::collection($subjects);

                // Prepend the special subject to the transformed collection
                $subjectsResource->prepend($allSubjectResource);

                // Return the modified collection
                return $subjectsResource;
             */
        } else if (auth()->user()->category_id == 2) {
            if (count($users) > 0) {
                $result = [];
                $subject_ids = [];
                foreach ($users as $user) {
                    $subject_ids[] = $user->centertypescollege->pluck("subjectscollege_id")->toArray();
                }

                $result = call_user_func_array("array_merge", $subject_ids);

                $subjects = SubjectsCollege::where('section_id', auth()->user()->section_id)->whereIn('id', $result)->where("active", 1)->get();
            } else {
                $subjects = SubjectsCollege::where('section_id', auth()->user()->section_id)->where("active", 1)->get();
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'محتوي هوم',
            'data' => HomeCategory::collection($subjects),
            'offers' => OfferResource::collection($offers), //
            'centeroffers' => OfferResource::collection($offers),

        ]);
    }
    public function course_classes(Request $request)
    {
        if (auth()->user()->category_id == 1) {
            $type1 = Type::where('id', $request->course_id)->first();
            if ($type1) {
                $type = Type::where('id', $request->course_id)->first()->subtypes()->where("active", 1)->orderBy('order_number', 'asc')->get();
                return response()->json([
                    'status' => true, 'message' => 'محتيت الكورس',
                    'data' => SubtypeResource::collection($type),
                ]);
            } else {
                return response()->json([
                    'status' => false, 'message' => ' لا يوجد كورس با ال id',
                ]);
            }
        } else if (auth()->user()->category_id == 2) {
            $type1 = TypesCollege::where('id', $request->course_id)->first();
            if ($type1) {
                $type = TypesCollege::where('id', $request->course_id)->first()->lessons()->where("active", 1)->orderBy('order_number', 'asc')->get();
                return response()->json([
                    'status' => true, 'message' => 'محتويات الكورس',
                    'data' => LessonResource::collection($type),
                ]);
            } else {
                return response()->json([
                    'status' => false, 'message' => ' لا يجد كور بهذا ال id',
                ]);
            }
        }
    }
    public function show_center(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code' => 'required',
            ],
            ['required' => 'حقل الكود طوب  ']
        );
        if ($validator->passes()) {
            if ($user = User::where('code', $request->code)->first()) {
                $user->centerstudents()->attach(auth()->id());
                //   $u = new User_Owner;
                //   $u->user_id = auth()->id();
                //   $u->owner_id = $user->id;
                //   $u->save();
                return response()->json([
                    'status' => true, 'message' => 'ت التاكد من صحه ال',
                    'data' => new CenterResource($user),
                ]);
            } else {
                return response()->json(['status' => false, 'message' => 'لا يوجد مستخدم بهذا اك']);
            }
        } else {
            return response()->json(['status' => false, 'message' => $validator->messages()->first()]);
        }
    }
    public function general_courses()
    {
        $generals = General::orderBy('id', 'desc')->get();
        return response()->json([
            'status' => true, 'message' => ' كل لاقسام ',
            'data' => GeneralResource::collection($generals),
        ]);
    }
    public function lecturer_info(Request $request)
    {
        $le = User::where('id', $request->lecturer_id)->first();
        if ($le->is_student == 2) {
            $courses = TypeResource::collection($le->types()->where("active", 1)->get());
        } elseif ($le->is_student == 3) {
            $courses = TypecollegeResource::collection($le->typescollege()->where("active", 1)->get());
        }
        return response()->json([
            'status' => true, 'message' => ' معلومات المحاض ',
            'info' => new LecturerResource($le),
            'courses' => $courses,
        ]);
    }

    public function getcoursevideos(Request $request)
    {
        $videos = VideosGeneral::where('course_id', $request->course_id)->where('active', 1)->get();
        return response()->json([
            'status' => true,
            'message' => 'فيديوهات كرس',
            'data' => VideogeneralResource::collection($videos),
        ]);
    }
    public function update_user(Request $request)
    {

        $user_id = \auth()->user()->id;

        $user = User::where('id', $user_id)->first();
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if ($request->user_name) {
            $user->name = $request->user_name;
        }
        if ($request->email) {
            $user->email = $request->email;
        }

        $user->save();
        #############################################################

        $user_info = \App\Model\User_info::where('user_id', $user_id)->first();
        if ($request->phone) {
            $user_info->phone = $request->phone;
        }
        if ($request->store_name) {
            $user_info->store = $request->store_name;
        }
        if ($request->country) {
            $user_info->location = $request->country;
        }
        $user_info->save();
        #############################################################
        return Response()->json([
            'status' => 'true',
            'data' => new ResourceUser($user),
        ]);
    }

    public function states()
    {
        return response()->json([
            'status' => true,
            'data' => StateResource::collection(\App\State::all()),
        ]);
    }
    public function years()
    {
        $year = \App\Year::get();
        return Response()->json([
            'data' => $year,
        ]);
    }
    public function buycourse(Request $request)
    {
        $user = auth()->user();
        if (auth()->user()->category_id == 1) {
            $points = $user->points;
            $type = Type::where('id', $request->course_id)->first();

            if ($type) {
                $typepoints = $type->points;
                if ($points >= $typepoints) {
                    //   $stutype = new Student_Type;
                    //   $stutype->student_id = auth()->user()->id;
                    //   $stutype->type_id = $type->id;
                    //   $stutype->save();
                    $user->stutypes()->attach($type->id);
                    $user->stutypes()->updateExistingPivot($type->id, ['type' => 1]);

                    $user->points = $user->points - $typepoints;
                    $user->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'تم شر الكورس',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'عفا لاتلك ناط كافيه',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'عفوا   لايجد كرس هذا الاسم',
                ]);
            }
        } else if (auth()->user()->category_id == 2) {
            $points = $user->points;
            $type = TypesCollege::where('id', $request->course_id)->first();
            if ($type) {
                $typepoints = $type->points;
                if ($points >= $typepoints) {
                    $user->stutypescollege()->attach($type->id);
                    $user->stutypescollege()->updateExistingPivot($type->id, ['type' => 1]);

                    //   $stutype = new Student_Typecollege;
                    //   $stutype->student_id = auth()->user()->id;
                    //   $stutype->typecollege_id = $type->id;
                    //   $stutype->save();
                    $user->points = $user->points - $typepoints;
                    $user->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'تم شرء اكورس',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'عفو لاتمل نقاط كافيه',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'عفوا   ايوج كور بهذا الاسم',
                ]);
            }
        } else if (auth()->user()->category_id == 3) {
            $points = $user->points;
            $type = Course::where('id', $request->course_id)->first();
            $typepoints = $type->points;
            if ($points >= $typepoints) {
                $user->stucourses()->attach($type->id);
                //   $stutype = new Student_Course;
                //   $stutype->student_id = auth()->user()->id;
                //   $stutype->course_id = $type->id;
                //   $stutype->save();
                $user->points = $user->points - $typepoints;
                $user->save();
                return response()->json([
                    'status' => true,
                    'message' => 'تم شرء الكورس',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'عفوا لاتملك نقط افيه',
                ]);
            }
        }
    }
    public function buygeneralcourse(Request $request)
    {
        $user = auth()->user();
        $points = $user->points;
        $type = Course::where('id', $request->course_id)->first();
        $typepoints = $type->points;
        if ($points >= $typepoints) {
            $user->stucourses()->sync($type->id);
            //   $stutype = new Student_Course;
            //   $stutype->student_id = auth()->user()->id;
            //   $stutype->course_id = $type->id;
            //   $stutype->save();
            $user->points = $user->points - $typepoints;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'تم راء الكورس',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'عفو لاتملك ناط كافي',
            ]);
        }
    }
    public function mycourses()
    {
        $user = auth()->user();
        $types = $user->stutypes()->wherePivot('active', 1)->get();
        $typescollege = $user->stutypescollege()->wherePivot('active', 1)->get();
        $courses = $user->stucourses()->wherePivot('active', 1)->get();
        return response()->json([
            'status' => true,
            'message' => 'كل كورستك',
            'data1' => TypeResource::collection($types),
            'data2' => TypecollegeResource::collection($typescollege),
            'data3' => CourseResource::collection($courses),
        ]);
    }
    public function buyclass(Request $request)
    {
        $user = auth()->user();
        if (auth()->user()->category_id == 1) {
            $points = $user->points;
            $type = Subtype::where('id', $request->class_id)->first();
            if ($type) {
                $typepoints = $type->points;
                if ($points >= $typepoints) {
                    //   $stutype = new Student_Subtype;
                    //   $stutype->student_id = auth()->user()->id;
                    //   $stutype->subtype_id = $type->id;
                    //   $stutype->save();
                    $user->stusubtypes()->attach($type->id);
                    $user->points = $user->points - $typepoints;
                    $user->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'تم شاء الدرس',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'عفا لاتمك قاط كافيه',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'عفوا   لايود در با الاسم',
                ]);
            }
        }
        if (auth()->user()->category_id == 2) {
            $points = $user->points;
            $type = Lesson::where('id', $request->class_id)->first();
            if ($type) {
                $typepoints = $type->points;
                if ($points >= $typepoints) {
                    $user->stulessons()->attach($type->id);
                    //   $stutype = new Student_Lesson;
                    //   $stutype->student_id = auth()->user()->id;
                    //   $stutype->lesson_id = $type->id;
                    //   $stutype->save();
                    $user->points = $user->points - $typepoints;
                    $user->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'تم شراء الدرس',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'عفوا لتمك قاط ايه',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'عفوا   ايوج درس بهذا السم',
                ]);
            }
        }
    }
    public function rate_course(Request $request)
    {
        $user = auth()->user();
        if (auth()->user()->category_id == 1) {
            $type = Type::where('id', $request->course_id)->first();
            $type_rate = Type_Rate::where('type_id', $type->id)->where('user_id', $user->id)->first();
            if ($type_rate) {
                return response()->json([
                    'status' => false,
                    'message' => 'لقد قيمت هذا الكورس من قبل',
                ]);
            } else {
                if ($type) {
                    $rate = new Type_Rate;
                    $rate->type_id = $type->id;
                    $rate->user_id = $user->id;
                    $rate->rate = $request->rate;
                    $rate->comment = $request->comment;
                    $rate->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'ت التقييم بنجا',
                        'data' => new RateResource($rate),

                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'عفو   ليد كورس بذا الاسم',
                    ]);
                }
            }
        } else if (auth()->user()->category_id == 2) {
            $type = TypesCollege::where('id', $request->course_id)->first();

            $typecollegetype_rate = Typecollege_Rate::where('typecollege_id', $type->id)->where('user_id', $user->id)->first();

            if ($typecollegetype_rate) {
                return response()->json([
                    'status' => false,
                    'message' => 'لقد قيمت هذا الكورس من قبل',
                ]);
            } else {
                if ($type) {
                    $rate = new Typecollege_Rate;
                    $rate->typecollege_id = $type->id;
                    $rate->user_id = $user->id;
                    $rate->rate = $request->rate;
                    $rate->comment = $request->comment;
                    $rate->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'م التقييم نجاح',
                        'data' => new RateResource($rate),

                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'عفوا   ليوجد كورس بذا الام',
                    ]);
                }
            }
        } else if (auth()->user()->category_id == 3) {
            $type = Course::where('id', $request->course_id)->first();
            $course_rate = Course_Rate::where('course_id', $type->id)->where('user_id', $user->id)->first();

            if ($course_rate) {
                return response()->json([
                    'status' => false,
                    'message' => 'لقد قيمت هذا الكورس من قبل',
                ]);
            } else {
                if ($type) {
                    $rate = new Course_Rate;
                    $rate->course_id = $type->id;
                    $rate->user_id = $user->id;
                    $rate->comment = $request->comment;
                    $rate->rate = $request->rate;
                    $rate->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'تم لتقيم بجاح',
                        'data' => new RateResource($rate),

                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'فوا   لاوجد كورس بهذا ااسم',
                    ]);
                }
            }
        }
    }
    public function course_rate(Request $request)
    {
        $user = auth()->user();
        if (auth()->user()->category_id == 1) {
            $type = Type::where('id', $request->course_id)->first();
            if ($type) {
                $rates = $type->rates;

                $allrates = array_sum($type->rates()->pluck('rate')->toArray());
                return response()->json([
                    'status' => true,
                    'message' => 'كل تقيمات الكور ',
                    'averagerate' => (count($rates) > 0) ? $allrates / count($rates) : 0,
                    'data' => RateResource::collection($rates),
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'عفوا   لايوجد كور بهذا الاس',
                ]);
            }
        } else if (auth()->user()->category_id == 2) {
            $type = TypesCollege::where('id', $request->course_id)->first();
            if ($type) {
                $rates = $type->rates;
                $allrates = array_sum($type->rates()->pluck('rate')->toArray());
                return response()->json([
                    'status' => true,
                    'message' => 'كل تقيما الكوس ',
                    'averagerate' => (count($rates) > 0) ? $allrates / count($rates) : 0,
                    'data' => RateResource::collection($rates),
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'فوا   ايود كورس بهذا الاسم',
                ]);
            }
        } else if (auth()->user()->category_id == 3) {
            $type = Course::where('id', $request->course_id)->first();
            if ($type) {
                $rates = $type->rates;
                $allrates = array_sum($type->rates()->pluck('rate')->toArray());
                return response()->json([
                    'status' => true,
                    'message' => 'ل تقييمت الكورس ',
                    'averagerate' => (count($rates) > 0) ? $allrates / count($rates) : 0,
                    'data' => RateResource::collection($rates),
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'عفوا   ليوجد كورس بذ ااسم',
                ]);
            }
        }
    }
    public function alllecturers(Request $request)
    {
        $users = auth()->user()->stdcenters;

        $result = [];

        if (auth()->user()->category_id == 1) {
            $user_owners = Center_Teacher::get()->pluck("teacher_id")->toArray();

            if (count($users) > 0) {

                $lecturer_ids = [];
                foreach ($users as $user) {
                    $lecturer_ids[] = $user->teachers->pluck("id")->toArray();
                }
                $result = call_user_func_array("array_merge", $lecturer_ids);

                $lecturers = Subject::where('id', $request->subject_id)->first()->teachers()->whereIn("users.id", $result)->get();
            } else {
                $lecturers = Subject::where('id', $request->subject_id)->first()->teachers()->whereNotIn("users.id", $user_owners)->get();
            }
        } elseif (auth()->user()->category_id == 2) {
            $user_owners = Center_Doctor::get()->pluck("doctor_id")->toArray();

            if (count($users) > 0) {

                $lecturer_ids = [];
                foreach ($users as $user) {
                    $lecturer_ids[] = $user->doctors->pluck("id")->toArray();
                }
                $result = call_user_func_array("array_merge", $lecturer_ids);
                $lecturers = SubjectsCollege::where('id', $request->subject_id)->first()->doctors()->whereIn("users.id", $result)->get();
            } else {
                $lecturers = SubjectsCollege::where('id', $request->subject_id)->first()->doctors()->whereNotIn("users.id", $user_owners)->get();
            }
        }
        return response()->json([
            'status' => true, 'message' => ' كل المحاضري  ',
            'data' => LecturerResource::collection($lecturers),
        ]);
    }
    public function subjectcourses(Request $request)
    {
        $user = auth()->user();
        $centers = auth()->user()->stdcenters;

        if (auth()->user()->category_id == 1) {
            if (count($centers) > 0 && (auth()->user()->is_public_platform_or_private_platform == 2 || auth()->user()->is_public_platform_or_private_platform == 0)) {
                if ($request->is_recently == 0) {
                    $types = Type::orderBy('id', 'desc')->whereIn('center_id', $centers->pluck('id'))->orWhereIn("user_id", $centers->pluck('id'))->where('subjects_id', $request->subject_id)->where("active", 1)->get();
                } else {
                    $types = Type::orderBy('id', 'desc')->whereIn('center_id', $centers->pluck('id'))->orWhereIn("user_id", $centers->pluck('id'))->where('subjects_id', $request->subject_id)->where("active", 1)->take(20)->get();
                }
            } else {
                if ($request->is_recently == 0) {
                    $types = Type::where('subjects_id', $request->subject_id)->orderBy('id', 'desc')->where('center_id', null)->where("active", 1)->get();
                } else {
                    $types = Type::where('subjects_id', $request->subject_id)->orderBy('id', 'desc')->where('center_id', null)->where("active", 1)->take(20)->get();
                }
            }
            $courses = TypeResource::collection($types);
        } else if (auth()->user()->category_id == 2) {
            if (count($centers) > 0 && (auth()->user()->is_public_platform_or_private_platform == 2 || auth()->user()->is_public_platform_or_private_platform == 0)) {
                if ($request->is_recently == 0) {
                    $types = TypesCollege::whereIn('center_id', $centers->pluck('id'))->orWhereIn("doctor_id", $centers->pluck('id'))->orderBy('id', 'desc')->where('subjectscollege_id', $request->subject_id)
                        ->where("active", 1)->get();
                } else {
                    $types = TypesCollege::whereIn('center_id', $centers->pluck('id'))->orWhereIn("doctor_id", $centers->pluck('id'))->orderBy('id', 'desc')->where('subjectscollege_id', $request->subject_id)
                        ->where("active", 1)
                        ->take(20)->get();
                }
            } else {
                if ($request->is_recently == 0) {
                    $types = TypesCollege::where('subjectscollege_id', $request->subject_id)->where("active", 1)->where('center_id', null)->orderBy('id', 'desc')->get();
                } else {
                    $types = TypesCollege::where('subjectscollege_id', $request->subject_id)->where("active", 1)->where('center_id', null)->orderBy('id', 'desc')->take(20)->get();
                }
            }
            $courses = TypecollegeResource::collection($types);
        }
        return response()->json([
            'status' => true,
            'message' => 'ل  الكورسات ',

            'data' => $courses,
        ]);
    }
    public function center_code(Request $request)
    {
        $user = User::where('code', $request->code)->first();
        return response()->json([
            'status' => true,
            'message' => 'مومات السنتر',
            'data' => new CenterResource($user),
        ]);
    }
    public function mycenters()
    {
        $user = auth()->user();
        $centers = $user->stdcenters;
        return response()->json([
            'status' => true,
            'message' => 'معومات السنتر',
            'data' => CenterResource::collection($centers),
        ]);
    }
    public function fetch_centers()
    {

        $centers = User::where('is_student', 5)->get();

        return response()->json([
            'status' => true,
            'message' => 'fetch_centers ',
            'data' => CenterResource::collection($centers),
        ]);
    }
    public function getcoursegeneral(Request $request)
    {
        $subs = Sub::where('general_id', '=', $request->id)->get();
        return response()->json([
            'status' => true,
            'data' => SubResource::collection($subs),
        ]);
    }
    public function searshforcourses(Request $request)
    {
        $name = $request->name;
        if (auth()->user()->category_id == 1) {
            $types = Type::where('name_ar', 'LIKE', '%' . $name . '%')->get();
            $teachers = User::where('is_student', 2)->where('name', 'LIKE', '%' . $name . '%')->get();
            $courses = new Collection;
            $courses = $courses->merge($types);
            foreach ($teachers as $teacher) {
                $courses = $courses->merge($teacher->types);
            }
            return response()->json([
                'status' => true,
                'message' => 'الكور',
                'data' => TypeResource::collection($courses),
            ]);
        } elseif (auth()->user()->category_id == 2) {
            $types = TypesCollege::where('name_ar', 'LIKE', '%' . $name . '%')->get();
            $teachers = User::where('is_student', 3)->where('name', 'LIKE', '%' . $name . '%')->get();
            $courses = new Collection;
            $courses = $courses->merge($types);
            foreach ($teachers as $teacher) {
                $courses = $courses->merge($teacher->typescollege);
            }
            return response()->json([
                'status' => true,
                'message' => 'لكورسات',
                'data' => TypecollegeResource::collection($courses),

            ]);
        }
    }
    public function sendmessage(Request $request)
    {
        $user = auth()->user();
        $message = new Message;
        $message->message = $request->message;
        $message->phone = $user->phone;
        $message->user_id = $user->id;
        $message->save();
        return response()->json(['status' => true, 'message' => 'تم ارسل الرساله بنجاح']);
    }
    public function lecturer_cover(Request $request)
    {
        $lect = User::where('id', $request->id)->firstOrFail();
        return response()->json([
            'status' => true,
            'data' => [
                'id' => $lect->id,
                'cover' => $lect->printsplash ? asset('uploads/' . $lect->printsplash) : '',
            ],
        ]);
    }
    public function app_status()
    {
        return $this->dataResponse("تم إرجاع الداتا بنجاح", AppResource::make(0));
        // return response()->json([
        //     "status" => true,
        //     "old_app_status" => 1,
        //     "new_app_status" => 0,
        // ]);
    }
}
