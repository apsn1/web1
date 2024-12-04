document.addEventListener('DOMContentLoaded', () => {
    const contactInfo = document.getElementById('contactInfo');

    // ตัวแปรเก็บสถานะ
    let currentButton = null;

    // ฟังก์ชันสำหรับจัดการการแสดง/ซ่อน
    function toggleInfo(content, buttonId) {
        if (currentButton === buttonId) {
            // ถ้าปุ่มเดิมถูกกด ให้ซ่อนข้อความ
            contactInfo.classList.add('hidden');
            currentButton = null; // รีเซ็ตสถานะ
        } else {
            // แสดงข้อความใหม่
            contactInfo.textContent = content;
            contactInfo.classList.remove('hidden');
            currentButton = buttonId; // บันทึกปุ่มที่ถูกกด
        }
    }
//-------------------------------------------------------------//

    // การคลิกปุ่มเบอร์ติดต่อ
    document.getElementById('phoneButton').addEventListener('click', () => {
        toggleInfo('เบอร์ติดต่อ: 08x-xxx-xxxx', 'phoneButton');
    });

    // การคลิกปุ่มไอดีไลน์
    document.getElementById('lineButton').addEventListener('click', () => {
        toggleInfo('ไอดีไลน์: @exampleline', 'lineButton');
    });
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

// ฟังก์ชันตรวจสอบตำแหน่งการเลื่อนหน้า
document.addEventListener('DOMContentLoaded', () => {
    const bgElement = document.getElementById('mainNav'); // เลือก Navbar ที่ต้องการเปลี่ยนพื้นหลัง
    const scrollThreshold = 100; // ระยะเลื่อนที่พื้นหลังจะกลับมาชัดเต็มที่

    window.addEventListener('scroll', () => {
        const scrollPosition = window.scrollY;

        if (scrollPosition > scrollThreshold) {
            bgElement.classList.remove('transparent'); // เอาฟิลเตอร์โปร่งใสออก
        } else {
            bgElement.classList.add('transparent'); // ทำให้โปร่งใส
        }
    });
});

//-------------------------------------------------------------//

window.addEventListener('DOMContentLoaded', event => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }



    };

    // Shrink the navbar 
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener('scroll', navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            rootMargin: '0px 0px -40%',
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

});


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

