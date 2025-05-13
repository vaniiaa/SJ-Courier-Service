document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.carousel-item');
  let current = 0;

  function showSlide(index) {
    slides.forEach(slide => slide.style.transform = `translateX(-${index * 100}%)`);
    current = index;
  }

  // Menampilkan slide yang sesuai dengan ID target dari href
  document.querySelectorAll('.carousel-item a').forEach(button => {
    button.addEventListener('click', e => {
      e.preventDefault();
      const targetIndex = Array.from(slides).findIndex(slide => slide.id === button.href.split('#')[1]);
      showSlide(targetIndex);
    });
  });

  // Menjalankan slide otomatis setiap 5 detik
  setInterval(() => showSlide((current + 1) % slides.length), 5000);
});