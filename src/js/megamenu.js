import { ready } from "./functions";
import { gsap } from "gsap";

const activeClass = "!text-black";

const addHeaderHeightVariable = () => {
    const header = document.querySelector("#masthead");
    if (!header) {
        return window.removeEventListener("resize", addHeaderHeightVariable);
    };
    const headerHeight = header.offsetHeight;
    document.body.style.setProperty("--header-height", `${headerHeight}px`);
}

ready(() => {
    addHeaderHeightVariable();
    window.addEventListener("resize", addHeaderHeightVariable);
})


const animateChevron = (navLink, isOpen) => {
    const chevron = navLink.querySelector("svg");
    const tl = gsap.timeline();
    tl.to(chevron, {
        rotateX: isOpen ? 0 : 180,
        y: isOpen ? 0 : 2,
        duration: 0.5,
        ease: "power2.inOut",
    });
};

const closeAllChevrons = (navLinksWithMegamenu, isOpen) => {
    navLinksWithMegamenu.forEach((navLink) => {
        navLink.classList.remove(activeClass);
        animateChevron(navLink, true);
    });
};

const closeAll = (navLinksWithMegamenu) => {
    navLinksWithMegamenu.forEach((navLink) => {
        navLink.classList.remove(activeClass);
        const megamenu = navLink.parentElement.querySelector(".megamenu");
        const directChild = megamenu.querySelector("div");
        if (!megamenu) return;
        megamenu.classList.add("hidden");
        gsap.set(directChild, { opacity: 0 });
        animateChevron(navLink, true);
    });
};

export const useMegaMenu = () => {
    const header = document.querySelector("#masthead");
    const primaryMenu = document.querySelector("#primary-menu");
    const navItems = primaryMenu.querySelectorAll(".menu-item");
    const navLinks = primaryMenu.querySelectorAll(".menu-item a");
    const navLinksWithMegamenu = primaryMenu.querySelectorAll("a.has-megamenu");
    const searchForm = document.querySelector("#header-search-desktop");
    const searchToggler = document.querySelector("#toggle-desktop-search");
    const navigationWrapper = document.querySelector("#site-navigation");

    searchToggler.addEventListener("click", () => {
        searchForm.classList.remove("hidden");
        gsap.set(searchForm, { y: 15, opacity: 0, scale: 0.95 });
        searchForm.classList.add("flex");
        navigationWrapper.classList.add("opacity-0");
        gsap.to(searchForm, {
            y: 0,
            opacity: 1,
            scale: 1,
            duration: 0.4,
            ease: "power2.inOut",
            onComplete: () => {
                searchForm.querySelector("input").focus();
            },
        });

        // Revert when clicking outside
        // If I don't defer it, the click event will be triggered immediately
        setTimeout(() => {
            window.addEventListener("click", closeSearch);
        }, 10);
    });
    const closeSearch = (event) => {
        const isOutside = !searchForm.contains(event.target);
        if (isOutside) {
            gsap.to(searchForm, {
                y: 15,
                opacity: 0,
                scale: 0.95,
                duration: 0.4,
                ease: "power2.inOut",

                onComplete: () => {
                    searchForm.classList.remove("flex");
                    searchForm.classList.add("hidden");
                },
            });
            navigationWrapper.classList.remove("opacity-0");
            window.removeEventListener("click", closeSearch);
        }
    };

    let activeMegamenu = null;

    if (!header) return false;

    const toggleSubmenu = (submenu, isOpen, initialHeight) => {
        if (!submenu) return;
        const tl = gsap.timeline();
        if (!isOpen) {
            submenu.classList.remove("hidden");
            const directChild = submenu.querySelector("div");
            tl.set(directChild, { opacity: 0 });
            tl.from(submenu, {
                height: initialHeight || 0,
                duration: 0.5,
                ease: "power2.inOut",
                onComplete: () => {
                    gsap.set(submenu, { clearProps: "height" });
                },
            });
            tl.to(
                directChild,
                {
                    opacity: 1,
                    duration: 0.2,
                    ease: "power2.inOut",
                },
                "-=0.2"
            );
            activeMegamenu = submenu;
            return;
        }
        gsap.to(submenu, {
            height: 0,
            duration: 0.5,
            ease: "power2.inOut",
            onComplete: () => {
                gsap.set(submenu, { clearProps: "height" });
                submenu.classList.add("hidden");
            },
        });
        activeMegamenu = null;
    };

    window.addEventListener("click", (event) => {
        const isOutside = !header.contains(event.target);
        if (isOutside) {
            toggleSubmenu(activeMegamenu, true);
            // closeAll();
            closeAllChevrons(navLinksWithMegamenu);
        }
    });

    navLinksWithMegamenu.forEach((navLink) => {
        const megamenu = navLink.parentElement.querySelector(".megamenu");
        if (!megamenu) return;
        navLink.addEventListener("click", (event) => {
            event.preventDefault();
            const isOpen = !megamenu.classList.contains("hidden");
            const initialHeight = activeMegamenu?.offsetHeight;
            if (!isOpen) {
                closeAll(navLinksWithMegamenu);
            }
            toggleSubmenu(megamenu, isOpen, initialHeight);
            navLink.classList.toggle(activeClass);
            animateChevron(navLink, isOpen);
        });
    });

    return {
        closeAll,
        toggleSubmenu,
        navLinks,
        navItems,
    };
};

