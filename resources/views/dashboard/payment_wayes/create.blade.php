@extends('App.dash')
@section('content')
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
                        <h5>اضافة وسيلة دفع </h5>
                    </div>
                    <form method="post" action="{{ route('paymentways.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="info">
                            <div class="row">

                                <div class="form-group col-lg-3 col-md-12 col-12">
                                    <label>اسم الوسيله </label>
                                    <input type="text" class="form-control" placeholder="ادخل اسم " name="title"
                                        value="{{ old('title') }}"required>
                                    @error('title')
                                        <div style="color:red;">{{ $message }} </div>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-3 col-md-12 col-12">
                                    <label>رقم الوسيلة </label>
                                    <input type="text" class="form-control" placeholder="ادخل الرقم " name="number"
                                        value="{{ old('number') }}"required>
                                    @error('number')
                                        <div style="color:red;">{{ $message }} </div>
                                    @enderror
                                </div>

                                {{-- @if (auth()->user()->isAdmin === 'admin')
                                    <div class="form-group col-3">
                                        <label> رقم المركز</label>
                                        <select class="form-control" multiple name="center_id[]">
                                            @foreach ($centers as $center)
                                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('center_id')
                                            <div style="color:red;">{{ $message }} </div>
                                        @enderror
                                    </div>
                                @endif --}}

                                <div class="form-group col-lg-3 col-md-12 col-12">
                                    <label>صورة الوسيلة </label>
                                    <input type="file" class="form-control" placeholder="ادخل الصورة " name="image">
                                    @error('image')
                                        <div style="color:red;">{{ $message }} </div>
                                    @enderror
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
@endsection
@section('scripts')
@endsection
