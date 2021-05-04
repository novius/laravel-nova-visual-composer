@php
    if (empty($content) || !is_array($content)) {
        return;
    }

    $title = $content['title'] ?? '';
    $subtitle = $content['subtitle'] ?? '';
    $htmlContent = $content['content'] ?? '';
    $images = (array) $content['image'] ?? [];
    $image = array_shift($images);
    $imageAlt = $content['image_alt'] ?? '';
@endphp

<div class="block-article">
    @if (!empty($title))
        <h3 class="title">
            {{ $title }}
        </h3>
    @endif
    @if (!empty($subtitle))
        <h4 class="subtitle">
            {{ $subtitle }}
        </h4>
    @endif
    @if (!empty($htmlContent))
        <div class="content">
            {!! $htmlContent !!}
        </div>
    @endif
    @if (!empty($image))
        <div class="image">
            <img src="{{ asset('storage/'.$image) }}"
                 alt="{{ $imageAlt }}" />
        </div>
    @endif
</div>
