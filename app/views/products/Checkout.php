<?php include 'app/views/shares/header.php'; ?>

<link rel="stylesheet" href="/WebBanHang_CNPM/public/css/cart.css">
<link rel="stylesheet" href="/WebBanHang_CNPM/public/css/checkout.css">

<div class="cart-container">
    <h1 class="cart-title">Thông tin thanh toán</h1>

    <?php if (!empty($cart)): ?>
        <form method="POST" action="/WebBanHang_CNPM/Product/processCheckout">
            <div class="cart-layout">
                
                <div class="cart-main">
                    <div class="cart-card">
                        <h4 class="checkout-section-title">Địa chỉ nhận hàng</h4>
                        
                        <div class="form-group-cyber">
                            <label for="name">Họ tên:</label>
                            <input type="text" id="name" name="name" class="quantity-input form-control-cyber" required>
                        </div>
                        
                        <div class="form-group-cyber">
                            <label for="phone">Số điện thoại:</label>
                            <input type="text" id="phone" name="phone" class="quantity-input form-control-cyber" required>
                        </div>
                        
                        <div class="form-group-cyber">
                            <label for="address">Địa chỉ giao hàng:</label>
                            <textarea id="address" name="address" class="quantity-input form-control-cyber textarea-cyber" required></textarea>
                        </div>
                    </div>

                    <div class="cart-actions">
                        <a href="/WebBanHang_CNPM/Product/cart" class="btn-continue">← Quay lại giỏ hàng</a>
                    </div>
                </div>

                <div class="cart-sidebar">
                    <div class="summary-card sidebar-static">
                        <h5 class="summary-title">Đơn hàng của bạn</h5>
                        
                        <div class="summary-product-list">
                            <?php 
                            $total_cart = 0;
                            foreach ($cart as $id => $item): 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total_cart += $subtotal; 
                            ?>
                                <div class="summary-product-item">
                                    <div class="product-item-left">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="/WebBanHang_CNPM/<?php echo $item['image']; ?>" alt="Product" class="product-item-img">
                                        <?php else: ?>
                                            <div class="product-item-placeholder">No Image</div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="product-item-name"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                            <span class="product-item-qty">Số lượng: <?php echo $item['quantity']; ?></span>
                                        </div>
                                    </div>
                                    <div class="product-item-price">
                                        <?php echo number_format($subtotal, 0, ',', '.'); ?>đ
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="summary-row summary-total-row">
                            <span class="summary-total-label">Tổng tiền hàng:</span>
                            <span class="total-price"><?php echo number_format($total_cart, 0, ',', '.'); ?>đ</span>
                        </div>
                        
                        <p class="summary-note checkout-note">* Vui lòng kiểm tra kỹ thông tin và danh sách sản phẩm trước khi bấm xác nhận thanh toán.</p>
                        
                        <button type="submit" class="btn-checkout">Xác nhận đặt hàng</button>
                    </div>
                </div>

            </div>
        </form>
    <?php else: ?>
        <div class="empty-cart-card">
            <div class="empty-cart-icon">🛒</div>
            <h3 class="empty-cart-text">Không có sản phẩm nào để thanh toán</h3>
            <p class="empty-cart-subtext">Vui lòng chọn sản phẩm vào giỏ hàng trước khi thực hiện thanh toán nhé!</p>
            <a href="/WebBanHang_CNPM/Product" class="btn-checkout inline-btn">Quay lại cửa hàng</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>