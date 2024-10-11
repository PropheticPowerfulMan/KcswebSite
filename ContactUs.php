<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Valider les champs
    if (!empty($name) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/[\r\n]/", $email) && !preg_match("/[\r\n]/", $name)) {
            // Adresse e-mail de destination
            $to = "Jonathan@ourkcs.org";

            // Sujet de l'e-mail
            $subject = "Nouveau message de contact de " . $name;

            // Corps de l'e-mail
            $body = "Nom : $name\n";
            $body .= "Email : $email\n\n";
            $body .= "Message :\n$message\n";

            // En-têtes
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            // Envoi de l'e-mail
            if (mail($to, $subject, $body, $headers)) {
                // Redirection après soumission réussie
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
?>