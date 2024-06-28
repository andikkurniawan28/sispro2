@extends('template.kaiadmin.master')

@section('title')
    Tambah {{ ucReplaceUnderscoreToSpace('jenis_produk_akhir') }}
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
            <a href="{{ route('jenis_produk_akhir.index') }}">{{ ucReplaceUnderscoreToSpace('jenis_produk_akhir') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('jenis_produk_akhir.index') }}">@yield('title')</a>
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
                                <form action="{{ route('jenis_produk_akhir.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama ..." required autofocus>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
