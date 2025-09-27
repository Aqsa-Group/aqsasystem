<x-filament-panels::page>
   <div class="flex flex-col md:flex-row h-fit gap-10 bg-white rounded-2xl shadow-md p-4">

      {{-- فرم ثبت مبلغ --}}
      <div class="md:w-1/2">
         <form wire:submit.prevent="addToSafe" class="mt-4 space-y-4">

            {{-- مبلغ --}}
            <div>
               <label for="amount" class="block mb-1 font-medium text-gray-700">مبلغ</label>
               <input type="number" id="amount" wire:model="addAmount" placeholder="مبلغ"
                  class="w-full rounded-md border-gray-300 p-2" min="0" step="0.01" required>
               @error('addAmount') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- انتخاب ارز --}}
            <div>
               <label for="currency" class="block mb-1 font-medium text-gray-700">ارز</label>
               <select wire:model="currency" class="w-full rounded-md border-gray-300 p-2" required>
                  <option value="AFN">افغانی</option>
                  <option value="USD">دالر</option>
               </select>

               @error('currency') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- توضیحات --}}
            <div>
               <label for="note" class="block mb-1 font-medium text-gray-700">توضیحات</label>
               <textarea id="note" wire:model="note" cols="52" rows="4"
                  class="w-full outline-none rounded-md border-gray-300 p-2"
                  placeholder="توضیحات افزودن به صندوق را بنویسید!"></textarea>
               @error('note') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- دکمه ثبت --}}
            <x-filament::button type="submit" color="info" class="w-full mt-2" wire:loading.attr="disabled">
               <span wire:loading.remove>ثبت مقدار</span>
               <span wire:loading>در حال ثبت...</span>
            </x-filament::button>

         </form>
      </div>

      {{-- تصویر --}}
      <div class="md:w-1/2 flex items-center justify-center">
         <img src="{{ asset('assets/safe.jpg') }}" alt="تصویر صندوق"
            class="h-52 md:h-64 w-full object-cover rounded-md shadow-sm">
      </div>

   </div>
</x-filament-panels::page>