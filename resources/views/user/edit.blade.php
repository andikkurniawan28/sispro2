@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('user') }}
@endsection

@section('navigation')
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('dashboard') }}">
                <i class="icon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.index') }}">{{ ucReplaceUnderscoreToSpace('user') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.edit', $user->id) }}">@yield('title')</a>
        </li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">@yield('title')</h4>
            @yield('navigation')
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('user.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama ..." value="{{ $user->nama }}" required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="jabatan_id">{{ ucReplaceUnderscoreToSpace('jabatan') }}</label>
                                        <select class="form-control jabatan_id" id="jabatan_id" name="jabatan_id" required>
                                            @foreach($jabatans as $jabatan)
                                                <option value="{{ $jabatan->id }}" @if($user->jabatan_id == $jabatan->id) selected @endif>{{ $jabatan->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">{{ ucReplaceUnderscoreToSpace('username') }}</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username ..." value="{{ $user->username }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">{{ ucReplaceUnderscoreToSpace('password') }}</label>
                                        <input type="password" class="form-control" id="password" name="password" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="is_active">{{ ucReplaceUnderscoreToSpace('status') }}</label>
                                        <input type="text" class="form-control" id="is_active" name="is_active" placeholder="Masukkan status ..." value="{{ $user->is_active }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.jabatan_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
    });
</script>
@endsection
