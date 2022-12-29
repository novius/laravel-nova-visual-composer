@php
    if (empty($content) || !is_array($content)) {
        return;
    }
    $title = array_shift($content);
@endphp
@if (!empty($title))
    <div class="vc-block vc-block-title">
        <h3 class="title">
            {{ $title }}
        </h3>
    </div>
@endif
