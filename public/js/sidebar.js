document.addEventListener("DOMContentLoaded", function () {
  const hamburgerBtn = document.getElementById("hamburgerBtn");
  const sidebar = document.getElementById("sidebar");
  const links = document.querySelectorAll(".sidebar-link");

  if (hamburgerBtn && sidebar) {
      hamburgerBtn.addEventListener("click", () => {
          sidebar.classList.toggle("-translate-x-full");
      });
  }

  links.forEach(link => {
      link.addEventListener("click", function () {
          links.forEach(l => l.classList.remove(
              "bg-yellow-400", "text-black", "font-medium", "rounded", "shadow", "px-2", "py-1"
          ));

          this.classList.add("bg-yellow-400", "text-black", "font-medium", "rounded", "shadow", "px-2", "py-1");
      });
  });
});