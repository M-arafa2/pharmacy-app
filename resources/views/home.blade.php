@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
@stop
@section('content')
    @if ($role =="pharmacy" or $role=="admin")
    <div style="display: flex;">
        <table class="table table-dark table-hover" style="width: fit-content;">
            <head>
                <tr>
                    <th>Logo</th>
                    <th>Pharmacy</th>
                    <th>Total Orders</th>
                    <th>Total Revenue</th>
                </tr>
            </head>
            <body>
            @foreach($revenue as $phar) 
                <tr>
                    <td><img style="width: 50px;" src="/{{ $phar['pharmacy_image'] }}"></td>
                    <td>{{ $phar['pharmacy_name'] }} </td>
                    <td>{{ $phar['count']}}</td>
                    <td>{{ $phar['total'] }}</td> 
                </tr>
                @endforeach
            </body>
        </table>
        @endif
    
        @if ($role=="admin")
        <canvas id="myChart" data-fem = {{ $females }} data-mal = {{ $males }} height="50px"></canvas>
        </div>
        <p class="alert text-center font-weight-bold text-wrap  bg-dark mt-5">Revenue in past 12 Months </p>
        <canvas id="mycChart"  width="100" height="25" class="mx-1 pb-3"></canvas>
        @endif

    
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')


    <script> 
    
  const chart = document.getElementById('myChart');
  const chartln = document.getElementById('lineChart');
  const data= {
        labels: [
            'Male',
            'Female',
            
        ],
        datasets: [{
            label: 'My First Dataset',
            data: [chart.dataset.mal,chart.dataset.fem],
            backgroundColor: [
            'rgb(54, 162, 235)',
            'rgb(255, 99, 132)',
            
            ],
            hoverOffset: 4
        }],
      
    };
    const config = {
                type: 'pie',
                data: data,
                options: {
                    plugins: {
                        datalabels: {
                            color: 'blue',
                            labels: {
                            title: {
                                font: {
                                weight: 'bold'
                                }
                            },
                            value: {
                                color: 'green'
                            }
                            }
                        }
                        }
                },
    }
    const myChart = new Chart(chart,config);
//Line chart

    var labs = JSON.parse('{!! json_encode($monthlyLabels) !!}');
    var vals = JSON.parse('{!! json_encode($monthlyRevenue) !!}');
    console.log(labs+vals)
    const ctx = document.getElementById('mycChart');
    const mycChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labs,
                    datasets: [{
                        label: '# of Votes',
                        data: vals,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
        



</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
@stop