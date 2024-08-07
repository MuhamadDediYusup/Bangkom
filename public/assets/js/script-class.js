document.getElementById("sidebarToggle").addEventListener("click", function () {
    document.getElementById("sidebar-wrapper").classList.toggle("collapsed");
    document
        .getElementById("page-content-wrapper")
        .classList.toggle("collapsed");
});
