function submitUserForm(event) {
    event.preventDefault();

    const nom = document.getElementById('nom').value;
    const prenom = document.getElementById('prenom').value;
    const tache = document.getElementById('tache').value;
    const id = document.getElementById('id').value;

    // Afficher les données du formulaire dans une alerte (optionnel)
    alert(`ID: ${id}\nNom: ${nom}\nPrénom: ${prenom}\ntache: ${tache}`);

    // Envoyer les données à ThingSpeak IoT
    creatuser(id,nom,prenom,tache);
    sendToESP8266(id);
    // Rediriger vers la page principale
    window.location.href = 'index.html';
}

function creatuser(id,nom,prenom,tache){
    fetch("http://192.168.137.1/Test/users.php?add_user&username="+nom+" "+prenom+"&fingerprint_id="+id+"&tache="+tache,{ method: 'GET' }, {
        
    })
    .then(response => {
       console.log(response);
    })
    .catch(error => {
       console.log(error);
    });
    
}

function sendToThingSpeak(id) {
    var apiKey = '3IDPOU7I6BV2RZNG'; // Remplacez par votre clé API ThingSpeak

    // Vérifiez si le champ est vide ou si la valeur est invalide
    if (id === "") {
        //alert("Veuillez entrer une valeur pour l'ID.");
        return;
    }

    // Effectuez une requête HTTP POST vers ThingSpeak
    var url = "https://api.thingspeak.com/update?api_key=" + apiKey + "&field1=" + id;
    fetch(url, { method: 'POST' })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur lors de l\'envoi des données à ThingSpeak');
        }
        //alert("ID envoyé avec succès à ThingSpeak !");
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert("Une erreur est survenue lors de l'envoi de l'ID à ThingSpeak.");
    });
}


function sendToESP8266(id) {
    // Remplacez l'adresse IP par celle de votre ESP8266
    var url = "http://192.168.137.116/data?id="+id; 
    console.log(id);
    
    const data = { 'id':id };
    fetch(url, {
        method: 'POST',
        
        mode: 'no-cors',    // Set mode to 'no-cors'
       //  body: JSON.stringify(data)  // Convert data into JSON string
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur lors de l\'envoi des données à ESP8266');
        }
        //alert("ID envoyé avec succès à ESP8266 !");
    })
    .catch(error => {
        console.error('Erreur:', error);
        //alert("Une erreur est survenue lors de l'envoi de l'ID à ESP8266.");
    });
    
}