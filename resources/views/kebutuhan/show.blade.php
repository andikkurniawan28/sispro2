@extends('template.kaiadmin.master')

@section('title')
    Detail {{ ucReplaceUnderscoreToSpace('kebutuhan') }}
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
            <a href="{{ route('kebutuhan.index') }}">{{ ucReplaceUnderscoreToSpace('kebutuhan') }}</a>
        </li>
        <li class="separator">
            <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('kebutuhan.index') }}">@yield('title')</a>
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
                                <h4 class="invoice-title">{{ ucReplaceUnderscoreToSpace('kebutuhan') }}</h4>
                                <div class="invoice-info">
                                    <div>
                                        <strong>Kode Produk:</strong> {{ $produk->kode }}
                                    </div>
                                    <div>
                                        <strong>Nama Produk:</strong> {{ $produk->nama }}
                                    </div>
                                    <div>
                                        <strong>Dibuat pada:</strong> {{ date('d-m-Y H:i:s', strtotime($produk->created_at)) }}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="invoice-body">
                                <h5>Detail Kebutuhan Bahan:</h5>
                                <br>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode Bahan</th>
                                            <th>Nama Bahan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kebutuhan as $index => $kebutuhan)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ ucReplaceUnderscoreToSpace($kebutuhan->bahan->kode) }}</td>
                                                <td>{{ ucReplaceUnderscoreToSpace($kebutuhan->bahan->nama) }}</td>
                                                <td>
                                                    {{ $kebutuhan->jumlah_dalam_satuan_kecil }}
                                                    {{ $kebutuhan->bahan->satuan_kecil->nama }}
                                                    /
                                                    {{ $kebutuhan->jumlah_dalam_satuan_besar }}
                                                    {{ $kebutuhan->bahan->satuan_besar->nama }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="invoice-footer">
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
                    filename: "Faktur {{ ucReplaceUnderscoreToSpace('kebutuhan') }}.pdf",
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
