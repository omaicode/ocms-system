@extends('system::install.layout')

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('install_success'))
        <div class="alert alert-success mb-3">
            @lang('system::messages.install_success')
        </div>
        <a href="{{ route('admin.auth.login') }}" class="btn btn-success w-100">
            GO TO DASHBOARD
        </a>
        @else
        <form action="{{ route('system.install.setup') }}" method="POST">
            @csrf
            <div class="mb-4 text-center">
                <h4 class="text-primary mb-1">OCMS Installation</h4>
            </div>
            <h5 class="text-muted m-0">Database connection</h5>
            <hr/>
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="db_host" class="form-label">Host:</label>
                        <input type="text" name="db_host" id="db_host" class="form-control" value="{{ old('db_host', $DB_HOST['value']) }}" aria-describedby="db_host_help" required>
                        @error('db_host')
                            <small id="db_host_help" class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="db_port" class="form-label">Port:</label>
                        <input type="number" name="db_port" id="db_port" class="form-control" value="{{ old('db_port', $DB_PORT['value']) }}" aria-describedby="db_port_help" required>
                        @error('db_port')
                            <small id="db_port_help" class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="db_database" class="form-label">Database:</label>
                        <input type="text" name="db_database" id="db_database" class="form-control" value="{{ old('db_database', $DB_DATABASE['value']) }}" aria-describedby="db_database_help" required>
                        @error('db_database')
                            <small id="db_database_help" class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="db_username" class="form-label">Username:</label>
                        <input type="text" name="db_username" id="db_username" class="form-control" value="{{ old('db_username', $DB_USERNAME['value']) }}" aria-describedby="db_username_help" required>
                        @error('db_username')
                            <small id="db_username_help" class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="db_password" class="form-label">Password:</label>
                        <input type="text" name="db_password" id="db_password" class="form-control" value="{{ old('db_password', $DB_PASSWORD['value']) }}" aria-describedby="db_password_help" required>
                        @error('db_password')
                            <small id="db_password_help" class="text-muted text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <h5 class="text-muted m-0">Admin account</h5>
            <hr/>
            <div class="row">  
                <div class="col-12">
                    <div class="mb-3">
                        <label for="admin_username" class="form-label">Username:</label>
                        <input type="text" name="admin_username" id="admin_username" class="form-control" value="{{ old('admin_username', 'administrator') }}" aria-describedby="admin_username_help" required>
                        @error('admin_username')
                            <small id="admin_username_help" class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>            
                <div class="col-12">
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Email:</label>
                        <input type="email" name="admin_email" id="admin_email" class="form-control" value="{{ old('admin_email', 'admin@example.com') }}" aria-describedby="admin_email_help" required>
                        @error('admin_email')
                            <small id="admin_email_help" class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>            
                <div class="col-12">
                    <div class="mb-3">
                        <label for="admin_password" class="form-label">Password:</label>
                        <input type="password" name="admin_password" id="admin_password" class="form-control" value="" aria-describedby="admin_password_help" required>
                        @error('admin_password')
                            <small id="admin_password_help" class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>            
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">INSTALL</button>
            </div>                
        </form>
        @endif
    </div>
</div>
@endsection