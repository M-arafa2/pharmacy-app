
@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"/>
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
  
@stop

@section('content')
<body >
    <p>Prescriptions</p>
    <div id="prescriptions" style="display: flex;" class="justify-content-center">
    </div>
    <!-- order data disabled form + (table+meds form)  -->
   <div style="display:flex" class=" mt-5">
        
        <form >
            <div class="form-group ">
                <label for="name" >Ordered User</label>
                <input disabled type="text" class="form-control" id="emp_name" placeholder="Enter Employee name" required> 
            </div>
                      
            <div class="form-group">
                <label for="insured" >insured</label> 
                <input type="text" class="form-control" id="insured" value ="e" disabled> 
            </div> 
            <div class="form-group">
                <label for="gender" >Address</label>
                <p id="address"></p>
            </div> 
            <div class="form-group">
                <label for="status" >Status</label>
                <input id="status" type="text" class="form-control" disabled />
            </div>
                      
        </form>
        <div>
            <!-- meds table -->      
            <table style="height:fit-content;width:60%;"class="table table-dark table-hover  mx-5" id='meds'> 
                <thead>
                    <tr>
                        <th>medicine name</th>
                        <th>type</th>
                        <th>quantity</th>
                        <th>price</th>
                    </tr>
                </thead>                  
                <tbody id = "databody">
                </tbody> 
            </table>
            <!-- meds form -->
            <div style="width:50%;display:none;" id="hide" class="justify-content-center mx-5"> 
                <div style="width:40%;display: flex;"> <!-- form labels -->
                    <div class="form-group" style="width: 80%;">
                        <label for="medicine">Medicine:</label>
                        <input type="text" name="medicine" id="medicine" >

                    </div>
                    <div class="form-group" style="width: 70%;">
                        <label for="type">Type:</label>
                        <input id = "type" type="text" name="type" >
                    </div>
                    <div class="form-group" style="width: 50%;">
                        <label for="quantity" >Quantity:</label>
                        <input id="quantity" type="number" name="quantity">
                                    
                    </div>
                    <div class="form-group" style="width: 40%;">
                        <label for="price">price:</label>
                        <input id="price" type="number" name="price">
                    </div>

                </div><!-- End form labels -->
                
                    <button class="btn btn-dark btn-lg btn-block mx-5" type="submit" id="addrow">Add</button>
            </div>  <!-- form  -->
        </div>     
            
            
    </div>
    <button class="btn btn-dark btn-lg btn-block mt-2 mb-5" id="btn_save" >Save</button>
                    
</body>
    
@stop

@section('css')
  
    
@stop

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
   <script type="text/javascript">
   // CSRF Token
   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'); 
   const lurl = window.location.href;
   let parts = lurl.split("/")
   let id = parts[parts.length-1];
 


   $(document).ready(function(){

    $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{url('orders')}}"+'/'+id,
                type: 'GET',
                data: {id: id},
                dataType: 'json',
                success: function(response){

                    if(response.success == 1){
                        const row = document.getElementById('prescriptions');
                        const databody = document.getElementById('databody');
                        const hide = document.getElementById('hide');
                        row.innerHTML='';
                        let images = response.images;
                        for(let i =0;i<images.length;i++){
                        row.insertAdjacentHTML('afterbegin', '<a href="/'+images[i]+'"><img style="width:300px;" src="/'+images[i]+'" ></a>')}
                         $('#emp_name').val(response.username);
                         $('#insured').val(response.is_insured);
                         $('#status').val(response.status);
                         $('#address').text(response.address);
                         let datarows = response.meds;
                        for(let i =0;i<datarows.length;i++){
                         let datarow ='<tr><td>'+datarows[i].name+
                         '</td><td>'+datarows[i].type+
                         '</td><td>'+datarows[i].quantity+
                         '</td><td>'+datarows[i].price+'</td></tr>' 
                         $('#meds tbody').append(datarow);
                         //databody.insertAdjacentElement('afterend',datarow);
                        }

                         if(response.status == "New"){
                            hide.style.display="";

                         }
                    }else{
                         alert("Invalid ID.");
                    }
                }
            });
   
     
       //add order row
       $('#addrow').click(function(event) {
            var medicine = $('#medicine').val().trim();
            var quantity = $('#quantity').val().trim();
            var type = $('#type').val().trim();
            var price = $('#price').val().trim();
        

            $.ajax({
            url: "{{ route('medicines.store') }}",
            type: 'POST',
            data: {_token: CSRF_TOKEN,id: id,medicine:medicine,quantity:quantity,type:type,price:price},
            dataType: 'json',
            success: function(response){
                if(response.success == 1){
                    let respdatarow ='<tr><td>'+response.medicine+
                         '</td><td>'+response.type+
                         '</td><td>'+response.quantity+
                         '</td><td>'+response.price+'</td></tr>' 
                    $('#meds tbody').append(respdatarow);
                    $('#meds').ajax.reload();
                    alert(response['msg']);
                   
                }else{
                    alert("Invalid Data.");
                    
                }
                }
           
            });
        });

       // Save user 
       $('#btn_save').click(function(){

                 // AJAX request
                 $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                     url: "{{url('orders')}}"+'/'+id,
                     type: 'POST',
                     data: {'_method': 'patch',id: id},
                     dataType: 'json',
                     success: function(response){
                         if(response.success == 1){
                              window.location.href='/orders'

                         }else{
                              window.location.href='/orders'
                         }
                     }
                 });

            
       });
    });
       </script>


@stop



      