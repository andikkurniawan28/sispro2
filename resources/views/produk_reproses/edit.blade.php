@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('produk_reproses') }}
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
            <a href="{{ route('produk_reproses.index') }}">{{ ucReplaceUnderscoreToSpace('produk_reproses') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('produk_reproses.index') }}">@yield('title')</a>
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
                            <form action="{{ route('produk_reproses.update', $produk_reproses->id) }}" method="POST">
                                @csrf @method("PUT")
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{ $produk_reproses->kode }}" placeholder="Masukkan kode ..." required
                                                autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                            <input type="text" class="form-control" id="nama" name="nama"
                                                value="{{ $produk_reproses->nama }}" placeholder="Masukkan nama ..." required>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="jenis_produk_reproses_id">{{ ucReplaceUnderscoreToSpace('jenis') }}</label>
                                            <select class="form-control jenis_produk_reproses_id" id="jenis_produk_reproses_id"
                                                name="jenis_produk_reproses_id" required>
                                                <option disabled selected>Pilih
                                                    {{ ucReplaceUnderscoreToSpace('jenis_produk_reproses') }}</option>
                                                @foreach ($jenis_produk_reproses as $jenis_produk_reproses)
                                                    <option value="{{ $jenis_produk_reproses->id }}"
                                                        @if ($produk_reproses->jenis_produk_reproses_id == $jenis_produk_reproses->id) selected @endif>{{ $jenis_produk_reproses->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                for="satuan_besar_id">{{ ucReplaceUnderscoreToSpace('satuan_besar') }}</label>
                                            <select class="form-control satuan_besar_id" id="satuan_besar_id"
                                                name="satuan_besar_id" required>
                                                <option disabled selected>Pilih
                                                    {{ ucReplaceUnderscoreToSpace('satuan_besar') }}</option>
                                                @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}"
                                                        @if ($produk_reproses->satuan_besar_id == $satuan->id) selected @endif>{{ $satuan->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="sejumlah">{{ ucReplaceUnderscoreToSpace('sejumlah') }}</label>
                                            <input type="number" class="form-control" id="sejumlah" name="sejumlah"
                                                value="{{ $produk_reproses->sejumlah }}" placeholder="Dalam 1 satuan besar mengandung sejumlah xxx satuan kecil ..." step="any" required>
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
                                                        @if ($produk_reproses->satuan_kecil_id == $satuan->id) selected @endif>{{ $satuan->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
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
            $('.jenis_produk_reproses_id').select2({
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
