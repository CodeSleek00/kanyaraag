const track = document.getElementById('carouselTrack');
let index = 0;

function moveSlide(direction) {
    const cards = document.querySelectorAll('.designer-card');
    const cardWidth = cards[0].offsetWidth + 35; // width + margin
    const visibleCards = Math.floor(document.querySelector('.carousel-wrapper').offsetWidth / cardWidth);
    const maxIndex = cards.length - visibleCards;

    index += direction;
    if (index < 0) index = 0;
    if (index > maxIndex) index = maxIndex;

    track.style.transform = `translateX(-${index * cardWidth}px)`;
}
