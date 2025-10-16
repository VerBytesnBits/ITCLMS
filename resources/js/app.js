import './session-alert';
import './stock-tooltip';

import qrScanner from './qr-scanner';
import Chart from 'chart.js/auto';
import labChart from './labChart.js';

window.Chart = Chart; // make Chart globally available

window.qrScanner = qrScanner;


document.addEventListener('alpine:init', () => {
    Alpine.data('labChart', labChart);
});
