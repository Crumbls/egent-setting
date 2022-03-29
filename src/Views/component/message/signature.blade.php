<x-box>
    <h3 class="text-xl font-semibold mb-4">
        @lang('Signature')
    </h3>
    <div class="md:flex">
        @php($value = (bool)old('signature[enabled]', $entity ? $entity->enabled : false))
        <div class="w-full md:w-1/2">
            <x-inputs.checkbox-radio name="signature[enabled]"
                                     value="0"
                                     :addHiddenValue="false"
                                     id="signature_disabled"
                                     :checked="!$value"
                                     :label="__('No signature')"/>
            <x-inputs.checkbox-radio name="signature[enabled]"
                                     value="1"
                                     :addHiddenValue="false"
                                     id="signature_enabled"
                                     :checked="$value"
                                     :label="__('Custom signature')"/>

        </div>
        <div class="w-full md:w-1/2">
            <x-inputs.checkbox-radio name="signature[attach_econtract]"
                                     value="1"
                                     type="checkbox"
                                     :addHiddenValue="true"
                                     id="signature_attach_econtract"
                                     :checked="$value"
                                     :label="__('Attach to eContract Message')"/>
        </div>
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