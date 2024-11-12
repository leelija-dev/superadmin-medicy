document.addEventListener("DOMContentLoaded", function () {
    const Nsidebar = document.querySelector(".Nsidebar");
    const expandSidebarBtn = document.getElementById("NexpandSidebarBtn");
    const leftIcon = expandSidebarBtn.querySelector(".fa-chevron-left");
    const rightIcon = expandSidebarBtn.querySelector(".fa-chevron-right");
    const logoFull = document.querySelector(".logo .logo-full");
    const logoFavicon = document.querySelector(".logo .logo-favicon");
    let sidebarExpanded = true; // Start with the sidebar expanded

    // Set the initial width and logo based on the screen size
    function setInitialSidebarWidth() {
        if (window.innerWidth <= 768) {
            Nsidebar.style.width = "8rem"; // Set for mobile
            sidebarExpanded = false;
            Nsidebar.classList.add("collapsed"); // Add class when sidebar is collapsed
            updateIconVisibility();
        } else {
            Nsidebar.style.width = "17rem"; // Set for desktop
            sidebarExpanded = true;
            Nsidebar.classList.remove("collapsed"); // Remove class when expanded
            updateIconVisibility();
        }
        updateLogoVisibility(); // Update logo visibility based on sidebar width
    }

    // Function to update icon visibility based on sidebar state
    function updateIconVisibility() {
        if (sidebarExpanded) {
            leftIcon.style.display = "inline"; // Show left icon
            rightIcon.style.display = "none"; // Hide right icon
        } else {
            leftIcon.style.display = "none"; // Hide left icon
            rightIcon.style.display = "inline"; // Show right icon
        }
    }

    // Function to update logo visibility based on sidebar width
    function updateLogoVisibility() {
        if (Nsidebar.style.width === "8rem") {
            logoFull.style.display = "none"; // Hide full logo
            logoFavicon.style.display = "inline"; // Show favicon
        } else {
            logoFull.style.display = "inline"; // Show full logo
            logoFavicon.style.display = "none"; // Hide favicon
        }
    }

    // Toggle sidebar width between expanded and collapsed
    function toggleSidebarWidth() {
        if (window.innerWidth > 768) { // Only apply if not on mobile
            sidebarExpanded = !sidebarExpanded;
            Nsidebar.style.width = sidebarExpanded ? "17rem" : "8rem";

            // Add/remove the 'collapsed' class based on sidebar width
            if (!sidebarExpanded) {
                Nsidebar.classList.add("collapsed");
                closeAllSubmenus();
            } else {
                Nsidebar.classList.remove("collapsed");
            }

            // Update icon and logo visibility
            updateIconVisibility();
            updateLogoVisibility();
        }
    }

    // Function to close all open submenus and reset arrow icons
    function closeAllSubmenus() {
        document.querySelectorAll(".Nsubmenu.show").forEach(submenu => submenu.classList.remove("show"));
        document.querySelectorAll(".NSarrow").forEach(icon => icon.classList.remove("rotate")); // Remove rotation from all arrows
    }

    // Toggle submenus based on screen size and sidebar state
    document.querySelectorAll(".Nmenu-item").forEach((menuItem) => {
        menuItem.addEventListener("click", function () {
            const submenu = this.nextElementSibling;
            const arrowIcon = this.querySelector(".NSarrow"); // Arrow icon in the clicked menu item

            // Mobile: Directly toggle submenu without expanding sidebar
            if (window.innerWidth <= 768) {
                if (submenu) {
                    if (submenu.classList.contains("show")) {
                        submenu.classList.remove("show"); // Close submenu if it's already open
                        if (arrowIcon) arrowIcon.classList.remove("rotate");
                    } else {
                        closeAllSubmenus(); // Close any other open submenu
                        submenu.classList.add("show"); // Open the clicked submenu
                        if (arrowIcon) arrowIcon.classList.add("rotate");
                    }
                }
                return; // Exit early for mobile
            }

            // Desktop: Expand sidebar if collapsed, then toggle submenu
            if (window.innerWidth > 768) {
                if (!sidebarExpanded) {
                    Nsidebar.style.width = "17rem"; // Expand sidebar
                    sidebarExpanded = true; // Update state
                    Nsidebar.classList.remove("collapsed");
                    closeAllSubmenus(); // Close all submenus
                    if (submenu) submenu.classList.add("show"); // Show current submenu
                    if (arrowIcon) arrowIcon.classList.add("rotate");
                } else {
                    if (submenu.classList.contains("show")) {
                        submenu.classList.remove("show"); // Close submenu
                        if (arrowIcon) arrowIcon.classList.remove("rotate");
                    } else {
                        closeAllSubmenus(); // Close any other open submenu
                        submenu.classList.add("show"); // Open clicked submenu
                        if (arrowIcon) arrowIcon.classList.add("rotate");
                    }
                }
            }

            // Update icon visibility after submenu toggle
            updateIconVisibility();
        });
    });

    // Manually toggle sidebar width with expand button
    expandSidebarBtn.addEventListener("click", toggleSidebarWidth);

    // Ensure correct sidebar width, logo, and submenu state on window resize
    window.addEventListener("resize", function () {
        setInitialSidebarWidth(); // Set sidebar width based on current window size
    });

    // Set initial sidebar width and logo on page load
    setInitialSidebarWidth();
});
