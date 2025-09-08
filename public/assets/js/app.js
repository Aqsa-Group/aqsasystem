const menuBtn = document.getElementById("menu-btn");
const sidebar = document.getElementById("sidebar");
const sidebarTexts = document.querySelectorAll(".sidebar-text");
const sidebarTitle = document.getElementById("sidebar-title");

const productsBtn = document.getElementById("products-btn");
const productsSubmenu = document.getElementById("products-submenu");
const productsArrow = document.getElementById("products-arrow");

const sidebarLinks = document.querySelectorAll(".sidebar-link");

let collapsed = false;

function setActiveLink(activeLink) {
  sidebarLinks.forEach(link => {
    link.style.backgroundColor = "";   
    link.style.color = "";             
  });
  activeLink.style.backgroundColor = "#212b36"; 
  activeLink.style.color = "#ffffff"; 
}

document.addEventListener("DOMContentLoaded", () => {
  const dashboardLink = sidebarLinks[0]; 
  setActiveLink(dashboardLink);
});

sidebarLinks.forEach(link => {
  link.addEventListener("click", () => {
    setActiveLink(link);
  });
});

menuBtn.addEventListener("click", () => {
  collapsed = !collapsed;
  if (collapsed) {
    sidebar.classList.remove("w-64");
    sidebar.classList.add("w-20");
    sidebarTexts.forEach(t => t.classList.add("hidden"));
    sidebarTitle.classList.add("hidden");
    productsSubmenu.classList.add("hidden"); 
    productsArrow.style.display = "none"; 
  } else {
    sidebar.classList.remove("w-20");
    sidebar.classList.add("w-64");
    sidebarTexts.forEach(t => t.classList.remove("hidden"));
    sidebarTitle.classList.remove("hidden");
    productsArrow.style.display = "inline-flex";
  }
});

productsBtn.addEventListener("click", () => {
  if (collapsed) return; 
  productsSubmenu.classList.toggle("hidden");
  productsArrow.classList.toggle("rotate-180"); 
});

window.addEventListener("beforeunload", () => {
  document.getElementById("loader").classList.remove("hidden");
});

// lang

document.getElementById('lang-btn').addEventListener('click', function () {
      document.getElementById('lang-menu').classList.toggle('hidden');
  });


// clock
function updateClock() {
const locale = "{{ session('locale', 'fa') }}"; // 'fa', 'ps', 'en'
const now = new Date();
const j = jalaali.toJalaali(now.getFullYear(), now.getMonth() + 1, now.getDate());

// ساعت ۱۲ ساعته
let hours = now.getHours();
const minutes = now.getMinutes();
const seconds = now.getSeconds();

let ampm = hours >= 12 ? 'بعد از ظهر' : 'قبل از ظهر';
if (locale === 'en') {
    ampm = hours >= 12 ? 'PM' : 'AM';
}

hours = hours % 12;
hours = hours ? hours : 12; // اگر صفر بود، ۱۲ نمایش بده

const timeStr = hours.toString().padStart(2,'0') + ':' +
                minutes.toString().padStart(2,'0') + ':' +
                seconds.toString().padStart(2,'0') + ' ' + ampm;

const dateStr = j.jy + '/' + j.jm.toString().padStart(2,'0') + '/' + j.jd.toString().padStart(2,'0');

const clockDiv = document.getElementById('jalali-clock');

// همه زبان‌ها به صورت flex با فاصله مشابه
clockDiv.innerHTML = `<div class="flex justify-between">${dateStr}<span>${timeStr}</span></div>`;
}
setInterval(updateClock, 1000);
updateClock();