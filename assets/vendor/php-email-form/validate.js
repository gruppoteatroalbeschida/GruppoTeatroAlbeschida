/**
 * PHP Email Form Validation - v3.6  
 */

$(document).ready(function() {
    $('#contactForm').submit(function(e) {
        e.preventDefault(); // Previene il comportamento predefinito del modulo di invio

        // Esegue la convalida dei campi del modulo
        if (validateForm()) {
            var formData = $(this).serialize(); // Serializza i dati del modulo

            // Effettua una richiesta AJAX
            $.ajax({
                type: 'POST',
                url: 'forms/contact.php', // Percorso del file PHP che gestisce l'invio della mail
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Gestisce la risposta dal server
                    if (response.status == 'success') {
                        alert(response.message); // Visualizza un messaggio di successo
                        $('#contactForm')[0].reset(); // Resettare il modulo dopo l'invio
                    } else {
                        alert(response.message); // Visualizza un messaggio di errore
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log dell'errore nella console
                    alert('Si è verificato un errore durante l\'invio del messaggio. Si prega di riprovare.'); // Visualizza un messaggio di errore generico
                }
            });
        }
    });

    // Funzione per la convalida dei campi del modulo
    function validateForm() {
        var isValid = true;
        $('.error').remove();

        // Validazione dei campi del modulo
        var name = $('#name').val().trim();
        var email = $('#email').val().trim();
        var subject = $('#subject').val().trim();
        var message = $('#message').val().trim();

        if (name === '') {
            $('#name').after('<span class="error">Il campo nome è obbligatorio</span>');
            isValid = false;
        } else if (name.length > 50) {
            $('#name').after('<span class="error">Il campo nome non può superare i 50 caratteri</span>');
            isValid = false;
        } else if (name.length < 2) {
            $('#name').after('<span class="error">Il nome deve contenere almeno 2 caratteri.</span>');
            isValid = false;
        }
        // Validazione del campo nome
        if (name === '') {
            $('#name').after('<span class="error">Il campo nome è obbligatorio</span>');
            isValid = false;
        } else if (name.length < 2 || name.length > 50) {
            $('#name').after('<span class="error">Il nome deve contenere tra 2 e 50 caratteri.</span>');
            isValid = false;
        }

        // Validazione del campo email
        if (email === '') {
            $('#email').after('<span class="error">Il campo email è obbligatorio</span>');
            isValid = false;
        } else if (!isValidEmail(email)) {
            $('#email').after('<span class="error">Formato email non valido</span>');
            isValid = false;
        } else if (!isValidDomain(email)) {
            $('#email').after('<span class="error">Dominio email non valido</span>');
            isValid = false;
        } else if (email.length > 50) {
            $('#email').after('<span class="error">L\'email non può superare i 50 caratteri</span>');
            isValid = false;
        }

        // Validazione del campo oggetto
        if (subject === '') {
            $('#subject').after('<span class="error">Il campo oggetto è obbligatorio</span>');
            isValid = false;
        } else if (subject.length < 5 || subject.length > 100) {
            $('#subject').after('<span class="error">L\'oggetto deve contenere tra 5 e 100 caratteri.</span>');
            isValid = false;
        }

        // Validazione del campo messaggio
        if (message === '') {
            $('#message').after('<span class="error">Il campo messaggio è obbligatorio</span>');
            isValid = false;
        } else if (message.length < 10 || message.length > 2000) {
            $('#message').after('<span class="error">Il messaggio deve contenere tra 10 e 2000 caratteri.</span>');
            isValid = false;
        } else if (!/^[a-zA-Z0-9 .,!?]*$/.test(message)) {
            $('#message').after('<span class="error">Il messaggio contiene caratteri non consentiti. Sono ammessi solo lettere, numeri, spazi e i seguenti caratteri speciali: . , ! ?</span>');
            isValid = false;
        }

        return isValid;
    }


    // Funzione ausiliaria per la convalida dell'email
    function isValidEmail(email) {
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }
});