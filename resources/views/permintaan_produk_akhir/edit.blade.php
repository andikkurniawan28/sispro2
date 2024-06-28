@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('permintaan_produk_akhir') }}
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
            <a href="{{ route('permintaan_produk_akhir.index') }}">{{ ucReplaceUnderscoreToSpace('permintaan_produk_akhir') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('permintaan_produk_akhir.index') }}">@yield('title')</a>
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
                            <form action="{{ route('permintaan_produk_akhir.update', $permintaan_produk_akhir->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{ old('kode', $permintaan_produk_akhir->kode) }}" placeholder="Masukkan kode ..." readonly>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="berlaku_sampai">{{ ucReplaceUnderscoreToSpace('berlaku_sampai') }}</label>
                                            <input type="datetime-local" class="form-control" id="berlaku_sampai"
                                                name="berlaku_sampai" value="{{ old('berlaku_sampai', $permintaan_produk_akhir->berlaku_sampai) }}"
                                                placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('berlaku_sampai') }} ..." required autofocus>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="produk_akhirs_container">
                                            @if (old('produk_akhirs', $permintaan_produk_akhir_detail ))
                                                @foreach (old('produk_akhirs', $permintaan_produk_akhir_detail ) as $index => $produk_akhir)
                                                    <div class="row mb-3" id="produk_akhir_row_{{ $index }}">
                                                        <div class="col-sm-4">
                                                            <label class="col-form-label" for="produk_akhir_{{ $index }}">
                                                                {{ ucReplaceUnderscoreToSpace('produk_akhir') }}
                                                            </label>
                                                            <select class="produk_akhir form-control" id="produk_akhir_{{ $index }}" name="produk_akhirs[]" required>
                                                                <option disabled>Pilih {{ ucReplaceUnderscoreToSpace('produk_akhir') }}</option>
                                                                @foreach ($produk_akhirs as $produk_akhirItem)
                                                                    <option value="{{ $produk_akhirItem->id }}" {{ $produk_akhirItem->id == $produk_akhir->produk_akhir_id ? 'selected' : '' }}>
                                                                        {{ ucwords(str_replace('_', ' ', $produk_akhirItem->kode)) }} | {{ ucwords(str_replace('_', ' ', $produk_akhirItem->nama)) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label class="col-form-label" for="jumlah_{{ $index }}">
                                                                {{ ucReplaceUnderscoreToSpace('jumlah') }}
                                                            </label>
                                                            <input type="number" class="form-control jumlah" id="jumlah_{{ $index }}" name="jumlahs[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah') }}" value="{{ old('jumlahs')[$index] ?? $produk_akhir->jumlah }}" required>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label class="col-form-label" for="satuan_besar_nama_{{ $index }}">
                                                                Satuan
                                                            </label>
                                                            <span class="satuan_besar_nama form-control" id="satuan_besar_nama_{{ $index }}">
                                                                {{ $produk_akhirs->find($produk_akhir->produk_akhir_id)->satuan_besar->nama }}
                                                            </span>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label class="col-form-label" for="remove_{{ $index }}">
                                                                {{ ucReplaceUnderscoreToSpace('hapus') }}
                                                            </label>
                                                            <br>
                                                            <button type="button" class="btn btn-danger btn-sm btn-block remove-produk_akhir">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="button" class="btn btn-success" id="add_produk_akhir">{{ ucReplaceUnderscoreToSpace('tambah_produk') }}</button>
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
                $('.produk_akhir').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
            }

            function initialize{{ ucReplaceUnderscoreToSpace('hapus') }}Button() {
                $('.remove-produk_akhir').click(function() {
                    $(this).closest('.row').remove();
                });
            }

            // Initialize Select2 for existing rows
            initializeSelect2();

            // Initialize {{ ucReplaceUnderscoreToSpace('hapus') }} button for existing rows
            initialize{{ ucReplaceUnderscoreToSpace('hapus') }}Button();

            // Products data with satuan_besar namas
            const produk_akhirsData = @json($produk_akhirs->mapWithKeys(function($item) {
                return [$item->id => $item->satuan_besar->nama];
            }));

            // Counter for unique IDs
            let produk_akhirCounter = {{ old('produk_akhirs', $permintaan_produk_akhir_detail ) ? count(old('produk_akhirs', $permintaan_produk_akhir_detail )) : 0 }};

            // Function to add a new Select2 produk_akhir dropdown with jumlah input
            function addProductDropdown() {
                produk_akhirCounter++;
                const newDropdownId = 'produk_akhir_' + produk_akhirCounter;

                // Create new row with Select2 dropdown and jumlah input
                const newRow = `
                    <div class="row mb-3" id="produk_akhir_row_${produk_akhirCounter}">
                        <div class="col-sm-4">
                            <label class="col-form-label" for="${newDropdownId}">
                                {{ ucReplaceUnderscoreToSpace('produk_akhir') }}
                            </label>
                            <select class="produk_akhir form-control" id="${newDropdownId}" name="produk_akhirs[]" required>
                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('produk_akhir') }}</option>
                                @foreach ($produk_akhirs as $produk_akhir)
                                    <option value="{{ $produk_akhir->id }}">
                                        {{ ucwords(str_replace('_', ' ', $produk_akhir->kode)) }} |
                                        {{ ucwords(str_replace('_', ' ', $produk_akhir->nama)) }}
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
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-produk_akhir">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                        </div>
                    </div>
                `;

                // Append the new row to the container
                $('#produk_akhirs_container').append(newRow);

                // Initialize Select2 for the new dropdown
                $('#' + newDropdownId).select2({
                    theme: 'bootstrap',
                    width: '100%',
                });

                // Add change event listener to update satuan_besar nama
                $('#' + newDropdownId).on('change', function() {
                    const selectedProductId = $(this).val();
                    const satuan_besarSymbol = produk_akhirsData[selectedProductId];
                    $('#satuan_besar_nama_' + newDropdownId).text(satuan_besarSymbol);
                });

                // Initialize {{ ucReplaceUnderscoreToSpace('hapus') }} button for new row
                initialize{{ ucReplaceUnderscoreToSpace('hapus') }}Button();
            }

            // Add produk_akhir button click event
            $('#add_produk_akhir').click(function() {
                addProductDropdown();
            });
        });
    </script>
@endsection
