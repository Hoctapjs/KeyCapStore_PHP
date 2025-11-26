@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h2 class="mb-4">Giỏ hàng của bạn</h2>

    @if($cart->items->count() == 0)
    <p class="text-muted">Giỏ hàng đang trống.</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
    @else
    <div id="cart-table-wrapper">
        <div id="cart-table-inner">
            @include('cart._table', ['cart' => $cart, 'subtotal' => $subtotal])
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function formatCurrency(amount) {
        return amount.toLocaleString('vi-VN') + '₫';
    }

    function recalcSubtotal() {
        let sum = 0;
        $(".line-total").each(function() {
            const total = parseInt($(this).data("total"), 10);
            if (!isNaN(total)) sum += total;
        });

        $("#cart-subtotal")
            .data("subtotal", sum)
            .text(formatCurrency(sum));
    }

    // UPDATE QUANTITY TỰ ĐỘNG KHI ĐỔI INPUT
    $(document).on("change", ".qty-input", function() {
        const input = $(this);
        const form = input.closest("form");
        const id = form.data("id");

        let qty = parseInt(input.val(), 10);
        if (isNaN(qty) || qty < 1) {
            qty = 1;
            input.val(qty);
        }

        $.post(`/cart/update/${id}`, form.serialize(), function() {
            // cập nhật header + offcanvas
            updateCartUI();

            // cập nhật lại dòng hiện tại
            const row = form.closest("tr");
            const lineCell = row.find(".line-total");
            const price = parseInt(lineCell.data("price"), 10);
            const newTotal = price * qty;

            lineCell
                .data("total", newTotal)
                .text(formatCurrency(newTotal));

            // tính lại tạm tính
            recalcSubtotal();
        }).fail(function(xhr) {
            console.error("Update cart error:", xhr.responseText);
            alert("Cập nhật số lượng thất bại.");
        });
    });

    // Không cần handler submit .update-cart-form nữa
    // $(document).on("submit", ".update-cart-form", ...);

    // REMOVE ITEM – chỉ 1 request POST
    $(document).on("submit", ".remove-cart-form", function(e) {
        e.preventDefault();

        const form = $(this);
        const url = form.data("url"); // lấy từ data-url

        $.ajax({
            url: url,
            type: "POST", // trùng với Route::post
            data: form.serialize(), // chỉ có _token
            success: function() {
                updateCartUI();
                reloadCartTable();
            },
            error: function(xhr) {
                console.error("Remove cart error:", xhr.responseText);
                alert("Xóa sản phẩm thất bại.");
            }
        });
    });

    function reloadCartTable() {
        $("#cart-table-inner").load("{{ url('/cart/ajax/table') }}?ts=" + Date.now());
    }
</script>
@endpush