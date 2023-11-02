// user.php
<?php
// بدء جلسة
session_start();

// اتصال بقاعدة بيانات MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// تحقق من وجود بيانات الدخول
if (isset($_POST['login'])) {
  // استقبال بيانات الدخول
  $email = $_POST['email'];
  $password = $_POST['password'];

  // تشفير كلمة المرور باستخدام md5
  $password = md5($password);

  // البحث عن المستخدم في قاعدة البيانات
  $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // جلب بيانات المستخدم
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $name = $row['name'];
    $role = $row['role'];
    $branch = $row['branch'];
    $branch_code = $row['branch_code'];
    $sector = $row['sector'];
    $permission = $row['permission'];

    // حفظ بيانات المستخدم في متغيرات جلسة
    $_SESSION['id'] = $id;
    $_SESSION['name'] = $name;
    $_SESSION['role'] = $role;
    $_SESSION['branch'] = $branch;
    $_SESSION['branch_code'] = $branch_code;
    $_SESSION['sector'] = $sector;
    $_SESSION['permission'] = $permission;

    // تحويل المستخدم إلى صفحة النموذج
    header("Location: forms/form.php");
  } else {
    // عرض رسالة خطأ
    echo "<script>alert('البريد الإلكتروني أو كلمة المرور غير صحيحة');</script>";
  }
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>

<html>
<head>
  <title>صفحة المستخدم</title>
  <!-- ربط ملف style.css -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>صفحة المستخدم</h1>
  <p>ادخل بيانات الدخول في الحقول التالية:</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p>البريد الإلكتروني:</p>
    <input type="email" name="email" required>
    <p>كلمة المرور:</p>
    <input type="password" name="password" required>
    <input type="submit" name="login" value="دخول">
  </form>
</body>
</html>

<?php
// إنهاء جلسة
session_destroy();
?>
