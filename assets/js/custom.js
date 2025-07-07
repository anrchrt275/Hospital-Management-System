// Stats Counter Animation
document.addEventListener('DOMContentLoaded', function() {
    const statsNumbers = document.querySelectorAll('.stats-number');
    statsNumbers.forEach(number => {
        const finalValue = parseInt(number.textContent);
        let currentValue = 0;
        const duration = 2000; // 2 seconds
        const increment = finalValue / (duration / 16);
        
        function updateNumber() {
            if(currentValue < finalValue) {
                currentValue += increment;
                number.textContent = Math.round(currentValue);
                requestAnimationFrame(updateNumber);
            } else {
                number.textContent = finalValue;
            }
        }
        
        updateNumber();
    });
});

// Modal Animation
document.addEventListener('show.bs.modal', function (event) {
    const modal = event.target;
    modal.classList.add('fade-in-scale');
});
