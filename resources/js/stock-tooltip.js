document.addEventListener('alpine:init', () => {
    Alpine.data('stockTooltip', () => ({
        bubble: null,
        arrow: null,

        init() {
            // Create global tooltip bubble
            this.bubble = document.createElement('div');
            this.bubble.className = 'text-white px-3 py-1 rounded-r-xl shadow-lg absolute z-50 animate-fade-in';
            this.bubble.style.position = 'absolute';
            this.bubble.style.whiteSpace = 'nowrap';
            this.bubble.style.display = 'none';
            document.body.appendChild(this.bubble);

            // Create global arrow
            this.arrow = document.createElement('div');
            this.arrow.style.position = 'absolute';
            this.arrow.style.width = '0';
            this.arrow.style.height = '0';
            document.body.appendChild(this.arrow);
        },

        show(event) {
            const td = event.currentTarget;
            const available = parseInt(td.dataset.available, 10);
            const description = td.dataset.description;

            let bgColor = '#facc15';
            let statusText = 'Low stock';
            if (available === 0) {
                bgColor = '#dc2626';
                statusText = 'Out of stock';
            } else if (available >= 5) {
                bgColor = '#16a34a';
                statusText = 'In Stock';
            }

            // Bubble content & style
            this.bubble.innerText = `${description} — ${statusText} (${available} left)`;
            this.bubble.style.backgroundColor = bgColor;
            this.bubble.style.display = 'inline-block';
            this.bubble.style.top = (td.getBoundingClientRect().top + window.scrollY + 10) + 'px';
            this.bubble.style.left = (td.getBoundingClientRect().right + window.scrollX - 45) + 'px';

            // Arrow
            this.arrow.style.borderTop = '6px solid transparent';
            this.arrow.style.borderBottom = '6px solid transparent';
            this.arrow.style.borderRight = `6px solid ${bgColor}`;
            this.arrow.style.top = (td.getBoundingClientRect().top + window.scrollY + td.offsetHeight / 2 - 6) + 'px';
            this.arrow.style.left = (td.getBoundingClientRect().right + window.scrollX - 55) + 'px';
            this.arrow.style.display = 'block';
        },

        hide() {
            this.bubble.style.display = 'none';
            this.arrow.style.display = 'none';
        }
    }));
});
