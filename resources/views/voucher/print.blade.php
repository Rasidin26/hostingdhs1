<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Voucher | Dipanet</title>
    <style>
        .voucher {
            width: 250px;
            border: 2px solid #3d5afe;
            border-radius: 8px;
            margin: 5px;
            font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffffff 60%, #e8eaf6 100%);
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            page-break-inside: avoid;
            position: relative;
            display: inline-block;
            vertical-align: top;
            overflow: hidden;
        }

        .voucher-header {
            background: linear-gradient(90deg, #00FFFF 0%, #536dfe 100%);
            color: white;
            padding: 8px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-weight: bold;
            font-size: 20px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .header-number {
            background: white;
            color: #3d5afe;
            width: 30px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .voucher-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 10px;
        }

        .voucher-label {
            font-size: 14px;
            color: #5c6bc0;
            font-weight: 500;
        }

        .voucher-value {
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            color: #303f9f;
        }

        .code-box {
            background-color: #e8eaf6;
            padding: 3px 8px;
            border-radius: 4px;
            border-left: 3px solid #3d5afe;
            font-family: calibri;
            font-size: 32px;
            font-weight: bold;
            color: #303f9f;
        }

        .bottom-row {
            border-top: 1px dashed #c5cae9;
            margin-top: 3px;
            padding-top: 8px;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
        }

        .voucher-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        @media print {
            .voucher {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .voucher-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="voucher-container">
    @foreach ($users as $index => $user)
        <div class="voucher">
            <div class="voucher-header">
                <div class="header-title">DHS</div>
                <div class="header-number">{{ $index + 1 }}</div>
            </div>

            <div class="voucher-row">
                <div class="voucher-label">Kode</div>
                <div class="voucher-value"><span class="code-box">{{ $user->code }}</span></div>
            </div>  

            <div class="voucher-row">
                <div class="voucher-label">Kontak</div>
                <div class="voucher-value">0881-0231-87630</div>
            </div>

            <div class="voucher-row bottom-row">
                <div class="voucher-value">
                    {{-- Tampilkan waktu dalam format rapi --}}
{{ $user->batas_waktu }}
                </div>
                <div class="voucher-value">Support by : PMYNET</div>
            </div>
        </div>
    @endforeach
</div>

</body>
</html>
