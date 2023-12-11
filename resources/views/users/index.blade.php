@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
<meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Users</title>

@stop

@section('content')
<body>
    <h1>Users</h1>
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
                        <div class="form-group">
                            <label for="gender" >Gender</label> 
                            <input type="text" class="form-control" id="gender" placeholder="Enter gender"> 
                        </div>
                        <div class="form-group">
                            <label for="dateofbirth" >Date of Birth</label> 
                            <input type="text" class="form-control" id="dateofbirth" placeholder="Enter dateofbirth"> 
                        </div>
                        <div class="form-group">
                            <label for="mobile" >Mobile Number</label> 
                            <input type="text" class="form-control" id="mobile" placeholder="Enter mobile number"> 
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
                        <div class="form-group">
                            <label for="cgender" >Gender</label>
                            <select id="cgender" class="select2" multiple="multiple" data-placeholder="Select a gender" data- 
                                    dropdown-css-class="select2-purple" style="width: 100%;">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cdateofbirth" >Date Of Birth</label> 
                            <input type="text" class="form-control" id="cdateofbirth" placeholder="year-month-day"> 
                        </div>
                        <div class="form-group">
                            <label for="cmobile" >Mobile Number</label> 
                            <input type="text" class="form-control" id="cmobile" placeholder="Enter mobile number"> 
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
       <button id="createUser" class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#createModal">New User</button>
       <table id='empTable' class='datatable table table-dark table-hover text-center' >
           <thead  >
               <tr>
                   <td>id</td>
                   <td>image</td>
                   <td>name</td>
                   <td>Email</td>
                   <td>national_id</td>
                   <td>gender</td>
                   <td>birthdate</td>
                   <td>phoneNum</td>
                   <td>created At</td>
                   <td>Action</td>
               </tr>
           </thead>
       </table>
   </div>
</body>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
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
             ajax: "{{ route('users.index') }}",
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
                 { data: 'name' },
                 { data: 'email' },
                 { data: 'national_id' },
                 { data: 'gender' },
                 { data: 'date_of_birth' },
                 { data: 'mobile_number' },
                 { data: 'created_at' },
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
                url:  "{{url('users')}}"+'/'+id,
                type: 'get',
                data: {_token: CSRF_TOKEN,id: id},
                dataType: 'json',
                success: function(response){
                    if(response.success == true){
                         //$('#image').(response.image);
                         $('#emp_name').val(response.data.name);
                         $('#email').val(response.data.email);
                         $('#national_id').val(response.data.national_id);
                         //$('#password').val(response.data.password); 
                         $('#gender').val(response.data.gender); 
                         $('#dateofbirth').val(response.data.date_of_birth); 
                         $('#mobile').val(response.data.mobile_number); 
                         
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
            var gender = $('#gender').val().trim();
            var date_of_birth = $('#dateofbirth').val().trim();
            var mobile_number= $('#mobile').val().trim();
            var password = $('#password').val().trim();
            var fd = new FormData();
            fd.append('_method', 'PATCH');
            fd.append("id",id);
            if($('#image').get(0).files.length !== 0){
                fd.append('image',image);
            }
            fd.append("name",emp_name);
            fd.append("email",email);
            fd.append("national_id",national_id);
            fd.append("gender",gender);
            fd.append("date_of_birth",date_of_birth);
            fd.append("mobile_number",mobile_number);
            if(password !=''){
                fd.append("password",password);
            }
            
            

            if(emp_name !='' && email != '' && national_id != ''&& gender != ''&&
            date_of_birth != ''&& mobile_number != ''){

                 // AJAX request
                 $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     url: "{{url('users')}}"+'/'+id,
                     type: 'POST',
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
            var gender = $('#cgender').find(":selected").val().trim();
            var date_of_birth = $('#cdateofbirth').val().trim();
            var mobile_number= $('#cmobile').val().trim();
            var password = $('#cpassword').val().trim();
            var fd = new FormData();
            fd.append('image',image);
            fd.append("name",emp_name);
            fd.append("email",email);
            fd.append("national_id",national_id);
            fd.append("gender",gender);
            fd.append("date_of_birth",date_of_birth);
            fd.append("mobile_number",mobile_number);
            fd.append("password",password);

            if(emp_name !='' && email != '' && national_id != ''&& gender != ''&&
             password != ''&& date_of_birth != ''&& mobile_number != ''){


                 // AJAX request
                 $.ajax({
                    headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     url: "{{ route('users.store') }}",
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

            var deleteConfirm = confirm("Are you sure?");
            if (deleteConfirm == true) {
                 // AJAX request
                 $.ajax({
                    headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     url: "{{url('users')}}"+'/'+id,
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

