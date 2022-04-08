<div class="bg-white text-black shadow p-4 mb-4">
    <div class="container mx-auto">
        <div class="md:flex mb-4 pb-4">
            <div class="w-full md:w-3/4 md:flex md:items-center">
                <div class="text-left">
                    <h3 class="text-xl font-extrabold mb-2">@lang('Messages')</h3>
                </div>
            </div>
            <div class="w-full md:w-1/4 md:justify-end md:flex md:items-center">
                <div class="text-center md:text-right">
                    <a href="{{ route('settings.templates.message-libraries.create') }}" class="text-black inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest transition ease-in-out duration-150">
                        @lang('Create Library')
                            <span class="text-center rounded-full inline-flex items-center ml-4 px-2 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 bg-yellow-400 shadow text-lg">
                                <i class="mdi mdi-content-save cus-save-icon"></i>
                            </span>
                    </a>
                </div>
            </div>
        </div>
        <select>
            @foreach($user->settingMessageLibraries as $entity)
                <option>{{ $entity->name }}</option>
            @endforeach
        </select>
    </div>
</div>