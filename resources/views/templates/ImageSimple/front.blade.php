@php
    if (empty($content) || !is_array($content)) {
        return;
    }
    $images = array_shift($content);
    $image = array_shift($images);
@endphp
<div class="block-image">
        <img src="{{ asset('storage/'.$image) }}" />
</div>
