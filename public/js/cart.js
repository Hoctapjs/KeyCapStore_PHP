function updateCartUI(data) {

    // Header
    $("#header-cart-total").text(
        new Intl.NumberFormat().format(data.total) + "đ"
    );
    $("#header-cart-count").text(
        "Your Cart (" + data.count + ")"
    );

    // Offcanvas badge
    $("#offcanvas-cart-count").text(data.count);

    // Rebuild offcanvas list
    let html = "";
    if (data.items.length === 0) {
        html += `<li class="list-group-item text-center py-4">Giỏ hàng đang trống</li>`;
    } else {
        data.items.forEach(item => {
            html += `
            <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                    <h6 class="my-0">${item.title}</h6>
                    ${item.variant ? `<small class="text-muted">${item.variant}</small>` : ""}
                    <small class="text-muted d-block">SL: ${item.quantity}</small>
                </div>
                <span>${new Intl.NumberFormat().format(item.total)}đ</span>
            </li>`;
        });

        html += `
    <li class="list-group-item d-flex justify-content-between">
        <span>Total (VND)</span>
        <strong>${new Intl.NumberFormat().format(data.total)}đ</strong>
    </li>`;
    }

    $("#offcanvas-cart-list").html(html);
}