<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SisPRO - {{ ucwords(str_replace(['.', '_', 'index'], [' ', ' ', ''], Route::currentRouteName())) }}</title>
    <link rel="icon" href="{{ $setup->company_logo }}" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #1A2035;
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background-color: #fff;
            color: #1A2035;
            border-radius: 0; /* Menghilangkan kelengkungan pada sudut */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #1A2035;
            color: #fff;
            border-bottom: none;
            border-top-left-radius: 0; /* Menghilangkan kelengkungan pada sudut */
            border-top-right-radius: 0; /* Menghilangkan kelengkungan pada sudut */
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            padding: 1rem 1.5rem;
        }
        .form-control:focus {
            border-color: #1A2035;
            box-shadow: 0 0 0 0.2rem rgba(26, 32, 53, 0.25);
        }
        .btn-primary {
            background-color: #1A2035;
            border-color: #1A2035;
        }
        .btn-primary:hover {
            background-color: #13192c;
            border-color: #13192c;
        }
        .form-text {
            color: #1A2035;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <img src="{{ $setup->company_logo }}" alt="navbar brand"
                            class="navbar-brand" height="100" weight="100" />
                        SisPRO
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('loginProcess') }}">
                            @csrf
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="username" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                });
            @endif

            @if (session('fail'))
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: '{{ session('fail') }}',
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: `
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                });
            @endif
        });
    </script>
</body>
</html>
