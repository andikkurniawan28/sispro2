@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('material') }}
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
                            <form action="{{ route('material.update', $material->id) }}" method="POST">
                                @csrf @method("PUT")
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{ $material->kode }}" placeholder="Masukkan kode ..." required
                                                autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                            <input type="text" class="form-control" id="nama" name="nama"
                                                value="{{ $material->nama }}" placeholder="Masukkan nama ..." required>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="fungsi_material_id">{{ ucReplaceUnderscoreToSpace('fungsi') }}</label>
                                            <select class="form-control fungsi_material_id" id="fungsi_material_id"
                                                name="fungsi_material_id" required>
                                                <option disabled selected>Pilih
                                                    {{ ucReplaceUnderscoreToSpace('fungsi_material') }}</option>
                                                @foreach ($fungsi_materials as $fungsi_material)
                                                    <option value="{{ $fungsi_material->id }}"
                                                        @if ($material->fungsi_material_id == $fungsi_material->id) selected @endif>{{ $fungsi_material->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="jenis_material_id">{{ ucReplaceUnderscoreToSpace('jenis') }}</label>
                                            <select class="form-control jenis_material_id" id="jenis_material_id"
                                                name="jenis_material_id" required>
                                                <option disabled selected>Pilih
                                                    {{ ucReplaceUnderscoreToSpace('jenis_material') }}</option>
                                                @foreach ($jenis_materials as $jenis_material)
                                                    <option value="{{ $jenis_material->id }}"
                                                        @if ($material->jenis_material_id == $jenis_material->id) selected @endif>{{ $jenis_material->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="harga_beli">{{ ucReplaceUnderscoreToSpace('harga_beli') }}</label>
                                            <input type="number" class="form-control" id="harga_beli" name="harga_beli"
                                                value="{{ $material->harga_beli }}" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('harga_beli') }} ..." step="any">
                                        </div>
                                        <div class="form-group">
                                            <label for="harga_jual">{{ ucReplaceUnderscoreToSpace('harga_jual') }}</label>
                                            <input type="number" class="form-control" id="harga_jual" name="harga_jual"
                                                value="{{ $material->harga_jual }}" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('harga_jual') }} ..." step="any">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barcode">{{ ucReplaceUnderscoreToSpace('barcode') }}</label>
                                            <input type="text" class="form-control" id="barcode" name="barcode"
                                                value="{{ $material->barcode }}" placeholder="Masukkan barcode ...">
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="satuan_besar_id">{{ ucReplaceUnderscoreToSpace('satuan_besar') }}</label>
                                            <select class="form-control satuan_besar_id" id="satuan_besar_id"
                                                name="satuan_besar_id" required>
                                                <option disabled selected>Pilih
                                                    {{ ucReplaceUnderscoreToSpace('satuan_besar') }}</option>
                                                @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}"
                                                        @if ($material->satuan_besar_id == $satuan->id) selected @endif>{{ $satuan->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="sejumlah">{{ ucReplaceUnderscoreToSpace('sejumlah') }}</label>
                                            <input type="number" class="form-control" id="sejumlah" name="sejumlah"
                                                value="{{ $material->sejumlah }}" placeholder="Dalam 1 satuan besar mengandung sejumlah xxx satuan kecil ..." step="any" required>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="satuan_kecil_id">{{ ucReplaceUnderscoreToSpace('satuan_kecil') }}</label>
                                            <select class="form-control satuan_kecil_id" id="satuan_kecil_id"
                                                name="satuan_kecil_id" required>
                                                <option disabled selected>Pilih
                                                    {{ ucReplaceUnderscoreToSpace('satuan_kecil') }}</option>
                                                @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}"
                                                        @if ($material->satuan_kecil_id == $satuan->id) selected @endif>{{ $satuan->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="hasil_per_batch_dalam_satuan_besar">{{ ucReplaceUnderscoreToSpace('hasil_per_batch_dalam_satuan_besar') }}</label>
                                            <input type="number" class="form-control" id="hasil_per_batch_dalam_satuan_besar" name="hasil_per_batch_dalam_satuan_besar"
                                                value="{{ $material->hasil_per_batch_dalam_satuan_besar }}" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('hasil_per_batch_dalam_satuan_besar') }} ..." step="any">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.fungsi_material_id').select2({
                theme: 'bootstrap',
                width: '100%',
            });
            $('.jenis_material_id').select2({
                theme: 'bootstrap',
                width: '100%',
            });
            $('.satuan_besar_id').select2({
                theme: 'bootstrap',
                width: '100%',
            });
            $('.satuan_kecil_id').select2({
                theme: 'bootstrap',
                width: '100%',
            });
        });
    </script>
@endsection
