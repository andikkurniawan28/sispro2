@extends('template.kaiadmin.master')

@section('title')
    Detail {{ ucReplaceUnderscoreToSpace('material') }}
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
            <a href="{{ route('material.index') }}">{{ ucReplaceUnderscoreToSpace('material') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('material.index') }}">@yield('title')</a>
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
                                    <div class="form-group">
                                        <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                        <input type="text" class="form-control" id="kode" name="kode"
                                            value="{{ $material->kode }}" placeholder="Masukkan kode ..." readonly
                                            autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="{{ $material->nama }}" placeholder="Masukkan nama ..." readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="fungsi_material_id">{{ ucReplaceUnderscoreToSpace('fungsi') }}</label>
                                        <input type="text" class="form-control" id="fungsi_material_id" name="fungsi_material_id"
                                            value="{{ $material->fungsi_material->nama }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis_material_id">{{ ucReplaceUnderscoreToSpace('jenis') }}</label>
                                        <input type="text" class="form-control" id="jenis_material_id" name="jenis_material_id"
                                            value="{{ $material->jenis_material->nama }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="barcode">{{ ucReplaceUnderscoreToSpace('barcode') }}</label>
                                        <input type="text" class="form-control" id="barcode" name="barcode"
                                            value="{{ $material->barcode }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="satuan_besar_id">{{ ucReplaceUnderscoreToSpace('satuan_besar') }}</label>
                                            <input type="text" class="form-control" id="satuan_besar" name="satuan_besar"
                                                value="{{ $material->satuan_besar->nama }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="sejumlah">{{ ucReplaceUnderscoreToSpace('sejumlah') }}</label>
                                        <input type="text" class="form-control" id="sejumlah" name="sejumlah"
                                            value="{{ $material->sejumlah }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="satuan_kecil_id">{{ ucReplaceUnderscoreToSpace('satuan_kecil') }}</label>
                                        <input type="text" class="form-control" id="satuan_kecil" name="satuan_kecil"
                                            value="{{ $material->satuan_kecil->nama }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
