<?php include 'app/views/shares/header.php'; ?>

<link rel="stylesheet" href="/WebBanHang_CNPM/public/css/cart.css">

<div class="cart-container">
    <h1 class="cart-title">Giỏ hàng của bạn</h1>

    <?php if (!empty($cart)): ?>
        <form method="POST" action="/WebBanHang_CNPM/Product/updateCart" id="cartForm">
            <div class="cart-layout">
                
                <div class="cart-main">
                    <div class="table-responsive cart-card">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th class="col-product">Sản phẩm</th>
                                    <th class="col-price text-center">Đơn giá</th>
                                    <th class="col-quantity text-center">Số lượng</th>
                                    <th class="col-subtotal text-end">Số tiền</th>
                                    <th class="col-action text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_cart = 0;
                                foreach ($cart as $id => $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total_cart += $subtotal; 
                                ?>
                                    <tr class="js-row" data-id="<?php echo $id; ?>">
                                        <td>
                                            <div class="product-info">
                                                <?php if (!empty($item['image'])): ?>
                                                    <img src="/WebBanHang_CNPM/<?php echo $item['image']; ?>" alt="Product Image" class="product-img">
                                                <?php else: ?>
                                                    <div class="product-img-placeholder">No Image</div>
                                                <?php endif; ?>
                                                <div class="product-detail">
                                                    <h6 class="product-name"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center price-text js-price" data-price="<?php echo $item['price']; ?>">
                                            <?php echo number_format($item['price'], 0, ',', '.'); ?>đ
                                        </td>
                                        <td class="text-center">
                                            <input type="number" 
                                                   name="quantity[<?php echo $id; ?>]" 
                                                   value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                   min="1" 
                                                   class="quantity-input js-quantity">
                                        </td>
                                        <td class="text-end subtotal-text js-subtotal">
                                            <?php echo number_format($subtotal, 0, ',', '.'); ?>đ
                                        </td>
                                        <td class="text-center">
                                            <a href="/WebBanHang_CNPM/Product/removeFromCart/<?php echo $id; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="cart-actions">
                        <a href="/WebBanHang_CNPM/Product" class="btn-continue">Tiếp tục mua sắm</a>
                    </div>
                </div>

                <div class="cart-sidebar">
                    <div class="summary-card">
                        <h5 class="summary-title">Tổng số tiền</h5>
                        <div class="summary-row">
                            <span>Tổng tiền hàng:</span>
                            <span class="total-price js-total-cart"><?php echo number_format($total_cart, 0, ',', '.'); ?>đ</span>
                        </div>
                        <p class="summary-note">* Chi phí vận chuyển và mã giảm giá sẽ được áp dụng tại trang thanh toán tiếp theo.</p>
                        <a href="/WebBanHang_CNPM/Product/checkout" class="btn-checkout">Mua Hàng (Thanh Toán)</a>
                    </div>
                </div>

            </div>
        </form>
    <?php else: ?>
        <div class="empty-cart-card">
            <div class="empty-cart-icon">🛒</div>
            <h3 class="empty-cart-text">Giỏ hàng của bạn đang trống</h3>
            <p class="empty-cart-subtext">Hãy quay lại cửa hàng để chọn cho mình những sản phẩm ưng ý nhé!</p>
            <a href="/WebBanHang_CNPM/Product" class="btn-checkout inline-btn">Mua sắm ngay</a>
        </div>
    <?php endif; ?>
</div>

<script src="/WebBanHang_CNPM/public/js/cart.js"></script>

<?php include 'app/views/shares/footer.php'; ?>