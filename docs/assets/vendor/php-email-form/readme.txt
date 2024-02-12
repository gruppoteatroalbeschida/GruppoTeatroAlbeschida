PHP Email Form Validation
Questo repository contiene un modulo PHP per la convalida dei campi di un modulo di contatto e l'invio di email utilizzando PHPMailer. Il modulo utilizza anche jQuery per la convalida dei campi del modulo e l'invio dei dati tramite AJAX.

Come funziona
- Convalida dei campi: Quando un utente compila il modulo di contatto sul sito web e tenta di inviarlo, il modulo esegue una serie di controlli per assicurarsi che tutti i campi obbligatori siano stati compilati correttamente e che i dati inseriti siano nel formato corretto. Questo include la verifica della presenza di un nome valido, un'email valida, un oggetto significativo e un messaggio adeguato.
- Invio dell'email: Una volta che tutti i campi del modulo sono stati convalidati con successo, il modulo utilizza la libreria PHPMailer per inviare l'email al destinatario desiderato. PHPMailer semplifica il processo di invio di email tramite PHP e consente di configurare facilmente il server SMTP per l'invio sicuro delle email.
- Risposta all'utente: Dopo aver inviato l'email con successo, il modulo fornisce un feedback all'utente informandolo che il messaggio è stato inviato correttamente. In caso di errori durante l'invio dell'email o se ci sono problemi con i dati inseriti nel modulo, viene visualizzato un messaggio di errore appropriato per guidare l'utente.

Requisiti 
- jQuery

Installazione
- Clonare questo repository sul proprio server web.
- Assicurarsi che la cartella vendor contenga la libreria PHPMailer.
- Assicurarsi che il percorso del file contact.php sia corretto nel file JavaScript.

Utilizzo
- Includere il modulo di contatto nel proprio sito web.
- Compilare i campi del modulo con le informazioni desiderate.
- Fare clic sul pulsante di invio.
- Se tutti i campi sono compilati correttamente, il modulo invierà l'email e visualizzerà un messaggio di successo. In caso contrario, verranno visualizzati messaggi di errore per i campi non validi.

Personalizzazione
- È possibile personalizzare il modulo di contatto modificando il file contact.php e aggiungendo o rimuovendo le convalide dei campi desiderati nel file JavaScript.

Licenza
Questo modulo è distribuito con licenza MIT. Consultare il file LICENSE per ulteriori informazioni.

