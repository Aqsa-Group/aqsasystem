<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: amiri, Tahoma, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
        th { background: #f0f0f0; }
        img { max-width: 200px; max-height: 100px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">لیست اجناس موجود در گدام</h2>
    <table>
        <thead>
            <tr>
                <th>نام جنس</th>
                {{-- <th>تعداد</th> 
                <th>واحد</th>
                <th>موجودی به دانه</th> --}}
                <th>قیمت پرچون</th>
                <th>قیمت عمده</th> 
                <th>ساخت کشور</th>
                <th>عکس محصول</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $item)
                <tr>
                   
                    <td>{{ $item->name }}</td>
                    {{-- <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->all_exist_number }}</td> --}}
                    <td>{{ $item->retail_price }}</td> 
                    <td>{{ $item->big_whole_price }}</td>
                    <td>{{ $item->brand }}</td>
                    <td>
                        @if($item->product_image)
                            <img src="{{ public_path('storage/' . $item->product_image) }}" alt="عکس">
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
