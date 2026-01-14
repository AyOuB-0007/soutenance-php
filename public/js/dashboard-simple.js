// DASHBOARD SIMPLE - SANS ERREURS
console.log('Dashboard simple loaded');

// FONCTION SIMPLE POUR CHANGER DE SECTION
function showSection(sectionName) {
    console.log('showSection called:', sectionName);
    
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

// FONCTIONS DE TEST POUR LES BOUTONS MODIFIER
function testEditProductCategory() {
    console.log('TEST: editProductCategory called');
    alert('Test editProductCategory - Cette fonction fonctionne !');
}

function testEditEmployeeCategory() {
    console.log('TEST: editEmployeeCategory called');
    alert('Test editEmployeeCategory - Cette fonction fonctionne !');
}

// FONCTIONS MODALES SIMPLES
function openCategoryChoiceModal() {
    console.log('openCategoryChoiceModal called');
    var modal = document.getElementById('categoryChoiceModal');
    if (modal) {
        modal.classList.add('active');
        setTimeout(function() {
            var modalContent = document.getElementById('categoryChoiceModalContent');
            if (modalContent) {
                modalContent.classList.add('active');
            }
        }, 50);
    }
}

function closeCategoryChoiceModal() {
    var modal = document.getElementById('categoryChoiceModal');
    var modalContent = document.getElementById('categoryChoiceModalContent');
    
    if (modalContent) {
        modalContent.classList.add('closing');
        modalContent.classList.remove('active');
    }
    
    setTimeout(function() {
        if (modal) {
            modal.classList.remove('active');
        }
        if (modalContent) {
            modalContent.classList.remove('closing');
        }
    }, 300);
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
    
    console.log('Product modal found:', !!productModal);
    console.log('Employee modal found:', !!employeeModal);
});