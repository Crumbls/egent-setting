<settings-templates-messages user="{{ $user->uuid }}"
                             :routes="{{ json_encode([
	'messagesIndex' => route('settings.templates.messages.index'),
	'messagesStore' => route('settings.templates.messages.store'),
	'messagesUpdate' => route('settings.templates.messages.store'),
	'messagesDelete' => route('settings.templates.messages.store'),
]) }}"
                             :libraries="{{ $user->settingMessageLibraries->map(function($e) { return ['value' => $e->getKey(), 'label' => $e->name]; })->toJson() }}">
</settings-templates-messages>