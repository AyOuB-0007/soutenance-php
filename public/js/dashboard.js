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
    } else {
        console.error('Card not found:', sectionName);
    }
    
    // Ajouter la classe active à la section correspondante
    var targetSection = document.getElementById(sectionName + '-section');
    if (targetSection) {
        targetSection.classList.add('active');
        console.log('Section activated:', sectionName + '-section');
    } else {
        console.error('Section not found:', sectionName + '-section');
    }
}

// FONCTIONS MODALES
function openCategoryChoiceModal() {
    const modal = document.getElementById('categoryChoiceModal');
    const modalContent = document.getElementById('categoryChoiceModalContent');
    
    modal.classList.add('active');
    setTimeout(() => {
        modalContent.classList.add('active');
    }, 50);
}

function closeCategoryChoiceModal() {
    const modal = document.getElementById('categoryChoiceModal');
    const modalContent = document.getElementById('categoryChoiceModalContent');
    
    modalContent.classList.add('closing');
    modalContent.classList.remove('active');
    
    setTimeout(() => {
        modal.classList.remove('active');
        modalContent.classList.remove('closing');
    }, 300);
}

function openCategoryModal(type) {
    closeCategoryChoiceModal();
    setTimeout(() => {
        if (type === 'product') {
            openProductCategoryModal();
        } else if (type === 'employee') {
            openEmployeeCategoryModal();
        }
    }, 350);
}

function openProductCategoryModal() {
    const modal = document.getElementById('productCategoryModal');
    const modalContent = document.getElementById('productCategoryModalContent');
    
    modal.classList.add('active');
    setTimeout(() => {
        modalContent.classList.add('active');
    }, 50);
}

function closeProductCategoryModal() {
    const modal = document.getElementById('productCategoryModal');
    const modalContent = document.getElementById('productCategoryModalContent');
    
    modalContent.classList.add('closing');
    modalContent.classList.remove('active');
    
    setTimeout(() => {
        modal.classList.remove('active');
        modalContent.classList.remove('closing');
        document.getElementById('productCategoryName').value = '';
        document.getElementById('productCategoryIcon').selectedIndex = 0;
    }, 300);
}

function saveProductCategory() {
    const name = document.getElementById('productCategoryName').value;
    
    if (!name.trim()) {
        const nameInput = document.getElementById('productCategoryName');
        nameInput.classList.add('error');
        nameInput.focus();
        nameInput.addEventListener('input', function() {
            this.classList.remove('error');
        }, { once: true });
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/dashboard';
    form.style.display = 'none';
    
    const nameInput = document.createElement('input');
    nameInput.type = 'hidden';
    nameInput.name = 'nom';
    nameInput.value = name;
    
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'type';
    typeInput.value = 'product';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'create_category';
    actionInput.value = '1';
    
    form.appendChild(nameInput);
    form.appendChild(typeInput);
    form.appendChild(actionInput);
    document.body.appendChild(form);
    
    closeProductCategoryModal();
    form.submit();
}

function openEmployeeCategoryModal() {
    const modal = document.getElementById('employeeCategoryModal');
    const modalContent = document.getElementById('employeeCategoryModalContent');
    
    modal.classList.add('active');
    setTimeout(() => {
        modalContent.classList.add('active');
    }, 50);
}

function closeEmployeeCategoryModal() {
    const modal = document.getElementById('employeeCategoryModal');
    const modalContent = document.getElementById('employeeCategoryModalContent');
    
    modalContent.classList.add('closing');
    modalContent.classList.remove('active');
    
    setTimeout(() => {
        modal.classList.remove('active');
        modalContent.classList.remove('closing');
        document.getElementById('employeeCategoryName').value = '';
        document.getElementById('employeeCategoryIcon').selectedIndex = 0;
    }, 300);
}

function saveEmployeeCategory() {
    const name = document.getElementById('employeeCategoryName').value;
    
    if (!name.trim()) {
        const nameInput = document.getElementById('employeeCategoryName');
        nameInput.classList.add('error');
        nameInput.focus();
        nameInput.addEventListener('input', function() {
            this.classList.remove('error');
        }, { once: true });
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/dashboard';
    form.style.display = 'none';
    
    const nameInput = document.createElement('input');
    nameInput.type = 'hidden';
    nameInput.name = 'nom';
    nameInput.value = name;
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'create_category';
    actionInput.value = '1';
    
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'type';
    typeInput.value = 'employee';
    
    form.appendChild(nameInput);
    form.appendChild(actionInput);
    form.appendChild(typeInput);
    document.body.appendChild(form);
    
    closeEmployeeCategoryModal();
    form.submit();
}

// FONCTIONS DELETE
let currentDeleteAction = null;

function openDeleteConfirmModal(type, id, name) {
    currentDeleteAction = { type, id, name };
    
    const modal = document.getElementById('deleteConfirmModal');
    const modalContent = document.getElementById('deleteConfirmModalContent');
    const message = document.getElementById('deleteConfirmMessage');
    
    message.innerHTML = `Êtes-vous sûr de vouloir supprimer la catégorie <strong>"${name}"</strong> ?<br>Cette action est irréversible.`;
    
    modal.classList.add('active');
    setTimeout(() => {
        modalContent.classList.add('active');
    }, 50);
}

function closeDeleteConfirmModal() {
    const modal = document.getElementById('deleteConfirmModal');
    const modalContent = document.getElementById('deleteConfirmModalContent');
    
    modalContent.classList.add('closing');
    modalContent.classList.remove('active');
    
    setTimeout(() => {
        modal.classList.remove('active');
        modalContent.classList.remove('closing');
        currentDeleteAction = null;
    }, 300);
}

function executeDelete() {
    if (!currentDeleteAction) return;
    
    const { type, id, name } = currentDeleteAction;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/dashboard';
    form.style.display = 'none';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'delete_category';
    actionInput.value = '1';
    
    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'category_id';
    idInput.value = id;
    
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'category_type';
    typeInput.value = type;
    
    form.appendChild(actionInput);
    form.appendChild(idInput);
    form.appendChild(typeInput);
    document.body.appendChild(form);
    
    closeDeleteConfirmModal();
    form.submit();
}

function deleteProductCategory(id, name) {
    openDeleteConfirmModal('product', id, name);
}

function deleteEmployeeCategory(id, name) {
    openDeleteConfirmModal('employee', id, name);
}

// Test de la fonction au chargement
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard JavaScript loaded');
    
    // Vérifier que les éléments existent
    var cards = document.querySelectorAll('.stat-card');
    var sections = document.querySelectorAll('.section-content');
    
    console.log('Found cards:', cards.length);
    console.log('Found sections:', sections.length);
    
    // Tester la fonction avec la première section
    if (cards.length > 0 && sections.length > 0) {
        console.log('Elements found, navigation should work');
    } else {
        console.error('Missing elements for navigation');
    }
    
    // Fermer les modales en cliquant sur l'overlay
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            if (e.target.id === 'deleteConfirmModal') closeDeleteConfirmModal();
            if (e.target.id === 'categoryChoiceModal') closeCategoryChoiceModal();
            if (e.target.id === 'productCategoryModal') closeProductCategoryModal();
            if (e.target.id === 'employeeCategoryModal') closeEmployeeCategoryModal();
        }
    });
});