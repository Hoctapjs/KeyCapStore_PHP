@props(['class' => '', 'bg' => '', 'title', 'subtitle' => null, 'percent' => null, 'href' => '#'])

<div class="banner-ad {{ $class }}" style="background: url('{{ $bg }}') no-repeat right bottom;">
    <div class="row banner-content p-5">
        <div class="content-wrapper col-md-7">
            @if($percent)<div class="categories sale mb-3 pb-3">{{ $percent }}</div>@endif
            <h3 class="banner-title">{{ $title }}</h3>
            @if($subtitle)<p>{{ $subtitle }}</p>@endif
            <a href="{{ $href }}" class="d-flex align-items-center nav-link">Shop Collection
                <svg width="24" height="24">
                    <use xlink:href="#arrow-right" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- dùng trong view:
 <x-banner.ad class="bg-success-subtle block-2"
  :bg="asset('images/ad-image-1.png')" title="Fruits & Vegetables" percent="20% off"/> -->