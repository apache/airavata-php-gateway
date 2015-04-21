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

                <div class="row">
                    <div class="tree">
                        <ul>
                            <li>
                                <span><i class="icon-calendar"></i>Experiment 1</span>
                                <ul>
                                    <li>
                                        <span class="badge badge-success"><i class="icon-minus-sign"></i>Pre Processing</span>
                                        <ul>
                                            <li>
                                                <a href=""><span class="alert alert-success"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;  PGA to Airavata Authentication Successful</a>
                                            </li>
                                            <li>
                                                <a href=""><span class="alert alert-success"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;  Airavata to Resource Authentication Successful</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span class="badge badge-success"><i class="icon-minus-sign"></i>Input Staging</span>
                                        <ul>
                                            <li>
                                                <span  class="alert alert-success"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash; <a href="">PGA to Airavata File Transfer Successful</a>
                                            </li>
                                            <li>
                                                <span  class="alert alert-success"abhi ><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash; <a href="">Airavata to Resource File Transfer Successful</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span class="badge badge-warning"><i class="icon-minus-sign"></i>Job Description</span>
                                        <ul>
                                            <li>
                                                <a href=""><span>
                                                   Long Script of Job Description / PBS Script <br/>
                                                   <br/>
                                                    <p>
                                                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean
                                                    </p>
                                                 </span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <span class="badge badge-important"><i class="icon-minus-sign"></i>Execution</span>
                                        <ul>
                                            <li>
                                                <a href=""><span  class="alert alert-success"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash; Execution of Job Description - No errors</a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li>
                                        <span class="badge badge-important"><i class="icon-minus-sign"></i>Experiment Complete</span>
                                        <ul>
                                            <li>
                                                <a href=""><span  class="alert alert-danger"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash; Output Transfer from Resource to Airavata UnSuccessful</a>
                                                <br/>
                                                <span> Some text about failure</span>
                                            </li>
                                            <li>
                                                <a href=""><span  class="alert alert-danger"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash; Output Transfer from Airavata to PGA UnSuccessful</a>
                                                <br/>
                                                <span> Some text about failure</span>
                                            </li>
                                        </ul>
                                    </li>


                                </ul>
                            </li>
                        </ul>
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

        $(function () {
            $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
            $('.tree li.parent_li > span').on('click', function (e) {
                var children = $(this).parent('li.parent_li').find(' > ul > li');
                if (children.is(":visible")) {
                    children.hide('fast');
                    $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
                } else {
                    children.show('fast');
                    $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
                }
                e.stopPropagation();
            });
        });
    </script>
@stop