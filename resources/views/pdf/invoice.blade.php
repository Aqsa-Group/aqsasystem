<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
</head>

<body style="vertical-align:middle;">

    <div class="invoice">شرکت حبیب یونس لمتید
        <div>
            وارد کننده انواع پرزه جات TVS از قبیل پرزه جات موتر ، موتور سیکلت و سچرخ 
        </div>
        <table>
            <tr>
                <td>عمده</td>
                <td>پرچون</td>
            </tr>
        </table>
    </div>
   

    <table>
        <tr>
            <td>
                @if ($sale->sale_type === 'wholesale' && $sale->customer)
                     محترم: {{ $sale->customer->name }}
                @endif
            </td>

            <td>
                &nbsp;&nbsp; شماره فاکتور: {{ $sale->invoice_number }}

            </td>
            <td>
                تاریخ: {{ jdate($sale->created_at)->format('Y/m/d') }}

            </td>
        </tr>
    </table>



@php
    $logoPath = public_path('assets/logo.png'); // دقت: assets
@endphp

<table style="
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
    border:1px solid #000;
    background-image: url('file://{{ $logoPath }}');
    background-position: center center;
    background-repeat: no-repeat;
    background-size: 200px 200px;
">
        <thead>
            <tr>
                <th>شماره</th>
                <th>نام جنس</th>
                <th>قیمت</th>
                <th>تعداد</th>
                <th>واحد</th>
                <th>مجموعه</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->warehouse->name ?? '-' }}</td>
                    <td>{{ number_format($item->price_per_unit) }}</td>
                    <td>{{ $item->quantity }}</td>
                    @if ($sale->sale_type === 'wholesale')
                    <td>{{ $item->warehouse->unit ?? '-' }}</td>
                    @else
                        <td>عدد</td>
                    @endif
                    <td>{{ number_format($item->total_price) }}</td>
                </tr>
            @endforeach

            @for ($i = count($sale->items) + 1; $i <=15; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
        </tbody>


    </table>

    <table>
        <tr>
            <td>
               مجموعه کل فاکتور : {{ number_format($sale->total_price) }}
                &nbsp;&nbsp;
            </td>
        

        </tr>
        @if ($sale->sale_type === 'wholesale')
            <td class="label">مبلغ دریافت شده:</td>
            <td class="value">{{ number_format($sale->received_amount) }}</td>


            <td class="label">باقیمانده:</td>
            <td class="value">{{ number_format($sale->remaining_amount) }}</td>
        @endif
    </table>






    <div class="footer">
        آدرس: هرات - سی متره - باغ آزادی
        &nbsp; | &nbsp;
        شماره‌های تماس: 0796471633 - 0789495940 - 0700472377
    </div>

    <div style="height: calc(297mm - [مجموع ارتفاع محتوای شما]);"></div>

</body>

</html>
