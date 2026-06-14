<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Buy extends Model
{
    protected $connection = 'import';
    protected $table = 'newbuys';

    protected $fillable = [
        'barcode',
        'name',
        'total_price',
        'unit',
        'user_id',
        'price',
        'brand',
        'big_whole_price',
        'all_exist_number',
        'retail_price',
        'product_image',
        'import_date',
        'currency',
        'amount',
        'paid',
        'remaining',
        'company_id',
    ];

  protected $casts = [
    'import_date'     => 'date',
    'price'           => 'decimal:2',
    'total_price'     => 'decimal:2',
    'big_whole_price' => 'decimal:2',
    'big_unit_price'  => 'decimal:2',
    'retail_price'    => 'decimal:2',
];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        // بعد از ایجاد خرید
        static::created(function (Buy $buy) {
            DB::transaction(function () use ($buy) {
                // انبار: اگر بود آپدیت کن، اگر نبود ایجاد کن
                $warehouse = Warehouse::where('user_id', $buy->user_id)
                    ->where('barcode', $buy->barcode)
                    ->first();

                if ($warehouse) {
                    // آپدیت تعداد و جزئیات
                    $warehouse->update([
                        'name'             => $buy->name,
                        'unit'             => $buy->unit,
                        'price'            => $buy->price,
                        'retail_price'     => $buy->retail_price,
                        'big_whole_price'  => $buy->big_whole_price,
                        'brand'            => $buy->brand,
                        'import_date'      => $buy->import_date,
                        'product_image'    => is_array($buy->product_image) ? ($buy->product_image[0] ?? null) : $buy->product_image,
                        'all_exist_number' => $warehouse->all_exist_number + (int) ($buy->all_exist_number ?? 0),
                    ]);
                } else {
                    // ایجاد ردیف جدید
                    Warehouse::create([
                        'user_id'          => $buy->user_id,
                        'barcode'          => $buy->barcode,
                        'name'             => $buy->name,
                        'unit'             => $buy->unit,
                        'price'            => $buy->price,
                        'retail_price'     => $buy->retail_price,
                        'big_whole_price'  => $buy->big_whole_price,
                        'brand'            => $buy->brand,
                        'import_date'      => $buy->import_date,
                        'product_image'    => is_array($buy->product_image) ? ($buy->product_image[0] ?? null) : $buy->product_image,
                        'all_exist_number' => $buy->all_exist_number ?? 0,
                    ]);
                }

                // صندوق
                $safe = Safe::where('user_id', $buy->user_id)->first();
                if ($safe && !empty($buy->paid)) {
                    $currency = strtoupper($buy->currency);
                    if (in_array($currency, ['AFN', 'USD'])) {
                        $safe->decrement($currency, (int) $buy->paid);
                    }
                }

                // بدهی شرکت
                if ($buy->company_id && $buy->currency && $buy->remaining > 0) {
                    $company = Company::find($buy->company_id);
                    if ($company) {
                        $currency = strtoupper($buy->currency);
                        if (in_array($currency, ['AFN', 'USD'])) {
                            $company->$currency += $buy->remaining;
                            $company->save();
                        }
                    }
                }
            });
        });

        // بعد از بروزرسانی خرید
        static::updated(function (Buy $buy) {
            DB::transaction(function () use ($buy) {
                $original = $buy->getOriginal();

                $origExist      = (int) ($original['all_exist_number'] ?? 0);
                $newExist       = (int) ($buy->all_exist_number ?? 0);
                $deltaExist     = $newExist - $origExist;

                $origPaid       = (int) ($original['paid'] ?? 0);
                $newPaid        = (int) ($buy->paid ?? 0);
                $deltaPaid      = $newPaid - $origPaid;

                $origRemaining  = (int) ($original['remaining'] ?? 0);
                $newRemaining   = (int) ($buy->remaining ?? 0);
                $deltaRemaining = $newRemaining - $origRemaining;

                // انبار
                if ($deltaExist !== 0) {
                    $warehouse = Warehouse::where('user_id', $buy->user_id)
                        ->where('barcode', $buy->barcode)
                        ->first();

                    if ($warehouse) {
                        $warehouse->update([
                            'name'             => $buy->name,
                            'unit'             => $buy->unit,
                            'price'            => $buy->price,
                            'retail_price'     => $buy->retail_price,
                            'big_whole_price'  => $buy->big_whole_price,
                            'brand'            => $buy->brand,
                            'import_date'      => $buy->import_date,
                            'product_image'    => is_array($buy->product_image) ? ($buy->product_image[0] ?? null) : $buy->product_image,
                            'all_exist_number' => $warehouse->all_exist_number + $deltaExist,
                        ]);
                    } else {
                        // اگر نبود دوباره ایجاد می‌کنیم
                        Warehouse::create([
                            'user_id'          => $buy->user_id,
                            'barcode'          => $buy->barcode,
                            'name'             => $buy->name,
                            'unit'             => $buy->unit,
                            'price'            => $buy->price,
                            'retail_price'     => $buy->retail_price,
                            'big_whole_price'  => $buy->big_whole_price,
                            'brand'            => $buy->brand,
                            'import_date'      => $buy->import_date,
                            'product_image'    => is_array($buy->product_image) ? ($buy->product_image[0] ?? null) : $buy->product_image,
                            'all_exist_number' => $newExist,
                        ]);
                    }
                }

                // صندوق
                $safe = Safe::where('user_id', $buy->user_id)->first();
                if ($safe && $deltaPaid !== 0) {
                    $currency = strtoupper($buy->currency);
                    if (in_array($currency, ['AFN', 'USD'])) {
                        if ($deltaPaid > 0) {
                            $safe->decrement($currency, $deltaPaid);
                        } else {
                            $safe->increment($currency, abs($deltaPaid));
                        }
                    }
                }

                // بدهی شرکت
                $currency = strtoupper($buy->currency);
                if ($buy->company_id && in_array($currency, ['AFN', 'USD']) && $deltaRemaining != 0) {
                    $company = Company::find($buy->company_id);
                    if ($company) {
                        $company->$currency += $deltaRemaining;
                        $company->save();
                    }
                }
            });
        });

        // بعد از حذف خرید
        static::deleted(function (Buy $buy) {
            DB::transaction(function () use ($buy) {
                // انبار
                if (!empty($buy->all_exist_number)) {
                    $warehouse = Warehouse::where('user_id', $buy->user_id)
                        ->where('barcode', $buy->barcode)
                        ->first();

                    if ($warehouse) {
                        $warehouse->decrement('all_exist_number', (int) $buy->all_exist_number);
                    }
                }

                // صندوق
                $safe = Safe::where('user_id', $buy->user_id)->first();
                if ($safe && !empty($buy->paid)) {
                    $currency = strtoupper($buy->currency);
                    if (in_array($currency, ['AFN', 'USD'])) {
                        $safe->increment($currency, (int) $buy->paid);
                    }
                }

                // بدهی شرکت
                if ($buy->company_id && $buy->currency && $buy->remaining > 0) {
                    $company = Company::find($buy->company_id);
                    $currency = strtoupper($buy->currency);
                    if ($company && in_array($currency, ['AFN', 'USD'])) {
                        $company->$currency -= $buy->remaining;
                        $company->save();
                    }
                }
            });
        });
    }
}
