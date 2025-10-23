<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سند پرداخت معاش - {{ $salary->staff->name }} {{ $salary->staff->lastname }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Noto Naskh Arabic', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            width: 80mm;
            height: 290mm;
            background: #ffffff;
            padding: 6mm;
            font-size: 9px;
            line-height: 1.4;
            color: #2d3748;
        }
        
        .container {
            width: 100%;
            height: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }
        
        /* هدر */
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%);
            color: white;
            padding: 12px 10px;
            text-align: center;
            border-bottom: 2px solid #4f46e5;
        }
        
        .company-name {
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }
        
        .document-title {
            font-size: 10px;
            font-weight: 500;
            opacity: 0.9;
        }
        
        .employee-name {
            font-size: 12px;
            font-weight: 600;
            margin-top: 4px;
            color: #fbbf24;
        }
        
        /* بخش اصلی */
        .content {
            padding: 10px;
        }
        
        /* کارت اطلاعات */
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 8px;
        }
        
        .card-title {
            font-size: 9px;
            font-weight: 600;
            color: #4f46e5;
            margin-bottom: 6px;
            padding-bottom: 3px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 3px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .label {
            font-size: 8px;
            color: #64748b;
            font-weight: 500;
            flex: 1;
        }
        
        .value {
            font-size: 9px;
            font-weight: 600;
            color: #1e293b;
            flex: 1;
            text-align: left;
        }
        
        /* بخش مبلغ */
        .amount-section {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 12px 8px;
            border-radius: 6px;
            text-align: center;
            margin: 10px 0;
            border: 1px solid #059669;
            box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
        }
        
        .amount-label {
            font-size: 9px;
            margin-bottom: 4px;
            opacity: 0.9;
        }
        
        .amount {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .currency {
            font-size: 10px;
            margin-right: 4px;
        }
        
        /* بخش توضیحات */
        .description-section {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 5px;
            padding: 8px;
            margin: 8px 0;
        }
        
        .description-title {
            font-size: 9px;
            font-weight: 600;
            color: #d97706;
            margin-bottom: 4px;
        }
        
        .description-text {
            font-size: 8px;
            color: #92400e;
            line-height: 1.4;
        }
     .signature-section {
        margin: 15px 0;
        padding: 12px 0;
        gap: 6px;
        border-top: 2px dashed #cbd5e1;
    }
    
    .signature-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        padding: 0 8px;
    }
    
    .signature-box {
        text-align: center;
        padding: 12px 6px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
    }
    
    .signature-title {
        font-size: 8px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .signature-role {
        font-size: 7px;
        color: #64748b;
        margin-bottom: 12px;
    }
    
    .signature-area {
        height: 35px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 4px;
        margin: 6px 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .signature-line {
        height: 1px;
        background: #94a3b8;
        width: 85%;
        position: relative;
    }
    
    .signature-line::after {
        content: "امضا";
        position: absolute;
        top: -5px;
        right: 50%;
        transform: translateX(50%);
        background: #f8fafc;
        padding: 0 6px;
        font-size: 6px;
        color: #64748b;
    }
    
    .signature-date {
        font-size: 6px;
        color: #94a3b8;
        margin-top: 4px;
    }
        
        /* فوتر */
        .footer {
            background: #f1f5f9;
            padding: 8px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            margin-top: 10px;
        }
        
        .document-id {
            font-size: 7px;
            color: #475569;
            margin-bottom: 3px;
        }
        
        .print-date {
            font-size: 7px;
            color: #64748b;
        }
        
        .barcode-area {
            margin-top: 5px;
            padding: 3px;
            background: white;
            border: 1px dashed #cbd5e1;
            font-family: monospace;
            font-size: 6px;
            letter-spacing: 1px;
        }
        
        /* واترمارک */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 40px;
            font-weight: 900;
            color: #f1f5f9;
            opacity: 0.3;
            z-index: 0;
            pointer-events: none;
        }
        
        /* استایل برای چاپ */
        @media print {
            body {
                margin: 0;
                padding: 6mm;
                background: white;
            }
            
            .container {
                border: none;
                box-shadow: none;
            }
            
            .watermark {
                opacity: 0.1;
            }
        }
        
        /* جداکننده */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);
            margin: 8px 0;
        }
    </style>
</head>
<body>

       @php
        $currentUser = Auth::guard('tools')->user();
    @endphp


    @php
            $currenciesFa = [
                'afn' => 'افغانی',
                'usd' => 'دالر',
                'eur' => 'یورو',
                'irr' => 'تومان',
                'aed' => 'درهم',
                'try' => 'لیره',
                'cny' => 'یوان',
                'pkr' => 'کلدار',
                'gbp' => 'پوند',
                'jpy' => 'ین',
                'sar' => 'ریال سعودی',
                'inr' => 'روپیه',
            ];
            @endphp

    <div class="container">
     
        
        <div class="header">
            <div class="company-name">{{ $currentUser->company_name }}</div>
            <div class="document-title">سند رسمی پرداخت معاش</div>
            <div class="employee-name">{{ $salary->staff->name }} {{ $salary->staff->lastname }}</div>
        </div>
        
        <div class="content">
            <!-- اطلاعات کارمند -->
            <div class="info-card">
                <div class="card-title">مشخصات کارمند</div>
                <div class="info-row">
                    <span class="label">نام کامل:</span>
                    <span class="value">{{ $salary->staff->name }} {{ $salary->staff->lastname }}</span>
                </div>
                <div class="info-row">
                    <span class="label">شغل:</span>
                    <span class="value">{{ $salary->staff->job }}</span>
                </div>
              
            </div>
            
            <!-- اطلاعات پرداخت -->
            <div class="info-card">
                <div class="card-title">اطلاعات پرداخت</div>
                <div class="info-row">
                    <span class="label">تاریخ پرداخت:</span>
                    <span class="value">{{ $salary->date }}</span>
                </div>
                <div class="info-row">
                    <span class="label">واحد پولی:</span>
                    <span class="value">
                         {{ $currenciesFa[strtolower($salary->currency)] ?? $salary->currency }}
                    ({{ strtoupper($salary->currency) }})
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">شماره سند:</span>
                    <span class="value">SAL-{{ $salary->id }}</span>
                </div>
            </div>
            
            <div class="divider"></div>
            
            <!-- مبلغ پرداختی -->
            <div class="amount-section">
                <div class="amount-label">مبلغ قابل پرداخت</div>
                <div class="amount">
                    <span class="currency">افغانی</span>
                    {{ number_format($salary->amount) }}
                </div>
            </div>
            
            <!-- توضیحات -->
            @if($salary->description)
            <div class="description-section">
                <div class="description-title">توضیحات پرداخت</div>
                <div class="description-text">{{ $salary->description }}</div>
            </div>
            @endif
            
            <!-- بخش امضا -->
            <div class="signature-section">
                <div class="signature-grid">
                    <div class="signature-box">
                        <div class="signature-title">مسئول امور مالی</div>
                        <div class="signature-role">امضا و مهر</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-title">کارمند محترم</div>
                        <div class="signature-role">امضای دریافت کننده</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="document-id">
                شناسه سند: PS-{{ $salary->id }}-{{ date('Ymd', strtotime($salary->date)) }}
            </div>
            <div class="print-date">
                چاپ شده در: {{ now()->format('Y/m/d - H:i') }}
            </div>
            <div class="barcode-area">
                {{ $salary->id }}{{ date('Ymd', strtotime($salary->date)) }}{{ $salary->staff->id }}
            </div>
        </div>
    </div>
</body>
</html>