@php
    if (empty($content) || !is_array($content)) {
            return;
    }

    list(
        $title,
        $subtitle,
        $htmlContent,
    ) = $content;
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
</div>
