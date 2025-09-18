<x-filament-panels::page>
   <div class="flex h-fit gap-10 bg-white rounded-2xl shadow-md">
      <div class="md:w-1/2 p-4">
         <form wire:submit.prevent="addToSafe" class="mt-10">
            <div class="flex-col space-y-4">

               <input  
                  type="number" 
                  id="amount" 
                  wire:model="addAmount" 
                  placeholder="مبلغ " 
                  class="w-full rounded-md border-gray-300" 
                  min="0" step="0.01" required>

               <select wire:model="currency" class="w-full rounded-md border-gray-300 p-2" required>
                  <option value="" disabled selected>انتخاب ارز</option>
                  <option value="AFN">افغانی</option>
                  <option value="USD">دالر</option>
               </select>

               <textarea 
                  wire:model="note"
                  cols="52" 
                  rows="6" 
                  class="w-full outline-none rounded-md border-gray-300 p-2"
                  placeholder="توضیحات افزودن به صندوق را بنویسید!"></textarea>
            </div>

            <x-filament::button type="submit" color="info" class="w-full mt-4" wire:loading.attr="disabled">
               <span wire:loading.remove>ثبت مقدار</span>
               <span wire:loading>در حال ثبت...</span>
            </x-filament::button>
         </form>
      </div>

      <div class="md:w-1/2">
         <img src="{{ asset('assets/safe.jpg') }}" 
              alt="تصویر صندوق"
              class="h-52 md:h-52 w-full object-cover rounded-md">
      </div>
   </div>
</x-filament-panels::page>
