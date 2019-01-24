<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ozelden.com</title>
</head>
    <body style="font-family: 'Raleway', sans-serif; color: #707070;">
        <div style="width: 100%; margin: 16px;" align="center">
            <img src="logo.png" style="height: 48px;">
            <p>Öğretmen ve Öğrenci Bulma Portalı</p>
        </div>
        <div style="border-radius: 4px; margin: 16px; padding: 16px; box-shadow: 0px 2px 8px 0px #707070; z-index: 2; position: relative; background-color: #fff;"
             align="center">
            <img src="hands.svg" style="width: 72px; height: 72px;">
            <h2 style="font-weight: 600;">Hoş Geldin {{$name}} {{$surname}}</h2>
            <p><b>{{$email}}</b> ile özelden ailesine katıldın!</p>
            <p>Hemen bilgilerini <a href="https://ozelden.com">buradan</a> güncelle ve senin için en uygun {{$type == 'student' ? 'öğretmeni' : 'öğrenciyi'}} bulalım.</p>
        </div>
        <div style="background-color: #D1A377; padding: 64px 16px 16px 16px; margin-top: -48px;" align="center">
            ozelden.com
        </div>
    </body>
</html>
