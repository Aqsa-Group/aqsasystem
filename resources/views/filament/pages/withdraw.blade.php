<x-filament-panels::page>
    <div class="flex items-center justify-center min-h-screen p-6 -mt-12">
        <div
            class="flex flex-col md:flex-row bg-white dark:bg-gray-900 rounded-2xl shadow-lg overflow-hidden max-w-5xl w-full">

            <div class="w-full md:w-1/2 p-6">
                {{-- اتصال فرم به متد withdrawFromSafe --}}
                <form wire:submit.prevent="withdrawFromSafe">

                    {{-- نوع برداشت --}}
                    <div class="mb-5 grid">
                        <label for="type" class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                            نوع مصرف
                        </label>
                        <select id="type" wire:model.live="withdrawType" {{-- این تغییر مهم است --}}
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   dark:bg-gray-800 dark:text-gray-100">
                            <option value="">انتخاب کنید</option>
                            <option value="electricity">برق</option>
                            <option value="rent">کرایه</option>
                            <option value="water">مالیه </option>
                            <option value="food">غذا</option>
                            <option value="salary">معاش کارمند</option>
                            <option value="other">متفرقه</option>

                        </select>
                    </div>

                    {{-- انتخاب کارمند (فقط وقتی معاش کارمند انتخاب شود) --}}
                    @if ($withdrawType === 'salary')
                        <div class="mb-5 grid">
                            <label for="staff_id" class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                انتخاب کارمند
                            </label>
                            <select id="staff_id" wire:model.defer="staffId"
                                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 
                                       dark:bg-gray-800 dark:text-gray-100">
                                <option value="">انتخاب کارمند</option>
                                @foreach ($staffList as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- مقدار برداشت --}}
                    <div class="mb-5 grid">
                        <label for="amount" class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                            مقدار برداشت
                        </label>
                        <input type="number" id="amount" wire:model.defer="withdrawAmount"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    {{-- توضیحات برداشت --}}
                    <div class="mb-5 grid">
                        <label for="description" class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                            توضیحات برداشت
                        </label>
                        <textarea id="description" rows="4" wire:model.defer="withdrawDescription"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   dark:bg-gray-800 dark:text-gray-100"></textarea>
                    </div>

                    {{-- دکمه ثبت --}}
                    <div>
                        <x-filament::button type="submit" color="info" class="w-full" wire:loading.attr="disabled">
                            <span wire:loading.remove>ثبت برداشت</span>
                            <span wire:loading>در حال ثبت...</span>
                        </x-filament::button>

                    </div>

                </form>
            </div>

            <div class="w-full md:w-1/2">
                <img src="{{ asset('assets/safe.jpg') }}" alt="تصویر صندوق" class="h-64 md:h-full w-full object-cover">
            </div>
        </div>
    </div>
</x-filament-panels::page>
