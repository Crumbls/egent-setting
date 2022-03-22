<x-box>
    <h3 class="text-xl font-semibold mb-4">
        @lang('Automatic Responder')
    </h3>
    <div class="md:flex">
        @php($value = (bool)old('responder[enabled]', $entity ? $entity->enabled : false))
        <div class="w-full md:w-1/2">
            <x-inputs.checkbox-radio name="responder[enabled]"
                               value="0"
                                     :addHiddenValue="false"
                                     id="responder_disabled"
                               :checked="!$value"
                               :label="__('Off')"/>
        </div>
        <div class="w-full md:w-1/2">
            <x-inputs.checkbox-radio name="responder[enabled]"
                               value="1"
                                     :addHiddenValue="false"
                                     id="responder_enabled"
                               :checked="$value"
                               :label="__('On')"/>
        </div>
    </div>
    <div class="md:flex">
        <div class="w-full md:w-1/2">
            <div class="inline-flex items-center md:space-x-4">
                <label for="start_at" class="label font-medium text-gray-700">@lang('First day')</label>
                <input type="date"
                       name="responder[start_at]"
                       id="start_at"
                       value="{{ old('responder[start_at]', $entity ? $entity->start_at : null) }}"
                       class="block litepicker"
                       data-single-mode="true"
                />
            </div>

        </div>
        <div class="w-full md:w-1/2">
            <div class="inline-flex items-center md:space-x-4">
                <label for="end_at" class="label font-medium text-gray-700">@lang('Last day')</label>
                <input type="date"
                       name="responder[end_at]"
                       id="end_at"
                       class="block litepicker"
                       value="{{ old('responder[end_at]', $entity ? $entity->end_at : null) }}"
                       data-single-mode="true"
                />
            </div>
        </div>
    </div>

    <div class="py-4 block my-4">
        <x-inputs.text name="responder[subject]" :value="old('responder[subject]', $entity ? $entity->subject : null)" :label="__('Subject')" />
    </div>

    <div class="mb-2">
        <div class="relative">
            @if($entity)
                @trix($entity, 'body', [ 'hideButtonIcons' => ['attach']])
            @else
                @trix($model, 'body', [ 'hideButtonIcons' => ['attach']])
            @endif

        </div>
    </div>
</x-box>