/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */
("use strict");

document.addEventListener("DOMContentLoaded", function () {
    // Get current URL path
    const pathArray = window.location.pathname.split("/");

    // Extract module and lesson IDs from the URL
    const moduleId = pathArray[4];
    const lessonId = pathArray[5];

    // Close all accordions first
    const accordionBodies = document.querySelectorAll(".accordion-body");
    accordionBodies.forEach(function (accordionBody) {
        accordionBody.classList.remove("show");
    });

    // Open the correct accordion
    const accordionBody = document.getElementById("panel-body-" + moduleId);
    if (accordionBody) {
        accordionBody.classList.add("show"); // Open the accordion
        const accordionHeader = accordionBody.previousElementSibling;
        if (accordionHeader) {
            accordionHeader.setAttribute("aria-expanded", "true");
        }
    }

    // Highlight the active lesson
    const lessonLinks = document.querySelectorAll(".lesson-link");
    lessonLinks.forEach(function (link) {
        if (
            link.getAttribute("data-module-id") === moduleId &&
            link.getAttribute("data-lesson-id") === lessonId
        ) {
            link.classList.add("active"); // Highlight the active lesson
            link.style.fontWeight = "bold"; // Optional: Make the active lesson bold
        }
    });
});

function resizeIframe(obj) {
    obj.style.height =
        obj.contentWindow.document.documentElement.scrollHeight + "px";
}

window.addEventListener("load", function () {
    var iframes = document.querySelectorAll(".responsive-iframe");
    iframes.forEach(function (iframe) {
        iframe.onload = function () {
            resizeIframe(this);
        };
    });
});
