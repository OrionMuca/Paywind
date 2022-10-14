@extends('layout')

@section('content')

    <script type="text/javascript">

        $(document).ready(function (){

        var id={{$user->id}};

        function display_user(){
            $.ajax({
                url:'{{route('user.fetch')}}',
                type: 'GET',
                data:{
                    id:id,
                }
            }).then(function(result){
                $.each(result, function(index,value){
                    $('#'+ index).val(value);
                    $('#'+ index+'Modal').val(value);
                })
            }).catch(function(result){//per extra errors
                if(result.status==422){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'})
                }
            });
        }

        display_user();

        // reset password
        $(document).on('click','#reset-password',function(){
            $('#exampleModalCenter').modal('show');
        })

        $(document).on('click','.close',function(){
            $('#exampleModalCenter').modal('hide');
        })

        $(document).on('click','#save_new_pass',function(){
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $.ajax({
                url:'{{route('user.resetPass')}}',
                type: 'GET',
                data:{
                    actual_password: $('#actual_password').val(),
                    new_password: $('#new_password').val(),
                    new_password_confirm: $('#new_password_confirm').val(),
                    id: {{$user->id}}
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
                console.log(result);
                if(result.status == 422){
                    let errors = result.responseJSON.errors
                    $.each(errors, function(index,value){
                        $('#'+ index).addClass('is-invalid')
                        $('#' + index).closest('.form-group').append(`<span class="invalid-feedback">${value}</span>`)
                    })
                }
            })
        })
        //// Password reset end

            ///Update data
            $(document).on('click','#edit-user', function(){;
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
                        id: {{$user->id}},
                        username: $('#usernameModal').val(),
                    }
                }).then(function(result){
                    $('#updateModal').modal('hide');
                    display_user();
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
                }).catch(function(result){
                    console.log(result);
                    if(result.status == 422){
                        let errors = result.responseJSON.errors
                        $.each(errors, function(index,value){
                            $('#'+ index+'Modal').addClass('is-invalid')
                            $('#' + index+'Modal').closest('.form-group').append(`<span class="invalid-feedback">${value}</span>`)
                        })
                    }else if(result.status== 423){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'})
                    }
                })
            })
            ///Update data end


            //Image update
            // $(function (){
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
            $('#but_upload').click( function (e){
                e.preventDefault();
                var files = $('#profile_pic')[0].files;
                let fd= new FormData();
                fd.append('profile_pic',files[0]);
                console.log(fd)
                $.ajax({
                    type: 'GET',
                    url: '{{route('user.image')}}',
                    data: {
                        profile_pic: fd,
                    },
                    contentType: false,
                    processData: false,
                    success: function (response){
                        console.log(response);
                    }
                })
            })
       // });
            //Image update end


 });
    </script>

<main class="user-modal">
    <div class="container">
        <div class="row">
           <div class="col-md-4">
               <form action="" method="" enctype="multipart/form-data" style="margin-left: 15%;" id="image-form">
                   @csrf
                   <div class='preview'>
                       <img src="/../uploads/avatars/{{ $user->profile_pic }}" id="img" width="200px" height="200px"
                            style="border-radius:50%; margin-right: 25px; margin-top: 8px; margin-bottom:8px; border: 2px solid darkgrey" >
                   </div>
                   <div>
                       <input type="file" id="profile_pic" name="profile_pic" >
                       <button type="submit" class="btn btn-info" id="but_upload">Upload</button>
                   </div>
               </form>
               @if(count($errors) > 0)
                   @foreach($errors->all() as $error)
                       <div class="alert alert-danger">{{ $error }}</div>
                   @endforeach
               @endif
           </div>
            <div class="col-md-8">
                <div class="user-data">
                    <form class="user-form" action="" method="post">
                        @csrf
                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label" >Your First Name</label>
                                <input type="text" id="first_name" class="form-control" name="first_name" disabled >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Your Last Name</label>
                                <input type="text" id="last_name" class="form-control" name="last_name" disabled >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label" >FatherHood</label>
                                <input type="text" id="fatherhood" class="form-control" disabled >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Phone Number</label>
                                <input type="tel" id="phone" class="form-control" disabled >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Birth Date</label>
                                <input type="date" id="birth_date" class="form-control" disabled >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Your Email</label>
                                <input type="email" id="email" class="form-control" disabled >
                            </div>
                        </div>

                        <div class="d-flex flex-row align-items-center mb-4">
                            <div class="form-outline flex-fill mb-0 form-group" >
                                <label class="form-label">Username</label>
                                <input  id="username" class="form-control" name="username" disabled >
                            </div>
                        </div>

                    </form>

                    <div class="d-flex justify-content-left mx-4 mb-3 mb-lg-4">
                        <button type="button" class="btn btn-primary btn-lg" id="edit-user">Edit</button>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-info"  id="reset-password" style="margin-left: 85%;">Reset Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>



@endsection
