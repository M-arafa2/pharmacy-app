@extends('adminlte::page')

@section('title', 'Areas')

@section('content_header')
<meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Users</title>
 
    
@stop

@section('content')
<body >
    <h1>Areas</h1>
   <div class='container '>

       <!-- Modal -->
       <div id="updateModal" class="modal fade" role="dialog">
           <div class="modal-dialog">

               <!-- Modal content-->
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Update</h4>
                      <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
                  </div>
                  <div class="modal-body">
                    
                        <div class="form-group">
                            <label for="name" >Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter Area name" required> 
                        </div>
                        <div class="form-group">
                            <label for="address" >address</label> 
                            <input type="text" class="form-control" id="address" placeholder="Enter address" required> 
                        </div> 
                        
                  </div>
                  <div class="modal-footer">
                      <input type="hidden" id="txt_empid" value="0">
                      <button type="button" class="btn btn-dark btn-sm" id="btn_save">Save</button>
                      <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                  </div>
             </div>

           </div>
       </div>
        <!-- Modal -->
       <div id="createModal" class="modal fade" role="dialog">
           <div class="modal-dialog">

               <!-- Modal content-->
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Create</h4>
                      <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
                  </div>
                  <div class="modal-body">
                    
                  <div class="form-group">
                            <label for="cname" >Name</label>
                            <input type="text" class="form-control" id="cname" placeholder="Enter Area name" required> 
                        </div>
                        <div class="form-group">
                            <label for="caddress" >address</label> 
                            <input type="text" class="form-control" id="caddress" placeholder="Enter address" required> 
                        </div> 
                  </div>
                  <div class="modal-footer">
                      <input type="hidden" id="txt_empid" value="0">
                      <button type="button" class="btn btn-dark btn-sm" id="btn_create">Save</button>
                      <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                  </div>
             </div>

           </div>
       </div>
       <!-- Table -->
       <button id="createUser" class="btn btn-dark  mb-3" data-bs-toggle="modal" data-bs-target="#createModal">New Area</button>
       <table id='empTable' class='datatable table table-dark table-hover text-center'>
           <thead >
               <tr>
                   <td>id</td>
                   <td>name</td>
                   <td>address</td>
                   <td>created At</td>
                   <td>Action</td>
               </tr>
           </thead>
       </table>
   </div>
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
             ajax: "{{ route('areas.index') }}",
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
                 { data: 'name' },
                 { data: 'address' },
                 { data: 'created_at' },
                 { data: 'action' },
             ],
             
       });
       
       // Update record
       $('#empTable').on('click','.updateUser',function(){
            var id = $(this).data('id');

            $('#txt_empid').val(id);

            // AJAX request
            $.ajax({
                url:  "{{url('areas')}}"+'/'+id,
                type: 'get',
                data: {_token: CSRF_TOKEN,id: id},
                dataType: 'json',
                success: function(response){

                    if(response.success == true){
                         //$('#image').(response.image);
                         $('#name').val(response.data.name);
                         $('#address').val(response.data.address);
                         
                         empTable.ajax.reload();
                    }else{
                         alert("Invalid ID.");
                    }
                }
            });

       });
       // Save user 
       $('#btn_save').click(function(){
            var id = $('#txt_empid').val();
            var name = $('#name').val().trim();
            var address = $('#address').val().trim();

            if(name !='' && address != ''){

                 // AJAX request
                 $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     url: "{{url('areas')}}"+'/'+id,
                     type: 'POST',
                     data: {'_method': 'patch',id: id,name:name,address:address},
                     dataType: 'json',
                     success: function(response){
                        if(response.success == true){
                              alert(response.message);

                              // Empty and reset the values
                              $('#name','#address').val('');
                              //$('#gender').val('Male');
                              $('#txt_empid').val(0);

                              // Reload DataTable
                              empTable.ajax.reload();

                              // Close modal
                              $('#updateModal').modal('toggle');
                         }else{
                              alert(response.message);
                         }
                     }
                 });

            }else{
                 alert('Please fill all fields.');
            }
       });
       $('#btn_create').click(function(){    
            var name = $('#cname').val().trim();
            var address = $('#caddress').val().trim();

            if(name !='' && address != ''){

                 // AJAX request
                 $.ajax({
                     url: "{{ route('areas.store') }}",
                     type: 'post',
                     data: {_token: CSRF_TOKEN,name:name,address:address},
                     dataType: 'json',  
                     success: function(response){
                        if(response.success == true){
                              alert(response.message);

                              // Empty and reset the values
                              $('#cname','#caddress').val('');
                              //$('#gender').val('Male');
                              $('#txt_empid').val(0);

                              // Reload DataTable
                              empTable.ajax.reload();

                              // Close modal
                              $('#createModal').modal('toggle');
                         }else{
                              alert(response.message);
                         }
                     }
                 });

            }else{
                 alert('Please fill all fields.');
            }
        });
         // Delete record
       $('#empTable').on('click','.deleteUser',function(){
            var id = $(this).data('id');

            var deleteConfirm = confirm("Are you sure?");
            if (deleteConfirm == true) {
                 // AJAX request
                 $.ajax({
                    headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     url: "{{url('areas')}}"+'/'+id,
                     type: 'DELETE',
                     success: function(response){
                        if(response.success == true){
                               alert("Record deleted.");

                               // Reload DataTable
                               empTable.ajax.reload();
                          }else{
                                alert("Invalid ID.");
                          }
                     }
                 });
            }

       });
       
   });

   </script>
   
@stop

