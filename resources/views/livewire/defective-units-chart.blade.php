<div
    x-data="barChart({
        labels: @js($labels),
        values: @js($values),
        title: @js($title),
    })"
    x-init="init()"
    class="w-full rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
    style="height: 320px;"
>
    <div class="mb-2 text-sm font-medium text-zinc-700 dark:text-zinc-200" x-text="title"></div>

    <canvas x-ref="canvas" class="w-full h-full"></canvas>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('barChart', (cfg) => ({
                chart: null,
                labels: cfg.labels ?? [],
                values: cfg.values ?? [],
                title: cfg.title ?? 'Bar Chart',

                init() {
                    const ctx = this.$refs.canvas.getContext('2d');

                    this.chart = new window.Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: this.labels,
                            datasets: [{
                                label: this.title,
                                data: this.values,
                                borderRadius: 6,
                                borderWidth: 1,
                                backgroundColor: 'rgba(239, 68, 68, 0.7)', // red-ish
                                borderColor: 'rgb(239, 68, 68)',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: true },
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 },
                                    grid: { drawBorder: false },
                                },
                            }
                        }
                    });
                },

                destroy() {
                    if (this.chart) {
                        this.chart.destroy();
                        this.chart = null;
                    }
                },
            }));
        });
    </script>
</div>
