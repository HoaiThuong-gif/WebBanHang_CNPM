<?php include 'app/views/shares/header.php'; ?>

<h1>Thêm danh mục mới</h1>

<?php if (!empty($errors)): ?>

    <div class="alert alert-danger">

        <ul>

            <?php foreach ($errors as $error): ?>

                <li>
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </li>

            <?php endforeach; ?>

        </ul>

    </div>

<?php endif; ?>

<form
    method="POST"
    action="/WebBanHang_CNPM/Category/save"
    onsubmit="return validateForm();">

    <div class="form-group">

        <label for="name">Tên danh mục:</label>

        <input
            type="text"
            id="name"
            name="name"
            class="form-control"
            required>

    </div>

    <button type="submit" class="btn btn-primary">
        Thêm danh mục
    </button>

</form>

<a href="/WebBanHang_CNPM/Category/list" class="btn btn-secondary mt-2">
    Quay lại 
</a>

<?php include 'app/views/shares/footer.php'; ?>