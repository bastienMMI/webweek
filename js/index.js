document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.carousel-track');
    const items = Array.from(track.children);
    const nextBtn = document.querySelector('.next');
    const prevBtn = document.querySelector('.prev');
    const nav = document.querySelector('.carousel-nav');

    let currentIndex = 0;

    items.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('nav-dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => moveToSlide(index));
        nav.appendChild(dot);
    });

    const dots = document.querySelectorAll('.nav-dot');


    function moveToSlide(index) {
        if (index < 0) index = items.length - 1;
        if (index >= items.length) index = 0;

        track.style.transform = `translateX(-${index * 100}%)`;
        
        dots.forEach(dot => dot.classList.remove('active'));
        dots[index].classList.add('active');
        
        currentIndex = index;
    }

    nextBtn.addEventListener('click', () => moveToSlide(currentIndex + 1));
    prevBtn.addEventListener('click', () => moveToSlide(currentIndex - 1));

    setInterval(() => {
        moveToSlide(currentIndex + 1);
    }, 5000);
});