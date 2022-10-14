<!DOCTYPE html>
<html>
<head>
    <title>PayWind </title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.5/datatables.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Raleway:300,400,600);

        body{
            margin: 0;
            font-size: .9rem;
            font-weight: 400;
            line-height: 1.6;
            color: #212529;
            text-align: left;
            background-color: #f5f8fa;
        }
        .navbar-laravel
        {
            box-shadow: 0 2px 4px rgba(0,0,0,.04);
        }
        .navbar-brand , .nav-link, .my-form, .login-form
        {
            font-family: Raleway, sans-serif;
        }
        .my-form
        {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .my-form .row
        {
            margin-left: 0;
            margin-right: 0;
        }
        .login-form
        {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .login-form .row
        {
            margin-left: 0;
            margin-right: 0;
        }


        /*.btnAdd {*/
        /* text-align: right;*/
        /* width: 83%;*/
        /* margin-bottom: 20px;*/
        /*}*/

    </style>

    <script>
        $(document).ready(function(){
        //username generating
        function createUsername()  {
            let fname = $('#first_name').val().substring(0,1).toLowerCase();
            let lname = $('#last_name').val().toLowerCase();
            $('#username').val(fname + lname);
        }

         $('#first_name').on('keyup', createUsername);
         $('#last_name').on('keyup', createUsername);
        ////




            $(document).on('click','#log-out',function(){
                Swal.fire({
                    title: 'Log out?',
                    text: "U will be redircted to log in page",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, log out!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('logout') }}',
                            type: 'GET',
                            data: {},
                        }).then(function(result){
                            window.location= '{{ route('login') }}';
                        })
                    }
                });

            })

     });
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="#">PayWind</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    @if(Auth::user()->role=='admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.logged') }}" id="home">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.payment') }}" id="payment">Payment</a>
                        </li>
                            @endif
                            <li class="nav-item align-right" >
                                <a class="nav-link" href="#" id="log-out" >Logout</a>
                            </li>

                @endguest
            </ul>

        </div>
    </div>
</nav>

@yield('content')


<!-- Modal to update data -->
<div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Update User</h5>
                <button type="button" id="user-btn" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="update-form" action="">
                    @csrf
                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-label">Your First Name</label>
                            <input type="text" id="first_nameModal" class="form-control" name="first_nameModal">
                        </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-label">Your Last Name</label>
                            <input type="text" id="last_nameModal" class="form-control" name="last_nameModal">
                        </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-label">FatherHood</label>
                            <input type="text" id="fatherhoodModal" class="form-control" name="fatherhoodModal">
                        </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" id="phoneModal" class="form-control" name="phoneModal">
                        </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-label">Birth Date</label>
                            <input type="date" id="birth_dateModal" class="form-control" name="birth_dateModal">
                        </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-label">Your Email</label>
                            <input type="email" id="emailModal" class="form-control" name="emailModal">
                        </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-label">Username</label>
                            <input id="usernameModal" class="form-control" name="usernameModal" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="update-user">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- End of modal update user -->

<!--Reset Password Modal -->
<main class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
      aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Reset Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="password-form" action="{{route('user.resetPass')}}" method="get">
                @csrf
                <div class="modal-body">
                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-reset">Actual Password</label>
                            <input type="password" id="actual_password" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-reset">New Password</label>
                            <input type="password" id="new_password" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex flex-row align-items-center mb-4">
                        <div class="form-outline flex-fill mb-0 form-group">
                            <label class="form-reset">Confirm New Password</label>
                            <input type="password" id="new_password_confirm" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="save_new_pass">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.5/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

</body>
</html>
