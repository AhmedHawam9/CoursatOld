@extends('App.dash')
@section('content')
    <style>
        .form-group.form-check.nas {
            display: flex;
            justify-content: start;
            align-items: center;
        }

        #checkAll {
            width: unset;
            transform: scale(1.5);
        }

        .setting .info input {
            width: unset;
        }

        .info .inputDetails input {
            width: 100%;
        }


        /* ====== Tablet style ====== */
        @media only screen and (max-width: 991.98px) {
            .nav-tabs .nav-item {
                width: 16%;
            }

            .nav-tabs .nav-link {
                text-align: center;
            }

            .tab-content {
                margin-top: 5%;
            }

            .form-check-inline {
                width: 22.5%;
            }

            .notifcation .nav-item {
                width: 25%;
            }
        }

        /* ====== Mobile style ====== */

        @media only screen and (max-width: 767.98px) {
            .nav-tabs .nav-item {
                width: 33%;
            }

            .form-check-inline {
                width: 45.5%;
            }

            .notifcation .nav-item {
                width: 45.5%;
            }

            .notifcation {
                margin-top: 10%;
            }
        }
    </style>
    <!--start page-body-->
    <div class="page-body">
        <div class="container">

            <!--start heed-->
            <div class="heed">

                <div class="row">
                    <div class="profile">
                        <div class="row">
                            <div class="col-3">
                                <img src="{{ asset('images/profile.svg') }}">
                            </div>
                            <div class="col-6">
                                <h5>{{ auth()->user()->name }}</h5>
                                <p>ادمن</p>

                            </div>


                        </div>
                    </div>
                    <div class="flag">

                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset('images/flag.svg') }}">
                            </div>
                            <div class="col-4">
                                <h5>العربية</h5>


                            </div>



                        </div>

                    </div>


                    <div class="noti text-center">
                        <span><i class="far fa-bell"></i></span>
                    </div>



                    <div class="search">

                        <input type="text" name="search">
                        <span class="srch"><i class="fas fa-search"></i></span>

                    </div>

                    <div class="datee">
                        <div class="row">
                            <span><i class="far fa-calendar-alt"></i></span>
                            <p>{{ Carbon\Carbon::now()->format('d-m-Y') }}</p>
                        </div>
                    </div>


                </div>


            </div>
            <!--end heed-->


            <!--start setting-->
            <div class="setting">
                <div class="container">
                    <div class="row def">
                        <img src="{{ asset('images/setting.svg') }}">
                        <h5>اضافه ادمن </h5>
                    </div>
                    <form method="post" action="{{ route('storeadmin') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="info">
                            <div class="row inputDetails">
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>الاسم</label>
                                    <input class="form-control" value="{{ old('name') }}" type="text" name="name">
                                    @error('name')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>الايميل</label>
                                    <input class="form-control" value="{{ old('email') }}" type="text" name="email">
                                    @error('email')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>كلمه السر</label>
                                    <input class="form-control" value="{{ old('password') }}" type="password"
                                        name="password">
                                    @error('password')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-12">
                                    <label>الهاتف</label>
                                    <input class="form-control" value="{{ old('phone') }}" type="text" name="phone">
                                    @error('phone')
                                        <p style="color:red;">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            @php
                                $models = [
                                    'admins',
                                    'teachers',
                                    'doctors',
                                    'lecturer',
                                    'centers',
                                    'states',
                                    'cities',
                                    'offers',

                                    // 'points',
                                    // 'pointscash' ,
                                    // 'messages' ,
                                    'stages',
                                    'years',
                                    'subjects',
                                    'types',
                                    'subtypes',
                                    'videos',
                                    // 'userpaqa' ,
                                    'subjectquestionsscenter',
                                    'universities',
                                    'colleges',
                                    'divisions',
                                    'sections',
                                    'subcolleges',
                                    'typescolleges',
                                    'lessons',
                                    'videoscolleges',
                                    'subjectscollegequestionscenter',
                                    'general',
                                    'sub',
                                    'course',
                                    'videosgeneral',
                                    'subquestioncenterss',
                                    'paqas',
                                    // 'students'
                                ];
                                $maps = ['create', 'read', 'update', 'delete'];
                                $models1 = ['sendnotification', 'sendnotificationbasic', 'sendnotificationuniversity', 'sendnotificationgeneral'];
                                $maps1 = ['create'];

                                $models2 = ['type_joins', 'typecollege_joins'];
                                $maps2 = ['read'];

                            @endphp

                            <div class="col-12">
                                <div class="mb-3">
                                    <label><input type="checkbox" class="formcotrol ml-3 mr-3" id="checkAll">كل
                                        الصلاحيات</label>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <h4 class="mb-3">الصلاحيات</h4>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            {{-- start --}}
                                            <tr>
                                                <td>المشرف</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>المدرسيين</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}

                                            {{-- start --}}
                                            <tr>
                                                <td>الدكاتره</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>المحاضرين</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>المراكز</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>المحافظات</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>المدن</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>العروض</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>المراحل</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>السنوات</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>مواد الأساسي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>حصص الأساسي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>فديوهات الأساسي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>بنك الأسئله</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>الجامعات</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>الكليات</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>الأقسام</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>الفرق</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>مواد الجامعي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>كورسات الجامعي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>حصص الجامعي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>فديوهات الجامعي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>بنك الأسئله الجامعي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>الأقسام الرئسيه</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>الأقسام الفرعيه</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>كورسات عام</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>فديوهات عام</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>بنك أسئله عام</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                             {{-- start --}}
                                             <tr>
                                                <td>الباقات</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">تعديل</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">حذف</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            {{-- end --}}
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                            <hr>
                            <h4 class="mb-3">الاشعارات</h4>

                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            {{-- start --}}
                                            <tr>
                                                <td>ارسال اشعارات</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>


                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>ارسال اشعارات اساسي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>


                                            </tr>
                                            {{-- end --}}

                                            {{-- start --}}
                                            <tr>
                                                <td>ارسال اشعارات جامعي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>


                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>ارسال اشعارات عام</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">اضافة</label>
                                                    </div>
                                                </td>


                                            </tr>
                                            {{-- end --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <h4 class="mb-3">طلبات الانضمام</h4>

                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            {{-- start --}}
                                            <tr>
                                                <td>طلبات الأنضمام الأساسي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>


                                            </tr>
                                            {{-- end --}}
                                            {{-- start --}}
                                            <tr>
                                                <td>طلبات الأنضمام الجامعي</td>
                                                <td>
                                                    <div class="form-group form-check nas">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">عرض</label>
                                                    </div>
                                                </td>


                                            </tr>
                                            {{-- end --}}

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>



                        <div class="save text-center mt-6">
                            <div class="row save">
                                <div class="col-12 text-center">
                                    <input type="submit" value="حفظ" class="text-center">

                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--end setting-->
        <!--start foter-->
        <div class="foter">
            <div class="row">
                <div class="col-12 text-center">
                    <h5>Made With <img src="{{ asset('images/red.svg') }}"> By Crazy Idea </h5>
                    <p>Think Out Of The Box</p>
                </div>
            </div>
        </div>
        <!--end foter-->
    </div>
    </div>
    <!--end page-body-->
    <script>
        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
@endsection





{{-- <ul class="nav nav-tabs">
                                    @foreach ($models as $index => $model)
                                    <li class="nav-item">
                                        <a href="#{{$model}}" data-toggle="tab" aria-expanded="false" class="nav-link {{$index == 0 ? 'active' : ''}}">
                                            <span>{{__('messages.'.$model)}}</span>
                                        </a>
                                    </li>
                                    @endforeach

                                </ul>
                                <div class="tab-content">
                                    @foreach ($models as $index => $model)

                                    <div role="tabpanel" class="tab-pane fade show {{$index == 0 ? 'active' : ''}}" id="{{$model}}">
                                        @foreach ($maps as $key => $map)
                                            <div class="checkbox checkbox-success form-check-inline">
                                                <input type="checkbox" name="permissions[]" id="inlineCheckbox{{$key}}" value="{{$model}}-{{$map}}">
                                                <label for="inlineCheckbox{{$key}}" style="margin-right: 30px;"> {{__('messages.'.$map)}}</label>
                                            </div>
                                         @endforeach
                                    </div>
                                  @endforeach
                                       <ul class="nav nav-tabs notifcation">
                                    @foreach ($models1 as $index => $model)
                                    <li class="nav-item">
                                        <a href="#{{$model}}" data-toggle="tab" aria-expanded="false" class="nav-link {{$index == 0 ? 'active' : ''}}">
                                            <span>{{__('messages.'.$model)}}</span>
                                        </a>
                                    </li>
                                    @endforeach

                                </ul>
                                <div class="tab-content">
                                    @foreach ($models1 as $index => $model)

                                    <div role="tabpanel" class="tab-pane fade show {{$index == 0 ? 'active' : ''}}" id="{{$model}}">
                                        @foreach ($maps1 as $key => $map)
                                            <div class="checkbox checkbox-success form-check-inline">
                                                <input type="checkbox" name="permissions[]" id="inlineCheckbox{{$key}}" value="{{$model}}-{{$map}}">
                                                <label for="inlineCheckbox{{$key}}" style="margin-right: 30px;"> {{__('messages.'.$map)}}</label>
                                            </div>
                                         @endforeach
                                    </div>
                                  @endforeach
                                </div>
                                <ul class="nav nav-tabs notifcation">
                                    @foreach ($models2 as $index => $model)
                                    <li class="nav-item">
                                        <a href="#{{$model}}" data-toggle="tab" aria-expanded="false" class="nav-link {{$index == 0 ? 'active' : ''}}">
                                            <span>{{__('messages.'.$model)}}</span>
                                        </a>
                                    </li>
                                    @endforeach

                                </ul>

                                <div class="tab-content">
                                    @foreach ($models2 as $index => $model)

                                    <div role="tabpanel" class="tab-pane fade show {{$index == 0 ? 'active' : ''}}" id="{{$model}}">
                                        @foreach ($maps2 as $key => $map)
                                            <div class="checkbox checkbox-success form-check-inline">
                                                <input type="checkbox" name="permissions[]" id="inlineCheckboxx{{$key}}" value="{{$model}}-{{$map}}">
                                                <label for="inlineCheckboxx{{$key}}" style="margin-right: 30px;"> {{__('messages.'.$map)}}</label>
                                            </div>
                                         @endforeach
                                    </div>
                                  @endforeach
                                </div>
                            </div> --}}
