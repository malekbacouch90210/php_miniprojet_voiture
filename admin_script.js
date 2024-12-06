var modifierButtons = document.querySelectorAll('[data-bs-toggle="modal"]');
modifierButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        document.getElementById('modalId').value = this.getAttribute('data-id');
        document.getElementById('modalMarque').value = this.getAttribute('data-marque');
        document.getElementById('modalModele').value = this.getAttribute('data-modele');
        document.getElementById('modalAnnee').value = this.getAttribute('data-annee');
        document.getElementById('modalImmatriculation').value = this.getAttribute('data-immatriculation');
        document.getElementById('modalDisponibilite').value = this.getAttribute('data-disponibilite');
    });
});
function confirmDelete() {
    return confirm("Êtes-vous sûr de vouloir supprimer cette voiture?");
}