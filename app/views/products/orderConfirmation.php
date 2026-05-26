<?php include 'app/views/shares/header.php'; ?>

<link rel="stylesheet" href="/WebBanHang_CNPM/public/css/cart.css">
<link rel="stylesheet" href="/WebBanHang_CNPM/public/css/order_success.css">

<div class="success-container">
    <div class="success-card">
        
        <div class="success-icon-wrapper">
            <div class="success-icon">✓</div>
        </div>

        <h1 class="cart-title" style="border: none; padding: 0; text-align: center; margin-bottom: 12px;">
            Khởi tạo đơn hàng thành công
        </h1>
        
        <p class="summary-note" style="font-size: 15px; font-style: normal; opacity: 0.9; max-width: 480px; margin: 0 auto; line-height: 1.6;">
            Hệ thống đã ghi nhận cổng dữ liệu thanh toán của bạn. Đơn hàng đang được điều phối đến trạm vận chuyển gần nhất.
        </p>

        <div class="success-actions">
            <a href="/WebBanHang_CNPM/Product" class="btn-checkout inline-btn" style="width: auto; margin: 0;">
                Tiếp tục mua sắm →
            </a>
        </div>

    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>