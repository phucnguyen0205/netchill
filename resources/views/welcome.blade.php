<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào Mừng Đến Với Netchill</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .intro-container {
            background-color: rgba(30, 30, 30, 0.8);
            border-radius: 12px;
            padding: 40px;
            max-width: 600px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        .logo-box {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo-box img {
            height: 50px;
            margin-right: 10px;
        }
        .logo-box h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #ffffff;
            margin: 0;
        }
        .logo-box .text-red { color: #e50914; }
        .logo-box .text-yellow { color: #FFC107; }
        .slogan {
            color: #aaaaaa;
            font-size: 1.1rem;
            margin-top: -5px;
            font-weight: 300;
        }
        .intro-text {
            font-size: 2rem;
            font-weight: bold;
            margin: 30px 0;
        }
        .btn-start {
            background-color: #ffc107;
            color: #1a1a1a;
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn-start:hover {
            background-color: #e5a800;
        }
    </style>
</head>
<body>
    <div class="intro-container">
        <div class="logo-box">
            <img src="{{ asset('images/logo-netchill.png') }}" alt="Netchill Logo">
            <h1><span class="text-red">Net</span><span class="text-yellow">Chill</span></h1>
        </div>
        <p class="slogan">Phim hay cả rổ</p>

        <p class="intro-text">
            Xem Phim Miễn Phí Cực Nhanh, Chất Lượng Cao
            <br>Và Cập Nhật Liên Tục
        </p>

        <a href="{{ route('home') }}" id="startButton" class="btn-start">
            Xem Ngay &rarr;
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('startButton').addEventListener('click', function(event) {
            // Lưu trạng thái đã xem trang chào mừng vào Local Storage
            localStorage.setItem('hasSeenIntro', 'true');
        });
    </script>
</body>
</html>