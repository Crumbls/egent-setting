<x-app-layout>
    <form method="post" action="{{ route('settings.messages.store') }}" novalidate>
        @csrf
        <div class="container mx-auto">
            <div class="md:flex mb-4 pb-4">
                <div class="w-full md:w-3/4 md:flex md:items-center">
                    <div class="text-left">
                        <h1 class="uppercase text-xl font-extrabold mb-2">@lang('Messaging Settings')</h1>
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
<div class="container mx-auto">
        <div class="md:flex md:space-x-6">
            <div class="w-full md:w-1/2">
                <x-setting-message-signature :user="$user" />
            </div>
            <div class="w-full md:w-1/2">
                <x-setting-message-responder :user="$user" />
            </div>
        </div>
</div>
    </form>
</x-app-layout>