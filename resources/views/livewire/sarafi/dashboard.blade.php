<div>
    <h1 class="mb-5" style="font-family: 'header'; font-size: 20px ">صفحه اصلی گزارشات و آمار</h1>
    <div class="grid grid-cols-1 md:grid-cols-6 gap-2 items-center justify-center ">

        {{-- payment card --}}
        <div
            class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
            <div class="flex items-center justify-center gap-2">
                <i class="fas fa-credit-card text-sm"></i>
                <a href="" class="text-[15px]">{{ __('messages.recipt/withdraw') }}</a>
            </div>
        </div>

        {{-- transfer card --}}
        <div
            class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
            <div class="flex items-center justify-center gap-2">
                <i class="fa-solid fa-money-bill-transfer"></i>
                <a href="" class="text-[15px]">{{ __('messages.transfer') }}</a>
            </div>
        </div>

        {{-- jornal accounts --}}

        <div
            class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
            <div class="flex items-center justify-center gap-2">
                <i class="fa-solid fa-newspaper"></i>
                <a href="" class="text-[15px]">{{ __('messages.newspaper_accounts') }}</a>
            </div>
        </div>


        <div
            class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
            <div class="flex items-center justify-center gap-2">
                <img src="{{ asset('assets/sarafi/exchange_money.png') }}" class="h-7 w-7" alt="">
                <a href="" class="text-[15px]">{{ __('messages.coversion_account') }}</a>
            </div>
        </div>







        <div
            class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
            <div class="flex items-center justify-center gap-2">
                <i class="fa-solid fa-money-bill-transfer"></i>
                <a href="" class="text-[15px]">{{ __('messages.coversion_transfer') }}</a>
            </div>
        </div>

        <div
            class="border bg-white border-gray-200 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
            <div class="flex items-center justify-center gap-2">
                <img src="{{ asset('assets/sarafi/general.png') }}" class="h-6 w-6" alt="">
                <a href="" class="text-[15px]">{{ __('messages.general_jornal') }}</a>
            </div>
        </div>
    </div>

    <div x-data="{ activeTab: @entangle('activeTab') }" class="mt-14 border-gray-200" style="font-family: 'header';">
        <div class="flex gap-20 mb-2 border-b border-gray-400">
            <!-- پنل عمومی -->
            <a href="#"
               @click.prevent="activeTab = 'general'"
               class="px-4 py-2"
               :class="activeTab === 'general' 
                   ? 'border-t-2 border-l-2 border-r-2 border-gray-300 border-b-0    bg-white' 
                   : 'border-b-2 border-transparent hover:border-gray-400'">
                پنل عمومی
            </a>
    
            <!-- پنل گزارشات -->
            <a href="#"
               @click.prevent="activeTab = 'reports'"
               class="px-4 py-2"
               :class="activeTab === 'reports' 
                   ? 'border-t-2 border-l-2 border-r-2 border-gray-200 border-b-0 bg-white shadow-md' 
                   : 'border-b-2 border-transparent hover:border-gray-400'">
                پنل گزارشات و بیلانس
            </a>
        </div>
    
        <div class="p-4">
            <template x-if="activeTab === 'general'">
                <div class="flex gap-4 ">
                  
                  <div class="md:w-1/2 grid grid-cols-1 md:grid-cols-2 gap-2">
                
                        <div class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
                              <div class="flex items-center flex-col space-y-2">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-users "></i> 
                                         <p>کاربران فعال</p>
                                    </div>
                                    <p>2</p>
                              </div> 
                        </div>
    
                        <div class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
                            <div class="flex items-center flex-col space-y-2">
                                  <div class="flex items-center gap-3">
                                      <i class="fas fa-users "></i> 
                                       <p>مشتریان</p>
                                  </div>
                                  <p>2</p>
                            </div> 
                      </div>
    
    
                      <div class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
                        <div class="flex items-center flex-col space-y-2">
                              <div class="flex items-center gap-3">
                                  <i class="fas fa-users "></i> 
                                   <p>ترانزکشن های امروز</p>
                              </div>
                              <p>2</p>
                        </div> 
                     </div>

                     <div class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
                        <div class="flex items-center flex-col space-y-2">
                              <div class="flex items-center gap-3">
                                  <i class="fas fa-users "></i> 
                                   <p>ترانزکشن های در انتظار</p>
                              </div>
                              <p>2</p>
                        </div> 
                     </div>
    
                    </div>



                    <div class="md:w-1/2 grid grid-cols-1 md:grid-cols-1 space-y-2">
                
                        {{-- payment card --}}
                        <div class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
                              <div class="flex items-center flex-col space-y-2">
                                    <div class="flex items-center gap-3">
                                         <p>موجودی افغانی در صندوق</p>
                                    </div>
                                    <p>200000 افغانی</p>
                              </div> 
                        </div>
    
                        <div class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
                            <div class="flex items-center flex-col space-y-2">
                                  <div class="flex items-center gap-3">
                                       <p>موجودی دالر در صندوق</p>
                                  </div>
                                  <p>200000 افغانی</p>
                            </div> 
                      </div>
  
    
                      <div class="border bg-white border-gray-300 rounded-md  shadow-sm hover:bg-cyan-400 transition-colors duration-300 ease-linear">
                        <div class="flex h-16">
                            
                            <div class="w-1/2 flex items-center justify-center">
                                <p class="text-lg font-semibold">3000</p>
                            </div>

                            <div class="w-1/2 flex items-center justify-center bg-green-500 text-white rounded-l-md">
                                <i class="fa-solid fa-euro-sign text-2xl"></i>
                            </div>
                    
                        </div>
                    </div>
                    


                  <div class="border bg-white border-gray-300 rounded-md p-3 shadow-sm hover:bg-cyan-400 transition-colors duration-300  ease-linear ">
                    <div class="flex items-center flex-col space-y-2">
                          <div class="flex items-center gap-3">
                               <p>موجودی تومان در صندوق</p>
                          </div>
                          <p>200000 افغانی</p>
                    </div> 
              </div>


    
                    </div>
                   

                </div>
              
              
      

                       
            </template>
    
            <template x-if="activeTab === 'reports'">
                <h2 class="text-lg font-bold">📊 محتوای پنل گزارشات و بیلانس</h2>
            </template>
        </div>
    </div>
    
</div>
