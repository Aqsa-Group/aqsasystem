<?php

namespace App\Filament\Import\Resources\BuyResource\Pages;

use App\Filament\Import\Resources\BuyResource;
use App\Models\Import\Buy;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateBuy extends CreateRecord
{
    protected static string $resource = BuyResource::class;

    // protected function handleRecordCreation(array $data): Buy
    // {
    //     $userId = Auth::id();

    //     if (isset($data['product_image']) && is_array($data['product_image'])) {
    //         $data['product_image'] = $data['product_image'][0] ?? null;
    //     }

    //     return DB::transaction(function () use ($data, $userId) {
    //         $existing = Buy::where('user_id', $userId)
    //             ->where('barcode', $data['barcode'])
    //             ->first();

    //         if ($existing) {
    //             $deltaExist = (int) ($data['all_exist_number'] ?? 0);
    //             $deltaTotal = (int) ($data['total_price'] ?? 0);

    //             $newExist = ((int) $existing->all_exist_number) + $deltaExist;
    //             $newTotal = ((int) $existing->total_price) + $deltaTotal;

    //             $existing->update([
    //                 'all_exist_number' => $newExist,
    //                 'total_price'      => $newTotal,
    //                 'price'            => $data['price'] ?? $existing->price,
    //                 'retail_price'     => $data['retail_price'] ?? $existing->retail_price,
    //                 'big_whole_price'  => $data['big_whole_price'] ?? $existing->big_whole_price,
    //                 'brand'            => $data['brand'] ?? $existing->brand,
    //                 'import_date'      => $data['import_date'] ?? $existing->import_date,
    //                 'product_image'    => $data['product_image'] ?? $existing->product_image,
    //                 'name'             => $data['name'] ?? $existing->name,
    //                 'unit'             => $data['unit'] ?? $existing->unit,
    //             ]);

    //             return $existing->refresh();
    //         }

    //         $data['user_id'] = $userId;

    //         return Buy::create($data);
    //     });
    // }
}