// Hero Image Slider
const heroImages = [
  'images/2.jpg',
  'images/3.jpg',
  'images/4.jpg',
  'images/5.jpg',
];

let currentIndex = 0;
const heroSec = document.getElementById('hero-sec');
const heroDots = document.querySelectorAll('.hero-dot');
const leftArrow = document.querySelector('.hero-arrow.left');
const rightArrow = document.querySelector('.hero-arrow.right');

function showSlide(index) {
  if (index >= heroImages.length) index = 0;
  if (index < 0) index = heroImages.length - 1;
  heroSec.style.backgroundImage = `url('${heroImages[index]}')`;
  heroDots.forEach(dot => dot.classList.remove('active'));
  heroDots[index].classList.add('active');
  currentIndex = index;
}

// Auto Slide
let slideInterval = setInterval(() => {
  showSlide(currentIndex + 1);
}, 1000);

// Dot Clicks
heroDots.forEach(dot => {
  dot.addEventListener('click', () => {
    const index = parseInt(dot.dataset.index);
    showSlide(index);
    clearInterval(slideInterval);
    slideInterval = setInterval(() => showSlide(currentIndex + 1), 4000);
  });
});

// Arrows
leftArrow.addEventListener('click', () => {
  showSlide(currentIndex - 1);
  clearInterval(slideInterval);
  slideInterval = setInterval(() => showSlide(currentIndex + 1), 4000);
});

rightArrow.addEventListener('click', () => {
  showSlide(currentIndex + 1);
  clearInterval(slideInterval);
  slideInterval = setInterval(() => showSlide(currentIndex + 1), 4000);
});

// Init First Slide
showSlide(currentIndex);

// ========== Load More Button Behavior ==========
const loadBtn = document.getElementById('load-btn');

loadBtn.addEventListener('click', () => {
  const container = document.querySelector('.containerB');
  for (let i = 2; i < 6; i++) {
    const newBox = document.createElement('div');
    newBox.className = 'box';
    newBox.innerHTML = `
      <div class="box-img" style="background-image: url('da/a${(i+1)%6+1}.jpg')"></div>
      <div class="price-sec">
        <p>New item just for you.</p>
        <p id="price">Rs.${Math.floor(Math.random() * 1000 + 500)}</p>
        <p id="cut">Rs.${Math.floor(Math.random() * 2000 + 1000)}</p>
      </div>
    `;
    container.appendChild(newBox);
  }
});

// ========== Navbar Sticky Animation ==========
window.addEventListener('scroll', () => {
  const nav = document.querySelector('.navbar');
  if (window.scrollY > 20) {
    nav.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
  } else {
    nav.style.boxShadow = 'none';
  }
});

// ========== Hover Zoom Effect on Categories ==========
document.querySelectorAll('.categories-box').forEach((box) => {
  box.addEventListener('mouseenter', () => {
    box.style.transform = 'scale(1.05)';
    box.style.transition = 'transform 0.3s ease';
  });
  box.addEventListener('mouseleave', () => {
    box.style.transform = 'scale(1)';
  });
});
