@extends('layout')

@section('content')
    <script type="text/javascript">

    $(document).ready(function (){


        // Date range picker
        $('#start_date').datepicker({
            todayBtn:'linked',
            format: "yyyy-mm-dd",
            autoclose: true,
            changeYear: true,

        });

        $('#end_date').datepicker({
            todayBtn:'linked',
            format: "yyyy-mm-dd",
            autoclose: true,
            changeYear: true,
        });

        //

       $(function (){
           $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let datatable= $('#example').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'serverSide': 'true',
            'processing': 'true',
            'paging': 'true',
            'order': [],
            'ajax': {
                'url': '{{route('admin.fetchAll')}}',
                'data' :function(d){
                    return $.extend( {}, d, {
                        "start_date": $("#start_date").val(),
                        "end_date": $("#end_date").val(),
                    });
                },
                'type': 'GET',
            },
            'columns': [
                {name: 'action', data:'action',orderable: false, searchable: false},
                {name: 'full_name', data: 'full_name', orderable:false},
                {name: 'fatherhood', data:'fatherhood'},
                {name: 'phone', data:'phone'},
                {name: 'birth_date', data:'birth_date'},
                {name: 'email', data: 'email'},
                {name: 'username', data:'username'},
                {name: 'dateEntered', data:'dateEntered'},
                {name: 'profilePicture', data:'profilePicture'},
            ]
        });
     });

        $(document).on('click','#filter-date',function(){
            $('#example').DataTable().draw();
        })

        let id;


        //Reset Password
        $(document).on('click','.resetPass',function(){
            $('#exampleModalCenter').modal('show');
            id= $(this).data('id');
            console.log(id);
        });

        $(document).on('click','.close',function(){
            $('#exampleModalCenter').modal('hide');
        });

        $(document).on('click','#save_new_pass',function(){
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                url:'{{route('user.resetPass')}}',
                type: 'GET',
                data:{
                    id:id,
                    actual_password: $('#actual_password').val(),
                    new_password: $('#new_password').val(),
                    new_password_confirm: $('#new_password_confirm').val(),

                }
            }).then(function(result){
                $('#exampleModalCenter').modal('hide');
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,

                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                Toast.fire({
                    icon: 'success',
                    title: 'Password changed successfully'
                })

            }).catch(function(result){
                if(result.status == 422){
                    let errors = result.responseJSON.errors
                    $.each(errors, function(index,value){
                        $('#'+ index).addClass('is-invalid')
                        $('#' + index).closest('.form-group').append(`<span class="invalid-feedback">${value}</span>`)
                    })
                }
            })
        })
        //End of Reset Password

        // To delete a user
        $(document).on('click','.deleteBtn', function(){
            var table= $('#example').DataTable();
            event.preventDefault();
            var el = this;
            var id= $(this).data('id');

            Swal.fire({
                title: 'Delete User?',
                text: "U can`t undo this action",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route('admin.delete')}}',
                        type: 'GET',
                        data: {
                            id:id,
                        },
                    }).then(function(data){
                      $(el).closest('tr').css('background','tomato');
                      $(el).closest('tr').fadeOut(800,function(){
                          $(this).remove();
                      });

                    })
                }
            });
        })
        //The end of delete user

        //Add users
        $(document).on('click','#add-btn',function(){
            $('#addUserModal').modal('show');
        })

        $(document).on('click','#close-user',function(e){
            e.preventDefault();
            $('#addUserModal').modal('hide');
        })

        $(document).on('click','#enter-data', function(e) {
            e.preventDefault();
            $('.is-invalid').removeClass('is-invalid')
            $('.invalid-feedback').remove()

            $.ajax({
                url: '{{route('admin.create')}}',
                type: 'GET',
                data: {
                    first_name: $("#first_nameUser").val(),
                    last_name: $("#last_nameUser").val(),
                    fatherhood: $("#fatherhoodUser").val(),
                    phone: $("#phoneUser").val(),
                    birth_date: $("#birth_dateUser").val(),
                    email: $('#emailUser').val(),
                    username: $("#usernameUser").val(),
                    password: $("#passwordUser").val(),
                    repeat_password: $("#repeat_passwordUser").val()
                }
            }).then(function(result) {
                $('#example').DataTable().draw();
                $('#addUserModal').modal('hide');
                 // unscrollable page fixed
                $(".modal-backdrop").remove();

                //
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                Toast.fire({
                    icon: 'success',
                    title: 'Created successfully'
                })
                $('body').css('overflow', 'show');
            }).catch(function (result) {
                let errors = result.responseJSON.errors
                $.each(errors, function (index, value) {
                    $('#' + index + 'User').addClass('is-invalid')
                    $('#' + index + 'User').closest('.form-group').append(`<span class="invalid-feedback">${value}</span>`)
                })
            })
        });
        //End of Add user end

        // Fetch single user
        $(document).on('click','.editbtn',function(){
            id=$(this).data('id');
            $.ajax({
                url:'{{route('user.fetch')}}',
                type: 'GET',
                data:{
                    id:id,
                }
            }).then(function(result){
                $.each(result, function(index,value){
                    $('#'+ index+'Modal').val(value);
                })
            }).catch(function(result){
                if(result.status==422){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'})
                }
            });

            $('#updateModal').modal('show');
        });

        //End of Fetch single user

        ///Update data
        $(document).on('click','#edit-user', function(){
            id=$(this).data('id');
            $('#updateModal').modal('show');
        })

        $(document).on('click','#user-btn',function(){
            $('#updateModal').modal('hide');
        })

        $(document).on('click','#update-user',function(){
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $.ajax({
                url:'{{route('user.update')}}',
                type:'GET',
                data: {
                    first_name: $('#first_nameModal').val(),
                    last_name: $('#last_nameModal').val(),
                    fatherhood: $('#fatherhoodModal').val(),
                    phone: $('#phoneModal').val(),
                    birth_date: $('#birth_dateModal').val(),
                    email: $('#emailModal').val(),
                    id: id,
                    username: $('#usernameModal').val(),
                }
            }).then(function(result){
                console.log(result);
                $('#updateModal').modal('hide');
                $('#example').DataTable().draw();
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,

                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: 'Data updated successfully'
                })
            })
            // .catch(function (result){
            //        console.log(result);
            //        if (result.status == 422) {
            //         let errors = result.responseJSON.errors
            //         $.each(errors, function (index, value) {
            //             $('#' + index + 'Modal').addClass('is-invalid')
            //           $('#' + index + 'Modal').closest('.form-group').append(`<span class="invalid-feedback">${value}</span>`)
            //        })
            //    } else if (result.status == 423) {
            //             Swal.fire({
            //             icon: 'error',
            //             title: 'Oops...',
            //             text: 'Something went wrong!'
            //         })
            //      }
            //  })
        })
        ///Update data end


});
    </script>

