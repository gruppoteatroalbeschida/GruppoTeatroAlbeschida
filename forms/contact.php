<?php 
// Inizializzazione della sessione
session_start();

// Inclusione dei file necessari di PHPMailer
require '../assets/vendor/php-email-form/PHPMailer-master/src/PHPMailer.php';
require '../assets/vendor/php-email-form/PHPMailer-master/src/SMTP.php';
require '../assets/vendor/php-email-form/PHPMailer-master/src/Exception.php';

// Funzione per pulire e filtrare l'input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Funzione per filtrare l'email
function filter_email($email) {
    return filter_var($email, FILTER_SANITIZE_EMAIL);
}

// Inizializzazione dell'array degli errori
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validazione e sanitizzazione dei campi del modulo di contatto
    $name = clean_input($_POST["name"]);
    $email = filter_email($_POST["email"]);
    $subject = clean_input($_POST["subject"]);
    $message = clean_input($_POST["message"]);

    // Verifica dei campi obbligatori
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $errors[] = "Tutti i campi sono obbligatori.";
    } else {
        // Verifica se l'email è valida
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Formato email non valido.";
        } else {
            // Verifica il dominio dell'email
            list($user, $domain) = explode('@', $email);
            if (!checkdnsrr($domain, 'MX')) {
                $errors[] = "Dominio email non valido.";
            }
        }

        // Verifica della lunghezza dei campi
        if (strlen($name) > 50 || strlen($subject) > 100 || strlen($email) > 50 || strlen($message) > 2000) {
            $errors[] = "I campi superano la lunghezza massima consentita.";
        }
        if (strlen($name) < 2) {
            $errors[] = "Il nome deve contenere almeno 2 caratteri.";
        }
        if (strlen($subject) < 5) {
            $errors[] = "L'oggetto deve contenere almeno 5 caratteri.";
        }
        if (strlen($message) < 10) {
            $errors[] = "Il messaggio deve contenere almeno 10 caratteri.";
        }
        // Filtraggio del messaggio per caratteri non consentiti
        // Filtraggio del messaggio per caratteri non consentiti
		if (!preg_match('/^[a-zA-Z0-9 .,@!?()-]*$/', $message)) {
			$errors[] = "Il messaggio contiene caratteri non consentiti. Sono ammessi solo lettere, numeri, spazi, e i seguenti caratteri speciali: . , ! ? @ -";
		}
    }

    // Se non ci sono errori di validazione, procedi con l'invio dell'email
    if (empty($errors)) {
        // Invia l'email
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'caredda.sara@gmail.com';
        $mail->Password = 'kxlq lshm evwh zfxa';
        $mail->Port = 587;
        $mail->SMTPDebug = 0;
        $mail->setFrom($email, $name);
        $mail->addReplyTo($email, $name);
        $mail->addAddress('caredda.sara@gmail.com');
        $mail->Subject = $subject;
        $mail->Body = $message;

        if ($mail->send()) {
            echo json_encode(['status' => 'success', 'message' => 'Il tuo messaggio è stato inviato con successo. Grazie!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Si è verificato un errore durante l\'invio del messaggio. Si prega di riprovare.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => implode("<br>", $errors)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Richiesta non valida.']);
}
?>
