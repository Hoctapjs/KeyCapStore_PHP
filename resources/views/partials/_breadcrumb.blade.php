@props(['items' => []]) {{-- items: [['label'=>'Trang chủ','url'=>'/'], ['label'=>'Sản phẩm']] --}}
<nav aria-label="breadcrumb" class="py-2">
    <ol class="breadcrumb mb-0">
        @foreach($items as $i => $item)
        @if(!empty($item['url']) && $i + 1 < count($items))
            <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
            @else
            <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
            @endif
            @endforeach
    </ol>
</nav>

<!-- trang nào dùng thì
@section('breadcrumb')
  @include('partials._breadcrumb', [
    'items' => [
      ['label'=>'Trang chủ', 'url'=>route('home')],
      ['label'=>'Sản phẩm']
    ]
  ])
@endsection -->