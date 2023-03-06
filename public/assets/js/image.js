let links =   document.querySelectorAll('[data-delete]');

// on boucles sur les liens

for (let link of links ) {
     // on met un ecouteur d'evement
    link.addEventListener("click", function (e) {
        // on empeche la navigation
        e.preventDefault();

        // on demande la comfirmation de  suppression

        if (confirm('Voulez-vous supprimer cette image ?')) {

            //on envoie la Request ajax
            fetch(this.getAttribute("href"), {
                method: "DELETE",
                headers: {
                    "X-Requested-with":XMLHttpRequest,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({"_token": this.dataset.token})
            }).then(response => response.json())
                .then(data => {
                    if (data) {
                        this.parentElement.remove();
                    } else {
                        alert(data.error)
                    }
                })

        }

    });
}