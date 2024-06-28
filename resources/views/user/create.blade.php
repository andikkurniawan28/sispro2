@extends('template.kaiadmin.master')

@section('title')
    Tambah {{ ucReplaceUnderscoreToSpace('user') }}
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
            <a href="{{ route('user.index') }}">@yield('title')</a>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('user.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama ..." value="{{ old('nama') }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="jabatan_id">{{ ucReplaceUnderscoreToSpace('jabatan') }}</label>
                                <select class="form-control jabatan_id" id="jabatan_id" name="jabatan_id" required>
                                    <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('jabatan') }}</option>
                                    @foreach($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="username">{{ ucReplaceUnderscoreToSpace('username') }}</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username ..." value="{{ old('username') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">{{ ucReplaceUnderscoreToSpace('password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password ..." value="{{ old('password') }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
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
