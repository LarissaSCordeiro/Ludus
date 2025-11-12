document.addEventListener('DOMContentLoaded', function () {
  const rows = document.querySelectorAll('.carousel-row');
  rows.forEach((row) => {
    const scroller = row.querySelector('.row-scroll');
    const btnPrev = row.querySelector('.row-prev');
    const btnNext = row.querySelector('.row-next');
    if (!scroller) return;
    const scrollAmount = () => Math.round(scroller.clientWidth * 0.85);

    const originalItems = Array.from(scroller.children);
    const originalCount = originalItems.length;
    let originalWidth = 0;
    if (originalCount > 0) {
      const gapValue = parseFloat(getComputedStyle(scroller).gap) || 24;
      originalWidth = originalItems.reduce((sum, it) => sum + it.offsetWidth, 0) + gapValue * Math.max(0, originalCount - 1);

      if (originalCount > 1) {
        originalItems.forEach((it) => {
          const c = it.cloneNode(true);
          scroller.appendChild(c);
        });
        for (let i = originalItems.length - 1; i >= 0; i--) {
          const c = originalItems[i].cloneNode(true);
          scroller.insertBefore(c, scroller.firstChild);
        }
        setTimeout(() => {
          scroller.scrollLeft = originalWidth;
        }, 50);
      }
    }

    btnNext && btnNext.addEventListener('click', () => {
      scroller.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
    });
    btnPrev && btnPrev.addEventListener('click', () => {
      scroller.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
    });

    let isDown = false;
    let startX, scrollLeft;
    scroller.addEventListener('mousedown', (e) => {
      isDown = true;
      scroller.classList.add('dragging');
      startX = e.pageX - scroller.offsetLeft;
      scrollLeft = scroller.scrollLeft;
    });
    window.addEventListener('mouseup', () => {
      isDown = false;
      scroller.classList.remove('dragging');
    });
    scroller.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - scroller.offsetLeft;
      const walk = (x - startX) * 1;
      scroller.scrollLeft = scrollLeft - walk;
    });

    let touchStartX = 0;
    scroller.addEventListener('touchstart', (e) => {
      touchStartX = e.touches[0].clientX;
    });
    scroller.addEventListener('touchend', (e) => {
      const touchEndX = e.changedTouches[0].clientX;
      const diff = touchStartX - touchEndX;
      if (Math.abs(diff) > 50) {
        scroller.scrollBy({ left: diff > 0 ? scrollAmount() : -scrollAmount(), behavior: 'smooth' });
      }
    });

    const updateArrows = () => {
      if (!btnPrev || !btnNext) return;
      const hasOverflow = scroller.scrollWidth > scroller.clientWidth + 1;
      if (!hasOverflow) {
        btnPrev.style.display = 'none';
        btnNext.style.display = 'none';
        return;
      }
      btnPrev.style.display = scroller.scrollLeft <= 0 ? 'none' : '';
      btnNext.style.display = scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth - 1 ? 'none' : '';
    };
    scroller.addEventListener('scroll', () => {
      updateArrows();

      if (originalWidth > 0 && originalCount > 1) {
        if (scroller.scrollLeft <= 1) {
          scroller.scrollLeft = scroller.scrollLeft + originalWidth;
        }
        if (scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth - 1) {
          scroller.scrollLeft = scroller.scrollLeft - originalWidth;
        }
      }
    });
    setTimeout(updateArrows, 120);
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => updateArrows(), 150);
    });
  });
});
