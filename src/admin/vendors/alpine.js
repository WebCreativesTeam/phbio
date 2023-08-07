import Alpine from 'alpinejs'
import links from './components/links';

window.Alpine = Alpine

Alpine.data('dataList', (initLinks = []) => links(initLinks));
Alpine.start();

