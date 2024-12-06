<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chứng chỉ UME</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f9fafb;
            font-family: 'DejaVu sans', sans-serif;
        }

        .container {
            background-image: url({{$bannerPath}});
            background-size: cover; 
            background-repeat: no-repeat;
            background-position: center;
            width: 94%;
            padding: 2rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
            border-radius: 6px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            width: 200px;
        }

        .header .title {
            font-size: 12px;
            text-align: right;
        }

        main {
            width: 88%;
        }

        main .h1 {
            font-size: 1.3rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #404854;
        }

        main .h2 {
            font-weight: 700;
            font-size: 3.3rem;
            color: #1f2937;
            margin-bottom: 1.2rem;
            margin-top: -10px;
        }

        main .bottom .h3 {
            font-size: 2.4rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2rem;
            padding-top: 50px;
        }

        main .h4 {
            font-size: 1.1rem;
        }

        main .bottom .p {
            color: #3c4452;
            margin-top: -10px;
        }
    </style>
</head>

<body>
    <div class=" container">
        <!-- Header -->
        <header class="header">
            <div>
                <img src="{{$logoPath}}" alt="logo">
            </div>
            <div class="title">
                <p>Số giấy chứng nhận: <b>UC-68eabd19-3637-4ab2-ba1a</b></p>
                <p>URL giấy chứng nhận: <b>ude.my/UC-68eabd19-3637-4ab2-ba1a</b></p>
                <p>Số tham chiếu: <b>0004</b></p>
            </div>
        </header>

        <!-- Content -->
        <main>
            <p class="h1">Giấy chứng nhận hoàn thành</p>
            <p class="h2">{{$course}}</p>
            <p class="h4">Giảng viên: <b>{{$teacher}}</b></p>
            <div class="bottom">
                <h3 class="h3">{{$name}}</h3>
                <p class="p">Ngày: <b>Ngày {{$day}} tháng {{$month}} năm {{$year}}</b></p>
                <p class="p">Thời lượng: <b>{{$minutes}} phút {{$seconds}} giây</b></p>
            </div>
        </main>
    </div>
</body>

</html>