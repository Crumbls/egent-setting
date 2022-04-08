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

    <x-setting-template-messages :user="$user" />

</x-app-layout>