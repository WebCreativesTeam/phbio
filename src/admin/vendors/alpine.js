import Alpine from "alpinejs";
import links from "./components/links";
import { analyticsFilter } from "./components/analytics-filter";
import socialLinks from "./components/social-links";
window.Alpine = Alpine;

Alpine.data("dataList", (initLinks = []) => links(initLinks));
Alpine.data("socialLinks", (initLinks = []) => socialLinks(initLinks));
Alpine.data("analyticsFilter", () => analyticsFilter());
Alpine.start();
