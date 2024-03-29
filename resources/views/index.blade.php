@extends('layoutLogin')

@section('content')
    <!-- Main Form -->
    @if(Session::has('error'))
        <script> alert('Incorrect email or password ')</script>
    @endif
    <div class="login-form-1">
        <form id="login-form" class="text-left" action="{{ route('dashboard') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
            <div class="login-form-main-message"></div>
            <div class="main-login-form">
                <div class="login-group">
                    <div class="form-group">
                        <label for="lg_username" class="sr-only">HP email</label>
                        <input type="text" class="form-control" id="lg_username" name="lg_username" placeholder="username">
                    </div>
                    <div class="form-group">
                        <label for="lg_password" class="sr-only">Password</label>
                        <input type="password" class="form-control" id="lg_password" name="lg_password" placeholder="password">
                    </div>
                    <div class="form-group login-group-checkbox">
                        <input type="checkbox" id="lg_remember" name="lg_remember">
                        <label for="lg_remember">remember</label>
                    </div>
                </div>
                <button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="etc-login-form">
                <p>forgot your password? <a href="{{ route('forgot') }}">click here</a></p>
            </div>
        </form>
    </div>
    <!-- end:Main Form -->
@endsection
