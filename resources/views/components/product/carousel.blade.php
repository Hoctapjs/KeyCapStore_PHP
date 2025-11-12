@props(['products' => [], 'prevId', 'nextId', 'buttonClass' => 'btn btn-primary'])

<div class="products-carousel swiper">
    <div class="swiper-wrapper">
        @foreach($products as $p)
        <div class="swiper-slide">
            <x-product.card
                :title="$p['title']"
                :image="$p['image']"
                :price="$p['price']"
                :qty="$p['qty'] ?? '1 Unit'"
                :rating="$p['rating'] ?? 4.5"
                :badge="$p['badge'] ?? null"
                :href="$p['href'] ?? '#'" />
        </div>
        @endforeach
    </div>
</div>

<div class="swiper-buttons">
    <button class="swiper-prev {{ $buttonClass }} {{ $prevId }}">❮</button>
    <button class="swiper-next {{ $buttonClass }} {{ $nextId }}">❯</button>
</div>

<!-- dùng trong view:
<x-section.header title="Best selling products" viewAllLabel="View All Categories →"
  :prev="'products-carousel-prev'" :next="'products-carousel-next'"/>

<x-product.carousel
  :products="$bestSelling" 
  prevId="products-carousel-prev"
  nextId="products-carousel-next"
/> -->