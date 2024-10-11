// Step 1: Define necessary variables
$errors = [];
$success = false;

// Step 2: Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Step 3: Sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Step 4: Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    // Step 5: If no errors, process the form
    if (empty($errors)) {
        // Prepare email content
        $to = "your-email@example.com"; // Replace with your email
        $subject = "New Contact Form Submission";
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email";

        // Step 6: Send email
        if (mail($to, $subject, $body, $headers)) {
            $success = true;
        } else {
            $errors[] = "Failed to send the message. Please try again later.";
        }
    }
}

// Step 7: Display the form with success or error messages

