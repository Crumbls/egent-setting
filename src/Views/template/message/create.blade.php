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
    <div class="bg-white text-black shadow p-4 mb-4">
        <div class="container mx-auto">
            <div class="md:flex mb-4 pb-4">
                <div class="w-full md:w-3/4 md:flex md:items-center">
                    <div class="text-left">
                        <h3 class="text-xl font-extrabold mb-2">Create a Library</h3>
                    </div>
                </div>
                <div class="w-full md:w-1/4 md:justify-end md:flex md:items-center">
                    <div class="text-center md:text-right">
                    </div>
                </div>
            </div>
            <form method="post" action="{{ route('settings.templates.message-libraries.store') }}" autocomplete="off">
                @csrf

            <div class="flex justify-start">
                <div class="mb-3 xl:w-96">
                    <label for="name" class="form-label inline-block mb-2 text-gray-700">
                        @lang('Library Name')
                    </label>
                    <input
                            required
                            type="text"
                            class="
        form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
        bg-white bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none
      "
                            value="{{ old('name') }}"
                            id="name"
                            name="name"
                            placeholder="@lang('Library Name')"
                    />
                </div>
            </div>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 bg-yellow-400 shadow text-lg">
                Create
            </button>
            </form>
        </div>
    </div>

</x-app-layout>