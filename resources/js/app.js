import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

document.addEventListener('alpine:init', () => {
    Alpine.data('pillDraw', () => ({
        state: 'idle',
        highlightedNumber: null,
        interval: null,
        assignedNumber: null,
        pillMin: 1,
        pillMax: 999,
        drawnPills: [],

        get allPills() {
            return Array.from({ length: this.pillMax - this.pillMin + 1 }, (_, i) => i + this.pillMin);
        },

        get availablePills() {
            return this.allPills.filter((p) => !this.drawnPills.includes(p));
        },

        get gridColsClass() {
            const count = this.pillMax - this.pillMin + 1;
            if (count <= 30) return 'h-full grid-cols-5 sm:grid-cols-6 auto-rows-fr';
            if (count <= 60) return 'h-full grid-cols-6 sm:grid-cols-8 auto-rows-fr';
            if (count <= 100) return 'h-full grid-cols-8 sm:grid-cols-10 auto-rows-fr';
            if (count <= 250) return 'h-full grid-cols-10 sm:grid-cols-16 md:grid-cols-20 auto-rows-fr';
            return 'h-full grid-cols-15 sm:grid-cols-20 md:grid-cols-25 auto-rows-fr';
        },

        get cellSizeClass() {
            const count = this.pillMax - this.pillMin + 1;
            if (count <= 30) return 'text-2xl';
            if (count <= 60) return 'text-xl';
            if (count <= 100) return 'text-lg';
            if (count <= 250) return 'text-xs';
            return 'text-[10px] leading-none';
        },

        get gridGapClass() {
            const count = this.pillMax - this.pillMin + 1;
            if (count <= 60) return 'gap-2';
            if (count <= 100) return 'gap-1.5';
            if (count <= 250) return 'gap-1';
            return 'gap-px';
        },

        pillClass(num) {
            if (num === this.highlightedNumber && this.state === 'dancing') {
                return 'bg-yellow-400 text-black scale-110 ring-2 ring-yellow-300 shadow-lg shadow-yellow-500/30';
            }
            if (num === this.assignedNumber && this.state === 'stopped') {
                return 'bg-red-600 text-white scale-110 ring-2 ring-red-500 shadow-lg shadow-red-500/30';
            }
            if (this.drawnPills.includes(num)) {
                return 'bg-blue-600 text-white';
            }
            return 'bg-zinc-700 text-white';
        },

        initDraw(detail) {
            const d = detail[0] ?? detail;
            this.state = 'idle';
            this.highlightedNumber = null;
            this.assignedNumber = null;
            this.pillMin = d.pillMin;
            this.pillMax = d.pillMax;
            this.drawnPills = d.drawnPills;
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
        },

        startDancing() {
            if (this.availablePills.length === 0) return;
            this.state = 'dancing';
            this.assignedNumber = null;
            this.interval = setInterval(() => {
                const pills = this.availablePills;
                this.highlightedNumber = pills[Math.floor(Math.random() * pills.length)];
            }, 80);
            this.$wire.broadcastDancing();
        },

        stopDancing() {
            if (this.state !== 'dancing') return;
            clearInterval(this.interval);
            this.interval = null;
            this.state = 'stopped';
            this.assignedNumber = this.highlightedNumber;
            this.$wire.assignPill(this.highlightedNumber).then(() => {
                this.drawnPills.push(this.assignedNumber);
                this.$nextTick(() => {
                    const el = document.getElementById('pill-cell-' + this.assignedNumber);
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
        },

        closeDraw() {
            this.state = 'idle';
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
            this.$wire.exitPillDraw();
        },

        toggle() {
            if (this.state === 'idle') {
                this.startDancing();
            } else if (this.state === 'dancing') {
                this.stopDancing();
            }
        },

        handleKeydown(e) {
            if (!this.$wire.isDrawing) return;
            if (e.target.closest('input, textarea, select, button[type=submit]')) return;
            if (e.key === 'Enter') {
                e.preventDefault();
                this.toggle();
            }
        },
    }));

    Alpine.data('eventPillDraw', () => ({
        mode: 'idle',
        className: '',
        driverName: '',
        carNumber: '',
        assignedPill: null,
        drawnPills: [],
        pillMin: 1,
        pillMax: 500,
        highlightedNumber: null,
        interval: null,

        get allPills() {
            return Array.from({ length: this.pillMax - this.pillMin + 1 }, (_, i) => i + this.pillMin);
        },

        get availablePills() {
            return this.allPills.filter((p) => !this.drawnPills.includes(p));
        },

        get gridColsClass() {
            const count = this.pillMax - this.pillMin + 1;
            if (count <= 30) return 'h-full grid-cols-5 sm:grid-cols-6 auto-rows-fr';
            if (count <= 60) return 'h-full grid-cols-6 sm:grid-cols-8 auto-rows-fr';
            if (count <= 100) return 'h-full grid-cols-8 sm:grid-cols-10 auto-rows-fr';
            if (count <= 250) return 'h-full grid-cols-10 sm:grid-cols-16 md:grid-cols-20 auto-rows-fr';
            return 'h-full grid-cols-15 sm:grid-cols-20 md:grid-cols-25 auto-rows-fr';
        },

        get cellSizeClass() {
            const count = this.pillMax - this.pillMin + 1;
            if (count <= 30) return 'text-3xl';
            if (count <= 60) return 'text-2xl';
            if (count <= 100) return 'text-xl';
            if (count <= 250) return 'text-sm';
            return 'text-[10px] leading-none';
        },

        get gridGapClass() {
            const count = this.pillMax - this.pillMin + 1;
            if (count <= 60) return 'gap-2';
            if (count <= 100) return 'gap-1.5';
            if (count <= 250) return 'gap-1';
            return 'gap-px';
        },

        pillClass(num) {
            if (num === this.highlightedNumber && this.mode === 'dancing') {
                return 'bg-yellow-400 text-black scale-110 ring-2 ring-yellow-300 shadow-lg shadow-yellow-500/30';
            }
            if (num === this.assignedPill && this.mode === 'stopped') {
                return 'bg-red-600 text-white scale-110 ring-2 ring-red-500 shadow-lg shadow-red-500/30';
            }
            if (this.drawnPills.includes(num)) {
                return 'bg-blue-600 text-white';
            }
            return 'bg-zinc-700 text-white';
        },

        handleUpdate(detail) {
            const d = detail[0] ?? detail;
            const newMode = d.mode ?? d.state ?? 'idle';
            this.className = d.className ?? '';
            this.driverName = d.driverName ?? '';
            this.carNumber = d.carNumber ?? '';
            this.assignedPill = d.assignedPill ?? null;
            this.drawnPills = d.drawnPills ?? [];
            this.pillMin = d.pillMin ?? 1;
            this.pillMax = d.pillMax ?? 500;

            if (newMode === 'dancing' && this.mode !== 'dancing') {
                this.startDancing();
            } else if (newMode === 'stopped') {
                this.stopDancing();
            } else if (newMode === 'idle') {
                this.reset();
            }
            this.mode = newMode;
        },

        startDancing() {
            if (this.interval) clearInterval(this.interval);
            this.interval = setInterval(() => {
                const pills = this.availablePills;
                if (pills.length > 0) {
                    this.highlightedNumber = pills[Math.floor(Math.random() * pills.length)];
                }
            }, 80);
        },

        stopDancing() {
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
            this.highlightedNumber = null;
            this.$nextTick(() => {
                if (this.assignedPill) {
                    const el = document.getElementById('pill-cell-' + this.assignedPill);
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        },

        reset() {
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
            this.highlightedNumber = null;
        },
    }));
});
