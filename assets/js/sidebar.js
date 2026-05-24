const menuToggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

//Función para mostrar/ocultar el sidebar y el overlay
function toggleSidebar() {
  sidebar.classList.toggle("active");
  overlay.classList.toggle("show");
}

menuToggle.addEventListener("click", toggleSidebar);
overlay.addEventListener("click", toggleSidebar);