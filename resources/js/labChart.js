export default function labChart(labels, operational, nonOperational) {
    return {
        chart: null,
        init() {
            const ctx = this.$refs.canvas.getContext('2d');

            if (this.chart) {
                this.chart.destroy();
            }

            this.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Operational',
                            data: operational,
                            backgroundColor: 'rgba(34,197,94,0.8)',
                        },
                        {
                            label: 'Non-Operational',
                            data: nonOperational,
                            backgroundColor: 'rgba(239,68,68,0.8)',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: { color: '#000' },
                        },
                        title: {
                            display: true,
                            text: 'Operational vs Non-Operational Units per Lab',
                            color: '#000',
                            font: { size: 20, weight: 'lighter' },
                        },
                    },
                    scales: {
                        x: {
                            ticks: { color: '#000' },
                            title: { display: true, text: 'Laboratories', color: '#000' },
                        },
                        y: {
                            ticks: { color: '#000' },
                            title: { display: true, text: 'Units Count', color: '#000' },
                        },
                    },
                },
            });
        },
    };
}
