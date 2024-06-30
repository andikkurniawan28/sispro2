@extends('template.kaiadmin.master')

@section('title')
    Tambah {{ ucReplaceUnderscoreToSpace('jurnal_gudang') }}
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
            <a href="{{ route('jurnal_gudang.index') }}">{{ ucReplaceUnderscoreToSpace('jurnal_gudang') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('jurnal_gudang.index') }}">@yield('title')</a>
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
                            <form action="{{ route('jurnal_gudang.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{ $kode }}" placeholder="Masukkan kode ..." readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_jurnal_gudang_id">{{ ucReplaceUnderscoreToSpace('jenis_jurnal_gudang') }}</label>
                                            <select class="jenis_jurnal_gudang form-control @error('jenis_jurnal_gudang_id') is-invalid @enderror"
                                                id="jenis_jurnal_gudang_id" name="jenis_jurnal_gudang_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('jenis_jurnal_gudang') }}
                                                </option>
                                                @foreach ($jenis_jurnal_gudangs as $jenis_jurnal_gudang)
                                                    <option value="{{ $jenis_jurnal_gudang->id }}" data-kode-jurnal-gudang="{{ $jenis_jurnal_gudang->kode }}">
                                                        {{ ucwords(str_replace('_', ' ', $jenis_jurnal_gudang->nama)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jenis_jurnal_gudang_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gudang_id">{{ ucReplaceUnderscoreToSpace('gudang') }}</label>
                                            <select class="gudang form-control @error('gudang_id') is-invalid @enderror"
                                                id="gudang_id" name="gudang_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('gudang') }}
                                                </option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}"
                                                        {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
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
                                                <tbody id="materials_table">
                                                    @if (old('materials'))
                                                        @foreach (old('materials') as $index => $material)
                                                            <tr id="material_row_{{ $index }}">
                                                                <td>
                                                                    <select class="form-control material" id="material_{{ $index }}" name="materials[]" required>
                                                                        <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('material') }}</option>
                                                                        @foreach ($materials as $materialItem)
                                                                            <option value="{{ $materialItem->id }}" data-satuan-kecil="{{ $materialItem->satuan_kecil->nama }}" data-satuan-besar="{{ $materialItem->satuan_besar->nama }}" data-sejumlah="{{ $materialItem->sejumlah }}" {{ $materialItem->id == $material ? 'selected' : '' }}>
                                                                                {{ ucwords(str_replace('_', ' ', $materialItem->kode)) }} | {{ ucwords(str_replace('_', ' ', $materialItem->nama)) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_{{ $index }}" name="jumlah_kecil[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}" value="{{ old('jumlah_kecil')[$index] }}" step="any" required>
                                                                    <span class="satuan_kecil" id="satuan_kecil_{{ $index }}">{{ $materials->find($material)->satuan_kecil->nama }}</span>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_{{ $index }}" name="jumlah_besar[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah_besar') }}" value="{{ old('jumlah_besar')[$index] }}" step="any" required>
                                                                    <span class="satuan_besar" id="satuan_besar_{{ $index }}">{{ $materials->find($material)->satuan_besar->nama }}</span>
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm btn-block remove-material">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-12 offset-sm-2">
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
                $('.jenis_jurnal_gudang').select2({
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
                    var index = $(this).attr('id').split('_')[1];
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
                const jumlahBesarInput = row.find('.jumlah_dalam_satuan_besar');
                calculateJumlahBesar(jumlahKecilInput);
                calculateJumlahKecil(jumlahBesarInput);
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

            function updateKode(selectElement) {
                const kodeInput = $('#kode');
                const jenisJurnalGudangKode = selectElement.find('option:selected').data('kode-jurnal-gudang');
                const defaultKode = "{{ $kode }}";
                const newKode = jenisJurnalGudangKode + defaultKode;
                kodeInput.val(newKode);
            }

            initializeSelect2();
            initializeRemoveButton();

            $(document).on('change', '.jenis_jurnal_gudang', function() {
                updateKode($(this));
            });

            $(document).on('input', '.jumlah_dalam_satuan_kecil', function() {
                calculateJumlahBesar($(this));
            });

            $(document).on('input', '.jumlah_dalam_satuan_besar', function() {
                calculateJumlahKecil($(this));
            });

            let materialCounter = {{ old('materials') ? count(old('materials')) : 0 }};

            function addMaterialRow() {
                materialCounter++;
                const newRowId = 'material_row_' + materialCounter;
                const newRow = `
                    <tr id="${newRowId}">
                        <td>
                            <select class="form-control material" id="material_${materialCounter}" name="materials[]" required>
                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('material') }}</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}" data-satuan-kecil="{{ $material->satuan_kecil->nama }}" data-satuan-besar="{{ $material->satuan_besar->nama }}" data-sejumlah="{{ $material->sejumlah }}">
                                        {{ ucwords(str_replace('_', ' ', $material->kode)) }} | {{ ucwords(str_replace('_', ' ', $material->nama)) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_${materialCounter}" name="jumlah_kecil[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}" step="any" required>
                            <span class="satuan_kecil" id="satuan_kecil_${materialCounter}"></span>
                        </td>
                        <td>
                            <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_${materialCounter}" name="jumlah_besar[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah_besar') }}" step="any" required>
                            <span class="satuan_besar" id="satuan_besar_${materialCounter}"></span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-material">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                        </td>
                    </tr>
                `;
                $('#materials_table').append(newRow);
                $('#material_' + materialCounter).select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    updateSatuan($(this));
                });
                initializeRemoveButton();
            }

            $('#add_material').click(function() {
                addMaterialRow();
            });
        });
    </script>
@endsection
