/// JS DEVENEMENTS
// Données des événements
const events = [
    {
        id: 1,
        title: "Tournoi de Football Inter-Entreprises",
        sport: "football",
        date: "15/06/2025",
        location: "Stade Municipal, Paris",
        description: "Tournoi annuel ouvert à toutes les entreprises de la région. Équipes de 7 joueurs.",
        image: "football-event.jpg",
        price: "25€ par équipe"
    },
    {
        id: 2,
        title: "Marathon de la Ville",
        sport: "running",
        date: "22/06/2025",
        location: "Centre-ville, Lyon",
        description: "Marathon annuel avec parcours de 42km dans les rues de la ville. Départs groupés.",
        image: "marathon-event.jpg",
        price: "40€ par personne"
    },
    {
        id: 3,
        title: "Compétition de Surf",
        sport: "surf",
        date: "05/07/2025",
        location: "Plage de Biarritz",
        description: "Compétition régionale de surf toutes catégories. Inscription obligatoire.",
        image: "surf-event.jpg",
        price: "30€ par participant"
    },
    {
        id: 4,
        title: "Tournoi de Tennis Open",
        sport: "tennis",
        date: "12/07/2025",
        location: "Club de Tennis, Bordeaux",
        description: "Tournoi open en simple et double. Catégories juniors et adultes.",
        image: "tennis-event.jpg",
        price: "20€ par personne"
    },
    {
        id: 5,
        title: "Tournoi de Basketball 3x3",
        sport: "basket",
        date: "20/07/2025",
        location: "Terrain de sport, Marseille",
        description: "Tournoi de street basketball en équipe de 3 joueurs. Lots à gagner.",
        image: "basket-event.jpg",
        price: "15€ par équipe"
    }
];

// Afficher les événements
function displayEvents(filter = "all", search = "") {
    const container = document.getElementById('events-grid');
    container.innerHTML = '';

    const filteredEvents = events.filter(event => {
        const matchesSport = filter === "all" || event.sport === filter;
        const matchesSearch = event.title.toLowerCase().includes(search.toLowerCase()) || 
                             event.location.toLowerCase().includes(search.toLowerCase());
        return matchesSport && matchesSearch;
    });

    if (filteredEvents.length === 0) {
        container.innerHTML = '<p class="no-events">Aucun événement ne correspond à votre recherche.</p>';
        return;
    }

    filteredEvents.forEach(event => {
        const eventCard = document.createElement('div');
        eventCard.className = 'event-card';
        eventCard.innerHTML = `
            <div class="event-image" style="background-image: url('images/${event.image}')"></div>
            <div class="event-info">
                <span class="event-sport">${getSportName(event.sport)}</span>
                <h3>${event.title}</h3>
                <div class="event-meta">
                    <span><i class="fas fa-calendar-alt"></i> ${event.date}</span>
                    <span><i class="fas fa-map-marker-alt"></i> ${event.location}</span>
                </div>
                <div class="event-meta">
                    <span><i class="fas fa-tag"></i> ${event.price}</span>
                </div>
                <p class="event-description">${event.description}</p>
                <a href="inscription.html?event=${event.id}" class="btn-event">S'inscrire</a>
            </div>
        `;
        container.appendChild(eventCard);
    });
}

// Helper pour les noms de sport
function getSportName(sportKey) {
    const sports = {
        football: "Football",
        basket: "Basketball",
        tennis: "Tennis",
        running: "Course à pied",
        surf: "Surf"
    };
    return sports[sportKey] || sportKey;
}

// Gestion des filtres
document.addEventListener('DOMContentLoaded', () => {
    displayEvents();

    document.getElementById('sport-filter').addEventListener('change', (e) => {
        const searchValue = document.getElementById('search-input').value;
        displayEvents(e.target.value, searchValue);
    });

    document.getElementById('search-input').addEventListener('input', (e) => {
        const sportValue = document.getElementById('sport-filter').value;
        displayEvents(sportValue, e.target.value);
    });
});