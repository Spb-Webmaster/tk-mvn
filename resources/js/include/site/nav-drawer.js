export function navDrawer() {
    const burger  = document.querySelector('.nav-burger');
    const overlay = document.querySelector('.nav-drawer-overlay');
    const drawer  = document.querySelector('.nav-drawer');
    if (!burger || !drawer) return;

    const open = () => {
        drawer.classList.add('is-open');
        overlay.classList.add('is-open');
        burger.classList.add('is-active');
        burger.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    };

    const close = () => {
        drawer.classList.remove('is-open');
        overlay.classList.remove('is-open');
        burger.classList.remove('is-active');
        burger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    };

    burger.addEventListener('click', open);
    overlay.addEventListener('click', close);
    drawer.querySelectorAll('.nav-drawer-links a, .nav-drawer-cta').forEach(link => {
        link.addEventListener('click', close);
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') close();
    });
}
