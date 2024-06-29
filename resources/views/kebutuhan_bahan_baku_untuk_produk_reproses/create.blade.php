@extends('template.kaiadmin.master')

@section('title')
    Tambah {{ ucReplaceUnderscoreToSpace('kebutuhan_bahan_baku_untuk_produk_reproses') }}
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
            <a
                href="{{ route('kbbupr.index') }}">{{ ucReplaceUnderscoreToSpace('kebutuhan_bahan_baku_untuk_produk_reproses') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('kbbupr.index') }}">@yield('title')</a>
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
                            <form action="{{ route('kbbupr.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="produk_reproses_id">{{ ucReplaceUnderscoreToSpace('produk_reproses') }}</label>
                                            <select class="produk_reproses form-control @error('produk_reproses_id') is-invalid @enderror" id="produk_reproses_id" name="produk_reproses_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('produk_reproses') }}</option>
                                                @foreach ($produk_reprosess as $produk_reproses)
                                                    <option value="{{ $produk_reproses->id }}" {{ old('produk_reproses_id') == $produk_reproses->id ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $produk_reproses->kode)) }} |
                                                        {{ ucwords(str_replace('_', ' ', $produk_reproses->nama)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('produk_reproses_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="bahan_bakus_container">
                                            <!-- Dynamic Bahan Baku Rows will be appended here -->
                                            @if (old('bahan_bakus'))
                                                @foreach (old('bahan_bakus') as $index => $bahan_baku)
                                                    <div class="row mb-3" id="bahan_baku_row_{{ $index }}">
                                                        <div class="col-sm-4">
                                                            <label
                                                                for="bahan_baku_{{ $index }}">{{ ucReplaceUnderscoreToSpace('bahan_baku') }}</label>
                                                            <select class="bahan_baku form-control"
                                                                id="bahan_baku_{{ $index }}" name="bahan_bakus[]"
                                                                required>
                                                                <option disabled selected>Pilih
                                                                    {{ ucReplaceUnderscoreToSpace('bahan_baku') }}</option>
                                                                @foreach ($bahan_bakus as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        data-satuan-kecil="{{ $item->satuan_kecil->nama }}"
                                                                        data-satuan-besar="{{ $item->satuan_besar->nama }}"
                                                                        data-sejumlah="{{ $item->sejumlah }}"
                                                                        {{ $item->id == $bahan_baku ? 'selected' : '' }}>
                                                                        {{ ucwords(str_replace('_', ' ', $item->kode)) }} |
                                                                        {{ ucwords(str_replace('_', ' ', $item->nama)) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label
                                                                for="jumlah_dalam_satuan_kecil_{{ $index }}">{{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}</label>
                                                            <input type="number"
                                                                class="form-control jumlah_dalam_satuan_kecil"
                                                                id="jumlah_dalam_satuan_kecil_{{ $index }}"
                                                                name="jumlah_dalam_satuan_kecil[]"
                                                                placeholder="Jumlah Kecil"
                                                                value="{{ old('jumlah_dalam_satuan_kecil')[$index] }}"
                                                                step="any" required>
                                                            <span id="satuan_kecil_{{ $index }}"
                                                                class="satuan_kecil"></span>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label
                                                                for="jumlah_dalam_satuan_besar_{{ $index }}">{{ ucReplaceUnderscoreToSpace('jumlah_besar') }}</label>
                                                            <input type="number"
                                                                class="form-control jumlah_dalam_satuan_besar"
                                                                id="jumlah_dalam_satuan_besar_{{ $index }}"
                                                                name="jumlah_dalam_satuan_besar[]"
                                                                placeholder="Jumlah Besar"
                                                                value="{{ old('jumlah_dalam_satuan_besar')[$index] }}"
                                                                step="any" readonly>
                                                            <span id="satuan_besar_{{ $index }}"
                                                                class="satuan_besar"></span>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label
                                                                for="remove_{{ $index }}">{{ ucReplaceUnderscoreToSpace('hapus') }}</label>
                                                            <br>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm btn-block remove-bahan_baku">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="button" class="btn btn-success"
                                                    id="add_bahan_baku">{{ ucReplaceUnderscoreToSpace('tambah_bahan_baku') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Simpan</button>
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
                $('.bahan_baku').select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    updateSatuan($(this));
                });
            }

            function initializeRemoveButton() {
                $('.remove-bahan_baku').click(function() {
                    $(this).closest('.row').remove();
                });
            }

            function updateSatuan(selectElement) {
                const rowId = selectElement.closest('.row').attr('id');
                const satuanKecil = selectElement.find('option:selected').data('satuan-kecil');
                const satuanBesar = selectElement.find('option:selected').data('satuan-besar');

                $(`#${rowId} .satuan_kecil`).text(satuanKecil);
                $(`#${rowId} .satuan_besar`).text(satuanBesar);

                // Update jumlah besar based on jumlah kecil and sejumlah
                const jumlahKecilInput = $(`#${rowId} .jumlah_dalam_satuan_kecil`);
                calculateJumlahBesar(jumlahKecilInput);
            }

            function calculateJumlahBesar(inputElement) {
                const rowId = inputElement.closest('.row').attr('id');
                const jumlahKecil = parseFloat(inputElement.val());
                const selectElement = $(`#${rowId} .bahan_baku`);
                const sejumlah = parseFloat(selectElement.find('option:selected').data('sejumlah'));

                if (!isNaN(jumlahKecil) && !isNaN(sejumlah)) {
                    const jumlahBesar = jumlahKecil / sejumlah;
                    $(`#${rowId} .jumlah_dalam_satuan_besar`).val(jumlahBesar.toFixed(2));
                }
            }

            // Initialize Select2 for existing rows
            initializeSelect2();

            // Initialize remove button for existing rows
            initializeRemoveButton();

            // Calculate jumlah besar when jumlah kecil changes
            $(document).on('input', '.jumlah_dalam_satuan_kecil', function() {
                calculateJumlahBesar($(this));
            });

            // Counter for unique IDs
            let bahan_bakuCounter = {{ old('bahan_bakus') ? count(old('bahan_bakus')) : 0 }};

            // Function to add a new row for bahan_baku selection and jumlah input
            function addBahanBakuRow() {
                bahan_bakuCounter++;
                const newRowId = 'bahan_baku_row_' + bahan_bakuCounter;

                const newRow = `
                    <div class="row mb-3" id="${newRowId}">
                        <div class="col-sm-4">
                            <label for="bahan_baku_${bahan_bakuCounter}">{{ ucReplaceUnderscoreToSpace('bahan_baku') }}</label>
                            <select class="bahan_baku form-control" id="bahan_baku_${bahan_bakuCounter}" name="bahan_bakus[]" required>
                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('bahan_baku') }}</option>
                                @foreach ($bahan_bakus as $item)
                                    <option value="{{ $item->id }}" data-satuan-kecil="{{ $item->satuan_kecil->nama }}" data-satuan-besar="{{ $item->satuan_besar->nama }}" data-sejumlah="{{ $item->sejumlah }}">
                                    {{ ucwords(str_replace('_', ' ', $item->kode)) }} |
                                    {{ ucwords(str_replace('_', ' ', $item->nama)) }}
                                    </option>
                                @endforeach
                                </select>
                                </div>
                                <div class="col-sm-3">
                                <label for="jumlah_dalam_satuan_kecil_${bahan_bakuCounter}">{{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}</label>
                                <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_${bahan_bakuCounter}" name="jumlah_dalam_satuan_kecil[]" placeholder="Jumlah Kecil" step="any" required>
                                <span id="satuan_kecil_${bahan_bakuCounter}" class="satuan_kecil" style="font-size: 10px; text-align: right;"></span>
                                </div>
                                <div class="col-sm-3">
                                <label for="jumlah_dalam_satuan_besar_${bahan_bakuCounter}">{{ ucReplaceUnderscoreToSpace('jumlah_besar') }}</label>
                                <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_${bahan_bakuCounter}" name="jumlah_dalam_satuan_besar[]" placeholder="Jumlah Besar" step="any" readonly>
                                <span id="satuan_besar_${bahan_bakuCounter}" class="satuan_besar" style="font-size: 10px; text-align: right;"></span>
                                </div>
                                <div class="col-sm-2">
                                <label for="remove_${bahan_bakuCounter}">{{ ucReplaceUnderscoreToSpace('hapus') }}</label>
                                <br>
                                <button type="button" class="btn btn-danger btn-sm btn-block remove-bahan_baku">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                                </div>
                                </div>
                                `;
                // Append the new row to the container
                $('#bahan_bakus_container').append(newRow);

                // Initialize Select2 for the new dropdown
                $('#bahan_baku_' + bahan_bakuCounter).select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    updateSatuan($(this));
                });

                // Initialize remove button for new row
                initializeRemoveButton();
            }

            // Add bahan_baku button click event
            $('#add_bahan_baku').click(function() {
                addBahanBakuRow();
            });
        });
    </script>
@endsection
