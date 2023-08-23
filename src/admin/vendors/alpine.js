import Alpine from "alpinejs";
import links from "./components/links";
import { analyticsFilter } from "./components/analytics-filter";
window.Alpine = Alpine;

Alpine.data("dataList", (initLinks = []) => links(initLinks));
Alpine.data("analyticsFilter", () => analyticsFilter());
Alpine.start();
