@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('kebutuhan') }}
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
            <a href="{{ route('kebutuhan.index') }}">{{ ucReplaceUnderscoreToSpace('kebutuhan') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('kebutuhan.index') }}">@yield('title')</a>
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
                            <form action="{{ route('kebutuhan.update', $id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="produk_id">{{ ucReplaceUnderscoreToSpace('produk') }}</label>
                                            <select class="produk form-control @error('produk_id') is-invalid @enderror" id="produk_id" name="produk_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('produk') }}</option>
                                                @foreach ($produks as $produk)
                                                    <option value="{{ $produk->id }}" {{ $id == $produk->id ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $produk->kode)) }} |
                                                        {{ ucwords(str_replace('_', ' ', $produk->nama)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('produk_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50%">{{ ucReplaceUnderscoreToSpace('bahan') }}</th>
                                                        <th style="width: 20%">{{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}</th>
                                                        <th style="width: 20%">{{ ucReplaceUnderscoreToSpace('jumlah_besar') }}</th>
                                                        <th style="width: 10%">{{ ucReplaceUnderscoreToSpace('hapus') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bahans_container">
                                                    @foreach ($kebutuhans as $index => $kebutuhan)
                                                        <tr id="bahan_row_{{ $index }}">
                                                            <td>
                                                                <select class="bahan form-control" id="bahan_{{ $index }}" name="bahans[]" required>
                                                                    <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('bahan') }}</option>
                                                                    @foreach ($bahans as $item)
                                                                        <option value="{{ $item->id }}" {{ $kebutuhan->bahan_id == $item->id ? 'selected' : '' }}
                                                                            data-satuan-kecil="{{ $item->satuan_kecil->nama }}"
                                                                            data-satuan-besar="{{ $item->satuan_besar->nama }}"
                                                                            data-sejumlah="{{ $item->sejumlah }}">
                                                                            {{ ucwords(str_replace('_', ' ', $item->kode)) }} |
                                                                            {{ ucwords(str_replace('_', ' ', $item->nama)) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_{{ $index }}" name="jumlah_dalam_satuan_kecil[]" placeholder="Jumlah Kecil" value="{{ $kebutuhan->jumlah_dalam_satuan_kecil }}" step="any" required>
                                                                <span id="satuan_kecil_{{ $index }}" class="satuan_kecil">{{ $kebutuhan->bahan->satuan_kecil->nama }}</span>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_{{ $index }}" name="jumlah_dalam_satuan_besar[]" placeholder="Jumlah Besar" value="{{ $kebutuhan->jumlah_dalam_satuan_besar }}" step="any">
                                                                <span id="satuan_besar_{{ $index }}" class="satuan_besar">{{ $kebutuhan->bahan->satuan_besar->nama }}</span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm btn-block remove-bahan">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-success" id="add_bahan">{{ ucReplaceUnderscoreToSpace('tambah_bahan') }}</button>
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
                $('.produk').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
                $('.bahan').select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    updateSatuan($(this));
                });
            }

            function initializeRemoveButton() {
                $('.remove-bahan').click(function() {
                    $(this).closest('tr').remove();
                });
            }

            function updateSatuan(selectElement) {
                const row = selectElement.closest('tr');
                const satuanKecil = selectElement.find('option:selected').data('satuan-kecil');
                const satuanBesar = selectElement.find('option:selected').data('satuan-besar');

                row.find('.satuan_kecil').text(satuanKecil);
                row.find('.satuan_besar').text(satuanBesar);

                const jumlahKecilInput = row.find('.jumlah_dalam_satuan_kecil');
                calculateJumlahBesar(jumlahKecilInput);
            }

            function calculateJumlahBesar(inputElement) {
                const row = inputElement.closest('tr');
                const jumlahKecil = parseFloat(inputElement.val());
                const selectElement = row.find('.bahan');
                const sejumlah = parseFloat(selectElement.find('option:selected').data('sejumlah'));

                if (!isNaN(jumlahKecil) && !isNaN(sejumlah)) {
                    const jumlahBesar = jumlahKecil / sejumlah;
                    row.find('.jumlah_dalam_satuan_besar').val(jumlahBesar.toFixed(2));
                }
            }

            // Initialize Select2 and remove button for existing rows
            initializeSelect2();
            initializeRemoveButton();

            // Handle input change for jumlah dalam satuan kecil
            $(document).on('input', '.jumlah_dalam_satuan_kecil', function() {
                calculateJumlahBesar($(this));
            });

            // Counter for unique IDs
            let bahanCounter = {{ $kebutuhans->count() }};

            // Function to add a new row for bahan selection and jumlah input
            function addBahanRow() {
                bahanCounter++;
                const newRowId = 'bahan_row_' + bahanCounter;

                const newRow = `
                    <tr id="${newRowId}">
                        <td>
                            <select class="bahan form-control" id="bahan_${bahanCounter}" name="bahans[]" required>
                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('bahan') }}</option>
                                @foreach ($bahans as $item)
                                    <option value="{{ $item->id }}"
                                        data-satuan-kecil="{{ $item->satuan_kecil->nama }}"
                                        data-satuan-besar="{{ $item->satuan_besar->nama }}"
                                        data-sejumlah="{{ $item->sejumlah }}">
                                        {{ ucwords(str_replace('_', ' ', $item->kode)) }} |
                                        {{ ucwords(str_replace('_', ' ', $item->nama)) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_${bahanCounter}" name="jumlah_dalam_satuan_kecil[]" placeholder="Jumlah Kecil" step="any" required>
                            <span class="satuan_kecil"></span>
                        </td>
                        <td>
                            <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_${bahanCounter}" name="jumlah_dalam_satuan_besar[]" placeholder="Jumlah Besar" step="any" readonly>
                            <span class="satuan_besar"></span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-bahan">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                        </td>
                    </tr>
                `;
                // Append the new row to the container
                $('#bahans_container').append(newRow);

                // Initialize Select2 for the new dropdown
                $('#bahan_' + bahanCounter).select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    updateSatuan($(this));
                });

                // Initialize remove button for new row
                initializeRemoveButton();
            }

            // Add bahan button click event
            $('#add_bahan').click(function() {
                addBahanRow();
            });

            // Handle input change for jumlah dalam satuan besar
            $(document).on('input', '.jumlah_dalam_satuan_besar', function() {
                const row = $(this).closest('tr');
                const jumlahBesar = parseFloat($(this).val());
                const selectElement = row.find('.bahan');
                const sejumlah = parseFloat(selectElement.find('option:selected').data('sejumlah'));

                if (!isNaN(jumlahBesar) && !isNaN(sejumlah)) {
                    const jumlahKecil = jumlahBesar * sejumlah;
                    row.find('.jumlah_dalam_satuan_kecil').val(jumlahKecil.toFixed(2));
                }
            });
        });
    </script>
@endsection