# Hướng dẫn cập nhật Cascade Delete cho Product

## Đã thực hiện:

### 1. Tạo migration mới
File: `database/migrations/2025_12_09_000001_update_product_foreign_keys_cascade.php`

Migration này sẽ:
- Cập nhật `cart_items.product_id` → CASCADE DELETE (xóa giỏ hàng khi xóa sản phẩm)
- Cập nhật `order_items.product_id` → SET NULL (giữ lịch sử đơn hàng, chỉ set product_id = null)

### 2. Thêm boot() method vào Product model
- Tự động xóa file ảnh vật lý khi xóa sản phẩm

## Chạy migration:

```bash
php artisan migrate
```

## Tổng quan Cascade Delete:

Khi xóa 1 sản phẩm, các dữ liệu sau sẽ bị XÓA CÙNG (cascade):
✅ `product_categories` - Quan hệ sản phẩm-danh mục
✅ `product_images` - Hình ảnh sản phẩm
✅ `product_variants` - Biến thể sản phẩm
✅ `product_tag_pivot` - Quan hệ sản phẩm-tag
✅ `reviews` - Đánh giá sản phẩm
✅ `wishlists` - Danh sách yêu thích
✅ `cart_items` - Sản phẩm trong giỏ hàng (SAU KHI CHẠY MIGRATION)
✅ File ảnh vật lý trong thư mục public

Các dữ liệu sau sẽ ĐƯỢC GIỮ LẠI (để bảo toàn lịch sử):
⚠️ `order_items` - Set product_id = NULL (giữ lại tên, giá snapshot)
⚠️ `inventory_movements` - Set product_id = NULL

## Lưu ý quan trọng:

**KHÔNG NÊN** xóa sản phẩm nếu:
- Đã có đơn hàng chứa sản phẩm đó
- Cần xem lại lịch sử bán hàng

**NÊN** sử dụng:
- Đổi status thành 'archived' thay vì xóa
- Hoặc ẩn sản phẩm khỏi giao diện người dùng

## Test thử:
1. Tạo 1 sản phẩm test
2. Thêm vào giỏ hàng
3. Tạo đơn hàng
4. Xóa sản phẩm
5. Kiểm tra:
   - Cart items → Đã bị xóa
   - Order items → Vẫn còn nhưng product_id = NULL
   - Product images → Đã bị xóa
   - File ảnh → Đã bị xóa
