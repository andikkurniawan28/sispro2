@extends('template.kaiadmin.master')

@section('title')
    Edit {{ ucReplaceUnderscoreToSpace('kebutuhan_bahan_baku_untuk_produk_akhir') }}
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
            <a href="{{ route('kbbupa.index') }}">{{ ucReplaceUnderscoreToSpace('kebutuhan_bahan_baku_untuk_produk_akhir') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('kbbupa.edit', $kebutuhan_bahan_baku_untuk_produk_akhir[0]->id) }}">@yield('title')</a>
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
                            <form action="{{ route('kbbupa.update', $id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="produk_akhir_id">{{ ucReplaceUnderscoreToSpace('produk_akhir') }}</label>
                                            <select class="produk_akhir form-control" id="produk_akhir_id" name="produk_akhir_id" required>
                                                <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('produk_akhir') }}</option>
                                                @foreach ($produk_akhirs as $produk_akhir)
                                                    <option value="{{ $produk_akhir->id }}" {{ $produk_akhir->id == $kebutuhan_bahan_baku_untuk_produk_akhir[0]->produk_akhir_id ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $produk_akhir->kode)) }} |
                                                        {{ ucwords(str_replace('_', ' ', $produk_akhir->nama)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="bahan_bakus_container">
                                            @foreach ($kebutuhan_bahan_baku_untuk_produk_akhir as $index => $kebutuhan)
                                                <div class="row mb-3" id="bahan_baku_row_{{ $index }}">
                                                    <div class="col-sm-4">
                                                        <label for="bahan_baku_{{ $index }}">{{ ucReplaceUnderscoreToSpace('bahan_baku') }}</label>
                                                        <select class="bahan_baku form-control" id="bahan_baku_{{ $index }}" name="bahan_bakus[]" required>
                                                            <option disabled selected>Pilih {{ ucReplaceUnderscoreToSpace('bahan_baku') }}</option>
                                                            @foreach ($bahan_bakus as $item)
                                                                <option value="{{ $item->id }}"
                                                                    data-satuan-kecil="{{ $item->satuan_kecil->nama }}"
                                                                    data-satuan-besar="{{ $item->satuan_besar->nama }}"
                                                                    data-sejumlah="{{ $item->sejumlah }}"
                                                                    {{ $item->id == $kebutuhan->bahan_baku_id ? 'selected' : '' }}>
                                                                    {{ ucwords(str_replace('_', ' ', $item->kode)) }} |
                                                                    {{ ucwords(str_replace('_', ' ', $item->nama)) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label for="jumlah_dalam_satuan_kecil_{{ $index }}">{{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}</label>
                                                        <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_{{ $index }}" name="jumlah_dalam_satuan_kecil[]" placeholder="Jumlah Kecil" value="{{ $kebutuhan->jumlah_dalam_satuan_kecil }}" step="any" required>
                                                        <span id="satuan_kecil_{{ $index }}" class="satuan_kecil">{{ $kebutuhan->bahan_baku->satuan_kecil->nama }}</span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label for="jumlah_dalam_satuan_besar_{{ $index }}">{{ ucReplaceUnderscoreToSpace('jumlah_besar') }}</label>
                                                        <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_{{ $index }}" name="jumlah_dalam_satuan_besar[]" placeholder="Jumlah Besar" value="{{ $kebutuhan->jumlah_dalam_satuan_besar }}" step="any" readonly>
                                                        <span id="satuan_besar_{{ $index }}" class="satuan_besar">{{ $kebutuhan->bahan_baku->satuan_besar->nama }}</span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label for="remove_{{ $index }}">{{ ucReplaceUnderscoreToSpace('hapus') }}</label>
                                                        <br>
                                                        <button type="button" class="btn btn-danger btn-sm btn-block remove-bahan_baku">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button type="button" class="btn btn-success" id="add_bahan_baku">{{ ucReplaceUnderscoreToSpace('tambah_bahan_baku') }}</button>
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
                $('.produk_akhir').select2({
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

            initializeSelect2();
            initializeRemoveButton();

            $(document).on('input', '.jumlah_dalam_satuan_kecil', function() {
                calculateJumlahBesar($(this));
            });

            let bahan_bakuCounter = {{ count($kebutuhan_bahan_baku_untuk_produk_akhir) }};

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
                $('#bahan_bakus_container').append(newRow);

                $(`#${newRowId} .bahan_baku`).select2({
                    theme: 'bootstrap',
                    width: '100%',
                }).on('change', function() {
                    updateSatuan($(this));
                });

                initializeRemoveButton();
            }

            $('#add_bahan_baku').click(function() {
                addBahanBakuRow();
            });
        });
    </script>
@endsection
