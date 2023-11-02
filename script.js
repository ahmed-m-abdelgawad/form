// script.js
// طلب تفعيل GPS لجلب إحداثيات الموقع
navigator.geolocation.getCurrentPosition(function(position) {
    // تحديد خطوط الطول والعرض
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
  
    // عرض إحداثيات الموقع في حقل الإدخال
    document.getElementById("coordinates").value = latitude + "," + longitude;
  });
  