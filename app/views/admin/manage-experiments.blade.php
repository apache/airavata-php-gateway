@extends('layout.basic')

@section('page-header')
    @parent
    {{ HTML::style('css/admin.css')}}
@stop

@section('content')

    <div id="wrapper">
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            @include( 'partials/dashboard-block')
        <div id="page-wrapper">
            <div class="col-md-12">
                <h3>Experiments</h3>
            </div>
            <div class="container-fluid">

                <div class="row">


                    <div class="well col-md-2 text-center">
                        Total 500
                    </div>

                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>

@stop


@section('scripts')
    @parent
    {{ HTML::script('js/gateway.js') }}
    <script>

        //make first tab of accordion open by default.
        //temporary fix
        $("#accordion2").children(".panel").children(".collapse").addClass("in");
        $(".add-tenant").slideUp();
        
        $(".toggle-add-tenant").click( function(){
            $(".add-tenant").slideDown();
        });
    </script>
@stop