// البحث
document.getElementById("searchMember").addEventListener("input", function () {
  let searchValue = this.value.trim().toLowerCase(); // إزالة الفراغات وتحويل النص إلى حروف صغيرة
  let tableRows = document.querySelectorAll("tbody tr");

  tableRows.forEach((row) => {
    let accountName = row.cells[1].textContent.toLowerCase(); // اسم الحساب (العمود الثاني)
    let accountNumber = row.cells[8].textContent.toLowerCase(); // رقم الحساب (العمود التاسع)

    if (
      accountName.includes(searchValue) ||
      accountNumber.includes(searchValue)
    ) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
});
// نهاية البحث

// بداية الاضافة
document.addEventListener("DOMContentLoaded", function () {
  // زر فتح النافذة المنبثقة
  document
    .getElementById("openAddMemberModal")
    .addEventListener("click", function () {
      let addModal = new bootstrap.Modal(
        document.getElementById("addMemberModal")
      );
      addModal.show();
    });

  // زر حفظ العضو الجديد
  document.getElementById("saveMember").addEventListener("click", function () {
    let formData = new FormData(document.getElementById("addMemberForm"));

    fetch("../config/add_member.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          // تحديث الجدول بعرض البيانات الجديدة
          let newRow = `
            <tr data-id="${data.member.id}">
                <td>${data.member.id}</td>
                <td>${data.member.account_name}</td>
                <td>0.00</td> 
                <td>0.00</td>
                <td>0</td>
                <td>${data.member.account_type}</td>
                <td>0.00</td>
                <td>0.00</td>
                <td>${data.member.account_number}</td>
                <td>${data.member.mobile_number}</td>
                <td>${data.member.address}</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
          `;
          // إضافة السطر الجديد إلى الجدول
          document
            .querySelector("tbody")
            .insertAdjacentHTML("beforeend", newRow);

          // إغلاق النافذة المنبثقة
          let addModal = bootstrap.Modal.getInstance(
            document.getElementById("addMemberModal")
          );
          addModal.hide();
          // تحديث الصفحة تلقائيًا بعد الإضافة
          setTimeout(() => {
            location.reload();
          }); // تأخير بسيط للتأكد من تنفيذ العمليات بشكل سلس

          // مسح الحقول بعد الإضافة
          document.getElementById("addMemberForm").reset();

          // إعادة التركيز على الزر بعد الإغلاق
          document.getElementById("openAddMemberModal").focus();
        } else {
          alert(data.message);
        }
      })
      .catch((error) => console.error("Error:", error));
  });
});
// نهاية الاضافة

// بداية العديل
document.addEventListener("DOMContentLoaded", function () {
  const rows = document.querySelectorAll("table tbody tr");
  const editModal = document.querySelector("#editMemberModal"); // نافذة التعديل
  const editBtn = document.querySelector("#editBtn"); // زر التعديل

  let selectedRow = null; // متغير لتخزين الصف المحدد

  // جعل زر التعديل غير مفعل في البداية
  editBtn.disabled = true;

  rows.forEach((row) => {
    row.addEventListener("click", function () {
      // إزالة التحديد من الصفوف الأخرى
      rows.forEach((r) => r.classList.remove("selected"));

      // إضافة التحديد للصف الحالي
      row.classList.add("selected");

      // تخزين البيانات الخاصة بالصف المحدد
      selectedRow = row;

      // تفعيل زر التعديل
      editBtn.disabled = false;
    });
  });

  // فتح نافذة التعديل عند الضغط على زر "تعديل"
  editBtn.addEventListener("click", function () {
    if (selectedRow === null) return; // تأكد من أنه تم تحديد صف

    // إحضار البيانات من الصف المحدد
    const id = selectedRow.cells[0].textContent; // معرّف العضو
    const name = selectedRow.cells[1].textContent; // اسم الحساب
    const type = selectedRow.cells[5].textContent; // نوع الحساب
    const number = selectedRow.cells[8].textContent; // رقم الحساب
    const mobile = selectedRow.cells[9].textContent; // رقم الموبايل
    const address = selectedRow.cells[10].textContent; // العنوان

    // تعبئة البيانات في نافذة التعديل
    editModal.querySelector('input[name="account_name"]').value = name;
    editModal.querySelector('select[name="account_type"]').value = type;
    editModal.querySelector('input[name="account_number"]').value = number;
    editModal.querySelector('input[name="mobile_number"]').value = mobile;
    editModal.querySelector('textarea[name="address"]').value = address;

    // حفظ معرّف العضو في زر التعديل لتحديثه لاحقًا
    editBtn.dataset.id = id;

    // فتح النافذة المنبثقة باستخدام Bootstrap
    var modal = new bootstrap.Modal(editModal);
    modal.show();
  });

  // إرسال البيانات لتعديل العضو
  document
    .querySelector("#saveEditMember")
    .addEventListener("click", function () {
      const id = editBtn.dataset.id; // الحصول على معرّف العضو من زر التعديل
      if (!id) return;

      // جمع البيانات من نافذة التعديل
      const account_name = editModal.querySelector(
        'input[name="account_name"]'
      ).value;
      const account_type = editModal.querySelector(
        'select[name="account_type"]'
      ).value;
      const account_number = editModal.querySelector(
        'input[name="account_number"]'
      ).value;
      const mobile_number = editModal.querySelector(
        'input[name="mobile_number"]'
      ).value;
      const address = editModal.querySelector('textarea[name="address"]').value;

      // التحقق من أن جميع الحقول تحتوي على قيم
      if (
        !account_name ||
        !account_type ||
        !account_number ||
        !mobile_number ||
        !address
      ) {
        alert("جميع الحقول مطلوبة!");
        return;
      }

      // جمع البيانات في FormData
      const formData = new FormData();
      formData.append("id", id);
      formData.append("account_name", account_name);
      formData.append("account_type", account_type);
      formData.append("account_number", account_number);
      formData.append("mobile_number", mobile_number);
      formData.append("address", address);

      // إضافة هذا السطر للتحقق من البيانات في وحدة التحكم
      console.log(
        "FormData:",
        account_name,
        account_type,
        account_number,
        mobile_number,
        address
      );

      // إرسال البيانات إلى الخادم لتعديل العضو
      fetch("../config/update_member.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // تحديث الجدول في الصفحة
            selectedRow.cells[1].textContent = data.account_name;
            selectedRow.cells[5].textContent = data.account_type;
            selectedRow.cells[8].textContent = data.account_number;
            selectedRow.cells[9].textContent = data.mobile_number;
            selectedRow.cells[10].textContent = data.address;

            // إغلاق النافذة
            const modal = bootstrap.Modal.getInstance(editModal);
            modal.hide();
            alert("تم تعديل البيانات بنجاح!");
          } else {
            alert(data.message || "حدث خطأ أثناء التعديل.");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("حدث خطأ أثناء الاتصال بالخادم.");
        });
    });
});
// نهاية تعديل الاعضاء

// بداية الحذف

// نهاية الحذف