export const useMobileMegaMenu = () => {
    const header = document.querySelector("#masthead");
    const mobileMenuWrapper = document.querySelector(".mobile-navigation");
    const mobileMenu = document.querySelector("#mobile-menu");
    const mobileMenuClose = document.querySelector(".mobile-navigation__close");
    const mobileMenuOpen = document.querySelector(".mobile-navigation__open");
    const navItems = mobileMenu.querySelectorAll(".menu-item");
    const navLinks = mobileMenu.querySelectorAll(".menu-item a");
    const navLinksWithChildren = mobileMenu.querySelectorAll(".has-children");
    const mobileMenuCTA = mobileMenuWrapper.querySelector(".mobile-menu-cta");

    navLinksWithChildren.forEach((item) => {
        const menuLi = item.parentElement;
        const submenu = menuLi.querySelector(".mobile-menu-submenu");

        item.addEventListener("click", (event) => {
            event.preventDefault();
            const isNowOpen = menuLi.classList.toggle("open");
            console.log(isNowOpen);
            if (isNowOpen) {
                submenu.classList.remove("hidden");
                gsap.to(submenu, {
                    height: "auto",
                    duration: 0.5,
                    ease: "power2.inOut",
                });
            } else {
                gsap.to(submenu, {
                    height: 0,
                    duration: 0.5,
                    ease: "power2.inOut",
                    onComplete: () => {
                        gsap.set(submenu, { clearProps: "height" });
                        submenu.classList.add("hidden");
                    },
                });
            }
            animateChevron(item, !isNowOpen);
            item.classList.toggle("!text-black");
        });
    });

    mobileMenuClose.addEventListener("click", (event) => {
        const timeline = gsap.timeline();
        timeline.to(mobileMenuCTA, {
            y: 50,
            opacity: 0,
            duration: 0.2,
            ease: "power2.inOut",
        });
        timeline.to(mobileMenuWrapper, {
            height: 0,
            duration: 0.5,
            ease: "power2.inOut",
            onComplete: () => {
                gsap.set(mobileMenuWrapper, { clearProps: "height" });
                mobileMenuWrapper.classList.add("hidden");
            },
        });
    });

    mobileMenuOpen.addEventListener("click", (event) => {
        gsap.set(mobileMenuWrapper, { height: 0 });
        mobileMenuWrapper.classList.remove("hidden");
        const timeline = gsap.timeline();
        timeline.set(mobileMenuCTA, { y: 50, opacity: 0 });
        timeline.to(mobileMenuWrapper, {
            height: "auto",
            duration: 0.5,
            ease: "power2.inOut",
        });
        timeline.from(
            navLinks,
            {
                y: 50,
                opacity: 0,
                duration: 0.2,
                ease: "power2.inOut",
                stagger: 0.01,
            },
            "-=0.3"
        );
        timeline.to(
            mobileMenuCTA,
            {
                y: 0,
                opacity: 1,
                duration: 0.3,
                ease: "power2.inOut",
            },
            "-=0.1"
        );
    });
};