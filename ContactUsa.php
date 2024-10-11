<?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    if ($name !== '' && $email !== '' && $message !== '') {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/[\r\n]/", $email) && !preg_match("/[\r\n]/", $name)) {
            $to = "jonathanlokala9@gmail.com";
            $subject = "Nouveau message de contact de " . $name;
            $body = "Nom : $name\nEmail : $email\n\nMessage :\n$message\n";
            $headers = "From: $email\r\nReply-To: $email\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";

            if (mail($to, $subject, $body, $headers)) {
                header("Location: confirmation.php");
                exit();
            } else {
                echo "Une erreur s'est produite lors de l'envoi du message.";
            }
        } else {
            echo "Veuillez entrer une adresse e-mail valide.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}
