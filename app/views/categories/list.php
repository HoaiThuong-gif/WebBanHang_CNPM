<?php include 'app/views/shares/header.php'; ?>

<div class="category-header">
    <div>
        <h1>Danh sách danh mục</h1>
        <p>Quản lý các nhóm sản phẩm trong cửa hàng</p>
    </div>

    <a href="/WebBanHang_CNPM/Category/add" class="category-add-btn">
        + Thêm danh mục mới
    </a>
</div>

<div class="category-grid">

    <?php foreach ($categories as $category): ?>

        <div class="category-card">

            <div class="category-icon">
                ✦
            </div>

            <div class="category-content">
                <h2>
                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                </h2>

                <p>
                    Danh mục sản phẩm
                </p>
            </div>

            <div class="category-actions">

                <a
                    href="/WebBanHang_CNPM/Category/edit/<?php echo $category->id; ?>"
                    class="category-edit-btn">
                    Sửa
                </a>

                <a
                    href="/WebBanHang_CNPM/Category/delete/<?php echo $category->id; ?>"
                    class="category-delete-btn"
                    onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                    Xóa
                </a>

            </div>

        </div>

    <?php endforeach; ?>

</div>

<?php include 'app/views/shares/footer.php'; ?>