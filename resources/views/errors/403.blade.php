<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #667eea;
            line-height: 1;
            margin-bottom: 20px;
        }
        .error-title {
            font-size: 32px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
        }
        .error-message {
            font-size: 18px;
            color: #718096;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        .btn {
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #2d3748;
        }
        .btn-secondary:hover {
            background: #cbd5e0;
            transform: translateY(-2px);
        }
        .info-box {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-top: 30px;
            text-align: left;
            border-radius: 5px;
        }
        .info-box p {
            color: #4a5568;
            font-size: 14px;
            margin: 5px 0;
        }
        .info-box strong {
            color: #2d3748;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔒</div>
        <div class="error-code">403</div>
        <h1 class="error-title">Akses Ditolak</h1>
        <p class="error-message">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. 
            Halaman yang Anda coba akses hanya tersedia untuk pemilik akun atau administrator.
        </p>

        <div class="info-box">
            <p><strong>Kemungkinan penyebab:</strong></p>
            <p>• Anda mencoba mengakses pesanan milik pengguna lain</p>
            <p>• Sesi login Anda mungkin telah kedaluwarsa</p>
            <p>• Anda tidak memiliki hak akses untuk halaman ini</p>
        </div>

        <div class="btn-group">
            <a href="{{ route('home') }}" class="btn btn-primary">
                ← Kembali ke Beranda
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                Halaman Sebelumnya
            </a>
        </div>
    </div>
</body>
</html>
