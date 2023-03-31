@extends('layouts.app')

@section('content')
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Seller</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="hid" name="hid">
                    <div class="row">
                        <div class="form-group col-md-12">
                        <label for="rate">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                        <label for="rate">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                        <label for="rate">Status</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                        <label for="vehicle1"> Active</label><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
                        <label for="vehicle2"> Inactive</label><br>
                        </div>
                    </div>

                </form>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success submit" id="submit">Save changes</button>
          </div>
      </div>
    </div>
</div>
{{-- prodct modal --}}
<div class="modal fade" id="modal_product">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered" id="tbl_product">
                                <thead>
                                    <tr>
                                        <th style="width:10%">ID</th>
                                        <th style="width:40%">Name</th>
                                        <th style="width:40%">Email</th>
                                        <th style="width:40%">Price</th>
                                        <th style="width:40%">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
</div>




<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h1 class="m-0 text-dark"></h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item active">Seller</a></li>
            </ol>
          </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Seller</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="tbl_seller">
                        <thead>
                            <tr>
                                <th style="width:10%">ID</th>
                                <th style="width:20%">Name</th>
                                <th style="width:20%">Email</th>
                                <th style="width:20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function(){

        // menu active
        $(".role_route").addClass('active');
        $(".user_tree").addClass('active');
        $(".user_tree_open").addClass('menu-open');
        $(".user_tree_open").addClass('menu-is-opening');


        //csrf token error
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        show_seller();

        $(document).on("blur",".form-control",function(){
            $("#submit").css("display","block");
        });


        //product list add
        $(".addNew").click(function(){
            empty_form();
            $("#modal").modal('show');
            $(".modal-title").html('Save Seller');
            $("#submit").html('Save seller');
            $("#submit").click(function(){
                $("#submit").css("display","none");
                var hid = $("#hid").val();
                //save data
                if(hid == ""){
                    var name =$("#name").val();
                    var email =$("#email").val();

                    $.ajax({
                    'type': 'ajax',
                    'dataType': 'json',
                    'method': 'post',
                    'data' : {name:name,email:email},
                    'url' : 'seller',
                    'async': false,
                    success:function(data){
                        if(data.validation_error){
                        validation_error(data.validation_error);//if has validation error call this function
                        }

                        if(data.db_error){
                        db_error(data.db_error);
                        }

                        if(data.db_success){
                            toastr.success(data.db_success);
                        setTimeout(function(){
                            $("#modal").modal('hide');
                            location.reload();
                        }, 2000);
                        }

                    },
                    error: function(jqXHR, exception) {
                        db_error(jqXHR.responseText);
                    }
                    });
                };
            });
        });

                //product list add
        $(".addNew").click(function(){
            empty_form();
            $("#modal").modal('show');
            $(".modal-title").html('Save Product List');
            $("#submit").html('Save List');
            $("#submit").click(function(){
                $("#submit").css("display","none");
                var hid = $("#hid").val();
                //save data
                if(hid == ""){
                    var product_name =$("#product_name").val();
                    var quntity =$("#quntity").val();
                    var price =$("#price").val();
                    var description =$("#description").val();

                    $.ajax({
                    'type': 'ajax',
                    'dataType': 'json',
                    'method': 'post',
                    'data' : {product_name:product_name,quntity:quntity,price:price,description:description},
                    'url' : 'product_list',
                    'async': false,
                    success:function(data){
                        if(data.validation_error){
                        validation_error(data.validation_error);//if has validation error call this function
                        }

                        if(data.db_error){
                        db_error(data.db_error);
                        }

                        if(data.db_success){
                            toastr.success(data.db_success);
                        setTimeout(function(){
                            $("#modal").modal('hide');
                            location.reload();
                        }, 2000);
                        }

                    },
                    error: function(jqXHR, exception) {
                        db_error(jqXHR.responseText);
                    }
                    });
                };
            });
        });

        //product list edit
        $(document).on("click", ".edit", function(){

            var id = $(this).attr('data');

            empty_form();
            $("#hid").val(id);
            $("#modal").modal('show');
            $(".modal-title").html('Edit Seller List');
            $("#submit").html('Update seller');

            $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'get',
                'url': 'seller/'+id,
                'async': false,
                success: function(data){

                    $("#hid").val(data.id);
                    $("#name").val(data.name);
                    $("#email").val(data.email);

                }

            });

            $("#submit").click(function(){

                if($("#hid").val() != ""){

                    var id = $("#hid").val();
                    var name = $("#name").val();
                    var email = $("#email").val();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Update it!'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                $.ajax({
                                    'type': 'ajax',
                                    'dataType': 'json',
                                    'method': 'put',
                                    'data' : {name:name,email:email},
                                    'url': 'seller/'+id,
                                    'async': false,
                                    success:function(data){
                                    if(data.validation_error){
                                        validation_error(data.validation_error);//if has validation error call this function
                                        }

                                        if(data.db_error){
                                        db_error(data.db_error);
                                        }

                                        if(data.db_success){
                                            toastr.success(data.db_success);
                                        setTimeout(function(){
                                            $("#modal").modal('hide');
                                            location.reload();
                                        }, 1000);
                                        }
                                    },
                                });
                            }
                    });
                }
            });
        });

        //delete product
        $(document).on("click", ".delete", function(){
            var id = $(this).attr('data');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            'type': 'ajax',
                            'dataType': 'json',
                            'method': 'delete',
                            'url': 'seller/'+id,
                            'async': false,
                            success: function(data){

                            if(data){
                                toastr.success('Product Deleted');
                                setTimeout(function(){
                                location.reload();
                                }, 1000);

                            }

                            }
                        });

                    }

            });

        });

        $(document).on("click", ".product_details", function(){
            var id = $(this).attr('data');
            show_product(id);
            $("#modal_product").modal('show');
        });
    });

    //Data Table show
    function show_seller(){
            $('#tbl_seller').DataTable().clear();
            $('#tbl_seller').DataTable().destroy();

            $("#tbl_seller").DataTable({
                'processing': true,
                'serverSide': true,
                "bLengthChange": false,
                'ajax': {
                            'method': 'get',
                            'url': 'seller/create'
                },
                'columns': [
                    {data: 'id'},
                    {data: 'email'},
                    {data: 'name'},

                    {
                    data: null,
                    render: function(d){
                        var html = "";
                        html+="<td><button class='btn btn-warning btn-sm edit' data='"+d.id+"' title='Edit'><i class='fas fa-edit'></i></button>";
                        html+="&nbsp;<button class='btn btn-danger btn-sm delete' data='"+d.id+"' title='Delete'><i class='fas fa-trash'></i></button>";
                        html+="&nbsp;<button class='btn btn-primary btn-sm product_details' data='"+d.id+"' title='Delete'><i class='fas fa-arrow-alt-circle-left'></i></button>";
                        return html;

                    }

                    }
                ]
            });
    }

        //Data Table show
        function show_product(id){
            $('#tbl_product').DataTable().clear();
            $('#tbl_product').DataTable().destroy();

            $("#tbl_product").DataTable({
                'processing': true,
                'serverSide': true,
                "bLengthChange": false,
                'ajax': {
                            'method': 'get',
                            'url': 'product_list/seller/'+id,
                },
                'columns': [
                    {data: 'id'},
                    {data: 'product_name'},
                    {data: 'quntity'},
                    {data: 'price'},
                    {data: 'desciption'},
                ]
            });
    }

    function empty_form(){
        $("#hid").val("");
        $("#name").val("");
        $("#email").val("");
    }

    function validation_error(error){
        for(var i=0;i< error.length;i++){
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error[i],
            });
        }
    }

    function db_error(error){
        Swal.fire({
            icon: 'error',
            title: 'Database Error',
            text: error,
        });
    }

    function db_success(message){
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
        });
    }
</script>
@endsection
