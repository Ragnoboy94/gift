import 'bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();

function initTooltips() {
    const tooltipContainers = document.querySelectorAll(".tooltip-container");
    tooltipContainers.forEach(container => {
        const tooltipText = container.querySelector(".tooltip-text");
        const tooltipTitle = container.getAttribute("data-bs-title");
        tooltipText.textContent = tooltipTitle;
        const showTooltip = () => {
            tooltipText.style.visibility = "visible";
            tooltipText.style.opacity = "1";
        };

        const hideTooltip = () => {
            tooltipText.style.visibility = "hidden";
            tooltipText.style.opacity = "0";
        };

        container.addEventListener("click", showTooltip);
        container.addEventListener("touchstart", showTooltip);
        container.addEventListener("mouseleave", hideTooltip);
        container.addEventListener("touchend", hideTooltip);
    });
}

