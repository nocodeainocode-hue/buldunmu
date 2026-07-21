//
// Fotoğraf Galerisi Lightbox
//
document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.gallery-item');
    const lightbox = document.getElementById('gallery-lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCounter = document.getElementById('lightbox-counter');

    if (!items.length || !lightbox) return;

    let currentIndex = 0;
    const sources = Array.from(items).map(el => el.dataset.src);

    function openLightbox(index) {
        currentIndex = index;
        lightboxImg.src = sources[currentIndex];
        lightboxCounter.textContent = (currentIndex + 1) + ' / ' + sources.length;
        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    window.closeLightbox = function (event) {
        if (event && event.target !== lightbox && event.target !== lightboxImg) return;
        lightbox.classList.add('hidden');
        document.body.style.overflow = '';
    };

    window.navigateLightbox = function (direction, event) {
        if (event) event.stopPropagation();
        currentIndex = (currentIndex + direction + sources.length) % sources.length;
        lightboxImg.src = sources[currentIndex];
        lightboxCounter.textContent = (currentIndex + 1) + ' / ' + sources.length;
    };

    items.forEach((item, index) => {
        item.addEventListener('click', () => openLightbox(index));
    });

    // Klavye navigasyonu
    document.addEventListener('keydown', function (e) {
        if (lightbox.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') navigateLightbox(-1);
        if (e.key === 'ArrowRight') navigateLightbox(1);
    });

    // Dokunmatik swipe desteği
    let touchStartX = 0;
    lightbox.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    lightbox.addEventListener('touchend', function (e) {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (Math.abs(diff) > 50) {
            navigateLightbox(diff > 0 ? 1 : -1);
        }
    }, { passive: true });
});
