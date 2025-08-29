const body = document.querySelector("body");
const userMenu = document.getElementById("user-menu");
const dropdownMenu = document.getElementById("dropdown-menu");
const sidebar = document.querySelector(".sidebar");
const sidebarDropdowns = document.querySelectorAll(".sidebar-dropdown");
const sidebarOpen = document.querySelector("#sidebarOpen");
sidebarOpen.addEventListener("click", () => sidebar.classList.toggle("close"));

sidebarDropdowns.forEach((sidebarDropdown) => {
  sidebarDropdown.addEventListener("click", () => {
    sidebarDropdown.classList.toggle("show-subitems");
  });
});



if (window.innerWidth < 768) {
  sidebar.classList.add("close");
} else {
  sidebar.classList.remove("close");
}

userMenu.addEventListener("click", (e) => {
  e.stopPropagation(); // evita conflito com clique fora
  dropdownMenu.style.display =
    dropdownMenu.style.display === "flex" ? "none" : "flex";
});

// Fecha o dropdown se clicar fora
document.addEventListener("click", () => {
  dropdownMenu.style.display = "none";
});
