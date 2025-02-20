// products.js

// الاستيراد

// البحث
// استدعاء دالة البحث عند إدخال نص في حقل البحث
$("#search").on("input", function () {
  var searchTerm = $(this).val().toLowerCase(); // الحصول على النص المدخل في حقل البحث
  $("#productsTable tr").each(function () {
    var rowText = $(this).text().toLowerCase(); // الحصول على النص الكامل للسطر
    if (rowText.indexOf(searchTerm) === -1) {
      $(this).hide(); // إخفاء السطر إذا لم يحتوي النص على النص المدخل
    } else {
      $(this).show(); // إظهار السطر إذا كان النص يحتوي على النص المدخل
    }
  });
});

// بداية كود الاضافة

$(document).ready(function () {
  // عند تقديم النموذج
  $("#addProductForm").submit(function (event) {
    event.preventDefault(); // منع إعادة تحميل الصفحة

    // جمع البيانات من النموذج
    var formData = $(this).serialize();

    // إرسال البيانات عبر AJAX
    $.ajax({
      url: "../config/add_product.php", // مسار المعالجة
      method: "POST",
      data: formData,
      success: function (response) {
        console.log(response); // إضافة هذه السطر للتحقق من الاستجابة
        if (response === "تم إضافة المنتج بنجاح!") {
          // إخفاء النافذة المنبثقة
          $("#addProductModal").modal("hide");

          // تحديث الصفحة بشكل تلقائي
          location.reload(); // سيتم إعادة تحميل الصفحة بأكملها
        } else {
          alert("حدث خطأ أثناء إضافة المنتج");
        }
      },
      error: function (xhr, status, error) {
        alert("حدث خطأ في الاتصال بالخادم: " + error);
      },
    });
  });

  // دالة لتحميل بيانات المنتجات في الجدول
  function loadProductTable() {
    $.ajax({
      url: "../config/fetch_products.php", // استبدال load_products.php بـ fetch_products.php
      method: "GET",
      success: function (data) {
        // تحديث الجدول داخل tbody
        $("#productsTable").html(data);
      },
      error: function () {
        alert("حدث خطأ في تحميل البيانات");
      },
    });
  }
});

// نهاية كود الاضافة

// بداية كود حذف المنتج

$(document).ready(function () {
  // تحديد الصف عند النقر عليه
  $("#productsTable").on("click", "tr", function () {
    $("#productsTable tr").removeClass("selected");
    $(this).addClass("selected");
  });

  // عند الضغط على زر الحذف
  $("#deleteProductBtn").click(function () {
    var selectedRow = $("#productsTable tr.selected"); // جلب الصف المحدد

    if (selectedRow.length === 0) {
      alert("يرجى تحديد صف للحذف!");
      return;
    }

    var productId = selectedRow.data("id");

    if (!confirm("هل أنت متأكد أنك تريد حذف هذا المنتج؟")) {
      return;
    }

    $.ajax({
      url: "../config/delete_product.php",
      method: "POST",
      data: { id: productId },
      dataType: "json", // تأكد أن الاستجابة بصيغة JSON
      success: function (response) {
        if (response.status === "success") {
          selectedRow.fadeOut(300, function () {
            $(this).remove(); // حذف الصف من الجدول
            updateRowNumbers(); // تحديث الترقيم بعد الحذف
          });
        } else {
          alert("خطأ: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        alert("حدث خطأ في الاتصال بالخادم: " + error);
      },
    });
  });

  // تحديث أرقام الصفوف بعد الحذف
  function updateRowNumbers() {
    $("#productsTable tr").each(function (index) {
      $(this)
        .find("td:first")
        .text(index + 1); // إعادة ترقيم التسلسل
    });
  }
});

// نهاية كود حذف المنتج

// تعديل البيانات

let selectedRow = null;

function selectRow(row) {
  if (selectedRow) {
    selectedRow.classList.remove("table-warning");
  }
  selectedRow = row;
  row.classList.add("table-warning");
}

// عند النقر على زر التعديل
document
  .getElementById("editProductBtn")
  .addEventListener("click", function () {
    if (!selectedRow) {
      alert("يرجى تحديد مادة لتعديلها");
      return;
    }

    let cells = selectedRow.children;
    document.getElementById("edit_product_id").value =
      selectedRow.getAttribute("data-id");
    document.getElementById("edit_product_name").value = cells[1].innerText;
    document.getElementById("edit_available_quantity").value =
      cells[2].innerText;
    document.getElementById("edit_sold_quantity").value = cells[3].innerText;
    document.getElementById("edit_customer_price").value = cells[5].innerText;
    document.getElementById("edit_member_price").value = cells[6].innerText;
    document.getElementById("edit_product_points").value = cells[7].innerText;
    document.getElementById("edit_barcode").value = cells[8].innerText;
    document.getElementById("edit_category").value = cells[9].innerText;

    new bootstrap.Modal(document.getElementById("editProductModal")).show();
  });

// عند إرسال نموذج التعديل
document
  .getElementById("editProductForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData();
    formData.append("id", document.getElementById("edit_product_id").value);
    formData.append(
      "product_name",
      document.getElementById("edit_product_name").value
    );
    formData.append(
      "available_quantity",
      document.getElementById("edit_available_quantity").value
    );
    formData.append(
      "sold_quantity",
      document.getElementById("edit_sold_quantity").value
    );
    formData.append(
      "customer_price",
      document.getElementById("edit_customer_price").value
    );
    formData.append(
      "member_price",
      document.getElementById("edit_member_price").value
    );
    formData.append(
      "product_points",
      document.getElementById("edit_product_points").value
    );
    formData.append("barcode", document.getElementById("edit_barcode").value);
    formData.append("category", document.getElementById("edit_category").value);

    fetch("../config/update_product.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          location.reload();
        } else {
          alert("حدث خطأ أثناء التعديل");
        }
      });
  });

// التعديل على القيمة البماعة
document
  .getElementById("edit_sold_quantity")
  .addEventListener("change", function () {
    if (!selectedRow) {
      alert("يرجى تحديد مادة لتعديلها");
      return;
    }

    let soldQuantity = parseInt(this.value);
    let productId = selectedRow.getAttribute("data-id");

    let formData = new FormData();
    formData.append("id", productId);
    formData.append("sold_quantity", soldQuantity);

    fetch("../config/update_sold_quantity.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          location.reload();
        } else {
          alert("حدث خطأ أثناء تحديث العدد المباع");
        }
      });
  });
// نهاية تعديل البيانات

// حذف كل البيانات
$(document).ready(function () {
  // عند الضغط على زر "حذف كل البيانات"
  $("#deleteAllBtn").click(function () {
    if (confirm("هل أنت متأكد أنك تريد حذف جميع البيانات؟")) {
      // إرسال طلب الحذف عبر AJAX
      $.ajax({
        url: "../config/delete_all_products.php", // تأكد من المسار الصحيح
        method: "POST",
        success: function (response) {
          console.log(response); // طباعة الاستجابة للتأكد
          if (response === "تم حذف جميع البيانات بنجاح! تم إعادة الترقيم!") {
            // مسح البيانات من الجدول
            $("#productsTable").html(
              '<tr><td colspan="10" class="text-center">لا توجد منتجات في قاعدة البيانات</td></tr>'
            );
          } else {
            alert("حدث خطأ أثناء الحذف: " + response);
          }
        },
        error: function (xhr, status, error) {
          alert("حدث خطأ في الاتصال بالخادم: " + error);
        },
      });
    }
  });
});

// نهاية حذف كل البيانات
