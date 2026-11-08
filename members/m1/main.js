document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll(".navbar nav a");

    function updateActiveLink() {
        const activeHash = window.location.hash || "#home";
        navLinks.forEach(function (link) {
            if (link.hash === activeHash) {
                link.setAttribute("aria-current", "location");
            } else {
                link.removeAttribute("aria-current");
            }
        });
    }

    window.addEventListener("hashchange", updateActiveLink);
    updateActiveLink();

    const lightbox = document.getElementById("projectLightbox");
    const lightboxImage = document.getElementById("lightboxImage");
    const lightboxCaption = document.getElementById("lightboxCaption");
    const closeButton = lightbox.querySelector(".lightbox-close");
    const projectImageButtons = document.querySelectorAll(".project-image-button");
    let lastFocusedElement = null;

    function openLightbox(button) {
        const image = button.querySelector("img");

        lastFocusedElement = button;
        lightboxImage.src = image.src;
        lightboxImage.alt = image.alt;
        lightboxCaption.textContent = image.alt;
        lightbox.hidden = false;
        lightbox.setAttribute("aria-hidden", "false");
        document.body.classList.add("lightbox-open");
        closeButton.focus();
    }

    function closeLightbox() {
        lightbox.hidden = true;
        lightbox.setAttribute("aria-hidden", "true");
        document.body.classList.remove("lightbox-open");
        lightboxImage.src = "";

        if (lastFocusedElement) {
            lastFocusedElement.focus();
        }
    }

    projectImageButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            openLightbox(button);
        });
    });

    closeButton.addEventListener("click", closeLightbox);

    lightbox.addEventListener("click", function (event) {
        if (event.target === lightbox) {
            closeLightbox();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && !lightbox.hidden) {
            closeLightbox();
        }
    });

});
