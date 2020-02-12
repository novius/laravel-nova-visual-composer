@php
    if (empty($content) || !is_array($content)) {
        return;
    }

    list(
        $image,
        $altText,
    ) = $content;
@endphp

<div class="vc-block vc-block-image">
    <img src="{{ asset('storage/'.$image[0]) }}" alt="{{ $altText ?? '' }}" />
</div>
