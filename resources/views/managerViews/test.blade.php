@extends('managerViews/layout')
@section('content')
    <h3>Hello</h3>
    <button id="btn">Button</button>
    <form method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    </form>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#btn').click(function(){
                $.ajax({
                    url: 'jibcd',
                    type: "post",
                    data: {'email':'zaki', '_token': $('input[name=_token]').val()},
                    success: function(response){
                        alert(response.email);
                    }
                });
            });
        });
    </script>
@endsection
