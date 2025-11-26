
document.addEventListener("DOMContentLoaded", () => {
  const links = document.querySelectorAll(".tab-link");
  const tabs = document.querySelectorAll(".tab-content");


  links.forEach(link => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const target = link.getAttribute("data-tab");


      tabs.forEach(tab => tab.classList.remove("active"));

      document.getElementById(target).classList.add("active");

    
      links.forEach(l => l.classList.remove("active"));
      link.classList.add("active");
    });
  });
});
