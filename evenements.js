<script>
    // Désactiver l'ancien script events.js qui pourrait causer des problèmes
    // et utiliser à la place ce script intégré
    document.addEventListener('DOMContentLoaded', () => {
        const sportFilter = document.getElementById('sport-filter');
        const searchInput = document.getElementById('search-input');
        const eventCards = document.querySelectorAll('.event-card');

        function filterEvents() {
            const sportValue = sportFilter.value;
            const searchText = searchInput.value.toLowerCase();

            eventCards.forEach(card => {
                const sport = card.querySelector('.event-sport').textContent.toLowerCase();
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('.event-description').textContent.toLowerCase();
                
                const matchesSport = sportValue === 'all' || sport === sportValue.toLowerCase();
                const matchesSearch = title.includes(searchText) || description.includes(searchText);
                
                card.style.display = (matchesSport && matchesSearch) ? 'block' : 'none';
            });
        }

        sportFilter.addEventListener('change', filterEvents);
        searchInput.addEventListener('input', filterEvents);
    });
    </script>