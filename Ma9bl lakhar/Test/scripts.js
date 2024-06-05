document.addEventListener("DOMContentLoaded", function() {
    const usersTableBody = document.querySelector("#usersTable tbody");

    // Fonction pour ajouter un utilisateur à la table
    function addUserToTable(user) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.nom}</td>
            <td>${user.prenom}</td>
            <td>${user.tache}</td>
            <td>${user.timein}</td>
            <td>${user.timeout}</td>
        `;
        usersTableBody.appendChild(row);
    }

    // Charger les utilisateurs existants depuis le serveur
    fetch('get_users.php')
        .then(response => response.json())
        .then(users => {
            users.forEach(user => {
                addUserToTable(user);
            });
        })
        .catch(error => console.error('Error fetching user data:', error));

    // Ajouter un nouvel utilisateur via le formulaire
    const addUserForm = document.getElementById("addUserForm");
    let nextId = 3; // Supposons que le prochain ID utilisateur commence à 3

    addUserForm.addEventListener("submit", function(event) {
        event.preventDefault();
        
        const nom = document.getElementById("nom").value;
        const prenom = document.getElementById("prenom").value;
        const tache = document.getElementById("tache").value;
        const timein = document.getElementById("timein").value;
        const timeout = document.getElementById("timeout").value;

        const newUser = {
            id: nextId++,
            nom: nom,
            prenom: prenom,
            tache: tache,
            timein: timein,
            timeout: timeout
        };

        addUserToTable(newUser);

        // Réinitialiser le formulaire
        addUserForm.reset();
    });
});
