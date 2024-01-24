<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Branch Card</title>

    <style>
        .box {
            position: relative;
        }
        .card {
            width: 170mm;
            height: 120mm;
        }
        .logo {
            position: absolute;
            top: 10pt;
            right: 5pt;
            font-size: 16pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }
        .logo p {
            margin-top: 35pt;
            text-align: right;
            margin-right: 2pt;
        }
        .logo img {
            position: absolute;
            top: 0;
            right: 10pt;
            width: 40px;
            height: 40px;
        }
        .name {
            position: absolute;
            top: 105pt;
            right: 10pt;
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }
        .phone {
            position: absolute;
            top: 120pt;
            right: 10pt;
            color: #fff;
        }
        .barcode {
            position: absolute;
            top: 90pt;
            left: 15pt;
            border: 1px solid #fff;
            padding: .5px;
            background: #fff;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>

</head>
<body>
    <section class="card" style="border: 1px solid #fff;">
        <table width="100%">
            @foreach ($databranch as $key => $data)
                <tr>
                    @foreach ($data as $item)
                        <td class="text-center">
                            <div class="box">
                                <img src="{{ public_path($setting->path_kartu_member) }}" alt="card" width="100%">
                                <div class="logo">
                                    <p>{{ $setting->nama_perusahaan }}</p>
                                    <img src="{{ public_path($setting->path_logo) }}" alt="logo">
                                </div>
                                <div class="name">{{ $item->name }}</div>
                                <div class="phone">{{ $item->phone }}</div>
                                <div class="barcode text-left">
                                    <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG("$item->code_branch", 'QRCODE') }}" alt="qrcode"
                                        height="45"
                                        width="45">
                                </div>
                            </div>
                        </td>

                        @if (count($databranch) == 1)
                            <td class="text-center" style="width: 50%;"></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </section>
</body>
</html>
