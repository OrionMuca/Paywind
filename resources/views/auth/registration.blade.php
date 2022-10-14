@extends('layout')


@section('content')
    <main class="login-form">
        <div class="cotainer">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Register</div>
                        <div class="card-body">

                            <form action="{{ route('register.post') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="first_name" class="form-control" name="first_name" required autofocus value="{{old('first_name')}}">
                                        @if ($errors->has('first_name'))
                                            <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="last_name" class="form-control" name="last_name" required autofocus value="{{old('last_name')}}">
                                        @if ($errors->has('last_name'))
                                            <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="fatherhood" class="col-md-4 col-form-label text-md-right">Fatherhood</label>
                                    <div class="col-md-6">
                                        <input type="text" id="fatherhood" class="form-control" name="fatherhood" required autofocus value="{{old('fatherhood')}}">
                                        @if ($errors->has('fatherhood'))
                                            <span class="text-danger">{{ $errors->first('fatherhood') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="phone" class="col-md-4 col-form-label text-md-right">Phone</label>
                                    <div class="col-md-6">
                                        <input type="text" id="phone" class="form-control" name="phone" required autofocus value="{{old('phone')}}">
                                        @if ($errors->has('phone'))
                                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="birth_date" class="col-md-4 col-form-label text-md-right">Birth Date</label>
                                    <div class="col-md-6">
                                        <input type="date" id="birth_date" class="form-control" name="birth_date" required autofocus value="{{old('birth_date')}}">
                                        @if ($errors->has('birth_date'))
                                            <span class="text-danger">{{ $errors->first('birth_date') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input type="text" id="email_address" class="form-control" name="email" required autofocus value="{{old('email')}}">
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>
                                    <div class="col-md-6">
                                        <input type="text" id="username" class="form-control" name="username" required autofocus value="{{old('username')}}">
                                        @if ($errors->has('username'))
                                            <span class="text-danger">{{ $errors->first('username') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" id="password" class="form-control" name="password" required>
                                        @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="repeat_password" class="col-md-4 col-form-label text-md-right">Repeat Password</label>
                                    <div class="col-md-6">
                                        <input type="password" id="repeat_password" class="form-control" name="repeat_password" required>
                                        @if ($errors->has('repeat_password'))
                                            <span class="text-danger">{{ $errors->first('repeat_password') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember"> Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary" id="register" style="margin-top:4%">
                                        Register
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
