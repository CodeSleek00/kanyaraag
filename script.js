// News Ticker
const items = document.querySelectorAll('.news-item');
let current = 0;
let tickerInterval;

function rotateNews() {
  items[current].classList.remove('active');
  current = (current + 1) % items.length;
  items[current].classList.add('active');
}
function startTicker() {
  tickerInterval = setInterval(rotateNews, 3000);
}
startTicker();

const tickerContainer = document.querySelector('.breaking-news-container');
tickerContainer.addEventListener('mouseenter', () => clearInterval(tickerInterval));
tickerContainer.addEventListener('mouseleave', startTicker);
tickerContainer.addEventListener('touchstart', () => clearInterval(tickerInterval), { passive: true });
tickerContainer.addEventListener('touchend', startTicker, { passive: true });

// Rotating Search Placeholder
const rotateText = document.getElementById('rotateText');
const keywords = ['Lehenga', 'Kurti', 'Saree', 'Dupatta', 'Jewellery', 'Salwar'];
let i = 0;
setInterval(() => {
  i = (i + 1) % keywords.length;
  rotateText.style.opacity = 0;
  setTimeout(() => {
    rotateText.textContent = keywords[i];
    rotateText.style.opacity = 1;
  }, 400);
}, 3000);

// Mobile Nav
const mobileNav = document.getElementById('mobileNav');
const mobileNavOverlay = document.getElementById('mobileNavOverlay');
const mobileMenuBtn = document.getElementById('mobileMenuBtn');

function toggleMobileNav() {
  const isOpen = mobileNav.style.right === '0px';
  mobileNav.style.right = isOpen ? '-100%' : '0px';
  mobileNavOverlay.classList.toggle('active', !isOpen);
  document.body.style.overflow = isOpen ? '' : 'hidden';
}
mobileMenuBtn.addEventListener('click', toggleMobileNav);
mobileNavOverlay.addEventListener('click', toggleMobileNav);
document.querySelectorAll('.mobile-nav a').forEach(link => link.addEventListener('click', toggleMobileNav));

// Hero Slider
const slides = document.querySelector('.slides');
const slideCount = document.querySelectorAll('.slide').length;
const dotsContainer = document.querySelector('.dots');
let index = 0;
let autoSlideInterval;

for (let i = 0; i < slideCount; i++) {
  const dot = document.createElement('div');
  dot.classList.add('dot');
  dot.addEventListener('click', () => goToSlide(i));
  dotsContainer.appendChild(dot);
}
const dots = document.querySelectorAll('.dot');

function updateSlide() {
  slides.style.transform = `translateX(${-index * 100}%)`;
  dots.forEach(dot => dot.classList.remove('active'));
  dots[index].classList.add('active');
}
function nextSlide() {
  index = (index + 1) % slideCount;
  updateSlide();
}
function prevSlide() {
  index = (index - 1 + slideCount) % slideCount;
  updateSlide();
}
function goToSlide(i) {
  index = i;
  updateSlide();
  resetAutoSlide();
}
function startAutoSlide() {
  autoSlideInterval = setInterval(nextSlide, 3000);
}
function resetAutoSlide() {
  clearInterval(autoSlideInterval);
  startAutoSlide();
}

document.querySelector('.click-left').addEventListener('click', () => { prevSlide(); resetAutoSlide(); });
document.querySelector('.click-right').addEventListener('click', () => { nextSlide(); resetAutoSlide(); });

updateSlide();
startAutoSlide();

// Reveal Category Cards on view
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.category-cards .card, .card-category .card');
  if (!cards.length) return;

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });

    cards.forEach(card => observer.observe(card));
  } else {
    cards.forEach(card => card.classList.add('visible'));
  }
});
