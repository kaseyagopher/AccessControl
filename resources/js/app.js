// Menu sidebar mobile + indicateur de chargement
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggle = document.getElementById('sidebar-toggle');
    const loadingOverlay = document.getElementById('loading-overlay');

    if (sidebar && overlay && toggle) {
        const open = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        };

        const close = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };

        toggle.addEventListener('click', open);
        overlay.addEventListener('click', close);

        sidebar.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) close();
            });
        });
    }

    const showLoading = () => {
        if (!loadingOverlay) return;
        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');
    };

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => {
            if (form.dataset.noLoading !== 'true') {
                showLoading();
            }
        });
    });

    document.querySelectorAll('a[href]').forEach((link) => {
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.target === '_blank') {
            return;
        }
        if (link.origin !== window.location.origin) {
            return;
        }
        link.addEventListener('click', () => showLoading());
    });
});
