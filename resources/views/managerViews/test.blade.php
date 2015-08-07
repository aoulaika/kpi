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
                    url: 'jib',
                    type: "post",
                    data: {'email':'zaki', '_token': $('input[name=_token]').val()},
                    success: function(_response){
                        alert(_response.return);
                    }
                });
            });
        });
    </script>
@endsection
