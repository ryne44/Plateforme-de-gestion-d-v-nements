document.addEventListener('DOMContentLoaded', function() {
    // Données simulées
    const reservations = [
        {
            id: 1,
            equipment: "Kit Football Complet",
            date: "15/06/2025",
            status: "Confirmée"
        },
        {
            id: 2,
            equipment: "Raquette Tennis Pro",
            date: "20/06/2025",
            status: "En attente"
        }
    ];

    // Afficher les réservations
    const reservationList = document.getElementById('reservation-list');
    reservations.forEach(res => {
        const item = document.createElement('div');
        item.className = 'reservation-item';
        item.innerHTML = `
            <div>
                <strong>${res.equipment}</strong>
                <div>${res.date}</div>
            </div>
            <span class="status">${res.status}</span>
        `;
        reservationList.appendChild(item);
    });w

    // Déconnexion
    document.getElementById('logout-btn').addEventListener('click', function() {
        if (confirm('Voulez-vous vraiment vous déconnecter ?')) {
            window.location.href = 'index.html';
        }
    });

    // Édition du profil
    document.getElementById('edit-profile').addEventListener('click', function() {
        alert('Fonctionnalité à implémenter');
    });
});