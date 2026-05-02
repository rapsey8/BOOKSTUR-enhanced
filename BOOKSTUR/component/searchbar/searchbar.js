document.addEventListener("DOMContentLoaded", function () {
  const searchContainer = document.querySelector(".search-box");

  if (window.location.pathname.includes("dashboard.php")) {
    if (searchContainer) {
      searchContainer.remove();
    }
  }
});
