<div class="form-group">
    <label for="{{ $name }}">
        @if ($label)
            {{ $label }}
        @else
            {{ Str::ucfirst(Str::replace('_', ' ', $name)) }}
        @endif

    </label>

    <select @if ($required) required @endif class="select2  form-control {{ $class }}"
        id="{{ $id ?? $name }}" {!! $attrs !!}
        @if ($multiple) multiple
            name="{{ $name }}[]"
        @else
            name="{{ $name }}" @endif>
        @if (!$multiple)
            <option selected disabled>Select {{ $label ?? Str::ucfirst(Str::replace('_', ' ', $name)) }}</option>
        @endif

        @forelse ($options as $option)
            <option
                @if (!empty($optionValue)) value="{{ $option->$optionValue }}"
            @else
            value="{{ $option->id ?? $option }}" @endif>
                {{ Str::ucfirst($option->name ?? ($option->title ?? $option)) }}
                @forelse ($additionalOptionText as $add)
                    {{ Str::ucfirst($option->$add ?? $add) }}
                @empty
                @endforelse

            </option>
        @empty
        @endforelse

    </select>
    <div class="invalid-tooltip">Please provide a valid {{ Str::ucfirst(Str::replace('_', ' ', $name)) }}</div>
</div>

@pushonce('component-script')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpushonce
