<?php
require '../assets/vendor/php-email-form/php-email-form.php'; // Adjust the path as needed

$receiving_email_address = 'nipurn2001@gmail.com';

$contact = new PHP_Email_Form;
$contact->ajax = true;

$contact->to = $receiving_email_address;
$contact->from_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$contact->from_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$contact->subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);

// Uncomment below code if you want to use SMTP to send emails. You need to enter your correct SMTP credentials
$contact->smtp = array(
    'host' => 'smtp.gmail.com',
    'username' => 'nipurn2001@gmail.com',
    'password' => 'Ganon-2218', // Consider using environment variables for sensitive data
    'port' => '587',
    'encryption' => 'tls' // Add encryption method
);

$contact->add_message($_POST['name'], 'From');
$contact->add_message($_POST['email'], 'Email');
$contact->add_message($_POST['message'], 'Message', 10);

try {
    if ($contact->send()) {
        echo 'Email sent successfully.';
    } else {
        echo 'Failed to send email.';
    }
} catch (Exception $e) {
    echo 'Exception occurred: ' . $e->getMessage();
}
?>


