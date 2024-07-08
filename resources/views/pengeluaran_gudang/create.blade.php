@extends('template.kaiadmin.master')

@section('title')
    Tambah {{ ucReplaceUnderscoreToSpace('pengeluaran_gudang') }}
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
            <a href="{{ route('pengeluaran_gudang.index') }}">{{ ucReplaceUnderscoreToSpace('pengeluaran_gudang') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('pengeluaran_gudang.index') }}">@yield('title')</a>
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
                            <form action="{{ route('pengeluaran_gudang.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kode">{{ ucReplaceUnderscoreToSpace('kode') }}</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{ $kode }}" placeholder="Masukkan kode ..." readonly>
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
                                                        <th style="width: 25%">{{ ucReplaceUnderscoreToSpace('material') }}</th>
                                                        <th style="width: 15%">{{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}</th>
                                                        <th style="width: 15%">{{ ucReplaceUnderscoreToSpace('jumlah_besar') }}</th>
                                                        <th style="width: 15%">{{ ucReplaceUnderscoreToSpace('harga') }}</th>
                                                        <th style="width: 20%">{{ ucReplaceUnderscoreToSpace('total') }}</th>
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
                                                                            <option value="{{ $materialItem->id }}" data-satuan-kecil="{{ $materialItem->satuan_kecil->nama }}" data-satuan-besar="{{ $materialItem->satuan_besar->nama }}" data-sejumlah="{{ $materialItem->sejumlah }}" data-harga-jual="{{ $materialItem->harga_jual }}" {{ $materialItem->id == $material ? 'selected' : '' }}>
                                                                                {{ ucwords(str_replace('_', ' ', $materialItem->kode)) }} | {{ ucwords(str_replace('_', ' ', $materialItem->nama)) }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control jumlah_dalam_satuan_kecil" id="jumlah_dalam_satuan_kecil_{{ $index }}" name="jumlah_kecil[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah_kecil') }}" value="{{ old('jumlah_kecil')[$index] ?? '' }}" step="any" required>
                                                                    <span class="satuan_kecil" id="satuan_kecil_{{ $index }}">{{ $materials->find($material)->satuan_kecil->nama }}</span>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control jumlah_dalam_satuan_besar" id="jumlah_dalam_satuan_besar_{{ $index }}" name="jumlah_besar[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('jumlah_besar') }}" value="{{ old('jumlah_besar')[$index] ?? '' }}" step="any" required>
                                                                    <span class="satuan_besar" id="satuan_besar_{{ $index }}">{{ $materials->find($material)->satuan_besar->nama }}</span>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control harga" id="harga_{{ $index }}" name="harga[]" placeholder="Masukkan {{ ucReplaceUnderscoreToSpace('harga') }}" value="{{ old('harga')[$index] ?? '' }}" step="any">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control total" id="total_{{ $index }}" name="total[]" value="{{ old('total')[$index] ?? '' }}" readonly>
                                                                    <span class="total_span" id="total_span_{{ $index }}">{{ formatRupiah(old('total')[$index]) ?? '' }}</span>
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
                });
            });

        });
    </script>
@endsection
