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
<<<<<<< HEAD
                    url: 'jib',
=======
                    url: 'http://localhost/kpi/public/jib',
>>>>>>> b633c8695344656922fe81b967d9f8e8451640b8
                    type: "post",
                    data: {'email':'zaki', '_token': $('input[name=_token]').val()},
                    success: function(response){
                        console.log(response.email);
                    }
                });
            });
        });
    </script>
@endsection
