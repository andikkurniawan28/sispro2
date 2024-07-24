@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('akun_dasar') }}
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
            <a href="{{ route('akun_dasar.index') }}">{{ ucReplaceUnderscoreToSpace('akun_dasar') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('akun_dasar.index') }}">@yield('title')</a>
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
                                <form action="{{ route('akun_dasar.update', $akun_dasar->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Column 1: Input Kode and Nama -->
                                    <div class="form-group">
                                        <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                        <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode" value="{{ old('kode', $akun_dasar->kode) }}" placeholder="Masukkan kode ..." required autofocus>
                                        @error('kode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $akun_dasar->nama) }}" placeholder="Masukkan nama ..." required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="laporan">{{ ucReplaceUnderscoreToSpace('laporan') }}</label>
                                        <select class="form-control laporan" id="laporan" name="laporan" required>
                                            <option disabled>Pilih {{ ucReplaceUnderscoreToSpace('laporan') }}</option>
                                            <option value="{{ ucReplaceUnderscoreToSpace('neraca') }}" {{ old('laporan', $akun_dasar->laporan) == ucReplaceUnderscoreToSpace('neraca') ? 'selected' : '' }}>{{ ucReplaceUnderscoreToSpace('neraca') }}</option>
                                            <option value="{{ ucReplaceUnderscoreToSpace('laporan_laba_rugi') }}" {{ old('laporan', $akun_dasar->laporan) == ucReplaceUnderscoreToSpace('laporan_laba_rugi') ? 'selected' : '' }}>{{ ucReplaceUnderscoreToSpace('laporan_laba_rugi') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kelompok">{{ ucReplaceUnderscoreToSpace('kelompok') }}</label>
                                        <select class="form-control kelompok" id="kelompok" name="kelompok" required>
                                            <option disabled>Pilih {{ ucReplaceUnderscoreToSpace('kelompok') }}</option>
                                            <option value="{{ ucReplaceUnderscoreToSpace('aktiva') }}" {{ old('kelompok', $akun_dasar->kelompok) == ucReplaceUnderscoreToSpace('aktiva') ? 'selected' : '' }}>{{ ucReplaceUnderscoreToSpace('aktiva') }}</option>
                                            <option value="{{ ucReplaceUnderscoreToSpace('passiva') }}" {{ old('kelompok', $akun_dasar->kelompok) == ucReplaceUnderscoreToSpace('passiva') ? 'selected' : '' }}>{{ ucReplaceUnderscoreToSpace('passiva') }}</option>
                                            <option value="{{ ucReplaceUnderscoreToSpace('operasional_usaha') }}" {{ old('kelompok', $akun_dasar->kelompok) == ucReplaceUnderscoreToSpace('operasional_usaha') ? 'selected' : '' }}>{{ ucReplaceUnderscoreToSpace('operasional_usaha') }}</option>
                                            <option value="{{ ucReplaceUnderscoreToSpace('operasional_lain-lain') }}" {{ old('kelompok', $akun_dasar->kelompok) == ucReplaceUnderscoreToSpace('operasional_lain-lain') ? 'selected' : '' }}>{{ ucReplaceUnderscoreToSpace('operasional_lain-lain') }}</option>
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
            $('.laporan').select2({
                theme: 'bootstrap',
                width: '100%',
            });
            $('.kelompok').select2({
                theme: 'bootstrap',
                width: '100%',
            });
        });
</script>
@endsection
