<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاکتور فروش - {{ $sale->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                margin: 0;
                size: A4;
            }
            body {
                margin: 1.5cm;
            }
            .no-print {
                display: none !important;
            }
        }
        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <!-- دکمه چاپ -->
    <div class="no-print text-center mb-6">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold">
            <i class="fas fa-print ml-2"></i>
            چاپ فاکتور
        </button>
        <button onclick="window.close()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold mr-4">
            <i class="fas fa-times ml-2"></i>
            بستن
        </button>
    </div>

    <!-- فاکتور -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-4xl mx-auto">
        <!-- هدر فاکتور -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">فاکتور فروش</h1>
                    <p class="text-blue-100 mt-2">سیستم مدیریت فروش</p>
                </div>
                <div class="text-left">
                    <div class="bg-white text-blue-800 px-4 py-2 rounded-lg text-center">
                        <div class="text-sm">شماره فاکتور</div>
                        <div class="text-xl font-bold">{{ $sale->invoice_number }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- اطلاعات فاکتور -->
        <div class="p-8">
            <!-- اطلاعات خریدار و تاریخ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">
                        <i class="fas fa-user ml-2"></i>
                        اطلاعات خریدار
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">نام خریدار:</span>
                            <span class="font-bold">{{ $sale->buyer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">نوع فروش:</span>
                            <span class="font-bold {{ $sale->sale_type === 'wholesale' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $sale->sale_type === 'wholesale' ? 'عمده' : 'پرچون' }}
                            </span>
                        </div>
                        @if($sale->customer)
                        <div class="flex justify-between">
                            <span class="text-gray-600">مشتری:</span>
                            <span class="font-bold">{{ $sale->customer->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">
                        <i class="fas fa-calendar ml-2"></i>
                        اطلاعات فاکتور
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاریخ فاکتور:</span>
                            <span class="font-bold">{{ jdate($sale->created_at)->format('Y/m/d H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">کاربر ثبت‌کننده:</span>
                            <span class="font-bold">{{ $sale->user->name ?? 'سیستم' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">وضعیت:</span>
                            <span class="font-bold text-green-600">تکمیل شده</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جدول اقلام فاکتور -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-list ml-2"></i>
                    اقلام فاکتور
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 p-3 font-bold">ردیف</th>
                                <th class="border border-gray-300 p-3 font-bold">نام محصول</th>
                                <th class="border border-gray-300 p-3 font-bold">بارکد</th>
                                <th class="border border-gray-300 p-3 font-bold">تعداد</th>
                                <th class="border border-gray-300 p-3 font-bold">قیمت واحد (دالر)</th>
                                <th class="border border-gray-300 p-3 font-bold">مجموع (دالر)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 p-3 text-center">{{ $index + 1 }}</td>
                                <td class="border border-gray-300 p-3">{{ $item->product_name }}</td>
                                <td class="border border-gray-300 p-3 text-center">{{ $item->barcode }}</td>
                                <td class="border border-gray-300 p-3 text-center">{{ number_format($item->quantity) }}</td>
                                <td class="border border-gray-300 p-3 text-left">{{ number_format($item->price_per_unit, 3) }}</td>
                                <td class="border border-gray-300 p-3 text-left font-bold">{{ number_format($item->total_price, 3) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- خلاصه مالی -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 border-b border-blue-200 pb-2">
                        <i class="fas fa-calculator ml-2"></i>
                        خلاصه مالی
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700">مجموع فاکتور:</span>
                            <span class="text-xl font-bold text-blue-800">{{ number_format($sale->total_price, 3) }} دالر</span>
                        </div>
                        
                        @if($sale->discount > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-red-600">تخفیف:</span>
                            <span class="text-lg font-bold text-red-600">- {{ number_format($sale->discount, 3) }} دالر</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center border-t border-blue-200 pt-3">
                            <span class="text-green-700 font-bold">مبلغ نهایی:</span>
                            <span class="text-2xl font-extrabold text-green-700">
                                {{ number_format($sale->total_price - $sale->discount, 3) }} دالر
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">مبلغ دریافتی:</span>
                            <span class="text-lg font-bold text-gray-800">{{ number_format($sale->received_amount, 3) }} دالر</span>
                        </div>

                        @if($sale->remaining_amount > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-orange-600">باقیمانده:</span>
                            <span class="text-lg font-bold text-orange-600">{{ number_format($sale->remaining_amount, 3) }} دالر</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-green-50 p-6 rounded-lg">
                    <h3 class="text-lg font-bold text-green-800 mb-4 border-b border-green-200 pb-2">
                        <i class="fas fa-chart-bar ml-2"></i>
                        اطلاعات سود و زیان
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-green-700">مجموع سود:</span>
                            <span class="text-lg font-bold text-green-700">
                                {{ number_format($sale->items->sum('profit'), 3) }} دالر
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-red-600">مجموع زیان:</span>
                            <span class="text-lg font-bold text-red-600">
                                {{ number_format($sale->items->sum('loss'), 3) }} دالر
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- پاورقی -->
            <div class="mt-8 pt-6 border-t border-gray-300 text-center text-gray-600 text-sm">
                <p>با تشکر از اعتماد شما</p>
                <p class="mt-2">این فاکتور به صورت خودکار تولید شده است</p>
                <p class="mt-1">تاریخ چاپ: {{ jdate()->format('Y/m/d H:i') }}</p>
            </div>
        </div>
    </div>

    <script>
        // چاپ خودکار هنگام لود صفحه
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };

        // برگشت به صفحه قبل بعد از چاپ
        window.onafterprint = function() {
            setTimeout(() => {
                // window.close(); // اگر می‌خواهید بعد از چاپ بسته شود
            }, 1000);
        };
    </script>
</body>
</html>