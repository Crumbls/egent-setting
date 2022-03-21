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

    <div class="container mx-auto max-w-lg">
        <div class="bg-white text-black shadow p-4 mb-4">
            @include('setting::tc-modal')
        </div>
    </div>

</x-app-layout>