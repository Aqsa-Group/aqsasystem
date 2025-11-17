<div class="min-h-screen bg-gray-50">
    <!-- Notifications -->
    @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-50 bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
        <div class="h-20 w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-lg">
                {{ session('message') }}
            </h2>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-50 bg-red-500 vazir">
        <div class="h-20 w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-lg">
                {{ session('error') }}
            </h2>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-4">
        <!-- Today's Withdrawals -->
        <div
            class="bg-gradient-to-br from-rose-100 to-rose-200 border-l-4 border-rose-500 text-rose-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold vazir">برداشت‌های امروز</h3>
                <div class="bg-rose-500 p-2 rounded-full">
                    <i class="fa-solid fa-calendar-day text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'تومان'] as $currency => $label)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rose-700 vazir">{{ $label }}:</span>
                    <span class="text-lg font-bold vazir">{{ number_format($withdrawalStats['today'][$currency])
                        }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- This Week's Withdrawals -->
        <div
            class="bg-gradient-to-br from-green-100 to-green-200 border-l-4 border-green-500 text-green-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold vazir">برداشت‌های این هفته</h3>
                <div class="bg-green-500 p-2 rounded-full">
                    <i class="fa-solid fa-calendar-week text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'تومان'] as $currency => $label)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-green-700 vazir">{{ $label }}:</span>
                    <span class="text-lg font-bold vazir">{{ number_format($withdrawalStats['week'][$currency])
                        }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- This Month's Withdrawals -->
        <div
            class="bg-gradient-to-br from-blue-100 to-blue-200 border-l-4 border-blue-500 text-blue-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold vazir">برداشت‌های این ماه</h3>
                <div class="bg-blue-500 p-2 rounded-full">
                    <i class="fa-solid fa-calendar-alt text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'تومان'] as $currency => $label)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-700 vazir">{{ $label }}:</span>
                    <span class="text-lg font-bold vazir">{{ number_format($withdrawalStats['month'][$currency])
                        }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Total Withdrawals -->
        <div
            class="bg-gradient-to-br from-purple-100 to-purple-200 border-l-4 border-purple-500 text-purple-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold vazir">برداشت‌های کلی</h3>
                <div class="bg-purple-500 p-2 rounded-full">
                    <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'تومان'] as $currency => $label)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-purple-700 vazir">{{ $label }}:</span>
                    <span class="text-lg font-bold vazir">{{ number_format($withdrawalStats['total'][$currency])
                        }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col lg:flex-row gap-6 p-4">

        <!-- Withdrawal Form -->
        <div class="w-full lg:w-1/2 xl:w-2/5">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white vazir">
                            <i class="fa-solid fa-money-bill-wave ml-2"></i>
                            {{ $editingId ? 'ویرایش برداشت' : 'ثبت برداشت جدید' }}
                        </h2>
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-wallet text-white"></i>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="p-6 space-y-6">
                    <form wire:submit.prevent="withdraw" class="space-y-6">

                        <!-- Withdrawal Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 vazir">
                                نوع برداشت <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="type"
                                class="w-full h-12 px-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir">
                                <option value="">انتخاب نوع برداشت</option>
                                @foreach($this->expansesTypes as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('type')
                            <p class="mt-1 text-sm text-red-600 vazir">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency and Amount -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Currency -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 vazir">
                                    ارز <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="currency"
                                    class="w-full h-12 px-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir">
                                    <option value="AFN">افغانی</option>
                                    <option value="USD">دالر</option>
                                    <option value="EUR">یورو</option>
                                    <option value="IRR">تومان</option>
                                </select>
                                @error('currency')
                                <p class="mt-1 text-sm text-red-600 vazir">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 vazir">
                                    مقدار برداشت <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="amount" step="0.01" min="0"
                                    class="w-full h-12 px-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir"
                                    placeholder="0">
                                @error('amount')
                                <p class="mt-1 text-sm text-red-600 vazir">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Receiver Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 vazir">
                                تحویل به <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="receiver_type"
                                class="w-full h-12 px-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir">
                                <option value="staff">کارمند</option>
                                <option value="customer">مشتری</option>
                            </select>
                        </div>

                        <!-- Receiver Selection -->
                        <div>
                            @if($receiver_type === 'staff')
                            <label class="block text-sm font-medium text-gray-700 mb-2 vazir">
                                کارمند دریافت‌کننده <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="staff_id"
                                class="w-full h-12 px-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir">
                                <option value="">انتخاب کارمند</option>
                                @foreach($this->staffs as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('staff_id')
                            <p class="mt-1 text-sm text-red-600 vazir">{{ $message }}</p>
                            @enderror
                            @else
                            <label class="block text-sm font-medium text-gray-700 mb-2 vazir">
                                مشتری دریافت‌کننده <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="customer_id"
                                class="w-full h-12 px-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir">
                                <option value="">انتخاب مشتری</option>
                                @foreach($this->customers as $id => $info)
                                <option value="{{ $id }}">{{ $info }}</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                            <p class="mt-1 text-sm text-red-600 vazir">{{ $message }}</p>
                            @enderror
                            @endif
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 vazir">
                                توضیحات
                            </label>
                            <textarea wire:model="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none vazir"
                                placeholder="دلیل برداشت را وارد کنید..."></textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600 vazir">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-3 px-6 rounded-xl font-medium transition shadow-lg vazir">
                                <i class="fa-solid fa-check ml-2"></i>
                                {{ $editingId ? 'بروزرسانی برداشت' : 'ثبت برداشت' }}
                            </button>

                            @if($editingId)
                            <button type="button" wire:click="cancelEdit"
                                class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-3 px-6 rounded-xl font-medium transition shadow-lg vazir">
                                <i class="fa-solid fa-times ml-2"></i>
                                لغو ویرایش
                            </button>
                            @else
                            <button type="button" wire:click="resetForm"
                                class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white py-3 px-6 rounded-xl font-medium transition shadow-lg vazir">
                                <i class="fa-solid fa-eraser ml-2"></i>
                                پاک کردن فرم
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Withdrawals Table -->
        <div class="w-full lg:w-1/2 xl:w-3/5">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white vazir">
                            <i class="fa-solid fa-list ml-2"></i>
                            تاریخچه برداشت‌ها
                        </h2>
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-history text-white"></i>
                        </div>
                    </div>
                </div>

                <!-- Table Body -->
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="bg-gray-50 text-gray-700 vazir">
                                <tr>
                                    <th class="px-4 py-3 font-bold border-b">#</th>
                                    <th class="px-4 py-3 font-bold border-b">نوع برداشت</th>
                                    <th class="px-4 py-3 font-bold border-b">ارز</th>
                                    <th class="px-4 py-3 font-bold border-b">مبلغ</th>
                                    <th class="px-4 py-3 font-bold border-b">دریافت‌کننده</th>
                                    <th class="px-4 py-3 font-bold border-b">توضیحات</th>
                                    <th class="px-4 py-3 font-bold border-b">تاریخ</th>
                                    <th class="px-4 py-3 font-bold border-b">عملیات</th>
                                </tr>
                            </thead>
                            <tbody class="vazir">
                                @forelse($withdrawals as $key => $withdrawal)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-medium text-gray-900 text-center">
                                        {{ ($withdrawals->currentPage() - 1) * $withdrawals->perPage() + $key + 1 }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $withdrawal->expanses_type }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                        $currencyStyles = [
                                        'AFN' => 'bg-rose-100 text-rose-800',
                                        'USD' => 'bg-green-100 text-green-800',
                                        'EUR' => 'bg-blue-100 text-blue-800',
                                        'IRR' => 'bg-purple-100 text-purple-800',
                                        ];
                                        $currencyLabels = [
                                        'AFN' => 'افغانی',
                                        'USD' => 'دالر',
                                        'EUR' => 'یورو',
                                        'IRR' => 'تومان',
                                        ];
                                        @endphp
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium {{ $currencyStyles[$withdrawal->currency] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $currencyLabels[$withdrawal->currency] ?? $withdrawal->currency }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ number_format($withdrawal->amount) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($withdrawal->staff_id && $withdrawal->staff)
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                            {{ $withdrawal->staff->fullname }}
                                        </span>
                                        @elseif($withdrawal->customer_id && $withdrawal->customer)
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                            {{ $withdrawal->customer->fullname }}
                                        </span>
                                        @else
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">
                                            صندوق
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 max-w-xs truncate">
                                        {{ $withdrawal->description ?? 'بدون توضیح' }}
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="text-sm font-medium">
                                            {{
                                            \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($withdrawal->created_at))->format('Y/m/d')
                                            }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-center gap-1 space-x-2">
                                            <button wire:click="edit({{ $withdrawal->id }})"
                                                class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition tooltip"
                                                title="ویرایش">
                                                <i class="fa-solid fa-edit text-sm"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $withdrawal->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition tooltip"
                                                title="حذف">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500 vazir">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-2"></i>
                                            <p class="text-lg">هیچ برداشتی یافت نشد</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($withdrawals->hasPages())
                    <div class="mt-6">
                        {{ $withdrawals->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($confirmDeleteId)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                    <i class="fa-solid fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-center text-gray-900 mb-2 vazir">
                    تأیید حذف برداشت
                </h3>
                <p class="text-gray-600 text-center mb-6 vazir">
                    آیا از حذف این برداشت اطمینان دارید؟ این عمل غیرقابل بازگشت است.
                </p>
                <div class="flex gap-3">
                    <button wire:click="deleteWithdrawal"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-xl font-medium transition vazir">
                        <i class="fa-solid fa-trash ml-2"></i>
                        بله، حذف شود
                    </button>
                    <button wire:click="$set('confirmDeleteId', null)"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-xl font-medium transition vazir">
                        <i class="fa-solid fa-times ml-2"></i>
                        انصراف
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<script>
    tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#EEF2FF',
                            500: '#6366F1',
                            600: '#4F46E5',
                        },
                    },
                    fontFamily: {
                        vazir: ['Vazir', 'sans-serif'],
                        shabnam: ['Shabnam', 'sans-serif'],
                        yekan: ['DimaYekan', 'sans-serif'],
                        amiri: ['Yekan-Regular', 'sans-serif'],
                        times: ['Times', 'serif'],
                    },
                },
            },
        }
</script>

<!-- ✅ فونت‌ها و کلاس‌ها -->
<style>
    @font-face {
        font-family: "DimaYekan";
        src: url("/fonts/Yekan-Regular.ttf") format("truetype");
    }

    @font-face {
        font-family: "times";
        src: url("/fonts/times.ttf") format("truetype");
    }

    @font-face {
        font-family: "vazir";
        src: url("/fonts/Vazir.ttf") format("truetype");
    }

    @font-face {
        font-family: "shabnam";
        src: url("/fonts/Shabnam-Medium.ttf") format("truetype");
    }

    @font-face {
        font-family: "Mj_Afrigha";
        src: url("/fonts/Mj_Afrigha.ttf") format("truetype");
    }

    @font-face {
        font-family: "Yekan-Regular";
        src: url("/fonts/Yekan-Regular.ttf") format("truetype");
    }

    /* کلاس‌های کمکی برای انتخاب سریع فونت */
    .yekan {
        font-family: "DimaYekan", sans-serif;
    }

    .shabnam {
        font-family: "shabnam", sans-serif;
    }

    .Mj_Afrigha {
        font-family: "Mj_Afrigha", sans-serif;
    }

    .vazir {
        font-family: "vazir", sans-serif;
    }

    .amiri {
        font-family: "Yekan-Regular", sans-serif;
    }

    .times {
        font-family: "times", serif;
    }
</style>
@endpush