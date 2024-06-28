@extends('template.kaiadmin.master')

@section('title')
    {{ ucReplaceUnderscoreToSpace('permintaan_produk_reproses') }}
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
            <a href="{{ route('permintaan_produk_reproses.index') }}">@yield('title')</a>
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
                                <a href="{{ route('permintaan_produk_reproses.create') }}" class="btn btn-sm btn-primary text-white">Tambah</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mt-4" id="permintaan_produk_reproses-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ ucReplaceUnderscoreToSpace('dibuat_pada') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('berlaku_sampai') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('kode') }}</th>
                                            {{-- <th>{{ ucReplaceUnderscoreToSpace('kode_produk') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('produk') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('jumlah') }}</th>
                                            <th>{{ ucReplaceUnderscoreToSpace('satuan_besar') }}</th> --}}
                                            <th>{{ ucReplaceUnderscoreToSpace('user') }}</th>
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
            $('#permintaan_produk_reproses-table').DataTable({
                order: [
                    [0, 'desc']
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('permintaan_produk_reproses.index') }}",
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'berlaku_sampai', name: 'berlaku_sampai' },
                    { data: 'kode', name: 'kode' },
                    // { data: 'produk_reproses_kode', name: 'produk_reproses.kode' },
                    // { data: 'produk_reproses_nama', name: 'produk_reproses.nama' },
                    // { data: 'jumlah', name: 'jumlah' },
                    // { data: 'satuan_besar', name: 'satuan_besar' },
                    { data: 'user_nama', name: 'user_nama' },
                    { data: 'tindakan', name: 'tindakan', orderable: false, searchable: false },
                ]
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('delete-btn')) {
                    event.preventDefault();
                    const button = event.target;
                    const permintaan_produk_reproses_id = button.getAttribute('data-id');
                    const permintaan_produk_reproses_nama = button.getAttribute('data-nama');
                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfTokenElement) {
                        console.error('CSRF token not found!');
                        return;
                    }
                    const csrfToken = csrfTokenElement.content;

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Anda tidak bisa mengembalikan data yang terhapus!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, saya yakin!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.setAttribute('method', 'POST');
                            form.setAttribute('action', `{{ url('permintaan_produk_reproses') }}/${permintaan_produk_reproses_id}`);
                            form.setAttribute('style', 'display: none;'); // Optional: Hide the form

                            const hiddenMethod = document.createElement('input');
                            hiddenMethod.setAttribute('type', 'hidden');
                            hiddenMethod.setAttribute('name', '_method');
                            hiddenMethod.setAttribute('value', 'DELETE');

                            const csrfTokenInput = document.createElement('input');
                            csrfTokenInput.setAttribute('type', 'hidden');
                            csrfTokenInput.setAttribute('name', '_token');
                            csrfTokenInput.setAttribute('value', csrfToken);

                            form.appendChild(hiddenMethod);
                            form.appendChild(csrfTokenInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
