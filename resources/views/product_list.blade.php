@extends('layouts.app')

@section('content')
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="hid" name="hid">

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="designation_id">Designation</label>
                            <select name="seller_id" id="seller_id" class="form-control selectpicker" required data-live-search="true" data-size="5">
                              <option value="">-- select designation --</option>
                              @foreach($result as $results)
                                  <option value="{{ $results->id }}">{{ $results->name }}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                        <label for="rate">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                        <label for="rate">Availabale Quntity</label>
                        <input type="text" class="form-control" id="quntity" name="quntity" placeholder="Enter Email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                        <label for="rate">Price</label>
                        <input type="text" class="form-control" id="price" name="price" placeholder="Enter Price" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                        <label for="rate">Description</label>
                        <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter Email" required></textarea>
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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h1 class="m-0 text-dark"></h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item active">Product List</a></li>
            </ol>
          </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary addNew"><i class="fa fa-plus"></i> Add New Product</button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="tbl_product">
                        <thead>
                            <tr>
                                <th style="width:10%">ID</th>
                                <th style="width:20%">Product Name</th>
                                <th style="width:20%">Quntity</th>
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

        show_product();

        $(document).on("blur",".form-control",function(){
            $("#submit").css("display","block");
        });
        //product list add
        $(".addNew").click(function(){
            empty_form();
            $("#modal").modal('show');
            $(".modal-title").html('Save Product List');
            $("#submit").html('Save List');
            $("#submit").click(function(){
                $("#submit").css("display","none");seller_id
                var hid = $("#hid").val();
                //save data
                if(hid == ""){
                    var seller_id =$("#seller_id").val();
                    var product_name =$("#product_name").val();
                    var quntity =$("#quntity").val();
                    var price =$("#price").val();
                    var description =$("#description").val();

                    $.ajax({
                    'type': 'ajax',
                    'dataType': 'json',
                    'method': 'post',
                    'data' : {product_name:product_name,quntity:quntity,price:price,description:description,seller_id:seller_id},
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
            $(".modal-title").html('Edit Product List');
            $("#submit").html('Update List');

            $.ajax({
                'type': 'ajax',
                'dataType': 'json',
                'method': 'get',
                'url': 'product_list/'+id,
                'async': false,
                success: function(data){

                    $("#hid").val(data.id);
                    $("#product_name").val(data.product_name);
                    $("#quntity").val(data.quntity);
                    $("#price").val(data.price);
                    $("#description").val(data.desciption);


                }

            });

            $("#submit").click(function(){

                if($("#hid").val() != ""){

                    var id = $("#hid").val();
                    var product_name = $("#product_name").val();
                    var quntity = $("#quntity").val();
                    var price = $("#price").val();
                    var description = $("#description").val();

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
                                    'data' : {product_name:product_name,quntity:quntity,price:price,description:description},
                                    'url': 'product_list/'+id,
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
                            'url': 'product_list/'+id,
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
    });

    //Data Table show
    function show_product(){
            $('#tbl_product').DataTable().clear();
            $('#tbl_product').DataTable().destroy();

            $("#tbl_product").DataTable({
                'processing': true,
                'serverSide': true,
                "bLengthChange": false,
                'ajax': {
                            'method': 'get',
                            'url': 'product_list/create'
                },
                'columns': [
                    {data: 'id'},
                    {data: 'product_name'},
                    {data: 'quntity'},

                    {
                    data: null,
                    render: function(d){
                        var html = "";
                        html+="<td><button class='btn btn-warning btn-sm edit' data='"+d.id+"' title='Edit'><i class='fas fa-edit'></i></button>";
                        html+="&nbsp;<button class='btn btn-danger btn-sm delete' data='"+d.id+"' title='Delete'><i class='fas fa-trash'></i></button>";
                        return html;

                    }

                    }
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
