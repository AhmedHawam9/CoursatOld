@extends('App.dash')
@section('content')
    <div class="page-body my-5">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-12">
                <div class="card_static card alert-info">
                    <i class="fas fa-user-graduate text-info"></i>
                    <h5>عدد الطلاب</h5>
                    <h4 class="counter">
                        @isset($students)
                            {{ $students }}
                        @endisset
                    </h4>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12">
                <div class="card_static card alert-light">
                    <i class="fas fa-book-open"></i>
                    <h5>عدد الكورسات</h5>
                    <h4 class="counter">
                        @isset($types)
                            {{ $types }}
                        @endisset
                    </h4>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12">
                <div class="card_static card alert-warning">
                    <i class="fas fa-university text-warning"></i>
                    <h5>عدد المدرسين</h5>
                    <h4 class="counter">
                        @isset($teachers)
                            {{ $teachers }}
                        @endisset
                    </h4>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- المدرسين / الكورسات -->
            <div class="col-lg-6 col-md-6 col-12 mt-5">
                <canvas id="myChart"></canvas>

                <script>
                    const zyx = document.getElementById("myChart").getContext('2d');
                    const labels2 = <?php echo json_encode($teachers_names); ?>;
                    const data2 = {
                        labels: labels2,
                        datasets: [{
                            label: 'المدرسين / الكورسات',
                            data: <?php echo json_encode($types_numbers); ?>,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)',
                            ],
                            borderColor: [
                                'rgb(54, 162, 235)',
                            ],
                            borderWidth: 1,
                            barPercentage: 0.5,
                            barThickness: 50,
                            // maxBarThickness: 15,
                            minBarLength: 3,
                        }]
                    };
                    const config2 = {
                        type: 'bar',
                        data: data2,
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        },
                    };
                    const myChart2 = new Chart(zyx, config2);
                </script>
            </div>
            <!-- المدرسين / الكورسات -->

            <!-- المدرسين / الاشتراكات -->
            <div class="col-lg-6 col-md-6 col-12 mt-5">
                <canvas id="myChart2"></canvas>

                <script>
                    const zyx2 = document.getElementById("myChart2").getContext('2d');
                    const labels3 = <?php echo json_encode($teachers2_names); ?>;
                    const data3 = {
                        labels: labels3,
                        datasets: [{
                            label: 'المدرسين / الاشتراكات',
                            data: <?php echo json_encode($types2_numbers); ?>,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                            ],
                            borderColor: [
                                'rgb(255, 99, 132)',
                            ],
                            borderWidth: 1,
                            barPercentage: 0.5,
                            barThickness: 50,
                            // maxBarThickness: 15,
                            minBarLength: 3,
                        }]
                    };
                    const config3 = {
                        type: 'bar',
                        data: data3,
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        },
                    };
                    const myChart3 = new Chart(zyx2, config3);
                </script>
            </div>
            <!-- المدرسين / الاشتراكات -->

            <!--  أكثر الكورسات طلبا  -->
            <div class="col-lg-6 col-md-6 col-12 mt-5">
                <canvas id="myChart3" style="transform: scale(.8);"></canvas>

                <script>
                    const zyx3 = document.getElementById("myChart3").getContext('2d');
                    const labels4 = <?php echo json_encode($types1_names); ?>;
                    const data4 = {
                        labels: labels4,
                        datasets: [{
                            label: 'الكورس / الاشتراكات',
                            data: <?php echo json_encode($types1_numbers); ?>,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(133, 73, 186 ,.2)'
                            ],
                            borderColor: [
                                'rgb(54, 162, 235)',
                                'rgb(255, 99, 132)',
                                '#8549ba'
                            ],
                            borderWidth: 1,
                            barPercentage: 0.5,
                            barThickness: 50,
                            // maxBarThickness: 15,
                            minBarLength: 3,
                        }]
                    };
                    const config4 = {
                        type: 'pie',
                        data: data4,
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        },
                    };
                    const myChart4 = new Chart(zyx3, config4);
                </script>
            </div>
            <!-- أكثر الكورسات طلبا -->

        <!--  أكثر المدرسين طلبا -->
        <div class="col-lg-6 col-md-6 col-12 mt-5">
            <canvas id="myChart4" style="transform: scale(.8);"></canvas>
            <script>
                const zyx4 = document.getElementById("myChart4").getContext('2d');
                const labels5 = <?php echo json_encode($students1_names);?>;
                const data5 = {
                    labels: labels5,
                    datasets: [{
                        label: 'أكثر الطلاب طلبا',
                        data: <?php echo json_encode($students1_numbers);?>,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(133, 73, 186 ,.2)'
                        ],
                        borderColor: [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                            '#8549ba'
                        ],
                        borderWidth: 1,
                        barPercentage: 0.5,
                        // barThickness: 50,
                        // maxBarThickness: 15,
                        minBarLength: 3,
                    }]
                };
                const config5 = {
                    type: 'pie',
                    data: data5,
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    },
                };
                const myChart5 = new Chart(zyx4, config5);
            </script>
        </div>
        <!--  أكثر المدرسين طلبا -->
    </div>
    <!-- <div class="row">
      <div class= "table-responsive">


        
        <table class="table w-100" id="example">
            <thead>
                <tr>
                    <th>اسم الطالب</th>
                    <th>اسم الكورس</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>أحمد</td>
                    <td>Computer science</td>
                    <td class="text-center">
                        <a href="#"> <img src="{{asset('images/pen.svg')}}" id="pen" style="cursor: pointer"></a>
                         @if(auth()->user()->hasPermission("stages-delete")) -->
                        <!-- <img src="{{asset('images/trash.svg')}}" id="trash" style="cursor:pointer;"> -->
                        <!-- @endif -->
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
    </div> -->
    </div>
@endsection


@section('style')
    <style>
        body {
            background-color: #eef0f8;
        }

        .card {
            box-shadow: 0 0 30px 0 rgb(82 63 105 / 5%);
            padding: 1.5rem 1.5rem;
        }

        .card svg {
            font-size: 1.7rem;
        }

        .card h5 {
            margin: 1rem 0;
            font-family: "med";
        }

        .card h4 {
            font-family: "med";
        }

        canvas {
            width: 90% !important;
            /* height: 300px !important; */
            margin: 0 auto;
        }

        .dataTables_wrapper {
            width: 90% !important;
            margin: 0 auto;
        }

        .table th {
            font-family: "med";
        }

        .table td {
            font-family: "reg";
        }

        @media only screen and (max-width: 650px) {
            .card {
                margin-top: 5%;
            }
        }
    </style>
@endsection

@section('scripts')
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{ asset('js/waypoints.min.js') }}"></script>
    <script src="{{ asset('js/counterup.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

    <script>
        $('.counter').counterUp({
            delay: 10,
            time: 2000
        });
    </script>
@endsection
