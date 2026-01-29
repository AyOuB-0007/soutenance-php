// DASHBOARD SIMPLE - VERSION SIMPLIFIÉE SANS CONFLITS
console.log('Dashboard simple loaded');

// FONCTION SIMPLE POUR CHANGER DE SECTION (backup au cas où)
function showSectionBackup(sectionName) {
    console.log('showSectionBackup called:', sectionName);
    
    // Retirer la classe active de toutes les cartes
    var cards = document.querySelectorAll('.stat-card');
    for (var i = 0; i < cards.length; i++) {
        cards[i].classList.remove('active');
    }
    
    // Retirer la classe active de toutes les sections
    var sections = document.querySelectorAll('.section-content');
    for (var i = 0; i < sections.length; i++) {
        sections[i].classList.remove('active');
    }
    
    // Ajouter la classe active à la carte cliquée
    var clickedCard = document.querySelector('[data-section="' + sectionName + '"]');
    if (clickedCard) {
        clickedCard.classList.add('active');
        console.log('Card activated:', sectionName);
    }
    
    // Ajouter la classe active à la section correspondante
    var targetSection = document.getElementById(sectionName + '-section');
    if (targetSection) {
        targetSection.classList.add('active');
        console.log('Section activated:', sectionName + '-section');
    }
}

// Test au chargement
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard simple JavaScript loaded successfully');
    
    // Test des éléments
    var cards = document.querySelectorAll('.stat-card');
    var sections = document.querySelectorAll('.section-content');
    
    console.log('Found cards:', cards.length);
    console.log('Found sections:', sections.length);
    
    // Test des modales
    var productModal = document.getElementById('productCategoryModal');
    var employeeModal = document.getElementById('employeeCategoryModal');
    var deleteModal = document.getElementById('deleteConfirmModal');
    
    console.log('Product modal found:', !!productModal);
    console.log('Employee modal found:', !!employeeModal);
    console.log('Delete modal found:', !!deleteModal);
    
    // Vérifier que les fonctions principales existent
    console.log('showSection function exists:', typeof showSection !== 'undefined');
    console.log('openProductModal function exists:', typeof openProductModal !== 'undefined');
    console.log('openEmployeeModal function exists:', typeof openEmployeeModal !== 'undefined');
    console.log('deleteProduct function exists:', typeof deleteProduct !== 'undefined');
});