<?php
/**
 * API Endpoint: Check for New Features/Updates
 */

header('Content-Type: application/json');

$lastCheck = isset($_GET['lastCheck']) ? intval($_GET['lastCheck']) : 0;

// Get the latest feature announcements
// Store features in a simple array - can be expanded to use a database
$featuresFile = 'features.json';

// Default response
$features = [
    'success' => true,
    'newFeatures' => [],
    'lastCheck' => time()
];

// Check if features file exists and read it
if (file_exists($featuresFile)) {
    $content = file_get_contents($featuresFile);
    $data = json_decode($content, true);
    
    if ($data && isset($data['features'])) {
        $features['newFeatures'] = $data['features'];
    }
}

// If no features, return empty array
if (empty($features['newFeatures'])) {
    $features['newFeatures'] = [];
}

echo json_encode($features);
