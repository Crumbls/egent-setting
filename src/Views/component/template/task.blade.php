<settings-templates-tasks user="{{ $user->uuid }}"
                             :routes="{{ json_encode([
	'libraryIndex' => route('settings.templates.listing-task-libraries.index'),
	'taskCreate' => route('settings.templates.listing-tasks.create')
]) }}"
                             :libraries="{{
	\Egent\Setting\Models\ListingTaskLibrary::all()->map(function($e) { return ['value' => $e->getKey(),
	'label' => $e->name,
	'routes' => [
		'show' => route('settings.templates.listing-task-libraries.show', $e->getKey()),
		'update' => route('settings.templates.listing-task-libraries.update', $e->getKey())
		]
	]; })->toJson() }}">
</settings-templates-tasks>
