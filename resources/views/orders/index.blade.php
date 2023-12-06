@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
<meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Orders</title>

   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"/>
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>

    
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
   <script type="text/javascript">
   // CSRF Token
   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); 
   $(document).ready(function(){

       // Initialize
       var empTable = $('#empTable').DataTable({
             processing: true,
             serverSide: true,
             ajax: "{{ route('orders.index') }}",
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

