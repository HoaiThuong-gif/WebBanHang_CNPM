<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function index()
    {
        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();

        $category_id = $_GET['category_id'] ?? '';

        if (!empty($category_id)) {
            $products = $this->productModel->getProductsByCategory($category_id);
        } else {
            $products = $this->productModel->getProducts();
        }

        include 'app/views/products/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);

        if ($product) {
            include 'app/views/products/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/products/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }

            $result = $this->productModel->addProduct(
                $name,
                $description,
                $price,
                $category_id,
                $image
            );

            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/products/add.php';
            } else {
                header('Location: /WebBanHang_CNPM/Product');
            }
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();

        if ($product) {
            include 'app/views/products/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }

            $edit = $this->productModel->updateProduct(
                $id,
                $name,
                $description,
                $price,
                $category_id,
                $image
            );

            if ($edit) {
                header('Location: /WebBanHang_CNPM/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /WebBanHang_CNPM/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";

        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra xem file có phải là hình ảnh không
        $check = getimagesize($file["tmp_name"]);

        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }

        // Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes)
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }

        // Chỉ cho phép một số định dạng hình ảnh nhất định
        if (
            $imageFileType != "jpg" &&
            $imageFileType != "png" &&
            $imageFileType != "jfif" &&
            $imageFileType != "gif"
        ) {
            throw new Exception("Chỉ cho phép các định dạng JPG, JFIF, PNG và GIF.");
        }

        // Lưu file
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }

        return $target_file;
    }

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }

        header('Location: /WebBanHang_CNPM/Product/cart');
    }

    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/products/Cart.php';
    }

    public function checkout()
    {
        // Lấy dữ liệu giỏ hàng từ Session, nếu chưa có thì gán thành mảng rỗng
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/products/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            
            // Kiểm tra giỏ hàng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }
            
            // Bắt đầu giao dịch
            $this->db->beginTransaction();
            
            try {
                // Lưu thông tin đơn hàng vào bảng orders
                $query = "INSERT INTO orders (name, phone, address) VALUES (:name, :phone, :address)";
                
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();
                
                // Lưu chi tiết đơn hàng vào bảng order_details
                $cart = $_SESSION['cart'];
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
                    
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }
                
                // Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);
                
                // Commit giao dịch
                $this->db->commit();
                
                // Chuyển hướng đến trang xác nhận đơn hàng
                header('Location: /WebBanHang_CNPM/Product/orderConfirmation');
            } catch (Exception $e) {
                // Rollback giao dịch nếu có lỗi
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }
    
    //
    // 1. HÀM XỬ LÝ CẬP NHẬT SỐ LƯỢNG GIỎ HÀNG
    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity'])) {
            if (isset($_SESSION['cart'])) {
                foreach ($_POST['quantity'] as $product_id => $new_quantity) {
                    $new_quantity = (int)$new_quantity;
                    if ($new_quantity > 0 && isset($_SESSION['cart'][$product_id])) {
                        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
                    }
                }
            }
        }
        
        // Kiểm tra nếu yêu cầu gửi đến là AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit();
        }

        // Nếu là submit form thông thường (fallback) thì mới chuyển hướng
        header('Location: /WebBanHang_CNPM/Product/cart');
        exit();
    }

    // 2. HÀM XỬ LÝ XÓA SẢN PHẨM KHỎI GIỎ HÀNG
    public function removeFromCart($id)
    {
        // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng không
        if (isset($_SESSION['cart'][$id])) {
            // Xóa bỏ sản phẩm đó khỏi mảng Session
            unset($_SESSION['cart'][$id]);
        }
        
        // Sau khi xóa xong, chuyển hướng quay trở lại trang giỏ hàng
        header('Location: /WebBanHang_CNPM/Product/cart');
        exit();
    }

    //3. ĐẶT HÀNG THÀNH CÔNG
    public function orderSuccess()
    {    
        // Truyền mã đơn hàng ra ngoài View
        include 'app/views/products/orderConfirmation.php';
    }
    //

    public function orderConfirmation()
    {
        include 'app/views/products/orderConfirmation.php';
    }
}
?>