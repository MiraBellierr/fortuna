import { createApp } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import ApexCharts from 'apexcharts';

// We expose the charting libraries globally
// so that Blade templates can initialise charts.
window.createVueApp = createApp;
window.VueApexCharts = VueApexCharts;
window.ApexCharts = ApexCharts;

console.log('Charts integrated. ApexCharts is now available for Vue apps and plain Blade pages.');
