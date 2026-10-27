const strip = document.querySelector('#lookStrip');
const step = () => Math.min(strip.clientWidth * 0.55, 360);

document.querySelector('.slide-next')?.addEventListener('click', () => {
  strip.scrollBy({ left: step(), behavior: 'smooth' });
});

document.querySelector('.slide-prev')?.addEventListener('click', () => {
  strip.scrollBy({ left: -step(), behavior: 'smooth' });
});

document.querySelectorAll('.heart').forEach((button) => {
  button.addEventListener('click', () => {
    const icon = button.querySelector('i');
    const liked = icon.classList.toggle('fa-solid');
    icon.classList.toggle('fa-regular', !liked);
    button.style.color = liked ? '#811329' : '';
  });
});

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in-view');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach((section) => revealObserver.observe(section));

const backTop = document.querySelector('.back-top');
window.addEventListener('scroll', () => backTop.classList.toggle('show', window.scrollY > 500), { passive: true });
backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

document.querySelector('.menu-toggle')?.addEventListener('click', () => {
  document.querySelector('.main-nav').classList.toggle('mobile-open');
});