<style>
    /* Responsive table for mobile */
    @media (max-width: 767.98px) {
        .cart-table-responsive {
            display: block;
        }
        
        .cart-table-responsive thead {
            display: none;
        }
        
        .cart-table-responsive tbody,
        .cart-table-responsive tr,
        .cart-table-responsive td {
            display: block;
            width: 100%;
        }
        
        .cart-table-responsive tr {
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            background: #fff;
        }
        
        .cart-table-responsive td {
            border: none;
            padding: 0.5rem 0;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .cart-table-responsive td::before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .cart-table-responsive td:last-child {
            justify-content: flex-end;
        }
        
        .cart-table-responsive td:last-child::before {
            display: none;
        }
        
        .cart-table-responsive .qty-input {
            width: 70px !important;
        }
        
        .cart-buttons-mobile {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .cart-buttons-mobile .btn {
            width: 100%;
        }
    }
</style>

<table class="table table-bordered align-middle cart-table-responsive">
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
            <td data-label="Sản phẩm">
                <div>
                    <strong>{{ $item->product->title }}</strong>
                    @if($item->variant)
                    <br>
                    <small>Biến thể: {{ $item->variant->name }}</small>
                    @endif
                </div>
            </td>

            <td data-label="Giá">{{ number_format($item->price_snapshot, 0, ',', '.') }}₫</td>

            <td data-label="Số lượng">
                <form class="update-cart-form" data-id="{{ $item->id }}">
                    @csrf
                    <input
                        type="number"
                        name="quantity"
                        class="form-control qty-input"
                        value="{{ $item->quantity }}"
                        min="1"
                        style="width:80px; display:inline-block;">
                </form>
            </td>

            <td data-label="Tổng" class="line-total"
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
    <div class="cart-buttons-mobile mt-3">
        <a href="{{ route('products.index') }}" class="btn btn-success btn-lg">Tiếp tục mua sắm</a>
        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
            Tiến hành thanh toán
        </a>
    </div>
</div>