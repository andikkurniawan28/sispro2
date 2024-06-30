@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('jenis_jurnal_gudang') }}
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
            <a href="{{ route('jenis_jurnal_gudang.index') }}">{{ ucReplaceUnderscoreToSpace('jenis_jurnal_gudang') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('jenis_jurnal_gudang.edit', $jenis_jurnal_gudang->id) }}">@yield('title')</a>
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
                                <form action="{{ route('jenis_jurnal_gudang.update', $jenis_jurnal_gudang->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                        <input type="text" class="form-control" id="kode" name="kode" value="{{ $jenis_jurnal_gudang->kode }}" placeholder="Masukkan kode ..." required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $jenis_jurnal_gudang->nama }}" placeholder="Masukkan nama ..." required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ ucReplaceUnderscoreToSpace('saldo') }}</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="saldo" id="saldo_plus" value="plus" {{ $jenis_jurnal_gudang->saldo == 'plus' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="saldo_plus">
                                                plus
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="saldo" id="saldo_minus" value="minus" {{ $jenis_jurnal_gudang->saldo == 'minus' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="saldo_minus">
                                                minus
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
