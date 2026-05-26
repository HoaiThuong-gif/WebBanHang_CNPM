document.addEventListener("DOMContentLoaded", function () {
    
    // Hàm định dạng số thành tiền tệ (Ví dụ: 500000 -> 500.000đ)
    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
    }

    // Hàm tính toán lại tất cả các con số hiển thị trên giao diện
    function updateCartUI() {
        let totalCart = 0;

        document.querySelectorAll(".js-row").forEach(row => {
            const price = parseFloat(row.querySelector(".js-price").getAttribute("data-price"));
            let qty = parseInt(row.querySelector(".js-quantity").value);
            
            if (isNaN(qty) || qty < 1) qty = 1;

            const subtotal = price * qty;
            totalCart += subtotal;

            // Đổi hiển thị thành tiền của từng dòng sản phẩm
            row.querySelector(".js-subtotal").innerText = formatVND(subtotal);
        });

        // Đổi hiển thị tổng tiền hóa đơn
        document.querySelector(".js-total-cart").innerText = formatVND(totalCart);
    }

    // Lắng nghe sự kiện khi thay đổi số lượng ở bất kỳ ô input nào
    document.querySelectorAll(".js-quantity").forEach(input => {
        input.addEventListener("input", function () {
            
            // 1. Thay đổi hiển thị tiền tệ lập tức trên màn hình (UI/UX mượt mà)
            updateCartUI();

            // 2. Gửi ngầm dữ liệu để cập nhật Session trong Controller
            const row = this.closest(".js-row");
            const productId = row.getAttribute("data-id");
            const newQuantity = this.value;

            // Tạo FormData tương ứng với cấu trúc mảng quantity[id] mà Controller yêu cầu
            let formData = new FormData();
            formData.append(`quantity[${productId}]`, newQuantity);

            // Gửi dữ liệu ngầm bằng Fetch API chuẩn AJAX
            fetch('/WebBanHang_CNPM/Product/updateCart', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Khai báo cho Controller biết đây là AJAX
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    console.log(`Đã đồng bộ sản phẩm ${productId} với số lượng ${newQuantity} vào Session.`);
                }
            })
            .catch(error => console.error('Lỗi kết nối đồng bộ giỏ hàng:', error));
        });
    });
});