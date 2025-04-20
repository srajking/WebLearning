// إظهار النافذة المنبثقة وإخفائها بعد الإضافة
document.getElementById("addUserForm").addEventListener("submit", function (e) {
  e.preventDefault(); // منع إعادة تحميل الصفحة
  var formData = new FormData(this);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../config/add_user.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      // إغلاق النافذة المنبثقة باستخدام bootstrap Modal API
      var modalElement = document.getElementById("addUserModal");
      var myModal = bootstrap.Modal.getInstance(modalElement); // الحصول على النسخة الحالية للنافذة
      myModal.hide(); // إغلاق النافذة

      // التأكد من إغلاق النافذة قبل تنفيذ بقية العمليات
      setTimeout(function () {
        // تحديث الجدول مع البيانات الجديدة
        var table = document
          .getElementById("admin-table")
          .getElementsByTagName("tbody")[0];
        var row = table.insertRow(table.rows.length);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        cell1.textContent = document.getElementById("modalUsername").value;

        // تغيير النص بناءً على الصلاحية المدخلة
        var role = document.getElementById("modalRole").value;
        if (role === "admin") {
          cell2.textContent = "مسؤول";
        } else if (role === "manager") {
          cell2.textContent = "إداري";
        } else {
          cell2.textContent = "كاشير";
        }

        // مسح المدخلات في النموذج
        document.getElementById("addUserForm").reset();
      }, 500); // التأخير قليلاً لضمان إغلاق النافذة أولاً
    } else {
      alert("حدث خطأ أثناء إضافة المستخدم.");
    }
  };
  xhr.send(formData);
});

// إضافة مستمعات للنقر على زر التعديل والحذف
document.querySelectorAll(".editBtn").forEach(function (button) {
  button.addEventListener("click", function () {
    var row = button.closest("tr");
    var userId = row.getAttribute("data-id"); // الحصول على المعرف الفريد للمستخدم
    var username = row.cells[0].textContent;
    var role = row.cells[1].textContent;

    // ضع البيانات في النموذج (لتعديلها)
    document.getElementById("modalUsername").value = username;
    document.getElementById("modalRole").value = role;
    document.getElementById("userIdToEdit").value = userId; // تخزين ID المستخدم في حقل مخفي

    // إظهار النافذة المنبثقة
    var modalElement = document.getElementById("addUserModal");
    var myModal = new bootstrap.Modal(modalElement);
    myModal.show();
  });
});

document.querySelectorAll(".deleteBtn").forEach(function (button) {
  button.addEventListener("click", function () {
    var row = button.closest("tr");
    var userId = row.getAttribute("data-id"); // الحصول على المعرف الفريد للمستخدم

    // يمكنك هنا إضافة AJAX لحذف المستخدم باستخدام المعرف (userId)
    alert("ستقوم بحذف المستخدم الذي ID الخاص به هو " + userId);
  });
});
