const sideMenu = document.querySelector("aside");
const menuBtn = document.getElementById("menu-btn");
const closeBtn = document.getElementById("close-btn");
const sideBar = document.querySelector("aside .sidebar a");

const darkMode = document.querySelector(".dark-mode");

menuBtn.addEventListener("click", () => {
  sideMenu.style.display = "block";
});

closeBtn.addEventListener("click", () => {
  sideMenu.style.display = "none";
});

darkMode.addEventListener("click", () => {
  document.body.classList.toggle("dark-mode-variables");
  darkMode.querySelector("span:nth-child(1)").classList.toggle("active");
  darkMode.querySelector("span:nth-child(2)").classList.toggle("active");
});

sideBar.addEventListener("click", () => {
  console.log("Hello");
  sideBar.classList.remove("active");
});

const navbarItems = document.querySelectorAll(".navbar-item");

// Tambahkan event listener untuk setiap elemen navbar
navbarItems.forEach((item) => {
  item.addEventListener("click", function () {
    // Hapus kelas active dari semua elemen navbar
    navbarItems.forEach((item) => {
      item.classList.remove("active");
    });
    // Tambahkan kelas active ke elemen yang diklik
    this.classList.add("active");
  });
});

Orders.forEach((order) => {
  const tr = document.createElement("tr");
  const trContent = `
        <td>${order.productName}</td>
        <td>${order.productNumber}</td>
        <td>${order.paymentStatus}</td>
        <td class="${
          order.status === "Declined"
            ? "danger"
            : order.status === "Pending"
            ? "warning"
            : "primary"
        }">${order.status}</td>
        <td class="primary">Details</td>
    `;
  tr.innerHTML = trContent;
  document.querySelector("table tbody").appendChild(tr);
});

// js buat
// Choese image
