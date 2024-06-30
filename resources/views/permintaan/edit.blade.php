@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('permintaan') }}
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
            <a href="{{ route('permintaan.index') }}">{{ ucReplaceUnderscoreToSpace('permintaan') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('permintaan.edit', $permintaan->id) }}">@yield('title')</a>
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
                            <form action="{{ route('permintaan.update', $permintaan->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{ $permintaan->kode }}" placeholder="Masukkan kode ..." readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="berlaku_sampai">{{ ucReplaceUnderscoreToSpace('berlaku_sampai') }}</label>
                                            <input type="datetime-local" class="form-control" id="berlaku_sampai"
                                                name="berlaku_sampai" value="{{ $permintaan->berlaku_sampai }}"
                                                placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('berlaku_sampai') }} ..." required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>{{ ucReplaceUnderscoreToSpace('material') }}</th>
                                                        <th>{{ ucReplaceUnderscoreToSpace('jumlah') }}</th>
                                                        <th>{{ ucReplaceUnderscoreToSpace('satuan_besar') }}</th>
                                                        <th>{{ ucReplaceUnderscoreToSpace('hapus') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="materials_table">
                                                    @foreach ($permintaan_detail as $index => $material)
                                                        <tr id="material_row_{{ $index }}">
                                                            <td>
                                                                <select class="form-control material" id="material_{{ $index }}" name="materials[]" required>
                                                                    <option disabled>Pilih {{ ucReplaceUnderscoreToSpace('material') }}</option>
                                                                    @foreach ($materials as $materialItem)
                                                                        <option value="{{ $materialItem->id }}" data-satuan-besar="{{ $materialItem->satuan_besar->nama }}" {{ $materialItem->id == $material->material->id ? 'selected' : '' }}>
                                                                            {{ ucwords(str_replace('_', ' ', $materialItem->kode)) }} | {{ ucwords(str_replace('_', ' ', $materialItem->nama)) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control jumlah" id="jumlah_{{ $index }}" name="jumlahs[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah') }}" value="{{ $material->jumlah }}" step="any" required>
                                                            </td>
                                                            <td>
                                                                <span class="form-control satuan_besar_nama" id="satuan_besar_nama_{{ $index }}">
                                                                    {{ $material->material->satuan_besar->nama }}
                                                                </span>
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
            let materialCounter = {{ count($permintaan->detail) }};

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

            // Initialize Select2 for existing materials on page load
            $('.material').each(function(index) {
                $(this).select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    var satuanBesar = $(this).find(':selected').data('satuan-besar');
                    $('#satuan_besar_nama_' + index).text(satuanBesar);
                });
            });
        });
    </script>
@endsection
