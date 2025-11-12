<!-- giới thiệu về cách dùng các component đã tạo, nhưng chưa có dữ liệu các biến như $categories, $bestSelling, v.v. -->

<!-- @extends('layouts.app')

@section('content')
<x-section.header title="Category" viewAllLabel="View All Categories →" :prev="'category-carousel-prev'" :next="'category-carousel-next'" btnClass="btn btn-yellow" />
<x-category.carousel :items="$categories" />

<x-section.header title="Best selling products" :prev="'products-carousel-prev'" :next="'products-carousel-next'" />
<x-product.carousel :products="$bestSelling" prevId="products-carousel-prev" nextId="products-carousel-next" />

<x-section.header title="Most popular products" :prev="'products-carousel-prev-2'" :next="'products-carousel-next-2'" />
<x-product.carousel :products="$popular" prevId="products-carousel-prev-2" nextId="products-carousel-next-2" />

<x-section.header title="Just arrived" :prev="'products-carousel-prev-3'" :next="'products-carousel-next-3'" />
<x-product.carousel :products="$justArrived" prevId="products-carousel-prev-3" nextId="products-carousel-next-3" />

<div class="row">
    @foreach($posts as $p)
    <div class="col-md-4">
        <x-blog.card :image="$p['image']" :date="$p['date']" :category="$p['category']" :title="$p['title']" :excerpt="$p['excerpt']" :href="$p['href']" />
    </div>
    @endforeach
</div>
@endsection -->