<div class="container-fluid">
    <h2 class="text-center">Welcome to Admin Home Page</h2>
    <p class="datatable design text-center">Welcome to Datatable</p>
    <div class="row">
        <div class="table-responsive">
            <br />
            <div class="row">
                <div class="input-daterange">
                    <div class="col-md-2">
                        <input type="text" name="start_date" id="start_date" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="end_date" id="end_date" class="form-control" />
                    </div>
                </div>
                <div class="col-md-4">
                    <input type="button" name="search" id="filter-date" value="Filter Date" class="btn btn-info" />
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="container">
            <div class="btnAdd" style="margin-bottom: 5%;">
                <a href="#!" data-bs-toggle="modal" class="btn btn-success btn-sm btn-add" id="add-btn"
                   data-bs-target="#addUserModal" style="float: right; margin-bottom:5%; padding: 12px 10px;">Add User</a>
            </div>
            <div class="row">
                <!-- <div class="col-md-2"></div> -->
                <div class="col-md-12">
                    <table id="example" class="table">
                        <thead>
                        <th>Options</th>
                        <th>Full Name</th>
                        <th>FatherHood</th>
                        <th>Phone Number</th>
                        <th>Birth Date</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Date Entered</th>
                        <th>Profile Picture</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>
</div>

    <!-- Add user Modal -->
    <div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="close-user" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUser" action="">
                        @csrf
                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label" >Your First Name</label>
                                <input type="text" id="first_nameUser" class="form-control" name="first_name">
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Your Last Name</label>
                                <input type="text" id="last_nameUser" class="form-control" name="last_name">
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label" >FatherHood</label>
                                <input type="text" id="fatherhoodUser" class="form-control" >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Phone Number</label>
                                <input type="tel" id="phoneUser" class="form-control" >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Birth Date</label>
                                <input type="date" id="birth_dateUser" class="form-control" >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Your Email</label>
                                <input type="email" id="emailUser" class="form-control" >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Username</label>
                                <input  id="usernameUser" class="form-control" name="username">
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Password</label>
                                <input type="password" id="passwordUser" class="form-control">
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Repeat your password</label>
                                <input type="password" id="repeat_passwordUser" class="form-control">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary enter-data-btn" name="enter-data" id="enter-data">Enter Data</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add user modal end -->

@endsection