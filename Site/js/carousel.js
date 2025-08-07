//Java Script do Carrossel das paginas Index e Filtragem

const carousel = document.getElementById('carouselImages');


  const images = carousel.querySelectorAll('img');
  const dotsContainer = document.getElementById('dotsContainer');
  const total = images.length;
  let index = 0;
  let interval;

  // Criar os dots
  for (let i = 0; i < total; i++) {
    const dot = document.createElement('div');
    dot.classList.add('dot');
    if (i === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(i));
    dotsContainer.appendChild(dot);
  }

  const updateDots = () => {
    document.querySelectorAll('.dot').forEach((dot, i) => {
      dot.classList.toggle('active', i === index);
    });
  };

  const goToSlide = (i) => {
    index = i;
    carousel.style.transform = `translateX(-${index * 100}vw)`;
    updateDots();
    resetAutoplay();
  };

  const nextSlide = () => {
    index = (index + 1) % total;
    goToSlide(index);
  };

  const prevSlide = () => {
    index = (index - 1 + total) % total;
    goToSlide(index);
  };

  document.querySelector('.next')?.addEventListener('click', nextSlide);
  document.querySelector('.prev')?.addEventListener('click', prevSlide);

  const startAutoplay = () => {
    interval = setInterval(nextSlide, 8000);
  };

  const resetAutoplay = () => {
    clearInterval(interval);
    startAutoplay();
  };

  startAutoplay();