<form method="post" action="{{ route('settings.transaction-coordinator.store') }}">
@csrf

    <label for="transactionCoordinator" class="block text-center uppercase text-xl font-extrabold mb-4">Connect with Transaction Coordinator</label>
        <div class="mb-2">
			<?php
            $value = old('transactionCoordinator');
			if (!$value || !\Str::isUuid($value)) {
				$value = $user->transactionCoordinators->first();
				if ($value) {
					$value = $value->uuid;
                }
            }
			?>
            <select
                    class="form-control user-choice search"
                    name="transactionCoordinator"
                    id="transactionCoordinator"
                    data-allow-html="false"
                    data-should-sort="true"
                    data-search-floor="3"
                    data-search-choices="false"
                    data-duplicate-items="false"
                    data-value="uuid"
                    data-label="name"
                    data-search-placeholder-value="@lang('Search for a Transaction Coordinator')"
            >
                @foreach(\App\Models\User::whereIs('transaction-coordinator')->orderBy('name_first','asc')->get() as $option)
                    <option value="{{ $option->uuid }}"{{ $value && $value == $option->uuid ? ' selected' : '' }}>{{ $option->name }} &lt;{{ $option->email }}&gt;</option>
                @endforeach
            </select>
        </div>

    <div class="text-center pt-4">
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-400 border border-transparent rounded-md font-semibold text-md text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
        @lang('Connect')
    </button>
    </div>
</form>