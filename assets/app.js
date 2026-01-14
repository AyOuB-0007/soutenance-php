/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
// FAQ Functionality
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const icon = item.querySelector('.faq-icon');
        
        question.addEventListener('click', function() {
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
            }
            // Si l'item était déjà actif, il reste fermé (déjà fait ci-dessus)
        });
    });
});