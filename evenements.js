document.addEventListener('DOMContentLoaded', function () {
    const sportFilter = document.getElementById('sport-filter');
    const searchInput = document.getElementById('search-input');
    const eventCards = document.querySelectorAll('.event-card');

    function filterEvents() {
        const sportValue = sportFilter.value.toLowerCase();
        const searchText = searchInput.value.toLowerCase();
        let visibleCount = 0;

        eventCards.forEach(function (card) {
            // Récupérer les données pertinentes de chaque carte
            const sportElement = card.querySelector('.event-sport');
            const sport = sportElement ? sportElement.textContent.toLowerCase() : '';
            const titleElement = card.querySelector('h3');
            const title = titleElement ? titleElement.textContent.toLowerCase() : '';
            const descriptionElement = card.querySelector('.event-description');
            const description = descriptionElement ? descriptionElement.textContent.toLowerCase() : '';

            // Appliquer les filtres
            const matchesSport = sportValue === 'all' || sport === sportValue;
            const matchesSearch = title.includes(searchText) || description.includes(searchText);

            // Afficher ou masquer la carte en fonction des filtres
            if (matchesSport && matchesSearch) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Afficher un message si aucun événement n'est trouvé
        const eventsGrid = document.getElementById('events-grid');
        let noResults = document.getElementById('no-results');
        
        if (visibleCount === 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.id = 'no-results';
                noResults.className = 'no-results';
                noResults.textContent = 'Aucun événement ne correspond à votre recherche.';
                eventsGrid.appendChild(noResults);
            }
            noResults.style.display = 'block';
        } else if (noResults) {
            noResults.style.display = 'none';
        }
    }

    // Attacher les écouteurs d'événements si les éléments existent
    if (sportFilter) {
        sportFilter.addEventListener('change', filterEvents);
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterEvents);
    }
});