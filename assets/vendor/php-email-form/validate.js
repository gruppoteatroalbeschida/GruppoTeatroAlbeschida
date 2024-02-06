/**
 * PHP Email Form Validation - v3.6  
 */

$(document).ready(function() {
    $('#contactForm').submit(function(e) {
        e.preventDefault(); // Evita il comportamento predefinito del modulo

        // Esegue la convalida dei campi del modulo
        if (validateForm()) {
            var formData = $(this).serialize(); // Serializza i dati del modulo

            // Effettua una richiesta AJAX
            $.ajax({
                type: 'POST',
                url: 'contact.php', // Assicurati di sostituire con il percorso corretto del tuo script PHP
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Gestisci la risposta dal server
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

    function validateForm() {
        var isValid = true;
        $('.error').remove();

        // Validazione e filtraggio del campo Nome
        var name = $('#name').val();
        if (name == '') {
            $('#name').after('<span class="error">Il campo nome è obbligatorio</span>');
            isValid = false;
        } else {
            // Filtraggio del nome: consenti solo caratteri alfabetici e spazi
            name = name.replace(/[^a-zA-Z\s]/g, '');
            $('#name').val(name); // Aggiorna il valore nel campo

        }
        var name = $('#name').val();
        if (name.length > 50) {
            $('#name').after('<span class="error">Il campo nome non può superare i 50 caratteri</span>');
            isValid = false;
        }
        // Validazione del campo Email
        var email = $('#email').val();
        if (email == '') {
            $('#email').after('<span class="error">Il campo email è obbligatorio</span>');
            isValid = false;
        } else {
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                $('#email').after('<span class="error">Formato email non valido</span>');
                isValid = false;
            }
        }

        // Validazione del campo Oggetto
        var subject = $('#subject').val();
        if (subject == '') {
            $('#subject').after('<span class="error">Il campo oggetto è obbligatorio</span>');
            isValid = false;
        }

        var subject = $('#subject').val();
        if (subject.length > 100) {
            $('#subject').after('<span class="error">Il campo oggetto non può superare i 100 caratteri</span>');
            isValid = false;
        }
        var message = $('#message').val();
        if (message.length > 500) {
            $('#message').after('<span class="error">Il campo messaggio non può superare i 700 caratteri</span>');
            isValid = false;
        }

        // Validazione del campo Messaggio
        var message = $('#message').val();
        if (message == '') {
            $('#message').after('<span class="error">Il campo messaggio è obbligatorio</span>');
            isValid = false;
        }

        return isValid;
    }
});