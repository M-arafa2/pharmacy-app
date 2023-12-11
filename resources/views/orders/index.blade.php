@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
<meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Orders</title>
    
@stop

@section('content')
<body>
   <h1>Orders</h1>
                    

       <!-- Table -->
       <table id='empTable' class='datatable table table-dark table-hover text-center'>
           <thead >
               <tr>
                   <td>id</td>
                   <td>ordered user</td>
                   <td>pharmacy</td>
                   <td>address</td>
                   <td>status</td>
                   <td>creator type</td>
                   <td>is_insured</td>
                   <td>Action</td>
               </tr>
           </thead>
       </table>
   </div>

   <!-- Script -->
   
</body>
    
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
   <script type="text/javascript">
   // CSRF Token
   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); 
   $(document).ready(function(){

       // Initialize
       var empTable = $('#empTable').DataTable({
             processing: true,
             serverSide: true,
             dom: 'Bfrtip',
             ajax: "{{ route('orders.index') }}",
             buttons: [{extend: 'excel',
                        text: 'Save As Excel',
                        exportOptions: {
                        modifier: {
                                    page: 'current'
                                }
                        }
                    },
                    {extend: 'pdf',
                        text: 'pdf',
                        exportOptions: {
                        modifier: {
                                    page: 'current'
                                }
                        }
                    }
                    ],
             columns: [
                 { data: 'id' },
                 { data: 'user.name' },
                 { data: 'pharmacy.staff.name' },
                 { data: 'address' },
                 { data: 'status' },
                 { data: 'creator_type' },
                 { data: 'is_insured' },
                 { data: 'action' },
             ]
       });
       //update record
       $('#empTable').on('click','.updateUser',function(){
            var id = $(this).data('id');

            $('#txt_empid').val(id);
            window.location.href = '/orders/'+id;

            // AJAX request
           
       
   });
});

   </script>
@stop

