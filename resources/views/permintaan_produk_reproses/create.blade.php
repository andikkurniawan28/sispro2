@extends('template.kaiadmin.master')

@section('title')
    Tambah {{ ucReplaceUnderscoreToSpace('permintaan_produk_reproses') }}
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
            <a href="{{ route('permintaan_produk_reproses.index') }}">{{ ucReplaceUnderscoreToSpace('permintaan_produk_reproses') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('permintaan_produk_reproses.index') }}">@yield('title')</a>
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
                            <form action="{{ route('permintaan_produk_reproses.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{ $kode }}" placeholder="Masukkan kode ..." readonly>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="berlaku_sampai">{{ ucReplaceUnderscoreToSpace('berlaku_sampai') }}</label>
                                            <input type="datetime-local" class="form-control" id="berlaku_sampai"
                                                name="berlaku_sampai" value="{{ old('berlaku_sampai') }}"
                                                placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('berlaku_sampai') }} ..." required autofocus>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="produk_reproses_container">
                                            @if (old('produk_reproses'))
                                                @foreach (old('produk_reproses') as $index => $produk_reproses)
                                                    <div class="row mb-3" id="produk_reproses_row_{{ $index }}">
                                                        <div class="col-sm-4">
                                                            <label class="col-form-label" for="produk_reproses_{{ $index }}">
                                                                {{ ucReplaceUnderscoreToSpace('produk_reproses') }}
                                                            </label>
                                                            <select class="produk_reproses form-control" id="produk_reproses_{{ $index }}" name="produk_reproses[]" required>
                                                                <option disabled>Pilih {{ ucReplaceUnderscoreToSpace('produk_reproses') }}</option>
                                                                @foreach ($produk_reproses as $produk_reprosesItem)
                                                                    <option value="{{ $produk_reprosesItem->id }}" {{ $produk_reprosesItem->id == $produk_reproses ? 'selected' : '' }}>
                                                                        {{ ucwords(str_replace('_', ' ', $produk_reprosesItem->kode)) }} | {{ ucwords(str_replace('_', ' ', $produk_reprosesItem->nama)) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label class="col-form-label" for="jumlah_{{ $index }}">
                                                                {{ ucReplaceUnderscoreToSpace('jumlah') }}
                                                            </label>
                                                            <input type="number" class="form-control jumlah" id="jumlah_{{ $index }}" name="jumlahs[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah') }}" value="{{ old('jumlahs')[$index] }}" required>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label class="col-form-label" for="satuan_besar_nama_{{ $index }}">
                                                                Satuan
                                                            </label>
                                                            <span class="satuan_besar_nama form-control" id="satuan_besar_nama_{{ $index }}">
                                                                {{ $produk_reproses->find($produk_reproses)->satuan_besar->nama }}
                                                            </span>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label class="col-form-label" for="remove_{{ $index }}">
                                                                {{ ucReplaceUnderscoreToSpace('hapus') }}
                                                            </label>
                                                            <br>
                                                            <button type="button" class="btn btn-danger btn-sm btn-block remove-produk_reproses">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="button" class="btn btn-success" id="add_produk_reproses">{{ ucReplaceUnderscoreToSpace('tambah_produk') }}</button>
                                            </div>
                                        </div>
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
            function initializeSelect2() {
                $('.produk_reproses').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
            }

            function initialize{{ ucReplaceUnderscoreToSpace('hapus') }}Button() {
                $('.remove-produk_reproses').click(function() {
                    $(this).closest('.row').remove();
                });
            }

            // Initialize Select2 for existing rows
            initializeSelect2();

            // Initialize {{ ucReplaceUnderscoreToSpace('hapus') }} button for existing rows
            initialize{{ ucReplaceUnderscoreToSpace('hapus') }}Button();

            // Products data with satuan_besar namas
            const produk_reprosesData = @json($produk_reproses->mapWithKeys(function($item) {
                return [$item->id => $item->satuan_besar->nama];
            }));

            // Counter for unique IDs
            let produk_reprosesCounter = {{ old('produk_reproses') ? count(old('produk_reproses')) : 0 }};

            // Function to add a new Select2 produk_reproses dropdown with jumlah input
            function addProductDropdown() {
                produk_reprosesCounter++;
                const newDropdownId = 'produk_reproses_' + produk_reprosesCounter;

                // Create new row with Select2 dropdown and jumlah input
                const newRow = `
                    <div class="row mb-3" id="produk_reproses_row_${produk_reprosesCounter}">
                        <div class="col-sm-4">
                            <label class="col-form-label" for="${newDropdownId}">
                                {{ ucReplaceUnderscoreToSpace('produk_reproses') }}
                            </label>
                            <select class="produk_reproses form-control" id="${newDropdownId}" name="produk_reproses[]" required>
                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('produk_reproses') }}</option>
                                @foreach ($produk_reproses as $produk_reproses)
                                    <option value="{{ $produk_reproses->id }}">
                                        {{ ucwords(str_replace('_', ' ', $produk_reproses->kode)) }} |
                                        {{ ucwords(str_replace('_', ' ', $produk_reproses->nama)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-form-label" for="jumlah_${newDropdownId}">
                                {{ ucReplaceUnderscoreToSpace('jumlah') }}
                            </label>
                            <input type="number" class="form-control jumlah" id="jumlah_${newDropdownId}" name="jumlahs[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah') }}" required>
                        </div>
                        <div class="col-sm-2">
                            <label class="col-form-label" for="satuan_besar_nama_${newDropdownId}">
                                Satuan
                            </label>
                            <span class="satuan_besar_nama form-control" id="satuan_besar_nama_${newDropdownId}"></span>
                        </div>
                        <div class="col-sm-2">
                            <label class="col-form-label" for="remove_${newDropdownId}">
                                {{ ucReplaceUnderscoreToSpace('hapus') }}
                            </label>
                            <br>
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-produk_reproses">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                        </div>
                    </div>
                `;

                // Append the new row to the container
                $('#produk_reproses_container').append(newRow);

                // Initialize Select2 for the new dropdown
                $('#' + newDropdownId).select2({
                    theme: 'bootstrap',
                    width: '100%',
                });

                // Add change event listener to update satuan_besar nama
                $('#' + newDropdownId).on('change', function() {
                    const selectedProductId = $(this).val();
                    const satuan_besarSymbol = produk_reprosesData[selectedProductId];
                    $('#satuan_besar_nama_' + newDropdownId).text(satuan_besarSymbol);
                });

                // Initialize {{ ucReplaceUnderscoreToSpace('hapus') }} button for new row
                initialize{{ ucReplaceUnderscoreToSpace('hapus') }}Button();
            }

            // Add produk_reproses button click event
            $('#add_produk_reproses').click(function() {
                addProductDropdown();
            });
        });
    </script>
@endsection
