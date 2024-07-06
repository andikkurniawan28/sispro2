@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('pemasukan_gudang') }}
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
            <a href="{{ route('pemasukan_gudang.index') }}">{{ ucReplaceUnderscoreToSpace('pemasukan_gudang') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('pemasukan_gudang.index') }}">@yield('title')</a>
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
                            <form action="{{ route('pemasukan_gudang.update', $data->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{ $data->kode }}" placeholder="Masukkan kode ..." readonly>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="jenis_pemasukan_gudang_id">{{ ucReplaceUnderscoreToSpace('jenis_pemasukan_gudang') }}</label>
                                            <select class="jenis_pemasukan_gudang form-control @error('jenis_pemasukan_gudang_id') is-invalid @enderror" id="jenis_pemasukan_gudang_id" name="jenis_pemasukan_gudang_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('jenis_pemasukan_gudang') }}</option>
                                                @foreach ($jenis_pemasukan_gudangs as $jenis_pemasukan_gudang)
                                                    <option value="{{ $jenis_pemasukan_gudang->id }}" {{ $data->jenis_pemasukan_gudang_id == $jenis_pemasukan_gudang->id ? 'selected' : '' }} data-kode-jurnal-gudang="{{ $jenis_pemasukan_gudang->kode }}">
                                                        {{ ucwords(str_replace('_', ' ', $jenis_pemasukan_gudang->nama)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jenis_pemasukan_gudang_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div> --}}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gudang_id">{{ ucReplaceUnderscoreToSpace('gudang') }}</label>
                                            <select class="gudang form-control @error('gudang_id') is-invalid @enderror" id="gudang_id" name="gudang_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('gudang') }}</option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}" {{ $data->gudang_id == $gudang->id ? 'selected' : '' }}>
                                                        {{-- {{ ucwords(str_replace('_', ' ', $gudang->kode)) }} | --}}
                                                        {{ ucwords(str_replace('_', ' ', $gudang->nama)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('gudang_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50%">{{ ucReplaceUnderscoreToSpace('material') }}</th>
                                                        <th style="width: 20%">{{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}</th>
                                                        <th style="width: 20%">{{ ucReplaceUnderscoreToSpace('jumlah_besar') }}</th>
                                                        <th style="width: 10%">{{ ucReplaceUnderscoreToSpace('hapus') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="materials_container">
                                                    @foreach ($pemasukan_gudang_detail as $index => $pemasukan_gudang)
                                                        <tr id="material_row_{{ $index }}">
                                                            <td>
                                                                <select class="material form-control" id="material_{{ $index }}" name="materials[]" required>
                                                                    <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('material') }}</option>
                                                                    @foreach ($materials as $item)
                                                                        <option value="{{ $item->id }}" {{ $pemasukan_gudang->material_id == $item->id ? 'selected' : '' }}
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
                                                                <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_{{ $index }}" name="jumlah_kecil[]" placeholder="Jumlah Kecil" value="{{ $pemasukan_gudang->jumlah_dalam_satuan_kecil }}" step="any" required>
                                                                <span id="satuan_kecil_{{ $index }}" class="satuan_kecil">{{ $pemasukan_gudang->material->satuan_kecil->nama }}</span>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_{{ $index }}" name="jumlah_besar[]" placeholder="Jumlah Besar" value="{{ $pemasukan_gudang->jumlah_dalam_satuan_besar }}" step="any">
                                                                <span id="satuan_besar_{{ $index }}" class="satuan_besar">{{ $pemasukan_gudang->material->satuan_besar->nama }}</span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm btn-block remove-material">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-success" id="add_material">{{ ucReplaceUnderscoreToSpace('tambah_material') }}</button>
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
                $('.material').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
                $('.gudang').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
                $('.material').select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    updateSatuan($(this));
                });
            }

            function initializeRemoveButton() {
                $('.remove-material').click(function() {
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
                const selectElement = row.find('.material');
                const sejumlah = parseFloat(selectElement.find('option:selected').data('sejumlah'));

                if (!isNaN(jumlahKecil) && !isNaN(sejumlah)) {
                    const jumlahBesar = jumlahKecil / sejumlah;
                    row.find('.jumlah_dalam_satuan_besar').val(jumlahBesar.toFixed(2));
                }
            }

            function calculateJumlahKecil(inputElement) {
                const row = inputElement.closest('tr');
                const jumlahBesar = parseFloat(inputElement.val());
                const selectElement = row.find('.material');
                const sejumlah = parseFloat(selectElement.find('option:selected').data('sejumlah'));

                if (!isNaN(jumlahBesar) && !isNaN(sejumlah)) {
                    const jumlahKecil = jumlahBesar * sejumlah;
                    row.find('.jumlah_dalam_satuan_kecil').val(jumlahKecil.toFixed(2));
                }
            }

            // Initialize Select2 and remove button for existing rows
            initializeSelect2();
            initializeRemoveButton();

            // Handle input change for jumlah dalam satuan kecil
            $(document).on('input', '.jumlah_dalam_satuan_kecil', function() {
                calculateJumlahBesar($(this));
            });

            // Handle input change for jumlah dalam satuan besar
            $(document).on('input', '.jumlah_dalam_satuan_besar', function() {
                calculateJumlahKecil($(this));
            });

            // Counter for unique IDs
            let materialCounter = {{ $pemasukan_gudang_detail->count() }};

            // Function to add a new row for material selection and jumlah input
            function addBahanRow() {
                materialCounter++;
                const newRowId = 'material_row_' + materialCounter;

                const newRow = `
                    <tr id="${newRowId}">
                        <td>
                            <select class="material form-control" id="material_${materialCounter}" name="materials[]" required>
                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('material') }}</option>
                                @foreach ($materials as $item)
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
                            <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_${materialCounter}" name="jumlah_kecil[]" placeholder="Jumlah Kecil" step="any" required>
                            <span class="satuan_kecil"></span>
                        </td>
                        <td>
                            <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_${materialCounter}" name="jumlah_besar[]" placeholder="Jumlah Besar" step="any">
                            <span class="satuan_besar"></span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-material">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                        </td>
                    </tr>
                `;
                // Append the new row to the container
                $('#materials_container').append(newRow);

                // Initialize Select2 for the new dropdown
                $('#material_' + materialCounter).select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    updateSatuan($(this));
                });

                // Initialize remove button for new row
                initializeRemoveButton();
            }

            // Add material button click event
            $('#add_material').click(function() {
                addBahanRow();
            });
        });
    </script>
@endsection
