@extends('App.dash')
@section('content')
<style>
    .setting .info button {
        width: 100% !important;
    }

    .bootstrap-select .dropdown-toggle .filter-option {
        text-align: start !important;
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
                            <img src="{{asset('images/profile.svg')}}">
                        </div>
                        <div class="col-6">
                            <h5>{{auth()->user()->name}}</h5>
                            <p>ادمن</p>

                        </div>


                    </div>
                </div>
                <div class="flag">

                    <div class="row">
                        <div class="col-4">
                            <img src="{{asset('images/flag.svg')}}">
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
                        <p>{{ Carbon\Carbon::now()->format('d-m-Y')}}</p>
                    </div>
                </div>


            </div>


        </div>
        <!--end heed-->


        <!--start setting-->
        <div class="setting">
            <div class="container">
                <div class="row def">
                    <img src="{{asset('images/setting.svg')}}">
                    <h5>ارسال الاشعارات </h5>
                </div>



                <div class="info">
                    <div class="row">
                        <div class="form-group col-md-6 col-12">

                            <label for="pay">الجامعات </label><br>
                            <select class="form-control qeno-select selectpicker" style="display:block;width:100% !important;" onchange="getcollege(this)" name="university_id" id="university" data-live-search="true">
                                <option value="0">اختر جامعه</option>
                                @foreach($universities as $university)
                                <option value="{{$university->id}}">{{$university->name_ar}}</option>
                                @endforeach
                            </select>
                            <script>
                                $(function() {
                                    $('.selectpicker').selectpicker();
                                });
                            </script>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="p">اسم الكليه </label>
                            <select id="college" class="form-control qeno-select selectpicker" style="display:block;width:100% !important;" name="college" data-live-search="true" onchange="getdivision(this)" required>
                                <option value="0" disabled="disabled" selected="selected">اختر كليه</option>
                                @foreach($colleges as $college)
                                <option value="{{$college->id}}">{{$college->name_ar}}</option>
                                @endforeach
                            </select>
                            <script>
                                $(function() {
                                    $('.selectpicker').selectpicker();
                                });
                            </script>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>اسم القسم </label>
                            <select multiple class="form-control qeno-select selectpicker" style="display:block;width:100% !important;" data-live-search="true" id="division" name="division_id[]" onchange="getsection2()" required>
                                <option value="0" disabled="disabled">اختر قسم</option>
                                @foreach($divisions as $division)
                                <option value="{{$division->id}}">{{$division->name_ar}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>اسم الفرقه </label>
                            <select name="section_id[]" multiple class="form-control qeno-select selectpicker" style="display:block;width:100% !important;" data-live-search="true" id="section" onchange="getsubcollege()" required>

                                @foreach($sections as $section)
                                <option value="{{$section->id}}">{{$section->name_ar}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label>العنوان </label>
                            <input type="text" class="form-control" placeholder="ادخل اسم " id="title" name="title">

                        </div>
                        <div class="form-group col-12">
                            <label> النص</label>
                            <input type="text" class="form-control" id="text" name="name_en">

                        </div>
                    </div>
                </div>




                <div class="save text-center mt-6">
                    <div class="row save">
                        <div class="col-12 text-center">
                            <input type="button" onclick="storenotification()" value="حفظ" class="text-center">

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!--end setting-->
    <!--start foter-->
    <div class="foter">
        <div class="row">
            <div class="col-12 text-center">
                <h5>Made With <img src="{{asset('images/red.svg')}}"> By Crazy Idea </h5>
                <p>Think Out Of The Box</p>
            </div>
        </div>
    </div>
    <!--end foter-->
</div>
</div>
<!--end page-body-->

@endsection
@section("scripts")
<script>
    function storenotification() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: 'storeuniversitynotification',
            type: 'post',
            dataType: 'json',
            data: {
                'title': $("#title").val(),
                'text': $("#text").val(),
                'section_id': $("#section").val()
            },
            beforeSend: function() {
                Swal.fire(
                    'يتم الان ارسال الاشعارات',
                    'انتظر قليلا',

                )
            },
            success: function(result) {
                if (result.status == true) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'تم الاشعار بنجاح ',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    location.reload();
                } else if (result.status == false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: result.message,

                    })
                }
            }
        })
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
                $('#division').empty();
                $('#division').html(result);
                $('#division').selectpicker('refresh');
            }

        });
    }

    function getsection2() {
        var selected = [];
        //  var division = [];
        // $.each($("#division"), function () {
        //     division.push( $(this).val());
        // });

        let division = $("#division").val();
        console.log(division);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({

            url: `getsection2`,
            type: "post",
            data: {
                "division": division
            },
            // contentType: "application/json; charset=utf-8",
            //   dataType: "Json",
            success: function(result) {
                $('#section').empty();
                $('#section').html(result);
                $('#section').selectpicker('refresh');
            }

        });
    }

    function getsubcollege() {

        let subcollege = $("#section").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: `getsubcollege2`,
            //  contentType: "application/json; charset=utf-8",

            data: {
                "subcollege": subcollege
            },
            success: function(result) {
                $('#subcollege').empty();
                $('#subcollege').html(result);
                $('#subcollege').selectpicker('refresh');
            }

        });
    }

    function getcollege(selected) {
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
            //     dataType: "Json",
            success: function(result) {
                $('#college').empty();
                $('#college').html(result.data);
                $('#college').selectpicker('refresh');
                console.log(result);
            }

        });
    }
</script>
@endsection