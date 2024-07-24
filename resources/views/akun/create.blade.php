@extends('template.kaiadmin.master')

@section('title')
    Tambah {{ ucReplaceUnderscoreToSpace('akun') }}
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
            <a href="{{ route('akun.index') }}">{{ ucReplaceUnderscoreToSpace('akun') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('akun.index') }}">@yield('title')</a>
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
                                <form action="{{ route('akun.store') }}" method="POST">
                                    @csrf

                                    <!-- Column 1: Input Kode and Nama -->
                                    <div class="form-group">
                                        <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                        <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode" value="{{ old('kode') }}" placeholder="Masukkan kode ..." required autofocus>
                                        @error('kode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama ..." required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="akun_sub">{{ ucReplaceUnderscoreToSpace('akun_sub') }}</label>
                                        <select class="form-control akun_sub" id="akun_sub" name="akun_sub_id" required>
                                            <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('akun_sub') }}</option>
                                            @foreach ($akun_subs as $akun_sub)
                                            <option value="{{ $akun_sub->id }}">{{ ucReplaceUnderscoreToSpace($akun_sub->nama) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        $(document).ready(function() {
            $('.akun_sub').select2({
                theme: 'bootstrap',
                width: '100%',
            });
        });
</script>
@endsection
