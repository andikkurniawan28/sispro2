@extends('template.kaiadmin.master')

@section('title')
    Tambah {{ ucReplaceUnderscoreToSpace('jabatan') }}
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
            <a href="{{ route('jabatan.index') }}">{{ ucReplaceUnderscoreToSpace('jabatan') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('jabatan.index') }}">@yield('title')</a>
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
            <form action="{{ route('jabatan.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama">{{ ucReplaceUnderscoreToSpace('nama') }}</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="{{ old('nama') }}" placeholder="Masukkan nama ..." required
                                        autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="divisi_id">{{ ucReplaceUnderscoreToSpace('divisi') }}</label>
                                    <select class="form-control divisi_id" id="divisi_id" name="divisi_id" required>
                                        <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('divisi') }}</option>
                                        @foreach ($divisis as $divisi)
                                            <option value="{{ $divisi->id }}">{{ $divisi->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="fitur">{{ ucReplaceUnderscoreToSpace('fitur') }}</label>
                                    <div class="col-sm-10 fitur-section">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="select_all">
                                            <label class="form-check-label" for="select_all">
                                                Pilih semua
                                            </label>
                                        </div>
                                        <hr>
                                        <!-- Checkbox for each fitur -->
                                        @foreach ($fiturs as $fitur)
                                            <div class="form-check">
                                                <input class="form-check-input fitur-checkbox" type="checkbox"
                                                    name="fitur_ids[]" value="{{ $fitur->id }}"
                                                    id="fitur_{{ $fitur->id }}">
                                                <label class="form-check-label" for="fitur_{{ $fitur->id }}">
                                                    {{ $fitur->nama }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.divisi_id').select2({
                theme: 'bootstrap',
                width: '100%',
            });
        });
        document.getElementById('select_all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.fitur-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
