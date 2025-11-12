@props(['title','viewAllLabel' => null,'viewAllHref' => '#','prev'=>null,'next'=>null,'btnClass'=>'btn btn-yellow'])

<div class="section-header d-flex flex-wrap justify-content-between mb-5">
    <h2 class="section-title">{{ $title }}</h2>
    <div class="d-flex align-items-center">
        @if($viewAllLabel)
        <a href="{{ $viewAllHref }}" class="btn-link text-decoration-none">{{ $viewAllLabel }} →</a>
        @endif
        @if($prev && $next)
        <div class="swiper-buttons">
            <button class="swiper-prev {{ $btnClass }} {{ $prev }}">❮</button>
            <button class="swiper-next {{ $btnClass }} {{ $next }}">❯</button>
        </div>
        @endif
    </div>
</div>