/**
 * Script pour redimensionner automatiquement les textareas selon leur contenu
 */

// Fonction pour redimensionner une textarea
function autoResizeTextarea(textarea) {
    // Réinitialise la hauteur pour obtenir la hauteur correcte
    textarea.style.height = 'auto';
    // Définit la nouvelle hauteur en fonction du contenu
    textarea.style.height = textarea.scrollHeight + 'px';

    // Désactive la scrollbar verticale mais garde la possibilité de redimensionnement manuel
    textarea.style.overflowY = 'hidden';
}

// Fonction pour initialiser toutes les textareas de la page
function initAutoResizeTextareas() {
    // Sélectionne toutes les textareas
    const textareas = document.querySelectorAll('textarea');

    textareas.forEach(textarea => {
        // Applique le redimensionnement initial
        autoResizeTextarea(textarea);

        // Ajoute un écouteur d'événement pour le redimensionnement dynamique
        textarea.addEventListener('input', function() {
            autoResizeTextarea(this);
        });

        // Redimensionne également lors du chargement des données externes
        textarea.addEventListener('change', function() {
            autoResizeTextarea(this);
        });
    });
}

// Initialise le redimensionnement automatique lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', initAutoResizeTextareas);

// Réapplique également en cas de chargement dynamique de contenu
window.addEventListener('load', initAutoResizeTextareas);
