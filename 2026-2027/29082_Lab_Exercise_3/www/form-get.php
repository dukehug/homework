<?php
/**
 * 表單查看示例 - GET 方法版本
 * 功能：使用 GET 方法查詢並顯示數據庫中存儲的所有表單提交記錄
 * 安全性：使用預處理語句防止 SQL 注入，使用 htmlspecialchars 防止 XSS 攻擊
 */

// ============================================
// 1. 數據庫連接配置
// ============================================
$host = 'mysql';
$user = 'app_user';
$password = 'app_password';
$database = 'my_app';

// 建立連接
$conn = new mysqli($host, $user, $password, $database);

// 檢查連接
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// 設置字符集為 UTF-8
$conn->set_charset("utf8mb4");

// ============================================
// 2. 初始化變量
// ============================================
$submissions = [];      // 存儲查詢結果
$total_count = 0;       // 記錄總數
$search_email = '';     // 搜尋郵箱（可選功能）
$delete_message = '';   // 刪除操作的反饋信息

// ============================================
// 3. 處理 GET 請求
// ============================================

// 檢查是否有搜尋郵箱的 GET 參數
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_email = trim($_GET['search']);
}

// 檢查是否有刪除操作
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    
    // 使用預處理語句刪除記錄
    $delete_sql = "DELETE FROM submissions WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);
    
    if ($delete_stmt->execute()) {
        $delete_message = "Record deleted successfully!";
    } else {
        $delete_message = "Failed to delete: " . $delete_stmt->error;
    }
    
    $delete_stmt->close();
}

// ============================================
// 4. 查詢數據庫中的所有提交記錄
// ============================================

// 準備 SQL 查詢語句
if (!empty($search_email)) {
    // 如果有搜尋條件，只查詢符合的記錄
    $sql = "SELECT id, name, email, phone, message, created_at 
            FROM submissions 
            WHERE email LIKE ? 
            ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $search_param = '%' . $search_email . '%';
    $stmt->bind_param("s", $search_param);
} else {
    // 如果沒有搜尋條件，查詢所有記錄
    $sql = "SELECT id, name, email, phone, message, created_at 
            FROM submissions 
            ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
}

// 執行查詢
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $submissions = $result->fetch_all(MYSQLI_ASSOC);
    $total_count = count($submissions);
} else {
    $delete_message = "Failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #616163 0%, #1b1a1a 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .search-box {
            margin-bottom: 30px;
        }
        .table-container {
            margin-top: 30px;
        }
        .btn-delete {
            color: white;
        }
        .alert {
            margin-bottom: 20px;
        }
        .nav-links {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- 導航 -->
    <div class="nav-links">
        <a href="index.php" class="btn btn-secondary btn-sm">Home</a>
        <a href="form-post.php" class="btn btn-primary btn-sm">Fill Form</a>
        <a href="form-get.php" class="btn btn-primary btn-sm">Check Submit</a>
        <a href="db-connect.php" class="btn btn-primary btn-sm">DB Connect test</a>
    </div>

    <h1>Form record</h1>

    <!-- 顯示刪除消息 -->
    <?php if (!empty($delete_message)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $delete_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- 搜尋框 -->
    <div class="search-box">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <input type="email" 
                       name="search" 
                       class="form-control" 
                       placeholder="enter email address..." 
                       value="<?php echo htmlspecialchars($search_email); ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="form_get.php" class="btn btn-secondary">Clear</a>
            </div>
        </form>
        <p class="text-muted mt-2">Find <strong><?php echo $total_count; ?></strong>  records</p>
    </div>

    <!-- 記錄表格 -->
    <div class="table-container">
        <?php if (!empty($submissions)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Submit Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['message'], 0, 50)) . (strlen($row['message']) > 50 ? '...' : ''); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this record?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                <p>📭 No submission records found</p>
                <a href="form_post.php" class="btn btn-primary">Go to Submit Form</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
