<?php
// Konnselex contact form handler
// Upload this file in the same folder as index.html on a PHP-enabled hosting server.

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html#contact");
    exit;
}

// Honeypot spam field. Real visitors will leave this empty.
if (!empty($_POST["website"])) {
    header("Location: index.html?sent=1#contact");
    exit;
}

$to = "contact@konnselex.com";
$subject = "New Konnselex Consultation Request";

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$education = trim($_POST["education"] ?? "");
$message = trim($_POST["message"] ?? "");
$terms = trim($_POST["terms"] ?? "");

if ($name === "" || $email === "" || $phone === "" || $terms === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.html?error=validation#contact");
    exit;
}

$clean = function ($value) {
    return htmlspecialchars(strip_tags($value), ENT_QUOTES, "UTF-8");
};

$emailBody = "New consultation request from Konnselex website:\n\n";
$emailBody .= "Name: " . $clean($name) . "\n";
$emailBody .= "Email: " . $clean($email) . "\n";
$emailBody .= "Phone / WhatsApp: " . $clean($phone) . "\n";
$emailBody .= "Education Level: " . $clean($education) . "\n\n";
$emailBody .= "Message:\n" . $clean($message) . "\n\n";
$emailBody .= "Terms Accepted: Yes\n";
$emailBody .= "Submitted: " . date("Y-m-d H:i:s") . "\n";

$headers = [];
$headers[] = "From: Konnselex Website <no-reply@konnselex.com>";
$headers[] = "Reply-To: " . $name . " <" . $email . ">";
$headers[] = "Content-Type: text/plain; charset=UTF-8";

if (mail($to, $subject, $emailBody, implode("\r\n", $headers))) {
    header("Location: index.html?sent=1#contact");
    exit;
}

header("Location: index.html?error=mail#contact");
exit;
?>