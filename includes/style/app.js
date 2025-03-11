document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("toggle-btn");
    const sidebar = document.getElementById("sidebar");
  
    if (toggleButton && sidebar) {
        toggleButton.addEventListener("click", toggleSidebar);
    }
  
    function toggleSidebar() {
        sidebar.classList.toggle("close");
        toggleButton.classList.toggle("rotate");
        closeAllSubMenus();
    }
  
    function toggleSubMenu(button) {
        if (!button.nextElementSibling.classList.contains("show")) {
            closeAllSubMenus();
        }
  
        button.nextElementSibling.classList.toggle("show");
        button.classList.toggle("rotate");
  
        if (sidebar.classList.contains("close")) {
            sidebar.classList.remove("close");
            toggleButton.classList.toggle("rotate");
        }
    }
  
    function closeAllSubMenus() {
        document.querySelectorAll("#sidebar .show").forEach((menu) => {
            menu.classList.remove("show");
            menu.previousElementSibling.classList.remove("rotate");
        });
    }
  
    document.querySelectorAll(".dropdown-btn").forEach((button) => {
        button.addEventListener("click", function () {
            toggleSubMenu(this);
        });
    });
  });