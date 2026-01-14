// FAQ Functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('FAQ script loaded'); // Pour debug
    
    const faqItems = document.querySelectorAll('.faq-item');
    console.log('Found FAQ items:', faqItems.length); // Pour debug
    
    faqItems.forEach((item, index) => {
        const question = item.querySelector('.faq-question');
        const icon = item.querySelector('.faq-icon');
        
        console.log(`Setting up FAQ item ${index}`); // Pour debug
        
        question.addEventListener('click', function() {
            console.log(`Clicked on FAQ item ${index}`); // Pour debug
            
            const isCurrentlyActive = item.classList.contains('active');
            
            // Fermer tous les items
            faqItems.forEach(otherItem => {
                otherItem.classList.remove('active');
                const otherIcon = otherItem.querySelector('.faq-icon');
                otherIcon.textContent = '+';
            });
            
            // Si l'item n'était pas actif, l'ouvrir
            if (!isCurrentlyActive) {
                item.classList.add('active');
                icon.textContent = '−';
                console.log(`Opened FAQ item ${index}`); // Pour debug
            } else {
                console.log(`Closed FAQ item ${index}`); // Pour debug
            }
        });
    });
});