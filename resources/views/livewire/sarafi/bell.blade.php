<div class="relative" wire:click.outside="$set('open', false)">

    {{-- دکمه زنگ --}}
    <button
        wire:click="toggle"
        type="button"
        class="relative rounded-full bg-[#DEE8FC] border border-[#2563EB] p-2 hover:bg-[#cdd9f9] transition">

        <img src="{{ asset('assets/sarafi/all_icon/bill-header.svg') }}" class="w-6 h-6">

        @if($count)
            <span
                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center animate-pulse">
                {{ $count }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    @if($open)
        <div class="absolute left-0 mt-2 w-80 bg-white shadow-xl rounded-xl z-50 overflow-hidden border border-gray-200">

            <div class="flex justify-between items-center px-4 py-2 border-b bg-gray-50">
                <span class="text-sm font-semibold text-gray-700">نوتیفیکیشن‌ها</span>
                <button wire:click="refreshData" class="text-xs text-blue-600 hover:underline">بروزرسانی</button>
            </div>

            <div class="max-h-96 overflow-y-auto">
                @forelse($notifications as $n)
                    <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition {{ $n->is_read ? 'opacity-70' : 'bg-blue-50' }}">

                        {{-- آیکن نوع نوتیفیکیشن --}}
                        <span class="flex-shrink-0 w-3 h-3 mt-1 rounded-full
                            {{ $n->type === 'receive' ? 'bg-green-500' : 'bg-red-500' }}">
                        </span>

                        {{-- متن و ساعت --}}
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $n->title }}</p>
                            <p class="text-xs text-gray-600 truncate">{{ $n->message }}</p>
                        </div>

                        {{-- ساعت --}}
                        <span class="text-xs text-gray-400 flex-shrink-0 mt-1">
                            {{ \Carbon\Carbon::parse($n->created_at)->format('H:i') }}
                        </span>

                        {{-- دکمه خواندن برای خوانده نشده‌ها --}}
                        @if(!$n->is_read)
                            <button wire:click="markAsRead({{ $n->id }})"
                                    class="text-xs text-blue-600 hover:underline ml-2">
                                خواندن
                            </button>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">
                        نوتیفیکیشنی وجود ندارد
                    </p>
                @endforelse
            </div>

            <div class="px-4 py-2 border-t text-center bg-gray-50 text-xs text-gray-500">
                همه نوتیفیکیشن‌ها
            </div>
        </div>
    @endif
</div>
