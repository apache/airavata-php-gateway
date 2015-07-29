@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="well">
    <div class="container">

        <?php

        if (CommonUtilities::id_in_session()) {
            $columnClass = 'col-md-4';
            if (Session::has("admin"))
                $admin = " Admin";
            else
                $admin = "";

            echo '<h4>Welcome' . $admin . ', ' . Session::get("username") . '!</h4>';
        } else {
            $columnClass = 'col-md-6';

            echo '
                <h1>PHP Gateway with Airavata</h1>
                <p>
                    PGA is a science gateway built with the Airavata API. You can reference PGA as you integrate Airavata
                    into your own gateway, or you can create your gateway on top of PGA by cloning it at the link below.
                    PGA is known to work well in the Chrome, Firefox, and Internet Explorer browsers.
                </p>
                <p><a href="https://github.com/apache/airavata-php-gateway"
                        target="_blank">See the code <span class="glyphicon glyphicon-new-window"></span></a></p>
                <p><a href="https://cwiki.apache.org/confluence/display/AIRAVATA/XSEDE+2015+Tutorial"
                    target="_blank">XSEDE 2015 tutorial documentation <span class="glyphicon glyphicon-new-window"></span></a></p>
            ';
        }
        ?>
    </div>
</div>

<div class="container">

    <div class="row">
        <?php

        if (CommonUtilities::id_in_session()) {
            echo '
                <div class="col-md-4">
                    <h2>PHP Gateway with Airavata</h2>
                    <p>
                        PGA is a science gateway built with the Airavata API. You can reference PGA as you integrate
                        Airavata into your own gateway, or you can create your gateway on top of PGA by cloning it at
                        the link below. PGA is known to work well in the Chrome, Firefox, and Internet Explorer browsers.
                    </p>
                    <p><a href="https://github.com/apache/airavata-php-gateway/"
                        target="_blank">See the code <span class="glyphicon glyphicon-new-window"></span></a></p>
                    <p><a href="https://cwiki.apache.org/confluence/display/AIRAVATA/XSEDE+2015+Tutorial"
                        target="_blank">View the XSEDE 2015 tutorial documentation <span class="glyphicon glyphicon-new-window"></span></a></p>
                </div>
            ';
        }

        ?>
        <div class="<?php echo $columnClass; ?>">
            <div class="thumbnail" style="border:none">
                <img src="assets/scigap-header-logo.png" alt="SciGaP">

                <div class="caption">
                    <p>
                        SciGaP is a hosted service with a public API that science gateways can use to manage
                        applications and workflows running on remote supercomputers, as well as other services. Gateway
                        developers can thus concentrate their efforts on building their scientific communities and not
                        worry about operations.
                    </p>

                    <p>
                        Science Gateway Platform as a Service (SciGaP) provides application programmer interfaces (APIs)
                        to hosted generic infrastructure services that can be used by domain science communities to
                        create Science Gateways.
                    </p>

                    <p><a href="http://scigap.org/"
                          target="_blank">Learn more <span class="glyphicon glyphicon-new-window"></span></a></p>
                </div>
            </div>
        </div>
        <div class="<?php echo $columnClass; ?>">
            <div class="thumbnail" style="border:none">
                <img src="assets/PoweredbyAiravata_Small.png" alt="Apache Airavata">

                <div class="caption">
                    <p>
                        Apache Airavata is a software framework which is dominantly used to build Web-based science
                        gateways and assist to compose, manage, execute and monitor large scale applications and
                        workflows on distributed computing resources such as local clusters, supercomputers, national
                        grids, academic and commercial clouds. Airavata mainly supports long running applications and
                        workflows on distributed computational resources.
                    </p>

                    <p><a href="http://airavata.apache.org/" target="_blank">Learn more <span
                                class="glyphicon glyphicon-new-window"></span></a></p>
                </div>
            </div>
        </div>
    </div>

</div>

@stop

