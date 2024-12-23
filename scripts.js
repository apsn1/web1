
//-------------------------------------------------------------//


    // จัดการการแสดง/ซ่อนข้อมูล
    document.getElementById("phoneButton").addEventListener("click", function () {
        const contactInfo = document.getElementById("contactInfo");
        if (contactInfo.classList.contains("hidden")) {
            contactInfo.classList.remove("hidden");
        } else {
            contactInfo.classList.add("hidden");
        }
    });

    document.getElementById("lineButton").addEventListener("click", function () {
        const contactInfo = document.getElementById("contactInfo");
        if (contactInfo.classList.contains("hidden")) {
            contactInfo.classList.remove("hidden");
        } else {
            contactInfo.classList.add("hidden");
        }
    });


//-------------------------------------------------------------//

document.addEventListener('DOMContentLoaded', () => {
    const dropdownMenu = document.querySelector('.dropdown-menu');
    const items = dropdownMenu.querySelectorAll('li');

    // ตรวจสอบจำนวนรายการ
    if (items.length > 10) {
        dropdownMenu.classList.add('scrollable');
    }
});
//-------------------------------------------------------------//

const maxLength = 50; // จำนวนตัวอักษรสูงสุดที่จะแสดง

document.querySelectorAll('.dropdown-item').forEach((item) => {
    if (item.textContent.length > 40) {
        item.setAttribute('title', item.textContent); // ใส่ข้อความเต็มใน title
        item.textContent = item.textContent.slice(0, 40) + '...'; // ตัดข้อความ
    }
});

// เพิ่มการแจ้งเตือน
function showNotification() {
    const icon = document.getElementById('notification-icon');
    icon.classList.add('notify');
    
    // ลบคลาสแจ้งเตือนหลังจาก 3 วินาที
    setTimeout(() => {
      icon.classList.remove('notify');
    }, 3000);
  }
  
  // เรียกใช้ฟังก์ชันนี้เมื่อคุณต้องการแสดงการแจ้งเตือน
  showNotification();
  
  // รับ Element ปุ่ม
const scrollToTopButton = document.getElementById('scrollToTop');

// ฟังก์ชันแสดง/ซ่อนปุ่ม
window.addEventListener('scroll', () => {
    if (window.scrollY > 300) { // เลื่อนถึง 300px
        scrollToTopButton.style.display = 'block';
    } else {
        scrollToTopButton.style.display = 'none';
    }
});
//-----------------------------------------------------------------------------------------------------------/

        // เมื่อคลิกที่รูป ให้เปิดตัวเลือกไฟล์
        document.getElementById('preview').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        // แสดงตัวอย่างรูปที่เลือก
        function previewImage(event) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
        }
    

          // JavaScript function to change the image
          function changeImageById(index) {
            const bannerImage = document.getElementById('bannerImage');
            bannerImage.src = images[index]; // Change the image source
        }


     