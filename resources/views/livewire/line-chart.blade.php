<div
    x-data="lineChart({
        labels: @js($labels),
        values: @js($values),
        title: @js($title),
    })"
    x-init="init()"
    wire:ignore
    class="w-full rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
    style="height: 320px;"
>
    <div class="mb-2 text-sm font-medium text-zinc-700 dark:text-zinc-200" x-text="title"></div>

    <canvas x-ref="canvas" class="w-full h-full"></canvas>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('lineChart', (cfg) => ({
                chart: null,
                labels: cfg.labels ?? [],
                values: cfg.values ?? [],
                title: cfg.title ?? 'Line Chart',

                init() {
                    // Restore from localStorage if exists
                    const stored = JSON.parse(localStorage.getItem(this.title + '-chart-data'));
                    if (stored) {
                        this.labels = stored.labels ?? this.labels;
                        this.values = stored.values ?? this.values;
                    }

                    const ctx = this.$refs.canvas.getContext('2d');
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
                    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

                    this.chart = new window.Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.labels,
                            datasets: [{
                                label: this.title,
                                data: this.values,
                                tension: 0.35,
                                borderWidth: 2,
                                backgroundColor: gradient,
                                borderColor: 'rgb(99, 102, 241)',
                                fill: true,
                                pointRadius: 3,
                                pointHoverRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: true },
                            },
                            scales: {
                                x: { grid: { display: false } },
                                y: { beginAtZero: true, grid: { drawBorder: false } }
                            }
                        }
                    });

                    // Watchers to update chart + persist data
                    this.$watch('labels', (val) => {
                        this.chart.data.labels = val;
                        this.chart.update();
                        this.saveState();
                    });

                    this.$watch('values', (val) => {
                        this.chart.data.datasets[0].data = val;
                        this.chart.update();
                        this.saveState();
                    });
                },

                saveState() {
                    localStorage.setItem(this.title + '-chart-data', JSON.stringify({
                        labels: this.labels,
                        values: this.values,
                    }));
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
