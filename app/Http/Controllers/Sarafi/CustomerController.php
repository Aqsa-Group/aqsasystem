<?php

namespace App\Http\Controllers\Sarafi;

use App\Http\Controllers\Controller;
use App\Models\Sarafi\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    // جستجوی مشتریان
    public function searchCustomers(Request $request)
    {
        $user = Auth::guard('sarafi')->user();

        $search = $request->q;

        if (!$search || strlen($search) < 2) {
            return response()->json(['error' => 'حداقل 2 کاراکتر وارد کنید'], 400);
        }

        $customers = Customer::where('fullname', 'like', "%{$search}%")
            ->orWhere('account_number', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->limit(20)
            ->get();

        // تعیین is_mine
        $customers->each(function ($customer) use ($user) {
            $customer->is_mine = $customer->admins->contains($user->id);
        });

        return response()->json(['customers' => $customers]);
    }

    // لینک کردن مشتری
   public function linkCustomerToMe(Request $request)
{
    try {
        $user = Auth::guard('sarafi')->user();
        $currentAdminId = $user->admin_id ?? $user->id;

        // اعتبارسنجی
        $request->validate([
            'customer_id' => 'required|exists:sarafi.customers,id'
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        $alreadyLinked = $customer->admins()
            ->wherePivot('admin_id', $currentAdminId) 
            ->exists();

        if ($alreadyLinked) {
            return response()->json([
                'success' => false,
                'message' => 'این مشتری قبلاً به سیستم شما لینک شده است'
            ]);
        }

        $customer->admins()->attach($currentAdminId);

        return response()->json([
            'success' => true,
            'message' => 'مشتری با موفقیت لینک شد'
        ]);

    } catch (\Exception $e) {
        Log::error('Link customer error: '.$e->getMessage()); // لاگ برای دیباگ
        return response()->json([
            'success' => false,
            'message' => 'خطا در لینک کردن مشتری'
        ], 500);
    }
}


    // دریافت مشتریان لینک شده
    public function getLinkedCustomers()
    {
        $user = Auth::guard('sarafi')->user();

        $linkedCustomers = Customer::whereHas('admins', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->with('sourceCustomer:id,fullname,admin_id')->get();

        return response()->json(['customers' => $linkedCustomers]);
    }
}
