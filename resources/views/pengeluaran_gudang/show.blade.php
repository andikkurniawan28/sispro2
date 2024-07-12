@extends('template.kaiadmin.master')

@section('title')
    Detail {{ ucReplaceUnderscoreToSpace('pengeluaran_gudang') }}
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
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card" id="invoice-section">
                        <div class="card-body">
                            <div class="invoice-header">
                                <h4 class="invoice-title">Faktur {{ ucReplaceUnderscoreToSpace('pengeluaran_gudang') }}</h4>
                                <div class="invoice-info">
                                    <div>
                                        <strong>Kode:</strong> {{ $pengeluaran_gudang->kode }}
                                    </div>
                                    <div>
                                        <strong>Dibuat pada:</strong>
                                        {{ date('d-m-Y H:i:s', strtotime($pengeluaran_gudang->created_at)) }}
                                    </div>
                                    <div>
                                        <strong>Gudang:</strong>
                                        {{ $pengeluaran_gudang->gudang->nama }}
                                    </div>
                                    <div>
                                        <strong>Dicatat Oleh:</strong> {{ $pengeluaran_gudang->user->nama }}
                                        ({{ $pengeluaran_gudang->user->jabatan->nama }})
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="invoice-body">
                                <h5>Detail {{ ucReplaceUnderscoreToSpace('pengeluaran_gudang') }}:</h5>
                                <br>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Jumlah Kecil</th>
                                            <th>Jumlah Besar</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengeluaran_gudang_detail as $index => $material)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ ucReplaceUnderscoreToSpace($material->material->kode) }}</td>
                                                <td>{{ ucReplaceUnderscoreToSpace($material->material->nama) }}</td>
                                                <td>{{ $material->jumlah_dalam_satuan_kecil }} {{ $material->material->satuan_kecil->nama }}</td>
                                                <td>{{ $material->jumlah_dalam_satuan_besar }} {{ $material->material->satuan_besar->nama }}</td>
                                                <td>{{ formatRupiah($material->harga) }}</td>
                                                <td>{{ formatRupiah($material->total) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6">Grand Total</th>
                                            <th>{{ formatRupiah($pengeluaran_gudang->grand_total) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="invoice-footer">
                                <div style="margin-top: 20px;">
                                    <p style="text-align: right;">Mengetahui,</p>
                                    <br><br><br>
                                    <p style="text-align: right;">(_____________________)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button onclick="exportToPDF()" class="btn btn-primary" id="exportBtn">Export ke PDF</button>
                    <div id="loading" style="display: none;">
                        <img src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/0.16.1/images/loader-large.gif" alt="Loading...">
                        <p>Exporting...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #invoice-section, #invoice-section * {
                visibility: visible;
            }

            #invoice-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            @page {
                size: A4 portrait; /* Changed to portrait */
                margin: 0;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function exportToPDF() {
            // Show loading animation
            document.getElementById('exportBtn').style.display = 'none';
            document.getElementById('loading').style.display = 'block';

            const element = document.getElementById('invoice-section');

            html2pdf()
                .from(element)
                .set({
                    margin: [0.5, 0.5, 0.5, 0.5], // top, left, bottom, right margins
                    filename: "Faktur {{ ucReplaceUnderscoreToSpace('pengeluaran_gudang') }}.pdf",
                    image: { type: 'png', quality: 1 },
                    html2canvas: { scale: 4, logging: true },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' } // Changed to portrait
                })
                .save()
                .then(() => {
                    // Hide loading animation after PDF is saved
                    document.getElementById('exportBtn').style.display = 'block';
                    document.getElementById('loading').style.display = 'none';
                });
        }
    </script>
@endsection
