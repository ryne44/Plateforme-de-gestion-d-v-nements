
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('category-filter');
    const searchInput = document.getElementById('search-equipment');
    const equipmentCards = document.querySelectorAll('.equipment-card');

    function filterEquipment() {
        const categoryValue = categoryFilter.value;
        const searchText = searchInput.value.toLowerCase();

        equipmentCards.forEach(card => {
            const type = card.getAttribute('data-type').toLowerCase();
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('.equipment-description').textContent.toLowerCase();
            
            const matchesCategory = categoryValue === 'all' || type === categoryValue.toLowerCase();
            const matchesSearch = title.includes(searchText) || description.includes(searchText);
            
            card.style.display = (matchesCategory && matchesSearch) ? 'block' : 'none';
        });
    }

    // Valider les dates de réservation pour chaque équipement
    equipmentCards.forEach(card => {
        const idEquipement = card.querySelector('input[name="equipement_id"]').value;
        const dateDebut = card.querySelector(`#date_debut_${idEquipement}`);
        const dateFin = card.querySelector(`#date_fin_${idEquipement}`);
        
        if (dateDebut && dateFin) {
            dateDebut.addEventListener('change', function() {
                // Mettre à jour la date minimum de fin
                const start = new Date(this.value);
                const nextDay = new Date(start);
                nextDay.setDate(start.getDate() + 1);
                
                // Formater en YYYY-MM-DD
                const minDate = nextDay.toISOString().split('T')[0];
                dateFin.min = minDate;
                
                // Si la date de fin est avant la nouvelle date minimum
                if (dateFin.value && new Date(dateFin.value) < nextDay) {
                    dateFin.value = minDate;
                }
            });
        }
    });

    categoryFilter.addEventListener('change', filterEquipment);
    searchInput.addEventListener('input', filterEquipment);
});
