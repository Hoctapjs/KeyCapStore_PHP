@props(['image','date','category','title','excerpt','href' => '#'])

<article class="post-item card border-0 shadow-sm p-3">
    <div class="image-holder zoom-effect">
        <a href="{{ $href }}"><img src="{{ $image }}" class="card-img-top" alt="post"></a>
    </div>
    <div class="card-body">
        <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
            <div class="meta-date"><svg width="16" height="16">
                    <use xlink:href="#calendar" />
                </svg>{{ $date }}</div>
            <div class="meta-categories"><svg width="16" height="16">
                    <use xlink:href="#category" />
                </svg>{{ $category }}</div>
        </div>
        <div class="post-header">
            <h3 class="post-title"><a href="{{ $href }}" class="text-decoration-none">{{ $title }}</a></h3>
            <p>{{ $excerpt }}</p>
        </div>
    </div>
</article>