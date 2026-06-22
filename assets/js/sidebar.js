// Función para mostrar/ocultar el sidebar y el overlay
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  if (!sidebar) return;

  //Obtenemos el overlay
  const overlay = document.getElementById("sidebar-overlay") || document.querySelector(".overlay");

  //Adaptamos el comportamiento según el tamaño de pantalla
  if (window.innerWidth >= 768) {
    sidebar.classList.toggle('toggled');
    document.body.classList.toggle('sidebar-collapsed');
    return;
  }

  //Comportamiento Móvil (< 768px)
  sidebar.classList.toggle("active");
  if (overlay) {
    overlay.classList.toggle("show");
  }
  document.body.classList.toggle('sidebar-open'); // Por si manejas scroll en el body
}

//Aseguramos que los listeners se registran una vez el DOM está listo
document.addEventListener('DOMContentLoaded', function () {
  const menuToggle = document.getElementById("menu-toggle");
  const overlay = document.getElementById("sidebar-overlay") || document.querySelector(".overlay");

  if (menuToggle) menuToggle.addEventListener('click', toggleSidebar);
  if (overlay) overlay.addEventListener('click', toggleSidebar);
});