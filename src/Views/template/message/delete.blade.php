<x-app-layout>
    <div class="container mx-auto">
        <div class="md:flex border-b mb-4 pb-4">
            <div class="w-full md:w-3/4 md:flex md:items-center">
                <div class="text-left">
                    <a href="{{ route('settings.index') }}" class="uppercase text-xl font-extrabold mb-2">@lang('Settings')</a>
                </div>
            </div>
            <div class="w-full md:w-1/4 md:justify-end md:flex md:items-center">
                <div class="text-center md:text-right">
                </div>
            </div>
        </div>
    </div>

    @action('content', 'settings', $user)
    <div class="bg-white text-black shadow p-4 mb-4">
        <div class="container mx-auto">
            <div class="md:flex mb-4 pb-4">
                <div class="w-full md:w-3/4 md:flex md:items-center">
                    <div class="text-left">
                        <h3 class="text-xl font-extrabold mb-2">Delete a Message</h3>
                    </div>
                </div>
                <div class="w-full md:w-1/4 md:justify-end md:flex md:items-center">
                    <div class="text-center md:text-right">
                    </div>
                </div>
            </div>
            <form method="post" action="{{ route('settings.templates.messages.destroy', $message) }}" autocomplete="off">
                @csrf
                @method('DELETE')
                <input type="hidden" name="redirectTo" value="{{ $redirectTo }}" />
                <p class="mb-4">Are you sure you wish to delete this message?</p>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 shadow text-lg">
                    @lang('Delete')
                </button>
            </form>
        </div>
    </div>

</x-app-layout>