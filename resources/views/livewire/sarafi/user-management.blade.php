<div class="p-8 min-h-screen font-sans bg-white/15">

    {{-- عنوان --}}
    <h1 class="text-4xl font-bold text-gray-800 mb-8 pb-4 flex items-center gap-3">
        <i class="fas fa-users-cog text-blue-600"></i>
        {{ $editId ? __('messages.edit_user') : __('messages.add_user') }}
    </h1>

    <div class>
        <div class="grid 2xl:grid-cols-2 gap-8">

            {{-- فرم --}}
            <div class="bg-white shadow-md rounded-2xl p-8 border border-gray-200">
                @php
                    $currentUser = Auth::guard('sarafi')->user();
                @endphp

                <form wire:submit.prevent="save" class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $inputs = [
                                ['model' => 'name', 'label' => __('messages.name'), 'type' => 'text', 'icon' => 'fas fa-user'],
                                ['model' => 'lastname', 'label' => __('messages.lastname'), 'type' => 'text', 'icon' => 'fas fa-user'],
                                ['model' => 'sarafi_name', 'label' => __('messages.sarafi_name'), 'type' => 'text', 'icon' => 'fas fa-building'],
                                ['model' => 'address', 'label' => __('messages.address'), 'type' => 'text', 'icon' => 'fas fa-map-marker-alt'],
                                ['model' => 'phone', 'label' => __('messages.phone'), 'type' => 'text', 'icon' => 'fas fa-phone'],
                                ['model' => 'username', 'label' => __('messages.username'), 'type' => 'text', 'icon' => 'fas fa-user-circle'],
                                ['model' => 'password', 'label' => __('messages.password'), 'type' => 'password', 'icon' => 'fas fa-lock'],
                            ];
                        @endphp

                        @foreach ($inputs as $input)
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">{{ $input['label'] }}</label>
                                <div class="relative">
                                    <i class="{{ $input['icon'] }} absolute top-3 right-3 text-gray-400"></i>
                                    <input wire:model.defer="{{ $input['model'] }}" type="{{ $input['type'] }}"
                                        placeholder="{{ $input['label'] }}"
                                        class="w-full border border-gray-300 rounded-lg px-10 py-2 bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition @error($input['model']) border-red-400 ring-red-200 @enderror">
                                </div>
                                @error($input['model'])
                                    <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach

                        {{-- Role & User Limitation --}}
                        @if ($currentUser && $currentUser->role === 'superadmin')
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('messages.user_limit') }}</label>
                                <input wire:model.defer="user_limition" type="number"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition @error('user_limition') border-red-400 ring-red-200 @enderror">
                                @error('user_limition')
                                    <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('messages.role') }}</label>
                                <select wire:model.defer="role"
                                    class="w-full border rounded-lg px-4 py-2 bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition @error('role') border-red-400 ring-red-200 @enderror">
                                    <option value="">{{ __('messages.select_role') }}</option>
                                    <option value="admin">{{ __('messages.admin') }}</option>
                                </select>
                                @error('role')
                                    <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif($currentUser && $currentUser->role === 'admin')
                            <div class="flex flex-col">
                                <label class="text-sm font-medium text-gray-700 mb-1">{{ __('messages.role') }}</label>
                                <select wire:model.defer="role"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none transition @error('role') border-red-400 ring-red-200 @enderror">
                                    <option value="">{{ __('messages.select_role') }}</option>
                                    <option value="warehouse_manager">{{ __('messages.warehouse_manager') }}</option>
                                    <option value="internal_officer">{{ __('messages.internal_officer') }}</option>
                                    <option value="external_officer">{{ __('messages.external_officer') }}</option>
                                </select>
                                @error('role')
                                    <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                    </div>

                    {{-- دکمه‌ها --}}
                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="resetInputFields"
                            class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <i class="fas fa-save"></i> {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- جدول --}}
            <div class="bg-white shadow-md rounded-2xl p-6 border border-gray-200">

                {{-- سرچ و فیلتر --}}
                <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
                    <div class="flex gap-2">
                        <button wire:click="$toggle('filterOpen')"
                            class="px-3 py-2 border rounded-lg bg-gray-100 hover:bg-gray-200 transition flex items-center gap-2">
                            <i class="fas fa-filter text-gray-700"></i>
                            {{ __('messages.filter') }}
                        </button>
                        @if ($filterOpen)
                            <div
                                class="absolute mt-12 bg-white border rounded-xl shadow-lg p-4 w-72 z-50 flex flex-col gap-3">
                                <select wire:model="filterRole" class="border rounded px-3 py-2 bg-white w-full">
                                    <option value="">{{ __('messages.all_roles') }}</option>
                                    @foreach ($roles as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                                <select wire:model="filterSarafi" class="border rounded px-3 py-2 bg-white w-full">
                                    <option value="">{{ __('messages.all_sarafis') }}</option>
                                    @foreach ($this->sarafis as $sarafi)
                                        <option value="{{ $sarafi }}">{{ $sarafi }}</option>
                                    @endforeach
                                </select>

                                <button wire:click="applyFilter"
                                    class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full">
                                    {{ __('messages.apply_filter') }}
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="relative w-1/3 min-w-[220px]">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" wire:model.debounce.500ms="search" wire:keydown.enter="searchUser"
                            placeholder="{{ __('messages.search_placeholder') }}"
                            class="border border-gray-300 rounded-lg pl-10 pr-3 py-2 w-full focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
                    </div>
                </div>

                {{-- جدول دیتا --}}
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 text-gray-700 text-left">#</th>
                                <th class="px-3 py-2 text-gray-700 text-left">{{ __('messages.name') }}</th>
                                <th class="px-3 py-2 text-gray-700 text-left">{{ __('messages.lastname') }}</th>
                                <th class="px-3 py-2 text-gray-700 text-left">{{ __('messages.username') }}</th>
                                <th class="px-3 py-2 text-gray-700 text-left">{{ __('messages.role') }}</th>
                                <th class="px-3 py-2 text-gray-700 text-left">{{ __('messages.status') }}</th>
                                <th class="px-3 py-2 text-gray-700 text-left">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                                                     @foreach ($users as $index => $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-3 py-2">{{ $users->firstItem() + $index }}</td>
                                    <td class="px-3 py-2">{{ $user->name }}</td>
                                    <td class="px-3 py-2">{{ $user->lastname }}</td>
                                    <td class="px-3 py-2">{{ $user->username }}</td>
                                    <td class="px-3 py-2">{{ $roles[$user->role] ?? $user->role }}</td>
                                    <td class="px-3 py-2">
                                        @if ($user->status)
                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">{{ __('messages.active') }}</span>
                                        @else
                                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">{{ __('messages.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 flex gap-2">
                                        <button wire:click="edit({{ $user->id }})" class="p-2 rounded hover:bg-blue-100 text-blue-600">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $user->id }})" class="p-2 rounded hover:bg-red-100 text-red-600">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- صفحه بندی --}}
                <div class="mt-5 flex justify-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>

        {{-- هشدار --}}
        @if ($alert)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-3xl shadow-2xl w-96 text-center animate-fadeIn z-50">
                    <h3 class="text-xl font-bold mb-3 text-gray-800">{{ $alert['title'] }}</h3>
                    <p class="text-gray-600 mb-4">{{ $alert['message'] }}</p>
                    <button wire:click="$set('alert', null)" class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                        {{ __('messages.ok') }}
                    </button>
                </div>
            </div>
        @endif

        {{-- تایید حذف --}}
        @if ($confirmDeleteId)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-3xl shadow-2xl w-96 text-center animate-fadeIn z-50">
                    <h3 class="text-xl font-bold mb-4 text-red-600">{{ __('messages.confirm_delete_title') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('messages.confirm_delete_message') }}</p>
                    <div class="flex justify-center gap-4">
                        <button wire:click="$set('confirmDeleteId', null)" class="px-5 py-2 bg-gray-300 rounded-xl hover:bg-gray-400 transition">{{ __('messages.no') }}</button>
                        <button wire:click="delete" class="px-5 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                            <i class="fas fa-trash-alt"></i> {{ __('messages.yes') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    @endpush

</div>
