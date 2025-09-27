<div x-data="{
    chart: null,
    init() {
        const ctx = this.$refs.canvas.getContext('2d');

        const labels = @js($chartData->pluck('lab')->values());
        const operational = @js($chartData->pluck('operational')->values());
        const nonOperational = @js($chartData->pluck('non_operational')->values());

        console.log('Labels:', labels);
        console.log('Operational:', operational);
        console.log('Non-Operational:', nonOperational);


        if (this.chart) {
            this.chart.destroy();
        }

        this.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Operational',
                        data: operational,
                        backgroundColor: 'rgba(34,197,94,0.8)',
                    },
                    {
                        label: 'Non-Operational',
                        data: nonOperational,
                        backgroundColor: 'rgba(239,68,68,0.8)',
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#000',
                        }
                    },
                    title: {
                        display: true,
                        text: 'Operational vs Non-Operational Units per Lab',
                        color: '#000',
                        font: {
                            size: 20, 
                            weight: 'lighter'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#000' },
                        title: { display: true, text: 'Laboratories', color: '#000' }
                    },
                    y: {
                        ticks: { color: '#000' },
                        title: { display: true, text: 'Units Count', color: '#000' }
                    }
                }
            }
        });
    }
}" class="h-96 w-full">
    <canvas x-ref="canvas"></canvas>
</div>
