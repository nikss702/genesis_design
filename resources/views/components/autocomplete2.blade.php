@push('scripts')
    <script type='text/javascript'>
        document.addEventListener('DOMContentLoaded', function () {
            M.Autocomplete.init(document.getElementById('{{$name}}2'), {
                data: JSON.parse('{!! json_encode($data) !!}')
            });
        });
    </script>
@endpush
<div class="row">
    <div class="input-field col s12">
        <input type="text" id="{{$name}}2" name="{{$name}}" class="autocomplete required" onchange="equipment2(this.value, {{$name}});">
        <label for="{{$name}}2" class="capitalize">{{$name}} <span class="red-text lead">*</span></label>
        <span class="helper-text red-text">Required</span>
    </div>
</div>
