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
        @can('setting-goal-monthly')
        <x-setting.goals-monthly :user="$user" />
        @endcan
        @can('setting-notification')
        <x-setting.notifications :user="$user" />
        @endcan
        @can('setting-signature')
        <x-setting.signature :user="$user" />
        @endcan
        @can('setting-ctm')
        <x-setting.ctm :user="$user" />
        @endcan
        @can('setting-messaging')
        <x-setting.messaging :user="$user" />
        @endcan
        @can('setting-template')
        <x-setting.templates :user="$user" />
        @endcan
        @can('setting-calendar')
        <x-setting.ecalendar :user="$user" />
        @endcan
        @can('setting-transaction-coordinator')
        <x-setting.transaction-coordinator :user="$user" />
        @endcan

    </form>

</x-app-layout>