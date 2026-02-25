<?php
/**
 * VAPID Key Generator
 * Run this once to generate VAPID keys, then delete it
 * 
 * Install web-push: composer require minishlink/web-push
 */

require_once 'vendor/autoload.php';

use Minishlink\WebPush\VAPID;

$vapid = VAPID::createVapidKeys();

echo "VAPID Keys Generated!\n\n";
echo "Public Key:\n" . $vapid['publicKey'] . "\n\n";
echo "Private Key:\n" . $vapid['privateKey'] . "\n\n";
echo "\nAdd these to your .env or config:\n";
echo "VAPID_PUBLIC_KEY=" . $vapid['publicKey'] . "\n";
echo "VAPID_PRIVATE_KEY=" . $vapid['privateKey'] . "\n";
