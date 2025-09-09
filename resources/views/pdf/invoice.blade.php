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
            <td>&nbsp;&nbsp; شماره فاکتور: {{ $sale->invoice_number }}</td>
            <td>تاریخ: {{ jdate($sale->created_at)->format('Y/m/d') }}</td>
        </tr>
    </table>

    @php
        $logoPath = public_path('assets/logo2.png'); 
    @endphp

    <table style="
        width:100%;
        border-collapse:collapse;
        margin-top:15px;
        border:1px solid #000;
        background-image: url('file://{{ $logoPath }}');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: 120px 120px;
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

    <table style="width:100%; margin-top:15px; border-collapse:collapse; border:1px solid #000;">
        <tbody>
            <tr>
                <td style="padding:6px; font-weight:bold; width:50%;">مجموعه کل فاکتور (قبل از تخفیف)</td>
                <td style="padding:6px; text-align:center;">
                    <strong>{{ number_format($sale->items->sum('total_price')) }}&nbsp;افغانی</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:6px; font-weight:bold;">مبلغ تخفیف</td>
                <td style="padding:6px; text-align:center;">
                    <strong>{{ number_format($sale->discount ?? 0) }}&nbsp;افغانی</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:6px; font-weight:bold;">مبلغ نهایی (بعد از تخفیف)</td>
                <td style="padding:6px; text-align:center; font-size:14px; color:#000;">
                    <strong>{{ number_format($sale->total_price) }}&nbsp;افغانی</strong>
                </td>
            </tr>
            @if ($sale->sale_type === 'wholesale')
                <tr>
                    <td style="padding:6px; font-weight:bold;">مبلغ دریافت شده</td>
                    <td style="padding:6px; text-align:center;">
                        <strong>{{ number_format($sale->received_amount) }}&nbsp;افغانی</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px; font-weight:bold;">باقیمانده</td>
                    <td style="padding:6px; text-align:center;">
                        <strong>{{ number_format($sale->remaining_amount) }}&nbsp;افغانی</strong>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        آدرس: هرات - سی متره - باغ آزادی
        &nbsp; | &nbsp;
        شماره‌های تماس: 0796471633 - 0789495940 - 0700472377
    </div>

    <div style="height: calc(297mm - [مجموع ارتفاع محتوای شما]);"></div>

</body>
</html>
