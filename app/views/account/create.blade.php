@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="col-md-offset-4 col-md-4">
    <div class="page-header">
        <h3>Create New Account
            <small>
                <small> (Already registered? <a href="login">Log in</a>)</small>
            </small>
        </h3>
    </div>
    @if ($errors->has())

    @foreach ($errors->all() as $error)
    {{ CommonUtilities::print_error_message($error) }}
    @endforeach

    @endif

    <form action="create" method="post" role="form">

        @if( Session::has('username_exists'))
        {{ CommonUtilities::print_error_message('The username you entered is already in use. Please select another.') }}
        @endif
        <?php
        Session::forget("username_exists");
        ?>
        <div class="form-group required"><label class="control-label">Username</label>

            <div><input class="form-control" id="username" minlength="6" maxlength="30" name="username"
                        placeholder="Username" required="required" type="text" value="{{Input::old('username') }}"/>
            </div>
        </div>
        <div class="form-group required"><label class="control-label">Password</label>

            <div><input class="form-control" id="password" minlength="6" name="password" placeholder="Password"
                        required="required" title="" type="password"/></div>
            <div><small>(Password should contain a digit[0-9], a lower case letter[a-z], an upper case letter[A-Z],
                    one of !@#$%&* characters)</small></div>
        </div>
        <div class="form-group required"><label class="control-label">Password (again)</label>

            <div><input class="form-control" id="confirm_password" name="confirm_password"
                        placeholder="Password (again)" required="required" title="" type="password"/>
            </div>
        </div>
        <div class="form-group required"><label class="control-label">E-mail</label>

            <div><input class="form-control" id="email" name="email" placeholder="email@example.com"
                        required="required" title="" type="email" value="{{Input::old('email') }}"/></div>
        </div>
        <div class="form-group required"><label class="control-label">First Name</label>

            <div><input class="form-control" id="first_name" maxlength="30" name="first_name"
                        placeholder="First Name" required="required" title="" type="text"
                        value="{{Input::old('first_name') }}"/></div>
        </div>
        <div class="form-group required"><label class="control-label">Last Name</label>

            <div><input class="form-control" id="last_name" maxlength="30" name="last_name"
                        placeholder="Last Name" required="required" title="" type="text"
                        value="{{Input::old('last_name') }}"/></div>
        </div>
<!--        <div class="form-group"><label class="control-label">Organization</label>-->
<!---->
<!--            <div><input class="form-control" id="organization" name="organization"-->
<!--                        placeholder="Organization" title="" type="text" value="{{Input::old('organization') }}"/>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="form-group"><label class="control-label">Address</label>-->
<!---->
<!--            <div><input class="form-control" id="address" name="address"-->
<!--                        placeholder="Address" title="" type="text" value="{{Input::old('address') }}"/>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="form-group"><label class="control-label">Country</label>-->
<!---->
<!--            <div><input class="form-control" id="country" name="country"-->
<!--                        placeholder="Country" title="" type="text" value="{{Input::old('country') }}"/>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="form-group"><label class="control-label">Telephone</label>-->
<!---->
<!--            <div><input class="form-control" id="telephone" name="telephone"-->
<!--                        placeholder="Telephone" title="" type="tel" value="{{Input::old('telephone') }}"/>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="form-group"><label class="control-label">Mobile</label>-->
<!---->
<!--            <div><input class="form-control" id="mobile" name="mobile"-->
<!--                        placeholder="Mobile" title="" type="tel" value="{{Input::old('mobile') }}"/>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="form-group"><label class="control-label">IM</label>-->
<!---->
<!--            <div><input class="form-control" id="im" name="im"-->
<!--                        placeholder="IM" title="" type="text" value="{{Input::old('im') }}"/>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="form-group"><label class="control-label">URL</label>-->
<!---->
<!--            <div><input class="form-control" id="url" name="url"-->
<!--                        placeholder="URL" title="" type="text" value="{{Input::old('url') }}"/>-->
<!--            </div>-->
<!--        </div>-->
        <br/>
        <input name="Submit" type="submit" class="btn btn-primary btn-block" value="Create">
    </form>

    <style media="screen" type="text/css">
        .form-group.required .control-label:after {
            content: " *";
            color: red;
        }
    </style>
    <br/><br/><br/>
</div>
</body>

@stop

