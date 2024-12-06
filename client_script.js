document.addEventListener('DOMContentLoaded', function() {
    anime({
        targets: '.card',
        opacity: [0, 1],
        translateY: [50, 0],
        delay: anime.stagger(200),
        duration: 1000,
        easing: 'easeOutExpo'
    });
    const confirmationMessage = document.getElementById('confirmation-message');
    if (confirmationMessage) {
        anime({
            targets: confirmationMessage,
            scale: [0.9, 1],
            opacity: [0, 1],
            duration: 800,
            easing: 'easeOutElastic(1, 0.5)'
        });
    }
});