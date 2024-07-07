@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('jenis_penyesuaian_gudang') }}
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
            <a href="{{ route('jenis_penyesuaian_gudang.index') }}">{{ ucReplaceUnderscoreToSpace('jenis_penyesuaian_gudang') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('jenis_penyesuaian_gudang.edit', $jenis_penyesuaian_gudang->id) }}">@yield('title')</a>
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
                                <form action="{{ route('jenis_penyesuaian_gudang.update', $jenis_penyesuaian_gudang->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $jenis_penyesuaian_gudang->nama }}" placeholder="Masukkan nama ..." required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label>{{ ucReplaceUnderscoreToSpace('saldo') }}</label>
                                        <div>
                                            <label class="radio-inline">
                                                <input type="radio" name="saldo" id="saldo_bertambah" value="bertambah" {{ old('saldo', $jenis_penyesuaian_gudang->saldo) == 'bertambah' ? 'checked' : '' }}> Bertambah
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="saldo" id="saldo_berkurang" value="berkurang" {{ old('saldo', $jenis_penyesuaian_gudang->saldo) == 'berkurang' ? 'checked' : '' }}> Berkurang
                                            </label>
                                        </div>
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
