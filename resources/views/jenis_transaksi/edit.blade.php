@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('jenis_transaksi') }}
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
            <a href="{{ route('jenis_transaksi.index') }}">{{ ucReplaceUnderscoreToSpace('jenis_transaksi') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('jenis_transaksi.edit', $jenis_transaksi->id) }}">@yield('title')</a>
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
                                <form action="{{ route('jenis_transaksi.update', $jenis_transaksi->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $jenis_transaksi->nama }}" placeholder="Masukkan nama ..." required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ ucReplaceUnderscoreToSpace('saldo') }}</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="saldo" id="saldo_plus" value="plus" {{ $jenis_transaksi->saldo == 'plus' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="saldo_plus">
                                                plus
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="saldo" id="saldo_minus" value="minus" {{ $jenis_transaksi->saldo == 'minus' ? 'checked' : '' }} required>
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
