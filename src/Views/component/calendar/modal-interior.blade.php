<form method="post" action="{{ route('settings.connect.store') }}" novalidate>
    @csrf
<div class="text-center">
    <h3 class="text-xl font-extrabold mb-2">@lang('Connect eCalendar')</h3>
    <p>@lang('Choose which provider you would like to connect your eCalendar with.')</p>
    <div class="md:flex space-x-4 space-y-4 justify-center">
        <?php
        $drivers = \egentCalendar::getInstalledDrivers();
		unset($drivers['Local']);
		?>
        @foreach($drivers as $provider => $name)
            <button type="submit" name="provider" value="{{ $provider }}" class="w-full md:w-1/2">
                <img src="{{ asset('img/logos/'.strtolower($name).'.png') }}" alt="{{ $name }} Logo" />
            </button>
        @endforeach
    </div>
</div>
</form>