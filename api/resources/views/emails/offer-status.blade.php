<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ozelden.com</title>
</head>
<body style="background: transparent; color: #ffffff; font-family: sans-serif; padding: 16px 0;">
<div align="center" style="background: #722947; width: auto; margin: 0 5%; padding: 8px; border-radius: 8px 8px 0 0;">
    <img src="https://images.ozelden.com/resources/logo-gold.png" style="height: 32px; width: 154px; margin-top: 8px;">
    <p style="font-size: 14px; margin: 0; color: #fff;">özel ders almanın en akıllı yolu</p>
</div>
<div style="width: auto; background: #f9f9f9; color: #303030; margin: 0 5%; padding: 32px 16px; font-size: 14px; -webkit-box-shadow: 0 4px 16px 4px #909090;
-moz-box-shadow: 0 4px 16px 4px #909090 ; box-shadow: 0 4px 16px 4px #909090;" align="center">
    <h3 style="color: #722947;">Teklif durumu</h3>
    <p><b>{{$name}} {{$surname}}</b> isimli kullanıcı sizin <b>{{$lecture_theme}} ({{$lecture_area}})</b> dersi için <b>{{$offer}}₺</b>'lik teklifinizi
        {{$status == 1 ? 'onayladı, iletişime geçmek için profilini ziyaret edebilirsiniz' : ''}}
        {{$status == 99 ? 'reddetdi, daha iyi bir teklif için profilini ziyaret edebilirsiniz' : ''}}
    </p>
    <a href="https://ozelden.com/app/profile/{{$id}}" style="background: #d3a67d; color: #fff; border: 0; padding: 8px 32px; border-radius: 4px; font-weight: 600; cursor: pointer; text-decoration: none;">Profili ziyaret et</a>
</div>
<div align="center" style="background: #722947; width: auto; margin: 0 5%; padding: 8px; border-radius: 0 0 8px 8px;">
    <p style="font-size: 14px; margin: 0 0 8px 0; color: #fff; text-decoration: none;">ozelden.com</p>
    <a style="color: #fff; text-decoration: none; margin: 4px;" target="_blank" href="https://www.instagram.com/ozeldencom/"><img src="https://images.ozelden.com/resources/instagram.png"/></a>
    <a style="color: #fff; text-decoration: none; margin: 4px;" target="_blank" href="https://www.facebook.com/ozelden/"><img src="https://images.ozelden.com/resources/facebook.png"/></a>
    <a style="color: #fff; text-decoration: none; margin: 4px;" target="_blank" href="https://twitter.com/ozelden4"><img src="https://images.ozelden.com/resources/twitter.png"/></a>
</div>
</body>
</html>


