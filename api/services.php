<?php
/**
 * API - Get Services List
 * Returns all active services in JSON format
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

try {
    $services = Database::fetchAll(
        "SELECT id, smmwiz_id, platform, name, type, category,
                min_quantity, max_quantity, rate, our_rate,
                refill, cancel, description, status
         FROM services
         WHERE status = 'active'
         ORDER BY platform, display_order, name"
    );

    echo json_encode([
        'success' => true,
        'data' => $services,
        'count' => count($services)
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}