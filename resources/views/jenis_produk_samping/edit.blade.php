@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('jenis_produk_samping') }}
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
            <a href="{{ route('jenis_produk_samping.index') }}">{{ ucReplaceUnderscoreToSpace('jenis_produk_samping') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('jenis_produk_samping.edit', $jenis_produk_samping->id) }}">@yield('title')</a>
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
                                <form action="{{ route('jenis_produk_samping.update', $jenis_produk_samping->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $jenis_produk_samping->nama }}" placeholder="Masukkan nama ..." required autofocus>
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
@endsection