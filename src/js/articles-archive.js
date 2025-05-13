import { ScrollTrigger } from "gsap/ScrollTrigger";

export const syncSectionsWithNav = () => {
    const activeClasses = ["border-b-4","border-accent"];
    const sections = document.querySelectorAll("section[id]");
    const navAnchors = document.querySelectorAll("nav a[href^='#']");
    const sectionNavPairs = {};

    sections.forEach((section) => {
        const id = section.id;
        const navAnchor = Array.from(navAnchors).find((nav) =>
            nav.getAttribute("href").includes("#" + id)
        );
        sectionNavPairs[id] = {
            section,
            navAnchor,
        };
    });

    const resetNavAnchors = () => {
        navAnchors.forEach((anchor) => {
            activeClasses.forEach((cls) => anchor.classList.remove(cls));
        });
    };

    sections.forEach((section) => {

        const syncNav = () => {
            const { navAnchor } = sectionNavPairs[section.id];
            activeClasses.forEach((cls) => navAnchor.classList.add(cls));
        };

        const handleEnter = () => {
            resetNavAnchors();
            syncNav();
        }

        ScrollTrigger.create({
            trigger: section,
            start: "top 80%",
            end: "bottom 40%",
            onEnter: handleEnter,
            onEnterBack: handleEnter,
        });
    });
};
