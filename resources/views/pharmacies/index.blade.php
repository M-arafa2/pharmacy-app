@extends('adminlte::page')

@section('title', 'Pharmacies')

@section('content_header')
<meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Pharmacies</title>
  
 
@stop

@section('content')
<body>
    <h1>Pharmacies</h1>
   <div class='container'>

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
                            <label for="image">Image </label>
                            <input id="image" type="file" name="image" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="name" >Name</label>
                            <input type="text" class="form-control" id="emp_name" placeholder="Enter name" required> 
                        </div>
                        <div class="form-group">
                            <label for="email" >Email</label> 
                            <input type="email" class="form-control" id="email" placeholder="Enter email"> 
                        </div> 
                        
                        <div class="form-group">
                            <label for="national_id" >National id</label> 
                            <input type="text" class="form-control" id="national_id" placeholder="Enter national_id"> 
                        </div>
                        <div class="select2-purple">
                            <label for="area" >Area</label>
                            <select id="area" class="select2" multiple="multiple" data-placeholder="Select a Pharmacy" data- 
                                    dropdown-css-class="select2-purple" style="width: 100%;">
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="priority" >priority</label> 
                            <input type="text" class="form-control" id="priority" placeholder="Enter priority"> 
                        </div>
                        <div class="form-group">
                            <label for="password" >Password</label> 
                            <input type="password" class="form-control" id="password" placeholder="Enter password"> 
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
                            <label for="cimage">Image </label>
                            <input id="cimage" type="file" name="image" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="cname" >Name</label>
                            <input type="text" class="form-control" id="cemp_name" placeholder="Enter name" required> 
                        </div>
                        <div class="form-group">
                            <label for="cemail" >Email</label> 
                            <input type="email" class="form-control" id="cemail" placeholder="Enter email"> 
                        </div> 
                        
                        <div class="form-group">
                            <label for="cnational_id" >National id</label> 
                            <input type="text" class="form-control" id="cnational_id" placeholder="Enter national_id"> 
                        </div>
                        <div class="select2-purple">
                            <label for="carea" >Area</label> 
                            <select id="carea" class="select2" multiple="multiple" data-placeholder="Select a Pharmacy" data- 
                                    dropdown-css-class="select2-purple" style="width: 100%;">
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cpriority" >priority</label> 
                            <input type="text" class="form-control" id="cpriority" placeholder="Enter priority"> 
                        </div>
                        <div class="form-group">
                            <label for="cpassword" >Password</label> 
                            <input type="password" class="form-control" id="cpassword" placeholder="Enter password"> 
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
       <button id="createUser" class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#createModal">New pharmacy</button>
       <table id='empTable' class='datatable table table-dark table-hover text-center'>
           <thead >
               <tr>
                   <td>id</td>
                   <td>image</td>
                   <td>name</td>
                   <td>Email</td>
                   <td>national_id</td>
                   <td>Area</td>
                   <td>priority</td>
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
   $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }});
   $(document).ready(function(){
      
       // Initialize
       var empTable = $('#empTable').DataTable({
             processing: true,
             serverSide: true,
             dom: 'Bfrtip',
             ajax: "{{ route('pharmacies.index') }}",
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
                 { data: 'image' },
                 { data: 'staff.name' },
                 { data: 'staff.email' },
                 { data: 'staff.national_id' },
                 { data: 'area.name' },
                 { data: 'priority' },
                 { data: 'staff.created_at' },
                 { data: 'action' },
             ]
       });
       
       // Update record
       $('#empTable').on('click','.updateUser',function(){
            var id = $(this).data('id');
            var role = $(this).data('role');

            $('#txt_empid').val(id);

            // AJAX request
            $.ajax({
                url:  "{{url('pharmacies')}}"+'/'+id,
                type: 'get',
                data: {_token: CSRF_TOKEN,id: id},
                dataType: 'json',
                success: function(response){

                    if(response.success == true){
                         //$('#image').(response.image);
                         $('#emp_name').val(response.data.name);
                         $('#email').val(response.data.email);
                         $('#national_id').val(response.data.national_id);
                         $('#password').val(response.data.password); 
                         $('#priority').val(response.data.priority); 
                         area =document.getElementById('area');
                         area.value = response.data.area;
                         area.dispatchEvent(new Event('change'));
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
            var image = $('#image')[0].files[0];
            var emp_name = $('#emp_name').val().trim();
            var email = $('#email').val().trim();
            var national_id = $('#national_id').val().trim();
            var area = $('#area').find(":selected").val().trim();
            var priority = $('#priority').val().trim();
            if(password!=""){
                var password = $('#password').val().trim();
            }
            
            var fd = new FormData();
            fd.append('_method', 'PATCH');
            fd.append("id",id);
            if($('#image').get(0).files.length !== 0){
                fd.append('image',image);
            }
            
            fd.append("name",emp_name);
            fd.append("email",email);
            fd.append("national_id",national_id);
            fd.append("area_id",area);
            fd.append("priority",priority);
            if(password !=''){
                fd.append("password",password);
            }

            if(emp_name !='' && email != '' && national_id != ''&& area != ''&&
              priority != ''){

                 // AJAX request
                 $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     url: "{{url('pharmacies')}}"+'/'+id,
                     type: 'POST',
                     //data: {_token: CSRF_TOKEN,id: id,image:image,name: emp_name, email: email, national_id: national_id, pharmacy: pharmacy},
                     data:fd,
                     dataType: 'JSON',
                     processData: false,
                     contentType: false,
                     success: function(response){
                        if(response.success == true){
                              alert(response.message);

                              // Empty and reset the values
                              $('#emp_name','#email','#name').val('');
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
            var image = $('#cimage')[0].files[0];
            var emp_name = $('#cemp_name').val().trim();
            var email = $('#cemail').val().trim();
            var national_id = $('#cnational_id').val().trim();
            var area = $('#carea').find(":selected").val().trim();
            var priority = $('#cpriority').val().trim();
            var password = $('#cpassword').val().trim();
            var fd = new FormData();
            fd.append("_token",CSRF_TOKEN);
            fd.append('image',image);
            fd.append("name",emp_name);
            fd.append("email",email);
            fd.append("national_id",national_id);
            fd.append("area_id",area);
            fd.append("priority",priority);
            fd.append("password",password);

            if(emp_name !='' && email != '' &&
             national_id != ''&& area != ''&&
              password != ''&& priority != ''){

                 // AJAX request
                 $.ajax({
                     url: "{{ route('pharmacies.store') }}",
                     type: 'post',
                     //data: {_token: CSRF_TOKEN,id: id,image:image,name: emp_name, email: email, national_id: national_id, pharmacy: pharmacy},
                     data:fd,
                     dataType: 'json',
                     processData: false,
                     contentType: false,
                     success: function(response){
                        if(response.success == true){
                              alert(response.message);

                              // Empty and reset the values
                              $('#emp_name','#email','#name').val('');
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
            var fd = new FormData();
            fd.append('_method', 'DELETE');
            fd.append("id",id);

            var deleteConfirm = confirm("Are you sure?");
            if (deleteConfirm == true) {
                 // AJAX request
                 
                 $.ajax({
                    headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     url: "{{url('pharmacies')}}"+'/'+id,
                     type: 'DELETE',
                     //type: 'POST',
                     //data:fd,
                     dataType: 'JSON',
                     //processData: false,
                     //contentType: false,
                     //cache: false,
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

