<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Tổng</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        @foreach($cart->items as $item)
        <tr>
            <td>
                <strong>{{ $item->product->title }}</strong>
                @if($item->variant)
                <br>
                <small>Biến thể: {{ $item->variant->name }}</small>
                @endif
            </td>

            <td>{{ number_format($item->price_snapshot, 0, ',', '.') }}₫</td>

            <td>
                <form class="update-cart-form" data-id="{{ $item->id }}">
                    @csrf
                    <input
                        type="number"
                        name="quantity"
                        class="form-control qty-input"
                        value="{{ $item->quantity }}"
                        min="1"
                        style="width:80px; display:inline-block;">
                    <!-- <button class="btn btn-sm btn-success">Cập nhật</button> đây mình đã bỏ dòng này-->
                </form>
            </td>

            {{-- thêm class + data-price + data-total --}}
            <td class="line-total"
                data-price="{{ $item->price_snapshot }}"
                data-total="{{ $item->price_snapshot * $item->quantity }}">
                {{ number_format($item->price_snapshot * $item->quantity, 0, ',', '.') }}₫
            </td>

            <td>
                <form
                    class="remove-cart-form"
                    data-id="{{ $item->id }}"
                    data-url="{{ route('cart.remove', $item->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="text-end mt-4">
    <h4>
        Tạm tính:
        <span id="cart-subtotal" data-subtotal="{{ $subtotal }}">
            {{ number_format($subtotal, 0, ',', '.') }}₫
        </span>
    </h4>
    <a href="{{ route('products.index') }}" class="btn btn-success btn-lg mt-3">Tiếp tục mua sắm</a>

    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg mt-3">
        Tiến hành thanh toán
    </a>
</div>