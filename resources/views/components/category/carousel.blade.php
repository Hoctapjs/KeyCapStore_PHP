@props(['items' => []])

<div class="category-carousel swiper">
    <div class="swiper-wrapper">
        @foreach($items as $c)
        <a href="{{ $c['href'] ?? '#' }}" class="nav-link category-item swiper-slide">
            <img src="{{ $c['icon'] }}" alt="Category Thumbnail">
            <h3 class="category-title">{{ $c['title'] }}</h3>
        </a>
        @endforeach
    </div>
</div>