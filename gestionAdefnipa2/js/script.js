document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('myModal');
    var successModal = document.getElementById('successModal');
    var openModalBtn = document.getElementById('openModalBtn');
    var closeModalButtons = document.querySelectorAll('.close');
    var userForm = document.getElementById('userForm');
    var successModalMessage = document.getElementById('modalMessage');

    // Vérification de l'élément modalMessage
    console.log('successModalMessage:', successModalMessage);

    openModalBtn.onclick = function() {
        modal.style.display = 'block';
    };

    function closeModal(modal) {
        modal.style.display = 'none';
    }
    
    closeModalButtons.forEach(button => {
        button.addEventListener('click', function() {
            closeModal(modal);
            closeModal(successModal);
            window.location.reload();
        });
    });

    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal(modal);
        }
        if (event.target === successModal) {
            closeModal(successModal);
            
        }
    };

    

    // Gestion de la soumission du formulaire
    userForm.addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(userForm);

        fetch('login.php', { // Utilisez 'login.php' pour le formulaire d'ajout d'administrateurs
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                successModalMessage.textContent = data.message;
                closeModal(modal);
                successModal.style.display = 'block';
                userForm.reset();
            } else {
                successModalMessage.textContent = data.message;
                successModal.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            successModalMessage.textContent = 'Erreur lors de l\'ajout de l\'administrateur.';
            successModal.style.display = 'block';
        });
    });

    

    // Fonction pour les actions de confirmation pour les étudiants
window.confirmStudentAction = function(action, id) {
    let message = "";
    let url = "";

    switch (action) {
        case 'supprimer':
            message = "Êtes-vous sûr de vouloir supprimer cet étudiant ?";
            url = 'supprim_etudiant.php?id=' + id;
            break;
        case 'archiver':
            message = "Êtes-vous sûr de vouloir archiver cet étudiant ?";
            url = 'archive.php?id=' + id;
            break;
        case 'editer':
            message = "Êtes-vous sûr de vouloir éditer cet étudiant ?";
            url = 'edit_etudiant.php?id=' + id;
            break;
        default:
            message = "Action inconnue.";
            break;
    }

    if (message && confirm(message)) {
        window.location.href = url;
    }
};


    // Fonction pour les actions de confirmation pour les administrateurs
    window.confirmAdminAction = function(action, id) {
    let message = "";
    let url = "";
    if (action === 'supprimer') {
        message = "Êtes-vous sûr de vouloir supprimer cet administrateur ?";
        url = 'supprim_admin.php?id=' + id;
    } else if (action === 'archiver') {
        message = "Êtes-vous sûr de vouloir archiver cet administrateur ?";
        url = 'archiver_admin.php?id=' + id;
    } 

    if (confirm(message)) {
        window.location.href = url;
    }
};

});


document.addEventListener('DOMContentLoaded', function() {
    // Ajouter un événement de confirmation pour les boutons de suppression
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')) {
                // Soumettre le formulaire de suppression associé au bouton
                this.closest('form').submit();
            }
        });
    });
    
    // Afficher un message de succès si la suppression a réussi
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('deleted')) {
        alert('Étudiant supprimé avec succès !');
    }
});




/******************* modification administrateur ************/

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-btn')) {
        var id = e.target.getAttribute('data-id');
        var nom = e.target.getAttribute('data-nom');
        var prenom = e.target.getAttribute('data-prenom');
        var email = e.target.getAttribute('data-email');

        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = nom;
        document.getElementById('edit_prenom').value = prenom;
        document.getElementById('edit_email').value = email;

        editModal.style.display = "block";
    }
});

// Close the modal
document.querySelectorAll(".close").forEach(function(closeBtn) {
    closeBtn.addEventListener("click", function() {
        editModal.style.display = "none";
    });
});

// Gestion de la soumission du formulaire de modification
document.querySelector('#editForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    fetch('editer_admin.php', { // Assurez-vous que l'URL est correcte
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('successModalMessage').textContent = data.message;
            document.getElementById('editModal').style.display = 'none'; // Ferme le modal d'édition
            document.getElementById('mysuccessModal').style.display = 'block'; // Affiche le modal de succès
        } else {
            document.getElementById('successModalMessage').textContent = data.message;
            document.getElementById('mysuccessModal').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('successModalMessage').textContent = 'Erreur lors de la mise à jour de l\'administrateur.';
        document.getElementById('mysuccessModal').style.display = 'block';
    });
});

// Fermeture du modal de succès
document.querySelector(".close").addEventListener("click", function() {
    document.getElementById('mysuccessModal').style.display = "none";
    location.reload(); // Recharger la page pour voir les mises à jour
});



//*********************** */  Controle de l'affichage ou non du mot de passe 

document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
});





