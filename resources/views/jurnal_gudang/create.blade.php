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
            <a href="{{ route('jurnal_gudang.create') }}">@yield('title')</a>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode" value="{{ $kode }}" placeholder="Masukkan kode ..." readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_jurnal_gudang">{{ ucReplaceUnderscoreToSpace('jenis_jurnal_gudang') }}</label>
                                            <select class="form-control jenis_jurnal_gudang_id" id="jenis_jurnal_gudang_id" name="jenis_jurnal_gudang_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('jenis_jurnal_gudang') }}</option>
                                                @foreach ($jenis_jurnal_gudangs as $jenis_jurnal_gudang)
                                                    <option value="{{ $jenis_jurnal_gudang->id }}">{{ $jenis_jurnal_gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gudang_id">{{ ucReplaceUnderscoreToSpace('gudang') }}</label>
                                            <select class="form-control gudang_id" id="gudang_id" name="gudang_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('gudang') }}</option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50%">{{ ucReplaceUnderscoreToSpace('material') }}</th>
                                                        <th style="width: 20%">{{ ucReplaceUnderscoreToSpace('jumlah') }}</th>
                                                        <th style="width: 20%">{{ ucReplaceUnderscoreToSpace('satuan_besar') }}</th>
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
                                                                            <option value="{{ $materialItem->id }}" data-satuan-besar="{{ $materialItem->satuan_besar->nama }}" {{ $materialItem->id == $material ? 'selected' : '' }}>
                                                                                {{ ucwords(str_replace('_', ' ', $materialItem->kode)) }} | {{ ucwords(str_replace('_', ' ', $materialItem->nama)) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control jumlah" id="jumlah_{{ $index }}" name="jumlahs[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah') }}" value="{{ old('jumlahs')[$index] }}" step="any" required>
                                                                </td>
                                                                <td>
                                                                    <span class="form-control satuan_besar_nama" id="satuan_besar_nama_{{ $index }}">
                                                                        {{ $materials->find($material)->satuan_besar->nama }}
                                                                    </span>
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
            // Function to initialize Select2 for material dropdowns
            function initializeSelect2() {
                $('.jenis_jurnal_gudang_id').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
                $('.gudang_id').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
                $('.material').select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    var index = $(this).attr('id').split('_')[1]; // Ambil indeks dari id dropdown
                    var satuanBesar = $(this).find(':selected').data('satuan-besar'); // Ambil data satuan-besar dari opsi terpilih

                    // Update teks pada span satuan_besar_nama
                    $('#satuan_besar_nama_' + index).text(satuanBesar);
                });
            }

            // Initialize Select2 for existing materials
            initializeSelect2();

            // Initialize remove button for existing materials
            $('.remove-material').click(function() {
                $(this).closest('tr').remove();
            });

            // Counter for unique IDs
            let materialCounter = {{ old('materials') ? count(old('materials')) : 0 }};

            // Function to add a new row for material selection
            function addMaterialRow() {
                materialCounter++;
                const newRowId = 'material_row_' + materialCounter;

                const newRow = `
                    <tr id="${newRowId}">
                        <td>
                            <select class="form-control material" id="material_${materialCounter}" name="materials[]" required>
                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('material') }}</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}" data-satuan-besar="{{ $material->satuan_besar->nama }}">
                                        {{ ucwords(str_replace('_', ' ', $material->kode)) }} |
                                        {{ ucwords(str_replace('_', ' ', $material->nama)) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control jumlah" id="jumlah_${materialCounter}" name="jumlahs[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah') }}" step="any" required>
                        </td>
                        <td>
                            <span class="form-control satuan_besar_nama" id="satuan_besar_nama_${materialCounter}"></span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-material">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                        </td>
                    </tr>
                `;

                // Append the new row to the materials table
                $('#materials_table').append(newRow);

                // Initialize Select2 for the new dropdown
                $('#material_' + materialCounter).select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    var index = $(this).attr('id').split('_')[1]; // Ambil indeks dari id dropdown
                    var satuanBesar = $(this).find(':selected').data('satuan-besar'); // Ambil data satuan-besar dari opsi terpilih

                    // Update teks pada span satuan_besar_nama
                    $('#satuan_besar_nama_' + index).text(satuanBesar);
                });

                // Initialize remove button for new row
                $('.remove-material').click(function() {
                    $(this).closest('tr').remove();
                });
            }

            // Add material button click event
            $('#add_material').click(function() {
                addMaterialRow();
            });
        });
    </script>
@endsection
