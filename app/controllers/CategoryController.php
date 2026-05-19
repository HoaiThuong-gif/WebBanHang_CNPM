<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index()
    {
        $this->list();
    }

    // HIỂN THỊ DANH SÁCH
    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/categories/list.php';
    }

    // HIỂN THỊ FORM THÊM
    public function add()
    {
        include 'app/views/categories/add.php';
    }

    // LƯU CATEGORY MỚI
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $_POST['name'] ?? '';

            if (empty($name)) {
                $errors[] = "Tên danh mục không được để trống";
                include 'app/views/categories/add.php';
                return;
            }

            $this->categoryModel->addCategory($name);

            header("Location: /webbanhang/Category/list");
        }
    }

    // FORM EDIT
    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if ($category) {
            include 'app/views/categories/edit.php';
        } else {
            echo "Không tìm thấy danh mục";
        }
    }

    // UPDATE
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $name = $_POST['name'];

            $this->categoryModel->updateCategory($id, $name);

            header("Location: /webbanhang/Category/list");
        }
    }

    // DELETE
    public function delete($id)
    {
        $this->categoryModel->deleteCategory($id);

        header("Location: /webbanhang/Category/list");
    }
}
?>