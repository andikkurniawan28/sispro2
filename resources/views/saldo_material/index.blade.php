@extends('template.kaiadmin.master')

@section('title')
    {{ ucReplaceUnderscoreToSpace('saldo_material') }}
@endsection

@section('akses-aktif')
    {{ 'active' }}
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
            <a href="{{ route('saldo_material.index') }}">@yield('title')</a>
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
                        <div class="card-header">
                            <div class="card-title text-white text-right">
                                <button id="exportExcel" class="btn btn-sm btn-success">Export Excel</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mt-4" id="saldo_material-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ ucReplaceUnderscoreToSpace('kode') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('nama') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('satuan') }}</th>
                                            @foreach ($gudangs as $gudang)
                                            <th>{{ ucReplaceUnderscoreToSpace($gudang->nama) }}</th>
                                            @endforeach
                                            <th>{{ ucReplaceUnderscoreToSpace('tindakan') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan CDN untuk SheetJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <!-- Tambahkan CDN untuk jQuery dan DataTables -->
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('#saldo_material-table').DataTable({
                order: [
                    [0, 'desc']
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('saldo_material.index') }}",
                columns: [
                    { data: 'kode', name: 'kode' },
                    { data: 'nama', name: 'nama' },
                    { data: 'satuan_besar', name: 'satuan_besar' },
                    @foreach ($gudangs as $gudang)
                    { data: '{{ ucReplaceSpaceToUnderscore($gudang->nama) }}', name: '{{ ucReplaceSpaceToUnderscore($gudang->nama) }}' },
                    @endforeach
                    { data: 'tindakan', name: 'tindakan', orderable: false, searchable: false },
                ]
            });

            $('#exportExcel').on('click', function() {
                var data = table.rows().data().toArray();
                var ws = XLSX.utils.json_to_sheet(data);
                var wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
                XLSX.writeFile(wb, 'Saldo_Material.xlsx');
            });
        });
    </script>
@endsection
