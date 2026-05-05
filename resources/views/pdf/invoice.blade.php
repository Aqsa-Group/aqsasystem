<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    
</head>


<body style="vertical-align:middle;">

    <div class="invoice">پرزه فروشی انجینیر محمد هارون صافی و مرادی
        <div>
            وارد کننده هر نوع پرزه جات ریکشا هندی از قبیل بجاج TVS از کشور های هند و چین
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



    <table style="
        width:100%;
        border-collapse:collapse;
        margin-top:15px;
        border:1px solid #000;
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
                <td>{{ number_format($item->price_per_unit , 2) }}</td>
                <td>{{ $item->quantity }}</td>
                @if ($sale->sale_type === 'wholesale')
                <td>{{ $item->warehouse->unit ?? '-' }}</td>
                @else
                <td>عدد</td>
                @endif
                <td>{{ number_format($item->total_price , 2)}}</td>
            </tr>
            @endforeach

            @for ($i = count($sale->items) + 1; $i <=13; $i++) <tr>
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
                    <strong>{{ number_format($sale->items->sum('total_price'),2) }}&nbsp;دالر</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:6px; font-weight:bold;">مبلغ تخفیف</td>
                <td style="padding:6px; text-align:center;">
                    <strong>{{ number_format($sale->discount ?? 2) }}&nbsp;دالر</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:6px; font-weight:bold;">مبلغ نهایی (بعد از تخفیف)</td>
                <td style="padding:6px; text-align:center; font-size:14px; color:#000;">
                    <strong>{{ number_format($sale->total_price , 2)}}&nbsp;دالر</strong>
                </td>
            </tr>
            @if ($sale->sale_type === 'wholesale')
            <tr>
                <td style="padding:6px; font-weight:bold;">مبلغ دریافت شده</td>
                <td style="padding:6px; text-align:center;">
                    <strong>{{ number_format($sale->received_amount , 2) }}&nbsp;دالر</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:6px; font-weight:bold;">باقیمانده فعلی</td>
                <td style="padding:6px; text-align:center;">
                    <strong>{{ number_format($sale->remaining_amount , 2) }}&nbsp;دالر</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:6px; font-weight:bold;">باقیمانده قبل (قرض قبلی)</td>
                <td style="padding:6px; text-align:center;">
                    <strong>{{ number_format($previousLoanRemaining , 2) }}&nbsp;دالر</strong>
                </td>
            </tr>
            <tr>
                <td style="padding:6px; font-weight:bold; color:red; font-size:16px;">
                    مجموع باقیمانده کل
                </td>
                <td style="padding:6px; text-align:center; color:red; font-size:16px;">
                    <strong>{{ number_format($sale->remaining_amount + $previousLoanRemaining , 2) }}&nbsp;دالر</strong>
                </td>
            </tr>
            @endif

        </tbody>
    </table>

    <div class="footer text-center">
        <div>
            آدرس: کابل - چهار راهی ماموریت ، بلاک دوم ، گذشته از پوهنتون مستقبل ، متصل نمایندگی بجاج
        </div>

        @php
        $whatsappIcon = public_path('assets/whatsapp.png');
        @endphp

        <div class="mt-2" style="display:flex; justify-content:center; align-items:center; gap:8px;">

            <span>
                شماره‌های تماس: 0796471633 - 0700472377 - 0786165140
            </span>

            <span>
                <span style="vertical-align: middle;"> - 0779434678</span>
            </span>

        </div>
    </div>

</body>

</html>