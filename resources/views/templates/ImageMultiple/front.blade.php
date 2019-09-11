@php
    if (empty($content) || !is_array($content)) {
        return;
    }
    $images = array_shift($content);
@endphp
<div class="block-images">
    @foreach($images as $image)
        <img src="{{ asset('storage/'.$image) }}" />
    @endforeach
</div>
