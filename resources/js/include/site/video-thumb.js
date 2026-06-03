export function initVideoThumb() {
    document.querySelectorAll('.media-video__thumb').forEach(thumb => {
        thumb.addEventListener('click', () => {
            const embedSrc = thumb.dataset.embed;
            if (!embedSrc) return;

            const isRutube = embedSrc.includes('rutube.ru');

            const wrap = document.createElement('div');
            wrap.className = 'media-video__youtube-wrap';

            const iframe = document.createElement('iframe');
            iframe.className  = 'media-video__youtube';
            iframe.src        = embedSrc;
            iframe.allowFullscreen = true;
            iframe.frameBorder = '0';
            iframe.allow = isRutube
                ? 'clipboard-write; autoplay'
                : 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';

            wrap.appendChild(iframe);
            thumb.replaceWith(wrap);
        });
    });
}
