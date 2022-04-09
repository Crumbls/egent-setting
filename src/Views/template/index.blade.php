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

    <x-setting-template-deadlines :user="$user" />
    <x-setting-template-deadline-explanations :user="$user" />
    <x-setting-template-clauses :user="$user" />
    <x-setting-template-contracts :user="$user" />
    <x-setting-template-tasks :user="$user" />
    @if(true)
        <settings-templates-messages user="{{ $user->uuid }}"
                                     :routes="{{ json_encode([
	'messagesIndex' => route('settings.templates.messages.index'),
	'messagesStore' => route('settings.templates.messages.store'),
	'messagesUpdate' => route('settings.templates.messages.store'),
	'messagesDelete' => route('settings.templates.messages.store'),
]) }}"
                                     :libraries="{{ $user->settingMessageLibraries->map(function($e) { return ['value' => $e->getKey(), 'label' => $e->name]; })->toJson() }}">
        </settings-templates-messages>
    @else
        <x-setting-template-messages :user="$user" />
    @endif

</x-app-layout>