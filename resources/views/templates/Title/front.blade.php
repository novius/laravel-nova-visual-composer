@php
    if (empty($content) || !is_array($content)) {
        return;
    }

    $title = $content['title'] ?? '';
@endphp
@if (!empty($title))
    <div class="block-title">
        <h3 class="title">
            {{ $title }}
        </h3>
    </div>
@endif
