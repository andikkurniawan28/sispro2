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
                                            <label for="gudang_id">{{ ucReplaceUnderscoreToSpace('gudang') }}</label>
                                            <select class="form-control select2" id="gudang_id" name="gudang_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('gudang') }}</option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_jurnal_gudang_id">{{ ucReplaceUnderscoreToSpace('jenis_jurnal_gudang') }}</label>
                                            <select class="form-control select2" id="jenis_jurnal_gudang_id" name="jenis_jurnal_gudang_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('jenis_jurnal_gudang') }}</option>
                                                @foreach ($jenis_jurnal_gudangs as $jenis_jurnal_gudang)
                                                    <option value="{{ $jenis_jurnal_gudang->id }}">{{ $jenis_jurnal_gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="dynamic_form_container">
                                            <!-- Container for dynamically added fields -->
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <div class="btn-group" role="group" aria-label="Tambah Produk dan Bahan">
                                                    <button type="button" class="btn btn-success btn-sm" id="add_produk_akhir">{{ ucReplaceUnderscoreToSpace('tambah_produk_akhir') }}</button>
                                                    <button type="button" class="btn btn-success btn-sm" id="add_produk_reproses">{{ ucReplaceUnderscoreToSpace('tambah_produk_reproses') }}</button>
                                                    <button type="button" class="btn btn-success btn-sm" id="add_produk_samping">{{ ucReplaceUnderscoreToSpace('tambah_produk_samping') }}</button>
                                                    <button type="button" class="btn btn-success btn-sm" id="add_bahan_baku">{{ ucReplaceUnderscoreToSpace('tambah_bahan_baku') }}</button>
                                                </div>
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
            const produkAkhirs = @json($produk_akhirs);
            const produkReproses = @json($produk_reproses);
            const produkSampings = @json($produk_sampings);
            const bahanBakus = @json($bahan_bakus);

            function ucReplaceUnderscoreToSpace(text) {
                // Replace underscores with spaces
                let replacedText = text.replace(/_/g, ' ');

                // Capitalize the first letter of each word
                replacedText = replacedText.replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                });

                return replacedText;
            }

            function initializeSelect2() {
                $('.select2').select2({
                    theme: 'bootstrap',
                    width: '100%',
                });
            }

            function initializeRemoveButton() {
                $('.remove-item').click(function() {
                    $(this).closest('.row').remove();
                });
            }

            function createSelectOptions(data, placeholder) {
                let options = `<option disabled selected>${placeholder}</option>`;
                data.forEach(item => {
                    options += `<option value="${item.id}" data-satuan-besar="${item.satuan_besar.nama}" data-satuan-kecil="${item.satuan_kecil.nama}" data-sejumlah="${item.sejumlah}">${item.kode} | ${item.nama}</option>`;
                });
                return options;
            }

            function addField(type, data) {
                const placeholder = `Pilih ${ucReplaceUnderscoreToSpace(type)}`;
                const options = createSelectOptions(data, placeholder);
                const rowId = `${type}_${Math.random().toString(36).substring(7)}`;

                const newRow = `
                    <div class="row mb-3" id="${rowId}">
                        <div class="col-sm-4">
                            <label class="col-form-label" for="${rowId}_select">
                                ${ucReplaceUnderscoreToSpace(type)}
                            </label>
                            <select class="form-control select2" id="${rowId}_select" name="${type}_id[]" required>
                                ${options}
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-form-label satuan-besar-label" for="jumlah_besar_${rowId}"></label>
                            <input type="number" class="form-control jumlah-besar" id="jumlah_besar_${rowId}" name="jumlah_besar[]" required>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-form-label satuan-kecil-label" for="jumlah_kecil_${rowId}"></label>
                            <input type="number" class="form-control jumlah-kecil" id="jumlah_kecil_${rowId}" name="jumlah_kecil[]" required>
                        </div>
                        <div class="col-sm-2">
                            <label class="col-form-label" for="remove_${rowId}">
                                {{ ucReplaceUnderscoreToSpace('hapus') }}
                            </label>
                            <br>
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-item">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                        </div>
                    </div>
                `;

                $('#dynamic_form_container').append(newRow);
                initializeSelect2();
                initializeRemoveButton();
                initializeSatuanChange(rowId);
            }

            function initializeSatuanChange(rowId) {
                $(`#${rowId}_select`).change(function() {
                    const selectedOption = $(this).find('option:selected');
                    const satuanBesar = selectedOption.data('satuan-besar');
                    const satuanKecil = selectedOption.data('satuan-kecil');
                    const sejumlah = selectedOption.data('sejumlah');

                    $(`#${rowId} .satuan-besar-label`).text(satuanBesar);
                    $(`#${rowId} .satuan-kecil-label`).text(satuanKecil);

                    $(`#jumlah_besar_${rowId}, #jumlah_kecil_${rowId}`).off('input').on('input', function() {
                        const jumlahBesar = $(`#jumlah_besar_${rowId}`).val();
                        const jumlahKecil = $(`#jumlah_kecil_${rowId}`).val();

                        if ($(this).attr('id') === `jumlah_besar_${rowId}`) {
                            $(`#jumlah_kecil_${rowId}`).val(jumlahBesar * sejumlah);
                        } else {
                            $(`#jumlah_besar_${rowId}`).val(jumlahKecil / sejumlah);
                        }
                    });
                });
            }

            $('#add_produk_akhir').click(function() {
                addField('produk_akhir', produkAkhirs);
            });

            $('#add_produk_reproses').click(function() {
                addField('produk_reproses', produkReproses);
            });

            $('#add_produk_samping').click(function() {
                addField('produk_samping', produkSampings);
            });

            $('#add_bahan_baku').click(function() {
                addField('bahan_baku', bahanBakus);
            });

            // Initialize Select2 for existing rows
            initializeSelect2();

            // Initialize remove button for existing rows
            initializeRemoveButton();
        });
    </script>
@endsection
