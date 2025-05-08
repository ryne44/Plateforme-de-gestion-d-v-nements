document.addEventListener('DOMContentLoaded', function() { // Attend que le contenu HTML de la page soit complètement chargé avant d'exécuter le code
    const categoryFilter = document.getElementById('category-filter'); // Récupère l'élément HTML avec l'ID 'category-filter' (un filtre de catégorie)
    const searchInput = document.getElementById('search-equipment'); // Récupère l'élément HTML avec l'ID 'search-equipment' (un champ de recherche pour l'équipement)
    const equipmentCards = document.querySelectorAll('.equipment-card'); // Récupère tous les éléments ayant la classe 'equipment-card' (les cartes d'équipement)

    function filterEquipment() { // Déclare une fonction pour filtrer les équipements
        const categoryValue = categoryFilter.value; // Récupère la valeur sélectionnée dans le filtre de catégorie
        const searchText = searchInput.value.toLowerCase(); // Récupère la valeur entrée dans le champ de recherche et la transforme en minuscule pour une recherche insensible à la casse

        equipmentCards.forEach(card => { // Parcourt chaque carte d'équipement pour appliquer les filtres
            const type = card.getAttribute('data-type').toLowerCase(); // Récupère l'attribut 'data-type' de la carte (le type d'équipement) et le met en minuscule
            const title = card.querySelector('h3').textContent.toLowerCase(); // Récupère le titre de l'équipement (le texte dans la balise <h3>) et le met en minuscule
            const description = card.querySelector('.equipment-description').textContent.toLowerCase(); // Récupère la description de l'équipement (le texte dans un élément avec la classe 'equipment-description') et le met en minuscule

            const matchesCategory = categoryValue === 'all' || type === categoryValue.toLowerCase(); // Vérifie si la catégorie de l'équipement correspond à la valeur sélectionnée ou si la catégorie est 'all' (tous les équipements)
            const matchesSearch = title.includes(searchText) || description.includes(searchText); // Vérifie si le texte de recherche est inclus dans le titre ou la description de l'équipement

            card.style.display = (matchesCategory && matchesSearch) ? 'block' : 'none'; // Si l'équipement correspond à la catégorie et à la recherche, on l'affiche, sinon on le cache
        });
    }

    // Valider les dates de réservation pour chaque équipement
    equipmentCards.forEach(card => { // Parcourt chaque carte d'équipement pour ajouter un gestionnaire d'événement sur les dates
        const idEquipement = card.querySelector('input[name="equipement_id"]').value; // Récupère l'ID de l'équipement (valeur de l'input nommé 'equipement_id')
        const dateDebut = card.querySelector(`#date_debut_${idEquipement}`); // Récupère l'élément input pour la date de début, en utilisant l'ID dynamique basé sur l'ID de l'équipement
        const dateFin = card.querySelector(`#date_fin_${idEquipement}`); // Récupère l'élément input pour la date de fin, en utilisant l'ID dynamique basé sur l'ID de l'équipement
        
        if (dateDebut && dateFin) { // Vérifie que les deux éléments de date existent
            dateDebut.addEventListener('change', function() { // Ajoute un gestionnaire d'événements à la date de début pour chaque modification
                // Mettre à jour la date minimum de fin
                const start = new Date(this.value); // Crée un objet Date à partir de la valeur de la date de début
                const nextDay = new Date(start); // Crée une nouvelle date à partir de la date de début
                nextDay.setDate(start.getDate() + 1); // Ajoute un jour à la date de début pour définir le début de la période de réservation

                // Formater en YYYY-MM-DD
                const minDate = nextDay.toISOString().split('T')[0]; // Formate la nouvelle date minimum pour la date de fin en format 'YYYY-MM-DD'
                dateFin.min = minDate; // Définit la date minimum pour la date de fin

                // Si la date de fin est avant la nouvelle date minimum
                if (dateFin.value && new Date(dateFin.value) < nextDay) { // Vérifie si la date de fin sélectionnée est avant la nouvelle date minimum
                    dateFin.value = minDate; // Si c'est le cas, réinitialise la date de fin à la nouvelle date minimum
                }
            });
        }
    });

    categoryFilter.addEventListener('change', filterEquipment); // Ajoute un gestionnaire d'événements pour filtrer les équipements chaque fois que le filtre de catégorie change
    searchInput.addEventListener('input', filterEquipment); // Ajoute un gestionnaire d'événements pour filtrer les équipements chaque fois que l'utilisateur tape dans le champ de recherche
}); 
