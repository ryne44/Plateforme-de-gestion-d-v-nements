document.addEventListener('DOMContentLoaded', function () {
    const sportFilter = document.getElementById('sport-filter');
    const searchInput = document.getElementById('search-input');
    const eventCards = document.querySelectorAll('.event-card');

    function filterEvents() {
        const sportValue = sportFilter.value.toLowerCase();
        const searchText = searchInput.value.toLowerCase();

        eventCards.forEach(function (card) {
            const sport = card.querySelector('.event-sport').textContent.toLowerCase();
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('.event-description').textContent.toLowerCase();

            const matchesSport = sportValue === 'all' || sport === sportValue;
            const matchesSearch = title.includes(searchText) || description.includes(searchText);

            card.style.display = (matchesSport && matchesSearch) ? 'block' : 'none';
        });
    }

    sportFilter.addEventListener('change', filterEvents);
    searchInput.addEventListener('input', filterEvents);
});
