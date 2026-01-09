<div class="border-[#8C8C8C] min-h-screen" style="font-family: 'header';">

    <h1 class="mb-8 text-[18px] font-bold text-gray-700 vazir  dark:text-white" style="font-weight: 800"><?php echo e(__('messages.page_title')); ?></h1>

    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-5 gap-4 vazir W-[1097PX]" style="font-weight: 400">

        <!-- رسید / برداشت -->
        <a href="<?php echo e(route('sarafi.transactions')); ?>" class="block">
            <div style="box-shadow: -1px 4px 4px 0px rgba(37, 99, 235, 0.25);" class="border bg-white
                rounded-xl py-3 px-6 flex items-center justify-center gap-[10px] 
                text-[#2563EB] text-[14px] ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 8.50488H22" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6 16.5049H8" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M10.5 16.5049H14.5" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M6.44 3.50488H17.55C21.11 3.50488 22 4.38488 22 7.89488V16.1049C22 19.6149 21.11 20.4949 17.56 20.4949H6.44C2.89 20.5049 2 19.6249 2 16.1149V7.89488C2 4.38488 2.89 3.50488 6.44 3.50488Z"
                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="whitespace-nowrap overflow-hidden text-ellipsis">
                    رسید / برد صندوق
                </span>
            </div>
        </a>


        <!-- انتقال -->
        <a href="<?php echo e(route('sarafi.account_to_account')); ?>" class="block">
            <div class="group border bg-white rounded-xl py-3 px-6 flex items-center justify-center gap-[10px]
                text-[#2563EB] text-[14px] hover:bg-[#2563EB] hover:text-white"
                style="box-shadow: -1px 4px 4px 0px rgba(37, 99, 235, 0.25);">

                <!-- آیکون پیشفرض -->
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="block group-hover:hidden">
                    <path
                        d="M14.55 21.67C18.84 20.54 22 16.64 22 12C22 6.48 17.56 2 12 2C5.33 2 2 7.56 2 7.56M2 7.56V3M2 7.56H4.01H6.44"
                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2 12C2 17.52 6.48 22 12 22" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" stroke-dasharray="3 3" />
                </svg>

                <!-- آیکون جایگزین -->
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="hidden group-hover:block">
                    <path
                        d="M14.55 21.67C18.84 20.54 22 16.64 22 12C22 6.48 17.56 2 12 2C5.33 2 2 7.56 2 7.56M2 7.56V3M2 7.56H4.01H6.44"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2 12C2 17.52 6.48 22 12 22" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" stroke-dasharray="3 3" />
                </svg>

                <span>انتقال حساب به حساب</span>
            </div>
        </a>

        <!-- خرید و فروش ارز / صندوق -->
        <a href="<?php echo e(route('sarafi.buy-sell-currency')); ?>" class="block">
            <div style="box-shadow: -1px 4px 4px 0px rgba(37, 99, 235, 0.25);" class="border bg-white
                rounded-xl py-3 px-6 flex items-center justify-center gap-[10px] 
                text-[#2563EB] text-[14px] ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9 8.38086H13.6846C14.7231 8.38086 15.5654 9.31548 15.5654 10.2616C15.5654 11.3001 14.7231 12.1424 13.6846 12.1424H9V8.38086Z"
                        stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M9 12.1309H14.3539C15.5423 12.1309 16.5 12.9732 16.5 14.0116C16.5 15.0501 15.5423 15.8924 14.3539 15.8924H9V12.1309Z"
                        stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M12.2769 15.8809V17.7616" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9.93457 15.8809V17.7616" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12.2769 6.5V8.38077" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M9.93457 6.5V8.38077" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M10.7769 8.38086H7.5" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M10.7769 15.8809H7.5" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                        stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" />
                </svg>
                <span><?php echo e(__('messages.selling')); ?></span>
            </div>
        </a>

        <!-- حساب تبدیل -->
        <a href="<?php echo e(route('sarafi.conversion.in.account')); ?>" class="block">
            <div style="box-shadow: -1px 4px 4px 0px rgba(37, 99, 235, 0.25);" class="border bg-white
                rounded-xl py-3 px-6 flex items-center justify-center gap-[10px] 
                text-[#2563EB] text-[14px] ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.28 10.4498L21 6.72974L17.28 3.00977" stroke="#2563EB" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3 6.72949H21" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M6.71997 13.5498L3 17.2698L6.71997 20.9898" stroke="#2563EB" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M21 17.2695H3" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span><?php echo e(__('messages.coversion_account')); ?></span>
            </div>
        </a>

        <!-- انتقال تبدیل -->
        <a href="<?php echo e(route('sarafi.conversion-transfer')); ?>" class="block">
            <div style="box-shadow: -1px 4px 4px 0px rgba(37, 99, 235, 0.25);" class="border bg-white
                rounded-xl py-3 px-6 flex items-center justify-center gap-[10px] 
                text-[#2563EB] text-[14px] ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 7V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V7C3 4 4.5 2 8 2H16C19.5 2 21 4 21 7Z"
                        stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M14.5 4.5V6.5C14.5 7.6 15.4 8.5 16.5 8.5H18.5" stroke="#2563EB" stroke-width="1.5"
                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M8 13H12" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M8 17H16" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>

                <span><?php echo e(__('messages.coversion_transfer')); ?></span>
            </div>
        </a>


        <!-- رسید بانکی -->
        <a href="<?php echo e(route('sarafi.remittance')); ?>" class="block">
            <div style="box-shadow: -1px 4px 4px 0px rgba(37, 99, 235, 0.25);" class="border bg-white
                rounded-xl py-3 px-6 flex items-center justify-center gap-[10px] 
                text-[#2563EB] text-[14px]">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M22 6V8.42C22 10 21 11 19.42 11H16V4.01C16 2.9 16.91 2 18.02 2C19.11 2.01 20.11 2.45 20.83 3.17C21.55 3.9 22 4.9 22 6Z"
                        stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M2 7V21C2 21.83 2.94 22.3 3.6 21.8L5.31 20.52C5.71 20.22 6.27 20.26 6.63 20.62L8.29 22.29C8.68 22.68 9.32 22.68 9.71 22.29L11.39 20.61C11.74 20.26 12.3 20.22 12.69 20.52L14.4 21.8C15.06 22.29 16 21.82 16 21V4C16 2.9 16.9 2 18 2H7H6C3 2 2 3.79 2 6V7Z"
                        stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M9 13.0098H12" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M9 9.00977H12" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M5.99561 13H6.00459" stroke="#2563EB" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M5.99561 9H6.00459" stroke="#2563EB" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span>رسید بانکی</span>
            </div>
        </a>

        <!-- برد بانکی -->
        <a href="<?php echo e(route('sarafi.withdrawbank')); ?>" class="block">
            <div style="box-shadow: -1px 4px 4px 0px rgba(37, 99, 235, 0.25);" class="border bg-white
                rounded-xl py-3 px-6 flex items-center justify-center gap-[10px] 
                text-[#2563EB] text-[14px] ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12.37 2.14984L21.37 5.74982C21.72 5.88982 22 6.30981 22 6.67981V9.99982C22 10.5498 21.55 10.9998 21 10.9998H3C2.45 10.9998 2 10.5498 2 9.99982V6.67981C2 6.30981 2.28 5.88982 2.63 5.74982L11.63 2.14984C11.83 2.06984 12.17 2.06984 12.37 2.14984Z"
                        stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M22 22H2V19C2 18.45 2.45 18 3 18H21C21.55 18 22 18.45 22 19V22Z" stroke="#2563EB"
                        stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4 18V11" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M8 18V11" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M12 18V11" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16 18V11" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M20 18V11" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M1 22H23" stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M12 8.5C12.8284 8.5 13.5 7.82843 13.5 7C13.5 6.17157 12.8284 5.5 12 5.5C11.1716 5.5 10.5 6.17157 10.5 7C10.5 7.82843 11.1716 8.5 12 8.5Z"
                        stroke="#2563EB" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>



                <span>برد بانکی</span>
            </div>
        </a>

        <!-- روزنامچه -->
        <a href="<?php echo e(route('sarafi.journal')); ?>" class="block">
            <div style="box-shadow: -1px 4px 4px 0px rgba(37, 99, 235, 0.25);" class="border bg-white
                rounded-xl py-3 px-6 flex items-center justify-center gap-[10px] 
                text-[#2563EB] text-[14px] ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M20.082 3.01775L20.1081 3.76729V3.76729L20.082 3.01775ZM16.5 3.48744L16.2849 2.76895V2.76895L16.5 3.48744ZM13.6738 4.80275L13.2982 4.15363L13.2982 4.15363L13.6738 4.80275ZM3.9824 3.07489L3.93639 3.82348L3.9824 3.07489ZM7 3.48744L7.19136 2.76227V2.76227L7 3.48744ZM10.2823 4.87546L9.93167 5.53847V5.53847L10.2823 4.87546ZM13.6276 20.0692L13.9804 20.7311V20.7311L13.6276 20.0692ZM17 18.6334L16.8086 17.9082H16.8086L17 18.6334ZM19.9851 18.2228L20.032 18.9714V18.9714L19.9851 18.2228ZM10.3724 20.0692L10.0196 20.7311H10.0196L10.3724 20.0692ZM7 18.6334L7.19136 17.9082H7.19136L7 18.6334ZM4.01486 18.2228L3.96804 18.9714H3.96804L4.01486 18.2228ZM22.75 10.5384C22.75 10.1242 22.4142 9.78839 22 9.78839C21.5858 9.78839 21.25 10.1242 21.25 10.5384H22H22.75ZM21.25 7C21.25 7.41421 21.5858 7.75 22 7.75C22.4142 7.75 22.75 7.41421 22.75 7H22H21.25ZM1.25 10.5707C1.25 10.9849 1.58579 11.3207 2 11.3207C2.41421 11.3207 2.75 10.9849 2.75 10.5707H2H1.25ZM2.75 14C2.75 13.5858 2.41421 13.25 2 13.25C1.58579 13.25 1.25 13.5858 1.25 14H2H2.75ZM20.082 3.01775L20.0559 2.2682C18.9175 2.30785 17.4296 2.42627 16.2849 2.76895L16.5 3.48744L16.7151 4.20594C17.6643 3.92179 18.9892 3.80627 20.1081 3.76729L20.082 3.01775ZM16.5 3.48744L16.2849 2.76895C15.2899 3.06684 14.1706 3.64868 13.2982 4.15363L13.6738 4.80275L14.0495 5.45188C14.9 4.95969 15.8949 4.45149 16.7151 4.20594L16.5 3.48744ZM3.9824 3.07489L3.93639 3.82348C4.90238 3.88285 5.99643 3.99829 6.80864 4.21262L7 3.48744L7.19136 2.76227C6.23055 2.50873 5.01517 2.38695 4.02841 2.3263L3.9824 3.07489ZM7 3.48744L6.80864 4.21262C7.77076 4.46651 8.95486 5.02196 9.93167 5.53847L10.2823 4.87546L10.6328 4.21244C9.63736 3.68606 8.32766 3.06211 7.19136 2.76227L7 3.48744ZM13.6276 20.0692L13.9804 20.7311C14.9714 20.2028 16.1988 19.6205 17.1914 19.3585L17 18.6334L16.8086 17.9082C15.6383 18.217 14.2827 18.8701 13.2748 19.4074L13.6276 20.0692ZM17 18.6334L17.1914 19.3585C17.9943 19.1466 19.0732 19.0313 20.032 18.9714L19.9851 18.2228L19.9383 17.4743C18.9582 17.5356 17.7591 17.6574 16.8086 17.9082L17 18.6334ZM10.3724 20.0692L10.7252 19.4074C9.71727 18.8701 8.3617 18.217 7.19136 17.9082L7 18.6334L6.80864 19.3585C7.8012 19.6205 9.0286 20.2028 10.0196 20.7311L10.3724 20.0692ZM7 18.6334L7.19136 17.9082C6.24092 17.6574 5.04176 17.5356 4.06168 17.4743L4.01486 18.2228L3.96804 18.9714C4.9268 19.0313 6.00566 19.1466 6.80864 19.3585L7 18.6334ZM22 16.1436H21.25C21.25 16.8293 20.6817 17.4278 19.9383 17.4743L19.9851 18.2228L20.032 18.9714C21.5062 18.8791 22.75 17.6798 22.75 16.1436H22ZM22 4.93319H22.75C22.75 3.46989 21.5847 2.21495 20.0559 2.2682L20.082 3.01775L20.1081 3.76729C20.7229 3.74588 21.25 4.25161 21.25 4.93319H22ZM2 16.1436H1.25C1.25 17.6798 2.49378 18.8791 3.96804 18.9714L4.01486 18.2228L4.06168 17.4743C3.31831 17.4278 2.75 16.8293 2.75 16.1436H2ZM13.6276 20.0692L13.2748 19.4074C12.4825 19.8297 11.5175 19.8297 10.7252 19.4074L10.3724 20.0692L10.0196 20.7311C11.2529 21.3885 12.7471 21.3885 13.9804 20.7311L13.6276 20.0692ZM13.6738 4.80275L13.2982 4.15363C12.4801 4.62709 11.4617 4.6507 10.6328 4.21244L10.2823 4.87546L9.93167 5.53847C11.2239 6.22177 12.791 6.18025 14.0495 5.45188L13.6738 4.80275ZM2 4.9978H2.75C2.75 4.30062 3.30243 3.78451 3.93639 3.82348L3.9824 3.07489L4.02841 2.3263C2.47017 2.23053 1.25 3.49864 1.25 4.9978H2ZM22 16.1436H22.75V10.5384H22H21.25V16.1436H22ZM22 7H22.75V4.93319H22H21.25V7H22ZM2 10.5707H2.75V4.9978H2H1.25V10.5707H2ZM2 16.1436H2.75V14H2H1.25V16.1436H2Z"
                        fill="#2563EB" />
                    <path d="M12 5.50049V16.0005V20.5005" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" />
                </svg>

                <span>روزنامچه</span>
            </div>
        </a>



    </div>


    <div x-data="{ activeTab: <?php if ((object) ('activeTab') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('activeTab'->value()); ?>')<?php echo e('activeTab'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('activeTab'); ?>')<?php endif; ?> }" class="mt-12">
        <div class="flex gap-2">

            <!-- TAB: عمومی -->
            <a href="#" @click.prevent="activeTab = 'general'" @click.prevent="activeTab = 'general'" class="flex items-center gap-3 px-6 py-2 rounded-2xl transition-all duration-300
          bg-white border" :class="activeTab === 'general'
      ? ' text-black tab-active-shadow'
        : 'border-transparent text-gray-500  hover:text-black'">
                <!-- آیکون -->
                <div class="h-9 w-9 flex items-center justify-center rounded-full ">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22 8.52V3.98C22 2.57 21.36 2 19.77 2H15.73C14.14 2 13.5 2.57 13.5 3.98V8.51C13.5 9.93 14.14 10.49 15.73 10.49H19.77C21.36 10.5 22 9.93 22 8.52Z"
                            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M22 19.77V15.73C22 14.14 21.36 13.5 19.77 13.5H15.73C14.14 13.5 13.5 14.14 13.5 15.73V19.77C13.5 21.36 14.14 22 15.73 22H19.77C21.36 22 22 21.36 22 19.77Z"
                            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M10.5 8.52V3.98C10.5 2.57 9.86 2 8.27 2H4.23C2.64 2 2 2.57 2 3.98V8.51C2 9.93 2.64 10.49 4.23 10.49H8.27C9.86 10.5 10.5 9.93 10.5 8.52Z"
                            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M10.5 19.77V15.73C10.5 14.14 9.86 13.5 8.27 13.5H4.23C2.64 13.5 2 14.14 2 15.73V19.77C2 21.36 2.64 22 4.23 22H8.27C9.86 22 10.5 21.36 10.5 19.77Z"
                            stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                </div>

                <span class="font-bold"><?php echo e(__('messages.tab_general')); ?></span>
            </a>

            <!-- TAB: صندوق‌ها -->
            <a href="#" @click.prevent="activeTab = 'safes'" @click.prevent="activeTab = 'safes'" class="flex items-center gap-3 px-5 py-2 rounded-2xl transition-all duration-300
          bg-white border" :class="activeTab === 'safes'
        ? ' text-black tab-active-shadow'
        : 'border-transparent text-gray-500  hover:text-black'">
                <div class="h-9 w-9 flex items-center justify-center rounded-full">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.5 12C13.5 12.8284 12.8284 13.5 12 13.5C11.1716 13.5 10.5 12.8284 10.5 12C10.5 11.1716 11.1716 10.5 12 10.5C12.8284 10.5 13.5 11.1716 13.5 12Z"
                            fill="#1C274C" />
                        <path d="M12 12V8" stroke="#1C274C" stroke-width="1.5" />
                        <path d="M12 12L15.5 13.5" stroke="#1C274C" stroke-width="1.5" />
                        <path d="M12 12L8.5 13.5" stroke="#1C274C" stroke-width="1.5" />
                        <path d="M4.5 7V10" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M4.5 14V17" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C21.5093 4.43821 21.8356 5.80655 21.9449 8"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M12 5C8.70017 5 7.05025 5 6.02513 6.02513C5 7.05025 5 8.70017 5 12C5 15.2998 5 16.9497 6.02513 17.9749C7.05025 19 8.70017 19 12 19C15.2998 19 16.9497 19 17.9749 17.9749C19 16.9497 19 15.2998 19 12C19 8.70017 19 7.05025 17.9749 6.02513C17.2933 5.34351 16.3354 5.11511 14.8 5.03857"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M10 8.53513C10.5883 8.19479 11.2714 8 12 8C14.2091 8 16 9.79086 16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12C8 11.6547 8.04375 11.3196 8.12602 11"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    </svg>

                </div>

                <span class="font-bold"><?php echo e(__('messages.tab_safes')); ?></span>
            </a>

            <!-- TAB: حساب‌ها -->
            <a href="#" @click.prevent="activeTab = 'account_safe'" @click.prevent="activeTab = 'account_safe'" class="flex items-center gap-3 px-5 py-2 rounded-2xl transition-all duration-300
          bg-white border" :class="activeTab === 'account_safe'
        ? ' text-black tab-active-shadow'
        : 'border-transparent text-gray-500  hover:text-black'">
                <div class="h-9 w-9 flex items-center justify-center rounded-full ">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12.37 2.14984L21.37 5.74982C21.72 5.88982 22 6.30981 22 6.67981V9.99982C22 10.5498 21.55 10.9998 21 10.9998H3C2.45 10.9998 2 10.5498 2 9.99982V6.67981C2 6.30981 2.28 5.88982 2.63 5.74982L11.63 2.14984C11.83 2.06984 12.17 2.06984 12.37 2.14984Z"
                            stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M22 22H2V19C2 18.45 2.45 18 3 18H21C21.55 18 22 18.45 22 19V22Z" stroke="#292D32"
                            stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4 18V11" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M8 18V11" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 18V11" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 18V11" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20 18V11" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M1 22H23" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M12 8.5C12.8284 8.5 13.5 7.82843 13.5 7C13.5 6.17157 12.8284 5.5 12 5.5C11.1716 5.5 10.5 6.17157 10.5 7C10.5 7.82843 11.1716 8.5 12 8.5Z"
                            stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>




                </div>

                <span class="font-bold"><?php echo e(__('messages.account_safes')); ?></span>
            </a>

        </div>


        <div class="p-10  w-full mt-8 h-[453px] bg-[#F5F5F5] dark:bg-black rounded-b-xl shadow-sm " :class="(activeTab === 'general') 
                      ? '' 
                      : ' bg-[#F5F5F5]'" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            <template x-if="activeTab === 'general'">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full">

                    <!-- کارت نمونه (تعداد کاربران) -->
                    <div class="group flex flex-col justify-between border rounded-2xl
                        bg-[#1A7477] bg-opacity-20 hover:bg-opacity-100
                        shadow-md hover:shadow-xl
                        w-full h-[180px] p-6 transform transition-colors duration-500 ease-in-out
  ">

                        <!-- ردیف بالا -->
                        <div class="flex items-start justify-between w-full">
                            <div
                                class="flex items-center justify-center group-hover:bg-white  bg-[#1A7477] rounded-full h-[60px] w-[60px] shadow-lg">
                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    class="block group-hover:hidden" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.63314 9.05817C7.5498 9.04984 7.4498 9.04984 7.35814 9.05817C5.3748 8.9915 3.7998 7.3665 3.7998 5.3665C3.7998 3.32484 5.4498 1.6665 7.4998 1.6665C9.54147 1.6665 11.1998 3.32484 11.1998 5.3665C11.1915 7.3665 9.61647 8.9915 7.63314 9.05817Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M13.6747 3.3335C15.2914 3.3335 16.5914 4.64183 16.5914 6.25016C16.5914 7.82516 15.3414 9.1085 13.7831 9.16683C13.7164 9.1585 13.6414 9.1585 13.5664 9.16683"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M3.4666 12.1335C1.44993 13.4835 1.44993 15.6835 3.4666 17.0252C5.75827 18.5585 9.5166 18.5585 11.8083 17.0252C13.8249 15.6752 13.8249 13.4752 11.8083 12.1335C9.52494 10.6085 5.7666 10.6085 3.4666 12.1335Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M15.2832 16.6665C15.8832 16.5415 16.4499 16.2998 16.9165 15.9415C18.2165 14.9665 18.2165 13.3582 16.9165 12.3832C16.4582 12.0332 15.8999 11.7998 15.3082 11.6665"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>



                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class=" hidden group-hover:block">
                                    <path
                                        d="M7.63314 9.05817C7.5498 9.04984 7.4498 9.04984 7.35814 9.05817C5.3748 8.9915 3.7998 7.3665 3.7998 5.3665C3.7998 3.32484 5.4498 1.6665 7.4998 1.6665C9.54147 1.6665 11.1998 3.32484 11.1998 5.3665C11.1915 7.3665 9.61647 8.9915 7.63314 9.05817Z"
                                        stroke="#1A7477" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M13.6747 3.3335C15.2914 3.3335 16.5914 4.64183 16.5914 6.25016C16.5914 7.82516 15.3414 9.1085 13.7831 9.16683C13.7164 9.1585 13.6414 9.1585 13.5664 9.16683"
                                        stroke="#1A7477" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M3.4666 12.1335C1.44993 13.4835 1.44993 15.6835 3.4666 17.0252C5.75827 18.5585 9.5166 18.5585 11.8083 17.0252C13.8249 15.6752 13.8249 13.4752 11.8083 12.1335C9.52494 10.6085 5.7666 10.6085 3.4666 12.1335Z"
                                        stroke="#1A7477" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M15.2832 16.6665C15.8832 16.5415 16.4499 16.2998 16.9165 15.9415C18.2165 14.9665 18.2165 13.3582 16.9165 12.3832C16.4582 12.0332 15.8999 11.7998 15.3082 11.6665"
                                        stroke="#1A7477" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>


                            </div>

                            <!-- عنوان -->
                            <p class="text-start font-medium text-[18px] text-[#1A7477]
            group-hover:text-white" style="font-weight: 400;">
                                تعداد کاربران
                            </p>
                        </div>

                        <!-- عدد + آپدیت -->
                        <div class="flex flex-col items-end text-end space-y-2">
                            <p class="text-[26px] font-extrabold text-[#1A7477]
            group-hover:text-white">
                                <?php echo e($UserCount); ?>

                            </p>

                            <div class="border-2 rounded-2xl px-3 py-2
            border-[#1A7477]
            group-hover:bg-white ">
                                <p class="text-sm vazir text-[#1A7477]
                group-hover:text-[#1A7477]">
                                    آخرین به‌روزرسانی 10 دقیقه پیش
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- تعداد مشتریان -->

                    <div class="group flex flex-col justify-between border rounded-2xl
                        bg-[#122EE1] bg-opacity-20 hover:bg-opacity-100
                        shadow-md hover:shadow-xl transition-colors duration-500 ease-in-out

                        w-full h-[180px] p-6">

                        <!-- ردیف بالا -->
                        <div class="flex items-start justify-between w-full">
                            <div
                                class="flex items-center justify-center group-hover:bg-white  bg-[#122EE1] rounded-full h-[60px] w-[60px] shadow-lg">
                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="block group-hover:hidden">
                                    <path
                                        d="M15.0004 5.96651C14.9504 5.95817 14.8921 5.95817 14.8421 5.96651C13.6921 5.92484 12.7754 4.98317 12.7754 3.81651C12.7754 2.62484 13.7337 1.6665 14.9254 1.6665C16.1171 1.6665 17.0754 2.63317 17.0754 3.81651C17.0671 4.98317 16.1504 5.92484 15.0004 5.96651Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M14.1422 12.0328C15.2839 12.2245 16.5422 12.0245 17.4255 11.4328C18.6005 10.6495 18.6005 9.36615 17.4255 8.58282C16.5339 7.99115 15.2589 7.79115 14.1172 7.99115"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M4.97539 5.96651C5.02539 5.95817 5.08372 5.95817 5.13372 5.96651C6.28372 5.92484 7.20039 4.98317 7.20039 3.81651C7.20039 2.62484 6.24206 1.6665 5.05039 1.6665C3.85873 1.6665 2.90039 2.63317 2.90039 3.81651C2.90872 4.98317 3.82539 5.92484 4.97539 5.96651Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M5.83353 12.0328C4.69186 12.2245 3.43353 12.0245 2.5502 11.4328C1.3752 10.6495 1.3752 9.36615 2.5502 8.58282C3.44186 7.99115 4.71686 7.79115 5.85853 7.99115"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M10.0004 12.1916C9.95039 12.1833 9.89206 12.1833 9.84206 12.1916C8.69206 12.1499 7.77539 11.2083 7.77539 10.0416C7.77539 8.84994 8.73372 7.8916 9.92539 7.8916C11.1171 7.8916 12.0754 8.85827 12.0754 10.0416C12.0671 11.2083 11.1504 12.1583 10.0004 12.1916Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M7.57461 14.8168C6.39961 15.6001 6.39961 16.8835 7.57461 17.6668C8.90794 18.5585 11.0913 18.5585 12.4246 17.6668C13.5996 16.8835 13.5996 15.6001 12.4246 14.8168C11.0996 13.9335 8.90794 13.9335 7.57461 14.8168Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>




                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="hidden group-hover:block">
                                    <path
                                        d="M15.0004 5.96651C14.9504 5.95817 14.8921 5.95817 14.8421 5.96651C13.6921 5.92484 12.7754 4.98317 12.7754 3.81651C12.7754 2.62484 13.7337 1.6665 14.9254 1.6665C16.1171 1.6665 17.0754 2.63317 17.0754 3.81651C17.0671 4.98317 16.1504 5.92484 15.0004 5.96651Z"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M14.1422 12.0328C15.2839 12.2245 16.5422 12.0245 17.4255 11.4328C18.6005 10.6495 18.6005 9.36615 17.4255 8.58282C16.5339 7.99115 15.2589 7.79115 14.1172 7.99115"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M4.97539 5.96651C5.02539 5.95817 5.08372 5.95817 5.13372 5.96651C6.28372 5.92484 7.20039 4.98317 7.20039 3.81651C7.20039 2.62484 6.24206 1.6665 5.05039 1.6665C3.85873 1.6665 2.90039 2.63317 2.90039 3.81651C2.90872 4.98317 3.82539 5.92484 4.97539 5.96651Z"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M5.83353 12.0328C4.69186 12.2245 3.43353 12.0245 2.5502 11.4328C1.3752 10.6495 1.3752 9.36615 2.5502 8.58282C3.44186 7.99115 4.71686 7.79115 5.85853 7.99115"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M10.0004 12.1916C9.95039 12.1833 9.89206 12.1833 9.84206 12.1916C8.69206 12.1499 7.77539 11.2083 7.77539 10.0416C7.77539 8.84994 8.73372 7.8916 9.92539 7.8916C11.1171 7.8916 12.0754 8.85827 12.0754 10.0416C12.0671 11.2083 11.1504 12.1583 10.0004 12.1916Z"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M7.57461 14.8168C6.39961 15.6001 6.39961 16.8835 7.57461 17.6668C8.90794 18.5585 11.0913 18.5585 12.4246 17.6668C13.5996 16.8835 13.5996 15.6001 12.4246 14.8168C11.0996 13.9335 8.90794 13.9335 7.57461 14.8168Z"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>



                            </div>

                            <!-- عنوان -->
                            <p class="text-start font-medium text-[18px] text-[#2563EB]
            group-hover:text-white" style="font-weight: 400;">
                                تعداد مشتریان
                            </p>
                        </div>

                        <!-- عدد + آپدیت -->
                        <div class="flex flex-col items-end text-end space-y-2">
                            <p class="text-[26px] font-extrabold text-[#2563EB]
            group-hover:text-white">
                                <?php echo e($customerCount); ?>

                            </p>

                            <div class="border-2 rounded-2xl px-3 py-2
            border-[#2563EB]
            group-hover:bg-white">
                                <p class="text-sm vazir text-[#2563EB]
                group-hover:text-[#2563EB]">
                                    آخرین به‌روزرسانی 10 دقیقه پیش
                                </p>
                            </div>
                        </div>
                    </div>


                    <!-- تراکنش‌های امروز -->

                    <div class="group flex flex-col justify-between border rounded-2xl
                        bg-[#EC4E19] bg-opacity-20 hover:bg-[#EB7825]
                        shadow-md hover:shadow-xl transition-colors duration-500 ease-in-out
                        w-full h-[180px] p-6">

                        <!-- ردیف بالا -->
                        <div class="flex items-start justify-between w-full">
                            <div
                                class="flex items-center justify-center group-hover:bg-white  bg-[#EC4E19] rounded-full h-[60px] w-[60px] shadow-lg">
                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="block group-hover:hidden">
                                    <path d="M10 4.6333H18.3333" stroke="white" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M11.85 1.6665H16.4833C17.9667 1.6665 18.3333 2.03317 18.3333 3.49984V6.92484C18.3333 8.3915 17.9667 8.75817 16.4833 8.75817H11.85C10.3667 8.75817 10 8.3915 10 6.92484V3.49984C10 2.03317 10.3667 1.6665 11.85 1.6665Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M1.66699 14.2168H10.0003" stroke="white" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M3.51699 11.25H8.15032C9.63366 11.25 10.0003 11.6167 10.0003 13.0833V16.5083C10.0003 17.975 9.63366 18.3417 8.15032 18.3417H3.51699C2.03366 18.3417 1.66699 17.975 1.66699 16.5083V13.0833C1.66699 11.6167 2.03366 11.25 3.51699 11.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M18.3333 12.5C18.3333 15.725 15.725 18.3333 12.5 18.3333L13.375 16.875"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M1.66699 7.49984C1.66699 4.27484 4.27533 1.6665 7.50033 1.6665L6.62534 3.12484"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>





                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="hidden group-hover:block">
                                    <path d="M10 4.6333H18.3333" stroke="#EB7725" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M11.85 1.6665H16.4833C17.9667 1.6665 18.3333 2.03317 18.3333 3.49984V6.92484C18.3333 8.3915 17.9667 8.75817 16.4833 8.75817H11.85C10.3667 8.75817 10 8.3915 10 6.92484V3.49984C10 2.03317 10.3667 1.6665 11.85 1.6665Z"
                                        stroke="#EB7725" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M1.66699 14.2168H10.0003" stroke="#EB7725" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M3.51699 11.25H8.15032C9.63366 11.25 10.0003 11.6167 10.0003 13.0833V16.5083C10.0003 17.975 9.63366 18.3417 8.15032 18.3417H3.51699C2.03366 18.3417 1.66699 17.975 1.66699 16.5083V13.0833C1.66699 11.6167 2.03366 11.25 3.51699 11.25Z"
                                        stroke="#EB7725" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M18.3333 12.5C18.3333 15.725 15.725 18.3333 12.5 18.3333L13.375 16.875"
                                        stroke="#EB7725" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M1.66699 7.49984C1.66699 4.27484 4.27533 1.6665 7.50033 1.6665L6.62534 3.12484"
                                        stroke="#EB7725" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>



                            </div>

                            <!-- عنوان -->
                            <p class="text-start font-medium text-[18px] text-[#EC4E19]
            group-hover:text-white" style="font-weight: 400;">
                                معاملات امروز
                            </p>
                        </div>

                        <!-- عدد + آپدیت -->
                        <div class="flex flex-col items-end text-end space-y-2">
                            <p class="text-[26px] font-extrabold text-[#EC4E19]
            group-hover:text-white">
                                <?php echo e($TransactionCount); ?>

                            </p>

                            <div class="border-2 rounded-2xl px-3 py-2
            border-[#EC4E19]
            group-hover:bg-white">
                                <p class="text-sm vazir text-[#EC4E19]
                group-hover:text-[#EC4E19]">
                                    آخرین به‌روزرسانی 10 دقیقه پیش
                                </p>
                            </div>
                        </div>
                    </div>




                    <!-- حواله های امروز -->

                    <div class="group flex flex-col justify-between border rounded-2xl
                        bg-[#5474BB] bg-opacity-20 hover:bg-opacity-100
                        shadow-md hover:shadow-xl transition-colors duration-500 ease-in-out

                        w-full h-[180px] p-6">

                        <!-- ردیف بالا -->
                        <div class="flex items-start justify-between w-full">
                            <div
                                class="flex items-center justify-center group-hover:bg-white  bg-[#5474BB] rounded-full h-[60px] w-[60px] shadow-lg">



                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="block group-hover:hidden">
                                    <path
                                        d="M6.16641 5.2668L13.2414 2.90846C16.4164 1.85013 18.1414 3.58346 17.0914 6.75846L14.7331 13.8335C13.1497 18.5918 10.5497 18.5918 8.96641 13.8335L8.26641 11.7335L6.16641 11.0335C1.40807 9.45013 1.40807 6.85846 6.16641 5.2668Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M8.4248 11.375L11.4081 8.3833" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>






                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="hidden group-hover:block">
                                    <path
                                        d="M6.16641 5.2668L13.2414 2.90846C16.4164 1.85013 18.1414 3.58346 17.0914 6.75846L14.7331 13.8335C13.1497 18.5918 10.5497 18.5918 8.96641 13.8335L8.26641 11.7335L6.16641 11.0335C1.40807 9.45013 1.40807 6.85846 6.16641 5.2668Z"
                                        stroke="#5474BB" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M8.4248 11.375L11.4081 8.3833" stroke="#5474BB" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>




                            </div>

                            <!-- عنوان -->
                            <p class="text-start font-medium text-[18px] text-[#5474BB]
            group-hover:text-white" style="font-weight: 400;">
                                حواله های امروز
                            </p>
                        </div>

                        <!-- عدد + آپدیت -->
                        <div class="flex flex-col items-end text-end space-y-2">
                            <p class="text-[26px] font-extrabold text-[#5474BB]
            group-hover:text-white">
                                <?php echo e($remittancecount); ?>

                            </p>

                            <div class="border-2 rounded-2xl px-3 py-2
            border-[#5474BB]
            group-hover:bg-white">
                                <p class="text-sm vazir text-[#5474BB]
                group-hover:text-[#5474BB]">
                                    آخرین به‌روزرسانی 10 دقیقه پیش
                                </p>
                            </div>
                        </div>
                    </div>






                    <!-- حواله های در انتظار تایید -->
                    <!--[if BLOCK]><![endif]--><?php if($waitting > 0): ?>
                    <a href="<?php echo e(route('sarafi.remittance-approval')); ?>" class="w-full">
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <div class="group flex flex-col justify-between border rounded-2xl
                        bg-[#EBA925] bg-opacity-20 hover:bg-[#AA7407]
                        shadow-md hover:shadow-xl transition-colors duration-500 ease-in-out

                        w-full h-[180px] p-6">

                            <!-- ردیف بالا -->
                            <div class="flex items-start justify-between w-full">
                                <div
                                    class="flex items-center justify-center group-hover:bg-white  bg-[#EBA925] rounded-full h-[60px] w-[60px] shadow-lg">

                                    <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="block group-hover:hidden">
                                        <path
                                            d="M12.7002 1.6665H7.30019C4.16686 1.6665 3.92519 4.48317 5.61686 6.0165L14.3835 13.9832C16.0752 15.5165 15.8335 18.3332 12.7002 18.3332H7.30019C4.16686 18.3332 3.92519 15.5165 5.61686 13.9832L14.3835 6.0165C16.0752 4.48317 15.8335 1.6665 12.7002 1.6665Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />

                                    </svg>






                                    <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="hidden group-hover:block">
                                        <path
                                            d="M12.7002 1.6665H7.30019C4.16686 1.6665 3.92519 4.48317 5.61686 6.0165L14.3835 13.9832C16.0752 15.5165 15.8335 18.3332 12.7002 18.3332H7.30019C4.16686 18.3332 3.92519 15.5165 5.61686 13.9832L14.3835 6.0165C16.0752 4.48317 15.8335 1.6665 12.7002 1.6665Z"
                                            stroke="#EBA925" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />

                                    </svg>








                                </div>

                                <!-- عنوان -->
                                <p class="text-start font-medium text-[18px] text-[#EBA925]
            group-hover:text-white" style="font-weight: 400;">
                                    حواله های در انتظار
                                </p>
                            </div>

                            <!-- عدد + آپدیت -->
                            <div class="flex flex-col items-end text-end space-y-2">
                                <p class="text-[26px] font-extrabold text-[#EBA925]
            group-hover:text-white">
                                    <?php echo e($waitting); ?>

                                </p>

                                <div class="border-2 rounded-2xl px-3 py-2
            border-[#EBA925]
            group-hover:bg-white">
                                    <p class="text-sm vazir text-[#EBA925]
                group-hover:text-[#EBA925]">
                                        آخرین به‌روزرسانی 10 دقیقه پیش
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if($waitting > 0): ?>
                    </a>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->







                    <!--  سود امروز -->

                    <div class="group flex flex-col justify-between border rounded-2xl
                        bg-[#108614] bg-opacity-20 hover:bg-[#1C9329]
                        shadow-md hover:shadow-xl transition-colors duration-500 ease-in-out

                        w-full h-[180px] p-6">


                        <!-- ردیف بالا -->
                        <div class="flex items-start justify-between w-full">
                            <div
                                class="flex items-center justify-center group-hover:bg-white  bg-[#108614] rounded-full h-[60px] w-[60px] shadow-lg">



                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="block group-hover:hidden">
                                    <path
                                        d="M22 7V12.5458M22 7H16.4179M22 7L17.5 11.5M14.6203 14.3347C13.6227 15.3263 13.1238 15.822 12.5051 15.822C11.8864 15.8219 11.3876 15.326 10.3902 14.3342L10.1509 14.0962C9.15254 13.1035 8.65338 12.6071 8.03422 12.6074C7.41506 12.6076 6.91626 13.1043 5.91867 14.0977L2 18"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                </svg>






                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="hidden group-hover:block">
                                    <path
                                        d="M22 7V12.5458M22 7H16.4179M22 7L17.5 11.5M14.6203 14.3347C13.6227 15.3263 13.1238 15.822 12.5051 15.822C11.8864 15.8219 11.3876 15.326 10.3902 14.3342L10.1509 14.0962C9.15254 13.1035 8.65338 12.6071 8.03422 12.6074C7.41506 12.6076 6.91626 13.1043 5.91867 14.0977L2 18"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>




                            </div>

                            <!-- عنوان -->
                            <p class="text-start font-medium text-[18px] text-[#108614]
            group-hover:text-white" style="font-weight: 400;">
                                سود امروز
                            </p>
                        </div>

                        <!-- عدد + آپدیت -->
                        <div class="flex flex-col items-end text-end space-y-2">
                            <p class="text-[26px] font-extrabold text-[#108614]
            group-hover:text-white">
                                <?php echo e($todayprofit); ?>

                            </p>

                            <div class="border-2 rounded-2xl px-3 py-2
            border-[#108614]
            group-hover:bg-white">
                                <p class="text-sm vazir text-[#108614]
                group-hover:text-[#108614]">
                                    آخرین به‌روزرسانی 10 دقیقه پیش
                                </p>
                            </div>
                        </div>
                    </div>





                    <!--  ضرر امروز  -->

                    <div class="group flex flex-col justify-between border rounded-2xl
                        bg-[#DD2424] bg-opacity-20 hover:bg-opacity-100
                        shadow-md hover:shadow-xl transition-colors duration-500 ease-in-out

                        w-full h-[180px] p-6">




                        <!-- ردیف بالا -->
                        <div class="flex items-start justify-between w-full">
                            <div
                                class="flex items-center justify-center group-hover:bg-white  bg-[#DD2424] rounded-full h-[60px] w-[60px] shadow-lg">



                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="block group-hover:hidden">
                                    <path
                                        d="M14.6203 10.6653C13.6227 9.67375 13.1238 9.17795 12.5051 9.17802C11.8864 9.17809 11.3876 9.674 10.3902 10.6658L10.1509 10.9038C9.15254 11.8965 8.65338 12.3929 8.03422 12.3926C7.41506 12.3924 6.91626 11.8957 5.91867 10.9023L2 7M22 18V12.4542M22 18H16.4179M22 18L17.5 13.5"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                </svg>






                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="hidden group-hover:block">
                                    <path
                                        d="M14.6203 10.6653C13.6227 9.67375 13.1238 9.17795 12.5051 9.17802C11.8864 9.17809 11.3876 9.674 10.3902 10.6658L10.1509 10.9038C9.15254 11.8965 8.65338 12.3929 8.03422 12.3926C7.41506 12.3924 6.91626 11.8957 5.91867 10.9023L2 7M22 18V12.4542M22 18H16.4179M22 18L17.5 13.5"
                                        stroke="#DD2424" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>




                            </div>

                            <!-- عنوان -->
                            <p class="text-start font-medium text-[18px] text-[#DD2424]
            group-hover:text-white" style="font-weight: 400;">
                                ضرر امروز
                            </p>
                        </div>

                        <!-- عدد + آپدیت -->
                        <div class="flex flex-col items-end text-end space-y-2">
                            <p class="text-[26px] font-extrabold text-[#DD2424]
            group-hover:text-white">
                                <?php echo e($todaylost); ?>

                            </p>

                            <div class="border-2 rounded-2xl px-3 py-2
            border-[#DD2424]
            group-hover:bg-white">
                                <p class="text-sm vazir text-[#DD2424]
                group-hover:text-[#DD2424]">
                                    آخرین به‌روزرسانی 10 دقیقه پیش
                                </p>
                            </div>
                        </div>
                    </div>







                    <!-- مجموع موجودی حساب‌ها -->


                    <div class="group flex flex-col justify-between border rounded-2xl
                        bg-[#125614] bg-opacity-20 hover:bg-opacity-100
                        shadow-md hover:shadow-xl transition-colors duration-500 ease-in-out

                        w-full h-[180px] p-6">




                        <!-- ردیف بالا -->
                        <div class="flex items-start justify-between w-full">
                            <div
                                class="flex items-center justify-center group-hover:bg-white  bg-[#125614] rounded-full h-[60px] w-[60px] shadow-lg">



                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="block group-hover:hidden">
                                    <path
                                        d="M3.17157 20.8284C4.34315 22 6.22876 22 10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14C22 12.8302 22 11.8419 21.965 11M20.8284 7.17157C19.6569 6 17.7712 6 14 6H10C6.22876 6 4.34315 6 3.17157 7.17157C2 8.34315 2 10.2288 2 14C2 15.1698 2 16.1581 2.03496 17"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M12 2C13.8856 2 14.8284 2 15.4142 2.58579C16 3.17157 16 4.11438 16 6M8.58579 2.58579C8 3.17157 8 4.11438 8 6"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M12 17.3333C13.1046 17.3333 14 16.5871 14 15.6667C14 14.7462 13.1046 14 12 14C10.8954 14 10 13.2538 10 12.3333C10 11.4129 10.8954 10.6667 12 10.6667M12 17.3333C10.8954 17.3333 10 16.5871 10 15.6667M12 17.3333V18M12 10V10.6667M12 10.6667C13.1046 10.6667 14 11.4129 14 12.3333"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" />

                                </svg>







                                <svg width="26" height="26" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="hidden group-hover:block">
                                    <path
                                        d="M3.17157 20.8284C4.34315 22 6.22876 22 10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14C22 12.8302 22 11.8419 21.965 11M20.8284 7.17157C19.6569 6 17.7712 6 14 6H10C6.22876 6 4.34315 6 3.17157 7.17157C2 8.34315 2 10.2288 2 14C2 15.1698 2 16.1581 2.03496 17"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M12 2C13.8856 2 14.8284 2 15.4142 2.58579C16 3.17157 16 4.11438 16 6M8.58579 2.58579C8 3.17157 8 4.11438 8 6"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M12 17.3333C13.1046 17.3333 14 16.5871 14 15.6667C14 14.7462 13.1046 14 12 14C10.8954 14 10 13.2538 10 12.3333C10 11.4129 10.8954 10.6667 12 10.6667M12 17.3333C10.8954 17.3333 10 16.5871 10 15.6667M12 17.3333V18M12 10V10.6667M12 10.6667C13.1046 10.6667 14 11.4129 14 12.3333"
                                        stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" />
                                </svg>




                            </div>

                            <!-- عنوان -->
                            <p class="text-start font-medium text-[18px] text-[#125614]
            group-hover:text-white" style="font-weight: 400;">
                                مجموعه موجودی
                                ها به دالر </p>
                        </div>

                        <!-- عدد + آپدیت -->
                        <div class="flex flex-col items-end text-end space-y-2">
                            <p class="text-[26px] font-extrabold text-[#125614]
            group-hover:text-white">
                                <?php $currentUser = Auth::guard('sarafi')->user(); ?>
                                <!--[if BLOCK]><![endif]--><?php if($currentUser && in_array($currentUser->role, ['superadmin', 'admin', 'cashier'])): ?>
                                <?php echo e(number_format($total_balance_usd, 2)); ?>

                                <?php else: ?>
                                0
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]--> </p>

                            <div class="border-2 rounded-2xl px-3 py-2
            border-[#125614]
            group-hover:bg-white">
                                <p class="text-sm vazir text-[#125614]
                group-hover:text-[#125614]">
                                    آخرین به‌روزرسانی 10 دقیقه پیش
                                </p>
                            </div>
                        </div>
                    </div>









                </div>


            </template>


            <?php
            $currentUser = Auth::guard('sarafi')->user();
            ?>

            <!--[if BLOCK]><![endif]--><?php if(
            $currentUser &&
            in_array($currentUser->role, ['superadmin', 'admin', 'cashier'])
            ): ?>
            <template x-if="activeTab === 'safes'">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 vazir">

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border bg-[#F5F5F5] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
                        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                        <div class="rounded-full dark:bg-white/100 bg-[#2563EB] p-6 flex items-center justify-center">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt=""
                                class="h-10 w-10 dark:hidden">
                            <i class="fa-solid fa-coins text-black text-2xl hidden  dark:block"></i>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-[16px] font-semibold dark:text-white text-gray-600"><?php echo e($label); ?></h1>
                            <p dir="ltr" class="  text-[25px] font-extrabold dark:text-white text-[#2563EB]">
                                <?php echo e(number_format($safe->$key ?? 0)); ?>

                            </p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                </div>
            </template>

            <template x-if="activeTab === 'account_safe'">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 vazir">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border bg-[#F5F5F5]  dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 dark:text-white rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
                        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                        <div class="rounded-full dark:bg-white bg-[#2563EB]  p-6 flex items-center justify-center">
                            <i class="fa-solid fa-credit-card dark:text-black text-white text-2xl"></i>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-[16px] font-semibold dark:text-white text-gray-600"><?php echo e($label); ?></h1>
                            <p dir="ltr" class="  text-[25px] font-extrabold dark:text-white text-[#2563EB]">
                                <?php echo e(number_format($safe_account[$key] ?? 0)); ?>

                            </p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </template>

            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 📈 گراف سود و زیان ماهانه
    const ctx1 = document.getElementById('monthlyProfitLossChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله'],
            datasets: [{
                    label: 'سود (دالر)',
                    data: [1200, 1500, 1100, 1800, 1700, 2000],
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2
                },
                {
                    label: 'زیان (دالر)',
                    data: [200, 300, 150, 250, 100, 400],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    const ctx2 = document.getElementById('transactionsByCurrencyChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['افغانی', 'دالر', 'یورو', 'ین چین'],
            datasets: [{
                label: 'تعداد تراکنش‌ها',
                data: [300, 120, 50, 40],
                backgroundColor: ['#3b82f6', '#22c55e', '#f59e0b', '#a855f7'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/dashboard.blade.php ENDPATH**/ ?>