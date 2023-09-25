import Alpine from "alpinejs";
import links from "./components/links";
import { analyticsFilter } from "./components/analytics-filter";
import socialLinks from "./components/social-links-new";
import { dashboard } from "./components/dashboard";
import dropdown from "./components/dropdown";
window.Alpine = Alpine;

Alpine.data("dataList", (initLinks = []) => links(initLinks));
Alpine.data("dropdown", (initIcons = [], selected = "") =>
  dropdown(initIcons, selected)
);
Alpine.data("socialLinks", (initLinks = []) => socialLinks(initLinks));
Alpine.data("analyticsFilter", () => analyticsFilter());
Alpine.data("dashboard", () => dashboard());
Alpine.start();
