// LOCATION.JS - Même structure que events.js

document.addEventListener('DOMContentLoaded', function() {
    // Données simulées (remplacer par appel API)
    const equipmentData = [
        {
            id: 1,
            name: "Kit Football Complet",
            type: "football",
            price: "25€/jour",
            image: "images/equipements/football.jpg",
            description: "Ballon + chasubles + plots + filet"
        },
        {
            id: 2,
            name: "Raquette Tennis Pro",
            type: "tennis",
            price: "15€/jour",
            image: "images/equipements/tennis.jpg",
            description: "Raquette Wilson Pro Staff + tube de balles"
        },
        {
            id: 3,
            name: "Sac à Dos Randonnée",
            type: "randonnee",
            price: "10€/jour",
            image: "images/equipements/randonnee.jpg",
            description: "Sac 40L étanche avec porte-gourde"
        }
    ];

    // Afficher les équipements
    function displayEquipment(category = "all", search = "") {
        const container = document.getElementById('equipment-container');
        container.innerHTML = '';

        const filtered = equipmentData.filter(item => {
            const matchesCategory = category === "all" || item.type === category;
            const matchesSearch = item.name.toLowerCase().includes(search.toLowerCase()) || 
                                item.description.toLowerCase().includes(search.toLowerCase());
            return matchesCategory && matchesSearch;
        });

        if (filtered.length === 0) {
            container.innerHTML = '<p class="no-results">Aucun équipement disponible</p>';
            return;
        }

        filtered.forEach(item => {
            const card = document.createElement('div');
            card.className = 'equipment-card';
            card.innerHTML = `
                <div class="equipment-image" style="background-image: url('${item.image}')"></div>
                <div class="equipment-info">
                    <span class="equipment-type">${getTypeName(item.type)}</span>
                    <h3>${item.name}</h3>
                    <div class="equipment-meta">
                        <span><i class="fas fa-tag"></i> ${item.price}</span>
                        <span><i class="fas fa-box-open"></i> En stock</span>
                    </div>
                    <p class="equipment-description">${item.description}</p>
                    <a href="#" class="btn-rent">Louer</a>
                </div>
            `;
            container.appendChild(card);
        });

        // Animation d'apparition
        animateCards();
    }

    // Helper pour les noms de catégories
    function getTypeName(type) {
        const types = {
            football: "Football",
            tennis: "Tennis",
            randonnee: "Randonnée"
        };
        return types[type] || type;
    }

    // Animation des cartes
    function animateCards() {
        const cards = document.querySelectorAll('.equipment-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `all 0.5s ease ${index * 0.1}s`;
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    }

    // Gestion des filtres
    document.getElementById('category-filter').addEventListener('change', function() {
        const search = document.getElementById('search-equipment').value;
        displayEquipment(this.value, search);
    });

    document.getElementById('search-equipment').addEventListener('input', function() {
        const category = document.getElementById('category-filter').value;
        displayEquipment(category, this.value);
    });

    // Initialisation
    displayEquipment();
});