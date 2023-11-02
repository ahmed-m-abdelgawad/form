// form.php
<?php
// بدء جلسة
session_start();

// اتصال بقاعدة بيانات MySQL باستخدام متغيرات جلسة
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// // جلب بيانات العميل من قاعدة البيانات
// if (isset($_POST['search'])) {
//   $search_code = $_POST['search_code'];
  

if (isset($_POST['search'])) {
  // تحقق من وجود قيمة في حقل البحث
  if (!empty($_POST['search_code'])) {
    $search_code = $_POST['search_code'];
    
  }



  // تحقق من صلاحية المستخدم للتعديل
  if ($_SESSION['permission'] == 'له حق التعديل') {
    // البحث عن العميل في جدول clients بناءً على كود العميل المدخل وكود الفرع الموجود في متغيرات جلسة
    $sql = "SELECT client_name, client_code FROM clients WHERE client_code = '$search_code' AND branch_code = '{$_SESSION['branch_code']}'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
      // جلب اسم وكود العميل
      $row = $result->fetch_assoc();
      $client_name = $row['client_name'];
      $client_code = $row['client_code'];
    } else {
      // لا يوجد عميل بهذا الكود أو ينتمي إلى فرع آخر
      $client_name = "";
      $client_code = "";
    }
    
    // جلب قائمة أكواد العملاء المتاحة للبحث من جدول clients بناءً على كود الفرع الموجود في متغيرات جلسة
    $sql = "SELECT client_code FROM clients WHERE status = 'not modified' AND branch_code = '{$_SESSION['branch_code']}'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
      // إنشاء مصفوفة لحفظ أكواد العملاء
      $client_codes = array();
      while($row = $result->fetch_assoc()) {
        // إضافة كود العميل إلى المصفوفة
        $client_codes[] = $row['client_code'];
      }
    } else {
      // لا يوجد عملاء غير معدّلين أو ينتمون إلى فرع آخر
      $client_codes = array();
    }
    
  } else {
    // ليس للمستخدم حق التعديل
    echo "<script>alert('ليس لديك صلاحية لتعديل بيانات العملاء');</script>";
  }

  

}


// حفظ بيانات التعديل في قاعدة البيانات
if (isset($_POST['submit'])) {
  // استقبال بيانات التعديل
  $client_name = $_POST['client_name'];
  $client_code = $_POST['client_code'];
  $coordinates = $_POST['coordinates'];
  $notes = $_POST['notes'];
  $date_time = date("Y-m-d H:i:s");

  // تحقق من صلاحية المستخدم للتعديل
  if ($_SESSION['permission'] == 'له حق التعديل') {
    // تحديث حالة العميل في جدول clients إلى modified
    $sql = "UPDATE clients SET status = 'modified' WHERE client_code = '$client_code'";
    if ($conn->query($sql) === TRUE) {
      echo "<script>alert('تم تحديث حالة العميل بنجاح');</script>";
    } else {
      echo "<script>alert('حدث خطأ أثناء تحديث حالة العميل');</script>";
    }

    // إضافة بيانات التعديل في جدول modifications
    $sql = "INSERT INTO modifications (client_code, client_name, coordinates, notes, date_time) VALUES ('$client_code', '$client_name', '$coordinates', '$notes', '$date_time')";
    if ($conn->query($sql) === TRUE) {
      echo "<script>alert('تم إضافة بيانات التعديل بنجاح');</script>";
    } else {
      echo "<script>alert('حدث خطأ أثناء إضافة بيانات التعديل');</script>";
    }
  } else {
    // ليس للمستخدم حق التعديل
    echo "<script>alert('ليس لديك صلاحية لتعديل بيانات العملاء');</script>";
  }
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>

<html>
<head>
  <title>نموذج بيانات العملاء</title>
  <!-- ربط ملف style.css -->
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <h1>نموذج بيانات العملاء</h1>
    <p>ادخل كود العميل في الحقل التالي:</p>
    <input type="text" name="search_code" list="client_codes">
    <datalist id="client_codes">
      <?php
      // عرض قائمة أكواد العملاء المتاحة
      foreach ($client_codes as $code) {
        echo "<option value='$code'>$code</option>";
      }
      ?>
    </datalist>
    <input type="submit" name="search" value="بحث">
    <p>اسم العميل:</p>
    <input type="text" name="client_name" value="<?php echo $client_name; ?>" readonly>
    <p>كود العميل:</p>
    <input type="text" name="client_code" value="<?php echo $client_code; ?>" readonly>
    <p>كود الفرع:</p>
    <input type="text" name="branch_code" value="<?php echo $_SESSION['branch_code']; ?>" readonly>
    <p>إحداثيات الموقع:</p>
    <input type="text" name="coordinates" id="coordinates" readonly>
    <p>الملاحظات:</p>
    <textarea name="notes" rows="5"></textarea>
    <input type="submit" name="submit" value="إرسال">
  </form>

  <!-- ربط ملف script.js -->
  <script src="../script.js"></script>
</body>
</html>

<?php
// إنهاء جلسة
session_destroy();
?>
