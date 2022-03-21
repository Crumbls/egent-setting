<x-app-layout>
    <form method="post" action="{{ route('settings.sync') }}">
        @csrf
        <div class="container mx-auto">
            <div class="md:flex mb-4 pb-4">
                <div class="w-full md:w-3/4 md:flex md:items-center">
                    <div class="text-left">
                        <h1 class="uppercase text-xl font-extrabold mb-2">@lang('Settings')</h1>
                    </div>
                </div>
                <div class="w-full md:w-1/4 md:justify-end md:flex md:items-center">
                    <div class="text-center md:text-right">
                        @include('icons.save')
                    </div>
                </div>
            </div>
        </div>

        @action('content', 'settings', $user)

        <x-setting.notifications :user="$user" />

    </form>
</x-app-layout>