function menuSearch(){
    var menu = document.getElementById("menu");
    if (menu.classList.contains("hidden")) {
        menu.classList.remove("hidden");
        menu.style.right = "0";
    } else {
        menu.style.right = "-200px";
        menu.addEventListener("transitionend", function() {
            menu.classList.add("hidden");
        }, { once: true });
    }
}