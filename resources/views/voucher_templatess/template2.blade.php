<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    background: #fff;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

.voucher-wrapper {
    display: flex;
    justify-content: center;
    padding: 10mm;
}

/* Hanya tampilkan voucher pertama di layar */
.voucher.hide-preview {
    display: none;
}

/* TAMPILAN DI LAYAR (preview contoh) */
.voucher {
    width: 8cm;        /* cukup besar */
    height: auto;
    aspect-ratio: 17 / 10;
    position: relative;
    border: 1px solid #000;
    border-radius: 8px;
    overflow: hidden;
    box-sizing: border-box;
}

/* background voucher */
.voucher img.bg {
    width: 100%;
    height: 100%;
    object-fit: contain;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
}

.username {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    top: 40%;
    font-weight: 700;
    font-size: 32px;
    color: #0033cc;
    z-index: 2;
}

.validity {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    top: 60%;
    font-weight: 600;
    font-size: 16px;
    color: #fff;
    z-index: 2;
}

.num {
    position: absolute;
    right: 10px;
    bottom: 10px;
    font-size: 14px;
    color: #fff;
    font-weight: 600;
    z-index: 2;
}

@media print {
    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    .voucher-wrapper {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 2mm;
        justify-items: center;
        padding: 0;
    }

    .voucher {
        width: 3.6cm;   /* <= supaya total pas */
        height: 2.3cm;
        border: 0;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .voucher.hide-preview {
        display: block !important;
    }

    .username { font-size: 12px; }
    .validity { font-size: 9px; }
    .num { font-size: 8px; }
}


</style>
</head>
<body>
<div class="voucher-wrapper">
    @foreach ($users as $index => $user)
        <div class="voucher {{ $index > 0 ? 'hide-preview' : '' }}">
            <img src="{{ asset('voucher/2rb.png') }}" class="bg" alt="Voucher Background" loading="eager">
            <div class="username">{{ $user->code }}</div>
            {{-- <div class="validity">Valid: {{ $user->validity }}</div> --}}
            {{-- <div class="num">{{ $index + 1 }}</div> --}}
        </div>
    @endforeach
</div>

<script>
window.onload = function() {
    const imgs = document.querySelectorAll('.voucher img');
    let loaded = 0;

    const doPrint = () => window.print();

    if (imgs.length === 0) {
        doPrint();
    } else {
        imgs.forEach(img => {
            img.onload = img.onerror = () => {
                loaded++;
                if (loaded === imgs.length) {
                    doPrint();
                }
            };
        });
    }
};
</script>
</body>
</html>
