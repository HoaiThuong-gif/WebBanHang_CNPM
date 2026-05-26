<?php include 'app/views/shares/header.php'; ?>

<div class="shop-header">
    <div>
        <h1>Danh sách sản phẩm</h1>
        <p>Khám phá các sản phẩm hiện có trong cửa hàng</p>
    </div>

    <a href="/WebBanHang_CNPM/Product/add" class="btn-add-product">
        + Thêm sản phẩm
    </a>
</div>

<form method="GET" action="/WebBanHang_CNPM/Product" class="filter-form">
    <div class="filter-group">
        <label for="category_id">Lọc theo danh mục:</label>

        <select name="category_id" id="category_id" class="filter-select">
            <option value="">Tất cả danh mục</option>

            <?php foreach ($categories as $category): ?>
                <option
                    value="<?php echo $category->id; ?>"
                    <?php echo isset($_GET['category_id']) && $_GET['category_id'] == $category->id ? 'selected' : ''; ?>>

                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>

                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="filter-btn">
            Lọc
        </button>

        <a href="/WebBanHang_CNPM/Product" class="filter-reset">
            Bỏ lọc
        </a>
    </div>
</form>

<div class="shop-grid">

    <?php if (!empty($products)): ?>

        <?php foreach ($products as $product): ?>

            <div class="shop-card">

                <a href="/WebBanHang_CNPM/Product/show/<?php echo $product->id; ?>" class="shop-image">
                    <?php if ($product->image): ?>
                        <img
                            src="/WebBanHang_CNPM/<?php echo $product->image; ?>"
                            alt="Product Image">
                    <?php else: ?>
                        <div class="empty-image">Không có ảnh</div>
                    <?php endif; ?>
                </a>

                <div class="shop-info">

                    <h2>
                        <a href="/WebBanHang_CNPM/Product/show/<?php echo $product->id; ?>">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h2>

                    <p class="shop-desc">
                        <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p class="shop-price">
                        <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
                    </p>

                    <p class="shop-category">
                        <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <div class="shop-actions">
                        <a
                            href="/WebBanHang_CNPM/Product/edit/<?php echo $product->id; ?>"
                            class="btn-edit">
                            Sửa
                        </a>

                        <a
                            href="/WebBanHang_CNPM/Product/delete/<?php echo $product->id; ?>"
                            class="btn-delete"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            Xóa
                        </a>

                        <a
                            href="/WebBanHang_CNPM/Product/addToCart/<?php echo $product->id; ?>"
                            class="btn-add">
                            Thêm
                        </a>
                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="empty-product-message">
            Không có sản phẩm nào trong danh mục này.
        </div>

    <?php endif; ?>

</div>

<?php include 'app/views/shares/footer.php'; ?>