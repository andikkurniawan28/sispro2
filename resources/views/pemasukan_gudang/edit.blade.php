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
                                @csrf @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{ $data->kode }}" placeholder="Masukkan kode ..." readonly>
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
                                                        {{ $data->gudang_id == $gudang->id ? 'selected' : '' }}>
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
                                                        <th style="width: 25%">{{ ucReplaceUnderscoreToSpace('material') }}
                                                        </th>
                                                        <th style="width: 15%">
                                                            {{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}</th>
                                                        <th style="width: 15%">
                                                            {{ ucReplaceUnderscoreToSpace('jumlah_besar') }}</th>
                                                        <th style="width: 15%">{{ ucReplaceUnderscoreToSpace('harga') }}
                                                        </th>
                                                        <th style="width: 20%">{{ ucReplaceUnderscoreToSpace('total') }}
                                                        </th>
                                                        <th style="width: 10%">{{ ucReplaceUnderscoreToSpace('hapus') }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="materials_table">
                                                    @foreach ($pemasukan_gudang_detail as $index => $pemasukan_gudang)
                                                        <tr id="material_row_{{ $index }}">
                                                            <td>
                                                                <select class="material form-control"
                                                                    id="material_{{ $index }}" name="materials[]"
                                                                    required>
                                                                    <option disabled selected>Pilih
                                                                        {{ ucReplaceUnderscoreToSpace('material') }}
                                                                    </option>
                                                                    @foreach ($materials as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            {{ $pemasukan_gudang->material_id == $item->id ? 'selected' : '' }}
                                                                            data-satuan-kecil="{{ $item->satuan_kecil->nama }}"
                                                                            data-satuan-besar="{{ $item->satuan_besar->nama }}"
                                                                            data-sejumlah="{{ $item->sejumlah }}"
                                                                            data-harga-jual="{{ $item->harga_jual }}"
                                                                            >
                                                                            {{ ucwords(str_replace('_', ' ', $item->kode)) }}
                                                                            |
                                                                            {{ ucwords(str_replace('_', ' ', $item->nama)) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    class="form-control jumlah_dalam_satuan_kecil"
                                                                    id="jumlah_dalam_satuan_kecil_{{ $index }}"
                                                                    name="jumlah_kecil[]" placeholder="Jumlah Kecil"
                                                                    value="{{ $pemasukan_gudang->jumlah_dalam_satuan_kecil }}"
                                                                    step="any" required>
                                                                <span id="satuan_kecil_{{ $index }}"
                                                                    class="satuan_kecil">{{ $pemasukan_gudang->material->satuan_kecil->nama }}</span>
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    class="form-control jumlah_dalam_satuan_besar"
                                                                    id="jumlah_dalam_satuan_besar_{{ $index }}"
                                                                    name="jumlah_besar[]" placeholder="Jumlah Besar"
                                                                    value="{{ $pemasukan_gudang->jumlah_dalam_satuan_besar }}"
                                                                    step="any">
                                                                <span id="satuan_besar_{{ $index }}"
                                                                    class="satuan_besar">{{ $pemasukan_gudang->material->satuan_besar->nama }}</span>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control harga" id="harga_{{ $index }}" name="harga[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('harga') }}" value="{{ $pemasukan_gudang->harga }}" step="any" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control total" id="total_{{ $index }}" name="total[]" value="{{ $pemasukan_gudang->total }}" readonly>
                                                                <span class="total_span" id="total_span_{{ $index }}">{{ formatRupiah($pemasukan_gudang->total) }}</span>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm btn-block remove-material">{{ ucReplaceUnderscoreToSpace('hapus') }}</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>{{ ucReplaceUnderscoreToSpace('grand_total') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input class="form-control" id="grand_total" name="grand_total" value="" readonly>
                                                            <span class="grand_total_span" id="grand_total_span"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-12 offset-sm-2">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-success"
                                                    id="add_material">{{ ucReplaceUnderscoreToSpace('tambah_material') }}</button>
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
        function formatRupiah(angka, prefix) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : 'Rp. 0');
        }

        $(document).ready(function() {
            function initializeSelect2() {
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
                const hargaJual = selectElement.find('option:selected').data('harga-jual');
                const sejumlah = parseFloat(selectElement.find('option:selected').data('sejumlah'));

                row.find('.satuan_kecil').text(satuanKecil);
                row.find('.satuan_besar').text(satuanBesar);
                row.find('input.harga').val(hargaJual);

                function calculateTotal() {
                    const jumlahBesar = parseFloat(row.find('.jumlah_dalam_satuan_besar').val()) || 0;
                    const harga = parseFloat(row.find('input.harga').val()) || 0;
                    const total = jumlahBesar * harga;
                    row.find('.total').val(total);
                    row.find('.total_span').text(formatRupiah(total, 'Rp. '));
                    hitungGrandTotal();
                }

                row.find('.jumlah_dalam_satuan_kecil').on('input', function() {
                    const jumlahKecil = parseFloat($(this).val());
                    const jumlahBesar = (jumlahKecil / sejumlah) || 0;
                    row.find('.jumlah_dalam_satuan_besar').val(jumlahBesar);
                    calculateTotal();
                });

                row.find('.jumlah_dalam_satuan_besar').on('input', function() {
                    const jumlahBesar = parseFloat($(this).val());
                    const jumlahKecil = jumlahBesar * sejumlah || 0;
                    row.find('.jumlah_dalam_satuan_kecil').val(jumlahKecil);
                    calculateTotal();
                });

                row.find('input.harga').on('input', function() {
                    calculateTotal();
                });
            }

            function hitungGrandTotal() {
                let grandTotal = 0;
                $('#materials_table tr').each(function() {
                    const total = parseFloat($(this).find('.total').val()) || 0;
                    grandTotal += total;
                });
                $('#grand_total').val(grandTotal.toFixed(0)); // Menghilangkan angka desimal
                $('#grand_total_span').text(formatRupiah(grandTotal.toFixed(0), 'Rp. ')); // Menghilangkan angka desimal
            }

            function ucReplaceUnderscoreToSpace(str) {
                return str.replace(/_/g, ' ').replace(/\b\w/g, function(txt) {
                    return txt.toUpperCase();
                });
            }

            $('#add_material').click(function() {
                const index = $('#materials_table tr').length;
                $('#materials_table').append(`
                    <tr id="material_row_${index}">
                        <td>
                            <select class="form-control material" id="material_${index}" name="materials[]" required>
                                <option disabled selected>Pilih ${ucReplaceUnderscoreToSpace('material')}</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}" data-satuan-kecil="{{ $material->satuan_kecil->nama }}" data-satuan-besar="{{ $material->satuan_besar->nama }}" data-sejumlah="{{ $material->sejumlah }}" data-harga-jual="{{ $material->harga_jual }}">
                                        {{ ucwords(str_replace('_', ' ', $material->kode)) }} | {{ ucwords(str_replace('_', ' ', $material->nama)) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_${index}" name="jumlah_kecil[]" placeholder="Masukkan ${ucReplaceUnderscoreToSpace('jumlah_kecil')}" step="any" required>
                            <span class="satuan_kecil" id="satuan_kecil_${index}"></span>
                        </td>
                        <td>
                            <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_${index}" name="jumlah_besar[]" placeholder="Masukkan ${ucReplaceUnderscoreToSpace('jumlah_besar')}" step="any" required>
                            <span class="satuan_besar" id="satuan_besar_${index}"></span>
                        </td>
                        <td>
                            <input type="number" class="form-control harga" id="harga_${index}" name="harga[]" placeholder="Masukkan ${ucReplaceUnderscoreToSpace('harga')}" step="any">
                        </td>
                        <td>
                            <input type="text" class="form-control total" id="total_${index}" name="total[]" readonly>
                            <span class="total_span" id="total_span_${index}"></span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-block remove-material">${ucReplaceUnderscoreToSpace('hapus')}</button>
                        </td>
                    </tr>
                `);
                initializeSelect2();
                initializeRemoveButton();
            });

            initializeSelect2();
            initializeRemoveButton();

            // Tambahkan inisialisasi ini agar berfungsi saat nilai lama dikembalikan
            $(document).ready(function() {
                $('#materials_table select.material').each(function() {
                    updateSatuan($(this));
                    hitungGrandTotal($(this));
                });
            });

        });
    </script>
@endsection
