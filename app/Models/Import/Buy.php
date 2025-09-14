<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;
use App\Models\Import\Warehouse;
use Illuminate\Support\Facades\DB;
use App\Models\Import\Safe;

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
    ];

    protected $casts = [
        'import_date' => 'date',
    ];

    protected static function booted()
    {
        static::created(function (Buy $buy) {
            DB::transaction(function () use ($buy) {
                // انبار
                $warehouse = Warehouse::where('user_id', $buy->user_id)
                    ->where('barcode', $buy->barcode)
                    ->first();

                if ($warehouse) {
                    if (!empty($buy->all_exist_number)) {
                        $warehouse->increment('all_exist_number', (int) $buy->all_exist_number);
                    }
                } else {
                    Warehouse::create([
                        'user_id'         => $buy->user_id,
                        'barcode'         => $buy->barcode,
                        'name'            => $buy->name,
                        'unit'            => $buy->unit,
                        'price'           => $buy->price,
                        'retail_price'    => $buy->retail_price,
                        'big_whole_price' => $buy->big_whole_price,
                        'brand'           => $buy->brand,
                        'import_date'     => $buy->import_date,
                        'product_image'   => is_array($buy->product_image) ? ($buy->product_image[0] ?? null) : $buy->product_image,
                        'all_exist_number'=> $buy->all_exist_number ?? 0,
                    ]);
                }

                $safe = Safe::where('user_id', $buy->user_id)->first();
                if ($safe && !empty($buy->total_price)) {
                    $safe->decrement('total', (int) $buy->total_price);
                }
            });
        });

        static::updated(function (Buy $buy) {
            DB::transaction(function () use ($buy) {
                $original = $buy->getOriginal();

                $origExist = (int) ($original['all_exist_number'] ?? 0);
                $origTotal = (int) ($original['total_price'] ?? 0);

                $newExist = (int) ($buy->all_exist_number ?? 0);
                $newTotal = (int) ($buy->total_price ?? 0);

                $deltaExist = $newExist - $origExist;
                $deltaTotal = $newTotal - $origTotal;

                $warehouse = Warehouse::where('user_id', $buy->user_id)
                    ->where('barcode', $buy->barcode)
                    ->first();

                if ($warehouse && $deltaExist !== 0) {
                    if ($deltaExist > 0) {
                        $warehouse->increment('all_exist_number', $deltaExist);
                    } else {
                        $warehouse->decrement('all_exist_number', abs($deltaExist));
                    }
                }

                $safe = Safe::where('user_id', $buy->user_id)->first();
                if ($safe && $deltaTotal !== 0) {
                    if ($deltaTotal > 0) {
                        $safe->decrement('total', $deltaTotal);
                    } else {
                        $safe->increment('total', abs($deltaTotal));
                    }
                }
            });
        });

        static::deleted(function (Buy $buy) {
            DB::transaction(function () use ($buy) {
                
                $warehouse = Warehouse::where('user_id', $buy->user_id)
                    ->where('barcode', $buy->barcode)
                    ->first();

                if ($warehouse && !empty($buy->all_exist_number)) {
                    $warehouse->decrement('all_exist_number', (int) $buy->all_exist_number);
                }

                $safe = Safe::where('user_id', $buy->user_id)->first();
                if ($safe && !empty($buy->total_price)) {
                    $safe->increment('total', (int) $buy->total_price);
                }
            });
        });
    }
}
