<?php
// PayFast Notification Handler

// Set debugging and sandbox/live mode
define('SANDBOX_MODE', true); // Set to false when going live
define('DEBUG', true);        // Set to false when going live

// Set PayFast merchant details (replace with your own)
$pfMerchantId = SANDBOX_MODE ? '10000100' : '27262073';
$pfMerchantKey = SANDBOX_MODE ? '46f0cd694581a' : ' gt6fyldqtfb02';
$passphrase = ''; // Set if configured in your PayFast account

// Set the PayFast server URL
$pfHost = SANDBOX_MODE ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';

// Error message array
$pfErrors = array();

// Log debugging information if enabled
if (DEBUG) {
    logData('PayFast ITN received');
    logData('POST data: ' . print_r($_POST, true));
}

// Verify that the request is from PayFast
if (!pfValidIP($_SERVER['REMOTE_ADDR'])) {
    $pfErrors[] = 'Invalid IP address: ' . $_SERVER['REMOTE_ADDR'];
    logData('Invalid IP address: ' . $_SERVER['REMOTE_ADDR']);
    exit('Invalid IP address');
}

// Verify that the data hasn't been tampered with
$pfData = $_POST;
$pfParamString = '';

// Remove the signature field for validation
if (isset($pfData['signature'])) {
    unset($pfData['signature']);
}

// Sort the data alphabetically
foreach ($pfData as $key => $val) {
    if ($key !== 'signature') {
        $pfParamString .= $key . '=' . urlencode($val) . '&';
    }
}

// Remove the last '&' from the parameter string
$pfParamString = rtrim($pfParamString, '&');

// If a passphrase is set, append it
if (!empty($passphrase)) {
    $pfParamString .= '&passphrase=' . urlencode($passphrase);
}

// Calculate security signature
$expectedSignature = md5($pfParamString);

// Verify signature
if (!isset($_POST['signature']) || $_POST['signature'] !== $expectedSignature) {
    $pfErrors[] = 'Invalid signature';
    logData('Invalid signature');
    exit('Invalid signature');
}

// Verify data based on merchant details
$validHosts = array(
    'www.payfast.co.za',
    'sandbox.payfast.co.za',
);

// Verify that the notification comes from a valid host
$validITN = false;
foreach ($validHosts as $pfHost) {
    $header = 'POST /eng/query/validate HTTP/1.0' . "\r\n";
    $header .= 'Host: ' . $pfHost . "\r\n";
    $header .= 'Content-Type: application/x-www-form-urlencoded' . "\r\n";
    $header .= 'Content-Length: ' . strlen($pfParamString) . "\r\n\r\n";

    // Connect to PayFast to validate data
    $socket = fsockopen('ssl://' . $pfHost, 443, $errno, $errstr, 10);
    if (!$socket) {
        $pfErrors[] = 'fsockopen error: ' . $errstr . ' (' . $errno . ')';
    } else {
        fputs($socket, $header . $pfParamString);
        $response = '';
        while (!feof($socket)) {
            $response .= fgets($socket, 1024);
        }
        fclose($socket);

        // Process the response
        $lines = explode("\r\n", $response);
        $verifyResult = trim(end($lines));

        if (strcasecmp($verifyResult, 'VALID') == 0) {
            $validITN = true;
            break;
        }
    }
}

// If the ITN is valid, process the payment
if ($validITN) {
    // Check payment data
    $pfPaymentId = $_POST['pf_payment_id'];
    $paymentStatus = $_POST['payment_status'];
    $amount = $_POST['amount_gross'];
    $merchantReference = $_POST['m_payment_id'];

    if (DEBUG) {
        logData('Payment validated successfully');
        logData('Payment ID: ' . $pfPaymentId);
        logData('Payment Status: ' . $paymentStatus);
        logData('Amount: ' . $amount);
        logData('Merchant Reference: ' . $merchantReference);
    }

    // Update your database with the payment information
    switch ($paymentStatus) {
        case 'COMPLETE':
            // Payment has been successfully processed
            updatePaymentStatus($merchantReference, 'complete', $pfPaymentId, $amount);
            break;
        case 'FAILED':
            // Payment has failed
            updatePaymentStatus($merchantReference, 'failed', $pfPaymentId, $amount);
            break;
        case 'PENDING':
            // Payment is pending
            updatePaymentStatus($merchantReference, 'pending', $pfPaymentId, $amount);
            break;
        default:
            // Unknown status
            updatePaymentStatus($merchantReference, 'unknown', $pfPaymentId, $amount);
            break;
    }
} else {
    // Log errors if any
    foreach ($pfErrors as $error) {
        logData('Error: ' . $error);
    }
    exit('Invalid ITN data');
}

/**
 * Validate that the IP address is from PayFast
 */
function pfValidIP($sourceIP)
{
    // Known PayFast IP addresses
    $validIPs = array(
        '170.247.101.0/28',
        '197.97.145.144/28',
        '41.74.179.192/27'
    );

    $valid = false;
    foreach ($validIPs as $ip) {
        if (isIPInRange($sourceIP, $ip)) {
            $valid = true;
            break;
        }
    }

    return $valid;
}

/**
 * Check if an IP is in a specific range
 */
function isIPInRange($ip, $range)
{
    list($range, $netmask) = explode('/', $range, 2);
    $rangeDecimal = ip2long($range);
    $ipDecimal = ip2long($ip);
    $wildcardDecimal = pow(2, (32 - $netmask)) - 1;
    $netmaskDecimal = ~$wildcardDecimal;

    return (($ipDecimal & $netmaskDecimal) == ($rangeDecimal & $netmaskDecimal));
}

/**
 * Log data to a file (you should implement this function)
 */
function logData($msg)
{
    // Implement your logging mechanism here
    $logFile = 'payfast_log.txt';
    file_put_contents($logFile, date('Y-m-d H:i:s') . ': ' . $msg . "\n", FILE_APPEND);
}

/**
 * Update payment status in your database (you should implement this function)
 */
function updatePaymentStatus($reference, $status, $paymentId, $amount)
{
    // Implement database update logic here
    // Example:
    // $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    // $stmt = $db->prepare("UPDATE payments SET status = ?, payment_id = ?, amount = ?, updated_at = NOW() WHERE reference = ?");
    // $stmt->execute([$status, $paymentId, $amount, $reference]);

    logData("Updated payment $reference to status: $status");
}

// Send a 200 OK response to PayFast
header('HTTP/1.0 200 OK');
echo 'OK';
