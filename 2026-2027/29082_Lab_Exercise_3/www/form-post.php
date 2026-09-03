<?php
/**
 * 表單提交示例 - POST 方法版本
 * 功能：使用 POST 方法提交表單數據並存入 MySQL 數據庫
 * 安全性：使用預處理語句防止 SQL 注入
 */

// ============================================
// 1. 數據庫連接配置
// ============================================
$host = 'mysql';                    // Docker 容器內 MySQL 主機名
$user = 'app_user';                  // MySQL 用戶名
$password = 'app_password';       // MySQL 密碼
$database = 'my_app';          // 數據庫名稱

// 建立 MySQL 連接
$conn = new mysqli($host, $user, $password, $database);

// 檢查連接是否成功
if ($conn->connect_error) {
    die('數據庫連接失敗: ' . $conn->connect_error);
}

// 設置字符集為 UTF-8（支持中文）
$conn->set_charset("utf8mb4");

// ============================================
// 2. 初始化變量
// ============================================
$success_message = '';  // 成功提交的提示信息
$error_message = '';    // 錯誤信息

// ============================================
// 3. 處理 POST 請求（表單提交時執行）
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 獲取並清理表單數據
    $name = trim($_POST['name'] ?? '');           // 獲取名字，移除空白
    $email = trim($_POST['email'] ?? '');         // 獲取郵箱
    $phone = trim($_POST['phone'] ?? '');         // 獲取電話
    $message = trim($_POST['message'] ?? '');     // 獲取訊息
    
    // 驗證表單數據是否為空
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        $error_message = '❌ 所有欄位都是必填項，請填寫完整！';
    }
    // 驗證郵箱格式
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = '❌ 請輸入有效的郵箱地址！';
    }
    // 驗證電話號碼（簡單驗證：只能包含數字和 + - ()）
    elseif (!preg_match('/^[0-9\+\-\(\)\s]+$/', $phone)) {
        $error_message = '❌ 電話號碼格式不正確！';
    }
    else {
        // 數據驗證通過，準備插入數據庫
        
        // 使用預處理語句（prepared statement）防止 SQL 注入攻擊
        $sql = "INSERT INTO submissions (name, email, phone, message, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        // 準備語句
        $stmt = $conn->prepare($sql);
        
        // 檢查語句準備是否成功
        if ($stmt === false) {
            $error_message = '❌ 數據庫錯誤: ' . $conn->error;
        } else {
            // 綁定參數到預處理語句
            // 參數類型說明：s = 字符串(string), i = 整數(integer), d = 浮點數(double)
            $stmt->bind_param("ssss", $name, $email, $phone, $message);
            
            // 執行語句
            if ($stmt->execute()) {
                // 執行成功
                $success_message = '✅ 表單提交成功！您的信息已保存到數據庫。';
                
                // 清空表單變量（可選）
                $name = '';
                $email = '';
                $phone = '';
                $message = '';
            } else {
                // 執行失敗
                $error_message = '❌ 提交失敗: ' . $stmt->error;
            }
            
            // 關閉預處理語句
            $stmt->close();
        }
    }
}

// ============================================
// 4. 關閉數據庫連接（在頁面結束時）
// ============================================
// 注意：在這裡先不關閉，因為下面可能還需要查詢數據庫

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission - POST Method</title>
    
    <!-- Bootstrap CSS 引入（美化頁面） -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- 自定義 CSS 樣式 -->
    <style>
        body {
            background: linear-gradient(135deg, #616163 0%, #1b1a1a 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 600px;
        }
        
        .card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: none;
            border-radius: 10px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #349bea 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #349bea 100%);
            border: none;
            padding: 10px 30px;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #349bea 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 頁面標題 -->
        <div class="text-center mb-4">
            <h1 class="text-white fw-bold"> Contact Form</h1>
            <p class="text-white-50">Submit data to the database using the POST method</p>
        </div>
        
        <!-- 主要卡片容器 -->
        <div class="card">
            <!-- 卡片頭部 -->
            <div class="card-header">
                <h5 class="mb-0">Please fill in the following information</h5>
            </div>
            
            <!-- 卡片主體 -->
            <div class="card-body p-4">
                
                <!-- 成功提示信息 -->
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- 錯誤提示信息 -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- HTML 表單 -->
                <form method="POST" action="">
                    
                    <!-- 名字欄位 -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="name" 
                            name="name" 
                            placeholder="Please enter your name"
                            value="<?php echo htmlspecialchars($name ?? '', ENT_QUOTES); ?>"
                            required
                        >
                    </div>
                    
                    <!-- 郵箱欄位 -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            placeholder="Please enter your email"
                            value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>"
                            required
                        >
                    </div>
                    
                    <!-- 電話欄位 -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                        <input 
                            type="tel" 
                            class="form-control" 
                            id="phone" 
                            name="phone" 
                            placeholder="Please enter your phone number"
                            value="<?php echo htmlspecialchars($phone ?? '', ENT_QUOTES); ?>"
                            required
                        >
                    </div>
                    
                    <!-- 訊息欄位（文本區域） -->
                    <div class="mb-4">
                        <label for="message" class="form-label">Msg <span class="text-danger">*</span></label>
                        <textarea 
                            class="form-control" 
                            id="message" 
                            name="message" 
                            rows="4" 
                            placeholder="Please enter your message"
                            required
                        ><?php echo htmlspecialchars($message ?? '', ENT_QUOTES); ?></textarea>
                    </div>
                    
                    <!-- 提交和重置按鈕 -->
                    <div class="d-grid gap-2 d-sm-flex">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                            Submit Form
                        </button>
                        <button type="reset" class="btn btn-outline-secondary btn-lg flex-grow-1">
                            Clear Form
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- 卡片底部提示 -->
            <div class="card-footer text-center text-muted small p-3">
                <p class="mb-0">
                    💡 Tip: All fields with <span class="text-danger">*</span> are required
                </p>
            </div>
        </div>
        
        <!-- 底部導航連結 -->
        <div class="text-center mt-4">
            <a href="form-get.php" class="btn btn-light btn-sm">
                Visit GET Version →
            </a>
        </div>
    </div>
    
    <!-- Bootstrap JavaScript 引入（使提示信息可關閉） -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// 在頁面最後關閉數據庫連接
$conn->close();
?>
