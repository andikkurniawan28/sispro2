@extends('template.kaiadmin.master')

@section('title')
    Penyesuaian {{ ucReplaceUnderscoreToSpace('saldo_material') }}
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
            <a href="{{ route('saldo_material.index') }}">{{ ucReplaceUnderscoreToSpace('saldo_material') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('saldo_material.index') }}">@yield('title')</a>
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
                                <form action="{{ route('saldo_material.proses') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control" id="nama" value="{{ $material->nama }}" placeholder="Masukkan nama ..." readonly>
                                    </div>
                                    @foreach($gudangs as $gudang)
                                    @php $gudang_nama = ucReplaceSpaceToUnderscore($gudang->nama); @endphp
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace($gudang->nama) }}</label>
                                        <input type="text" class="form-control" id="{{ $gudang_nama }}" name="{{ $gudang_nama }}" value="{{ $material->$gudang_nama }}" placeholder="Masukkan saldo ...">
                                    </div>
                                    @endforeach
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
