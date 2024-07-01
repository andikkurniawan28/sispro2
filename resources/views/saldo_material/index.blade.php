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
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mt-4" id="saldo_material-table" width="100%">
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
    <script type="text/javascript">
        $(function () {
            $('#saldo_material-table').DataTable({
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
        });
    </script>
@endsection
