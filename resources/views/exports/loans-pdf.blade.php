<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش قرضه‌ها</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; direction: rtl; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #f0f0f0; }
        tfoot td { font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">💳 گزارش قرضه‌ها</h2>

    @if($customer_name || $type || $currency || $date)
        <p>فیلتر شده بر اساس:</p>
        <ul>
            @if($customer_name) <li>نام مشتری: {{ $customer_name }}</li> @endif
            @if($type) <li>نوع تراکنش: {{ $type }}</li> @endif
            @if($currency) <li>نوع ارز: {{ $currency }}</li> @endif
            @if($date) <li>تاریخ: {{ $date }}</li> @endif
        </ul>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>تاریخ</th>
                <th>نوع</th>
                <th>ارز</th>
                <th>نام مشتری</th>
                <th>مبلغ قرضه</th>
                <th>رسید</th>
                <th>باقی‌مانده</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalsByCustomerCurrency = [];
            @endphp

            @foreach($loans as $index => $loan)
                @php
                    $key = $loan['customer']['name'] . '_' . $loan['currency'];
                    if(!isset($totalsByCustomerCurrency[$key])){
                        $totalsByCustomerCurrency[$key] = [
                            'total_loan' => 0,
                            'total_receipt' => 0,
                            'balance' => 0
                        ];
                    }
                    $totalsByCustomerCurrency[$key]['total_loan'] += $loan['amount'];
                    $totalsByCustomerCurrency[$key]['total_receipt'] += $loan['loan_recipt'];
                    $totalsByCustomerCurrency[$key]['balance'] = $loan['reminded'];
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($loan['date'])->format('Y/m/d') }}</td>
                    <td>{{ $loan['type'] }}</td>
                    <td>{{ $loan['currency'] }}</td>
                    <td>{{ $loan['customer']['name'] }}</td>
                    <td>{{ number_format($loan['amount']) }}</td>
                    <td>{{ number_format($loan['loan_recipt']) }}</td>
                    <td>{{ number_format($loan['reminded']) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @foreach($totalsByCustomerCurrency as $key => $totals)
                <tr>
                    <td colspan="5">جمع برای {{ explode('_', $key)[0] }} ({{ explode('_', $key)[1] }})</td>
                    <td>{{ number_format($totals['total_loan']) }}</td>
                    <td>{{ number_format($totals['total_receipt']) }}</td>
                    <td>{{ number_format($totals['balance']) }}</td>
                </tr>
            @endforeach
        </tfoot>
    </table>
</body>
</html>
