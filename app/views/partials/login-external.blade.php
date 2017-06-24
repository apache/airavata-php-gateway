
@foreach ($auth_code_options as $auth_code_option)
    <a href="{{ $auth_code_option["auth_url"] }}" class="btn btn-primary">Sign in with {{{ $auth_code_option["name"] }}}</a>
@endforeach
