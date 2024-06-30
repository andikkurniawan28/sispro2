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
            <a href="{{ route('permintaan.index') }}">@yield('title')</a>
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
                                                value="{{ old('kode', $permintaan->kode) }}" placeholder="Masukkan kode ..." readonly>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="berlaku_sampai">{{ ucReplaceUnderscoreToSpace('berlaku_sampai') }}</label>
                                            <input type="datetime-local" class="form-control" id="berlaku_sampai"
                                                name="berlaku_sampai" value="{{ old('berlaku_sampai', $permintaan->berlaku_sampai) }}"
                                                placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('berlaku_sampai') }} ..." required autofocus>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="materials_container">
                                            @if (old('materials', $permintaan_detail ))
                                                @foreach (old('materials', $permintaan_detail ) as $index => $material)
                                                    <div class="row mb-3" id="material_row_{{ $index }}">
                                                        <div class="col-sm-4">
                                                            <label class="col-form-label" for="material_{{ $index }}">
                                                                {{ ucReplaceUnderscoreToSpace('material') }}
                                                            </label>
                                                            <select class="material form-control" id="material_{{ $index }}" name="materials[]" required>
                                                                <option disabled>Pilih {{ ucReplaceUnderscoreToSpace('material') }}</option>
                                                                @foreach ($materials as $materialItem)
                                                                    <option value="{{ $materialItem->id }}" {{ $materialItem->id == $material->material_id ? 'selected' : '' }}>
                                                                        {{ ucwords(str_replace('_', ' ', $materialItem->kode)) }} | {{ ucwords(str_replace('_', ' ', $materialItem->nama)) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label class="col-form-label" for="jumlah_{{ $index }}">
                                                                {{ ucReplaceUnderscoreToSpace('jumlah') }}
                                                            </label>
                                                            <input type="number" class="form-control jumlah" id="jumlah_{{ $index }}" name="jumlahs[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah') }}" value="{{ old('jumlahs')[$index] ?? $material->jumlah }}" required>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label class="col-form-label" for="satuan_besar_nama_{{ $index }}">
                                                                Satuan
                                                            </label>
                                                            <span class="satuan_besar_nama form-control" id="satuan_besar_nama_{{ $index }}">
                                                                {{ $materials->find($material->material_id)->satuan_besar->nama }}
                                                            </span>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label class="col-form-label" for="remove_{{ $index }}">
                                                                {{ ucReplaceUnderscoreToSpace('hapus') }}
                                                            </label>
                                                            <br>
                                                            <button type="button" class="btn btn-danger btn-sm btn-block remove-material">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="button" class="btn btn-success" id="add_material">{{ ucReplaceUnderscoreToSpace('tambah_produk') }}</button>
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
            }

            function initialize{{ ucReplaceUnderscoreToSpace('hapus') }}Button() {
                $('.remove-material').click(function() {
                    $(this).closest('.row').remove();
                });
            }

            // Initialize Select2 for existing rows
            initializeSelect2();

            // Initialize {{ ucReplaceUnderscoreToSpace('hapus') }} button for existing rows
            initialize{{ ucReplaceUnderscoreToSpace('hapus') }}Button();

            // Products data with satuan_besar namas
            const materialsData = @json($materials->mapWithKeys(function($item) {
                return [$item->id => $item->satuan_besar->nama];
            }));

            // Counter for unique IDs
            let materialCounter = {{ old('materials', $permintaan_detail ) ? count(old('materials', $permintaan_detail )) : 0 }};

            // Function to add a new Select2 material dropdown with jumlah input
            function addProductDropdown() {
                materialCounter++;
                const newDropdownId = 'material_' + materialCounter;

                // Create new row with Select2 dropdown and jumlah input
                const newRow = `
                    <div class="row mb-3" id="material_row_${materialCounter}">
                        <div class="col-sm-4">
                            <label class="col-form-label" for="${newDropdownId}">
                                {{ ucReplaceUnderscoreToSpace('material') }}
                            </label>
                            <select class="material form-control" id="${newDropdownId}" name="materials[]" required>
                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('material') }}</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}">
                                        {{ ucwords(str_replace('_', ' ', $material->kode)) }} |
                                        {{ ucwords(str_replace('_', ' ', $material->nama)) }}
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
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-material">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                        </div>
                    </div>
                `;

                // Append the new row to the container
                $('#materials_container').append(newRow);

                // Initialize Select2 for the new dropdown
                $('#' + newDropdownId).select2({
                    theme: 'bootstrap',
                    width: '100%',
                });

                // Add change event listener to update satuan_besar nama
                $('#' + newDropdownId).on('change', function() {
                    const selectedProductId = $(this).val();
                    const satuan_besarSymbol = materialsData[selectedProductId];
                    $('#satuan_besar_nama_' + newDropdownId).text(satuan_besarSymbol);
                });

                // Initialize {{ ucReplaceUnderscoreToSpace('hapus') }} button for new row
                initialize{{ ucReplaceUnderscoreToSpace('hapus') }}Button();
            }

            // Add material button click event
            $('#add_material').click(function() {
                addProductDropdown();
            });
        });
    </script>
@endsection
