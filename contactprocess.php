<?php
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$to_email = "info@sablemed.com";
$from_domain = $_SERVER['HTTP_HOST'];
$site_name = "Sable Medical";

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to send JSON response
function send_json_response($success, $message, $redirect = null) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    if ($redirect) {
        $response['redirect'] = $redirect;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    send_json_response(false, "Invalid request method.");
}

// Get form type
$form_type = isset($_POST['form_type']) ? sanitize_input($_POST['form_type']) : '';

// Initialize response variables
$errors = [];
$form_data = [];

// Process based on form type
switch ($form_type) {
    case 'general':
        // Validate fields
        $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
        $subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : '';
        $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

        // Validation
        if (empty($name)) $errors[] = "Name is required.";
        if (empty($email)) $errors[] = "Email is required.";
        elseif (!validate_email($email)) $errors[] = "Invalid email format.";
        if (empty($subject)) $errors[] = "Subject is required.";
        if (empty($message)) $errors[] = "Message is required.";

        if (empty($errors)) {
            $email_subject = "General Inquiry: $subject";
            $email_body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; }
                        .header { background: #0066cc; color: white; padding: 20px; text-align: center; }
                        .content { padding: 20px; background: #f8fafc; }
                        .field { margin-bottom: 15px; }
                        .label { font-weight: bold; color: #0a3147; }
                        .value { margin-left: 10px; }
                        .footer { font-size: 12px; color: #666; text-align: center; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h2>New General Inquiry - Sable Medical</h2>
                    </div>
                    <div class='content'>
                        <div class='field'><span class='label'>Name:</span> <span class='value'>$name</span></div>
                        <div class='field'><span class='label'>Email:</span> <span class='value'>$email</span></div>
                        <div class='field'><span class='label'>Phone:</span> <span class='value'>" . (!empty($phone) ? $phone : 'Not provided') . "</span></div>
                        <div class='field'><span class='label'>Subject:</span> <span class='value'>$subject</span></div>
                        <div class='field'><span class='label'>Message:</span></div>
                        <div class='value'>" . nl2br($message) . "</div>
                    </div>
                    <div class='footer'>
                        <p>This message was sent from the Sable Medical website contact form.</p>
                        <p>IP Address: " . $_SERVER['REMOTE_ADDR'] . " | Date: " . date('Y-m-d H:i:s') . "</p>
                    </div>
                </body>
                </html>
            ";
        }
        break;

    case 'quotation':
        $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
        $company = isset($_POST['company']) ? sanitize_input($_POST['company']) : '';
        $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
        $category = isset($_POST['category']) ? sanitize_input($_POST['category']) : '';
        $details = isset($_POST['details']) ? sanitize_input($_POST['details']) : '';
        $quantity = isset($_POST['quantity']) ? sanitize_input($_POST['quantity']) : '';

        // Validation
        if (empty($name)) $errors[] = "Name is required.";
        if (empty($email)) $errors[] = "Email is required.";
        elseif (!validate_email($email)) $errors[] = "Invalid email format.";
        if (empty($phone)) $errors[] = "Phone is required.";
        if (empty($category)) $errors[] = "Product category is required.";
        if (empty($details)) $errors[] = "Product details are required.";

        if (empty($errors)) {
            $email_subject = "Quotation Request: $category";
            $email_body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; }
                        .header { background: #0066cc; color: white; padding: 20px; text-align: center; }
                        .content { padding: 20px; background: #f8fafc; }
                        .field { margin-bottom: 15px; }
                        .label { font-weight: bold; color: #0a3147; }
                        .value { margin-left: 10px; }
                        .footer { font-size: 12px; color: #666; text-align: center; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h2>New Quotation Request - Sable Medical</h2>
                    </div>
                    <div class='content'>
                        <div class='field'><span class='label'>Name:</span> <span class='value'>$name</span></div>
                        <div class='field'><span class='label'>Company:</span> <span class='value'>" . (!empty($company) ? $company : 'Not provided') . "</span></div>
                        <div class='field'><span class='label'>Email:</span> <span class='value'>$email</span></div>
                        <div class='field'><span class='label'>Phone:</span> <span class='value'>$phone</span></div>
                        <div class='field'><span class='label'>Product Category:</span> <span class='value'>$category</span></div>
                        <div class='field'><span class='label'>Quantity:</span> <span class='value'>" . (!empty($quantity) ? $quantity : 'Not specified') . "</span></div>
                        <div class='field'><span class='label'>Product Details:</span></div>
                        <div class='value'>" . nl2br($details) . "</div>
                    </div>
                    <div class='footer'>
                        <p>This message was sent from the Sable Medical website quotation form.</p>
                        <p>IP Address: " . $_SERVER['REMOTE_ADDR'] . " | Date: " . date('Y-m-d H:i:s') . "</p>
                    </div>
                </body>
                </html>
            ";
        }
        break;

    case 'partnership':
        $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
        $company_name = isset($_POST['company_name']) ? sanitize_input($_POST['company_name']) : '';
        $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
        $website = isset($_POST['website']) ? sanitize_input($_POST['website']) : '';
        $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

        // Validation
        if (empty($name)) $errors[] = "Name is required.";
        if (empty($company_name)) $errors[] = "Company name is required.";
        if (empty($email)) $errors[] = "Email is required.";
        elseif (!validate_email($email)) $errors[] = "Invalid email format.";
        if (empty($phone)) $errors[] = "Phone is required.";
        if (empty($message)) $errors[] = "Message is required.";

        if (empty($errors)) {
            $email_subject = "Partnership Inquiry: $company_name";
            $email_body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; }
                        .header { background: #0066cc; color: white; padding: 20px; text-align: center; }
                        .content { padding: 20px; background: #f8fafc; }
                        .field { margin-bottom: 15px; }
                        .label { font-weight: bold; color: #0a3147; }
                        .value { margin-left: 10px; }
                        .footer { font-size: 12px; color: #666; text-align: center; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h2>New Partnership Inquiry - Sable Medical</h2>
                    </div>
                    <div class='content'>
                        <div class='field'><span class='label'>Name:</span> <span class='value'>$name</span></div>
                        <div class='field'><span class='label'>Company:</span> <span class='value'>$company_name</span></div>
                        <div class='field'><span class='label'>Email:</span> <span class='value'>$email</span></div>
                        <div class='field'><span class='label'>Phone:</span> <span class='value'>$phone</span></div>
                        <div class='field'><span class='label'>Website:</span> <span class='value'>" . (!empty($website) ? $website : 'Not provided') . "</span></div>
                        <div class='field'><span class='label'>Message:</span></div>
                        <div class='value'>" . nl2br($message) . "</div>
                    </div>
                    <div class='footer'>
                        <p>This message was sent from the Sable Medical website partnership form.</p>
                        <p>IP Address: " . $_SERVER['REMOTE_ADDR'] . " | Date: " . date('Y-m-d H:i:s') . "</p>
                    </div>
                </body>
                </html>
            ";
        }
        break;

    case 'support':
        $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
        $order_ref = isset($_POST['order_ref']) ? sanitize_input($_POST['order_ref']) : '';
        $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

        // Validation
        if (empty($name)) $errors[] = "Name is required.";
        if (empty($email)) $errors[] = "Email is required.";
        elseif (!validate_email($email)) $errors[] = "Invalid email format.";
        if (empty($message)) $errors[] = "Message is required.";

        if (empty($errors)) {
            $email_subject = "Customer Support Request" . (!empty($order_ref) ? " - Order: $order_ref" : "");
            $email_body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; }
                        .header { background: #0066cc; color: white; padding: 20px; text-align: center; }
                        .content { padding: 20px; background: #f8fafc; }
                        .field { margin-bottom: 15px; }
                        .label { font-weight: bold; color: #0a3147; }
                        .value { margin-left: 10px; }
                        .footer { font-size: 12px; color: #666; text-align: center; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h2>Customer Support Request - Sable Medical</h2>
                    </div>
                    <div class='content'>
                        <div class='field'><span class='label'>Name:</span> <span class='value'>$name</span></div>
                        <div class='field'><span class='label'>Email:</span> <span class='value'>$email</span></div>
                        <div class='field'><span class='label'>Order Reference:</span> <span class='value'>" . (!empty($order_ref) ? $order_ref : 'Not provided') . "</span></div>
                        <div class='field'><span class='label'>Message:</span></div>
                        <div class='value'>" . nl2br($message) . "</div>
                    </div>
                    <div class='footer'>
                        <p>This message was sent from the Sable Medical website support form.</p>
                        <p>IP Address: " . $_SERVER['REMOTE_ADDR'] . " | Date: " . date('Y-m-d H:i:s') . "</p>
                    </div>
                </body>
                </html>
            ";
        }
        break;

    default:
        send_json_response(false, "Invalid form type.");
}

// If there are errors, return them
if (!empty($errors)) {
    send_json_response(false, implode("<br>", $errors));
}

// Send email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: Sable Medical Website <noreply@$from_domain>" . "\r\n";
$headers .= "Reply-To: $email" . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send to info@sablemed.com
$mail_sent = mail($to_email, $email_subject, $email_body, $headers);

// Also send a confirmation to the user (optional)
$user_subject = "Thank you for contacting Sable Medical";
$user_message = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #0066cc; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8fafc; }
        .footer { font-size: 12px; color: #666; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Thank You for Contacting Sable Medical</h2>
        </div>
        <div class='content'>
            <p>Dear $name,</p>
            <p>Thank you for reaching out to Sable Medical. We have received your inquiry and a representative will respond within 24 hours.</p>
            <p>If you have any urgent matters, please call us at <strong>+263 719 204 080</strong>.</p>
            <p>Best regards,<br>Sable Medical Team</p>
        </div>
        <div class='footer'>
            <p>Shop 125, 1st Floor, Joina City, Harare, Zimbabwe<br>
            +263 719 204 080 | info@sablemed.com</p>
        </div>
    </div>
</body>
</html>
";

$user_headers = "MIME-Version: 1.0" . "\r\n";
$user_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$user_headers .= "From: Sable Medical <info@sablemed.com>" . "\r\n";

mail($email, $user_subject, $user_message, $user_headers);

// Return response
if ($mail_sent) {
    send_json_response(true, "Thank you! Your message has been sent successfully.", "thank-you.html");
} else {
    send_json_response(false, "Sorry, there was an error sending your message. Please try again later or call us directly.");
}
?>