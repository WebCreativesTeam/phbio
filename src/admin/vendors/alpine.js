import Alpine from "alpinejs";
import links from "./components/links";
import { analyticsFilter } from "./components/analytics-filter";
import socialLinks from "./components/social-links";
import { dashboard } from "./components/dashboard";
window.Alpine = Alpine;

Alpine.data("dataList", (initLinks = []) => links(initLinks));
Alpine.data("socialLinks", (initLinks = []) => socialLinks(initLinks));
Alpine.data("analyticsFilter", () => analyticsFilter());
Alpine.data("dashboard", () => dashboard());
Alpine.start();
