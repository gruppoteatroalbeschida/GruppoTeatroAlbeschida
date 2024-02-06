<?php 
// Inizializza la sessione
session_start();

// Include i file necessari di PHPMailer
require '../assets/vendor/php-email-form/PHPMailer-master/src/PHPMailer.php';
require '../assets/vendor/php-email-form/PHPMailer-master/src/SMTP.php';
require '../assets/vendor/php-email-form/PHPMailer-master/src/Exception.php';

// Funzione per pulire l'input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Imposta il limite di richieste consentite entro il periodo di throttling (ad esempio, 5 richieste ogni 10 minuti)
$throttle_limit = 5;
$throttle_period = 600; // 10 minuti (600 secondi)

// Genera e memorizza il token CSRF nella sessione
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica il token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Token CSRF non valido.";
    }

    // Verifica il throttling
    $ip = $_SERVER['REMOTE_ADDR'];
    $timestamp = time();
    $throttle_key = 'throttle_' . $ip;
    $throttle_data = isset($_SESSION[$throttle_key]) ? unserialize($_SESSION[$throttle_key]) : array('count' => 0, 'timestamp' => $timestamp);

    if ($timestamp - $throttle_data['timestamp'] < $throttle_period) {
        if ($throttle_data['count'] >= $throttle_limit) {
            $errors[] = "Limite di richieste raggiunto. Riprova più tardi.";
        } else {
            $throttle_data['count']++;
        }
    } else {
        // Resetta il conteggio delle richieste se il periodo di throttling è scaduto
        $throttle_data = array('count' => 1, 'timestamp' => $timestamp);
    }

    // Aggiorna i dati di throttling nella sessione
    $_SESSION[$throttle_key] = serialize($throttle_data);

    // Continua con la logica per l'invio dell'email solo se il token CSRF è valido e il throttling non ha superato il limite
    if (empty($errors)) {
        // Validazione dei campi del modulo di contatto
        if (empty($_POST["name"])) {
            $errors[] = "Il campo nome è obbligatorio.";
        } else {
            $name = clean_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $errors[] = "Il campo nome può contenere solo lettere e spazi.";
            }
        }

        // Verifica se l'email è vuota e se è un'email valida
        if (empty($_POST["email"])) {
            $errors[] = "Il campo email è obbligatorio.";
        } else {
            $email = clean_input($_POST["email"]);
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
        }

        // Verifica se l'oggetto è stato inserito
        if (empty($_POST["subject"])) {
            $errors[] = "Il campo oggetto è obbligatorio.";
        } else {
            $subject = clean_input($_POST["subject"]);
        }

        if (empty($_POST["message"])) {
            $errors[] = "Il campo messaggio è obbligatorio.";
        } else {
            $message = clean_input($_POST["message"]);
        }

        // Se non ci sono errori di validazione, procedi con l'invio dell'email
        if (empty($errors)) {
            // Crea un nuovo oggetto PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            // Imposta il metodo di invio dell'email
            $mail->isSMTP();
            // Imposta l'host del server SMTP
            $mail->Host = 'smtp.example.com';
            // Imposta l'autenticazione SMTP
            $mail->SMTPAuth = true;
            // Imposta l'username SMTP
            $mail->Username = 'yourusername';
            // Imposta la password SMTP
            $mail->Password = 'yourpassword';
            // Imposta la porta SMTP
            $mail->Port = 587; // Modifica la porta se necessario
            // Abilita il debug SMTP
            $mail->SMTPDebug = 0; // Puoi impostare a 2 per ottenere più informazioni di debug

            // Imposta l'email del mittente
            $mail->setFrom($email, $name);
            // Imposta l'email di risposta
            $mail->addReplyTo($email, $name);
            // Imposta l'email del destinatario
            $mail->addAddress('recipient@example.com');
            // Imposta l'oggetto dell'email
            $mail->Subject = $subject;
            // Imposta il corpo dell'email
            $mail->Body = $message;

            // Invia l'email
            if ($mail->send()) {
                echo json_encode(array('status' => 'success', 'message' => 'Il tuo messaggio è stato inviato con successo. Grazie!'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Si è verificato un errore durante l\'invio del messaggio. Si prega di riprovare.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => implode("<br>", $errors)));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => implode("<br>", $errors)));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Si è verificato un errore durante l\'invio del messaggio. Si prega di riprovare.'));
}
?>
