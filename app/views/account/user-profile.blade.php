
@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/user-settings.css')}}
@stop

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}/account/settings">User Settings</a></li>
        <li class="active">Your Profile</li>
    </ol>
    
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1>Profile for {{ Session::get("username") }}</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
    <form action="{{ URL::to("account/user-profile") }}" method="post" role="form">

        <div class="form-group">
            <label class="control-label">E-mail</label>
            <p class="form-control-static">{{{ $emailAddress }}}</p>
        </div>
        <div class="form-group required">
            <label class="control-label">First Name</label>
            <div><input class="form-control" id="first_name" maxlength="30" name="first_name"
                        placeholder="First Name" required="required" title="" type="text"
                        value="{{Input::old('first_name') }}"/></div>
        </div>
        <div class="form-group required">
            <label class="control-label">Last Name</label>
            <div><input class="form-control" id="last_name" maxlength="30" name="last_name"
                        placeholder="Last Name" required="required" title="" type="text"
                        value="{{Input::old('last_name') }}"/></div>
        </div>
        <div class="form-group">
            <label class="control-label">Organization</label>
            <div><input class="form-control" id="organization" name="organization"
                        placeholder="Organization" title="" type="text" value="{{Input::old('organization') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Address</label>
            <div><input class="form-control" id="address" name="address"
                        placeholder="Address" title="" type="text" value="{{Input::old('address') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Country</label>
            <div><input class="form-control" id="country" name="country"
                        placeholder="Country" title="" type="text" value="{{Input::old('country') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Telephone</label>
            <div><input class="form-control" id="telephone" name="telephone"
                        placeholder="Telephone" title="" type="tel" value="{{Input::old('telephone') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Mobile</label>
            <div><input class="form-control" id="mobile" name="mobile"
                        placeholder="Mobile" title="" type="tel" value="{{Input::old('mobile') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">IM</label>
            <div><input class="form-control" id="im" name="im"
                        placeholder="IM" title="" type="text" value="{{Input::old('im') }}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">URL</label>
            <div><input class="form-control" id="url" name="url"
                        placeholder="URL" title="" type="text" value="{{Input::old('url') }}"/>
            </div>
        </div>
        <br/>
        <input name="update" type="submit" class="btn btn-primary btn-block" value="Update">
    </form>
    
</div>

@stop

@section('scripts')
@parent
@stop
