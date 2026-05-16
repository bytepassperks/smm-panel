<?php
/**
 * Smmwiz API Client v2
 *
 * Complete client for Smmwiz API v2 integration:
 * - services() - List all services
 * - order() - Add new order
 * - status() - Check single order status
 * - multiStatus() - Check multiple orders
 * - refill() - Request single refill
 * - multiRefill() - Request multiple refills
 * - refillStatus() - Check refill status
 * - multiRefillStatus() - Check multiple refills
 * - cancel() - Cancel order
 * - balance() - Get account balance
 *
 * @version 1.0.0
 * @see https://smmwiz.com/api/v2
 */

class SmmwizClient
{
    private string $apiKey;
    private string $baseUrl;
    private bool $debug;
    private array $lastRequest = [];
    private array $lastResponse = [];

    /**
     * Constructor
     *
     * @param string|null $apiKey - API key from Smmwiz
     * @param bool $debug - Enable debug mode
     */
    public function __construct(?string $apiKey = null, bool $debug = false)
    {
        $this->apiKey = $apiKey ?? SMMWIZ_API_KEY;
        $this->baseUrl = SMMWIZ_API_URL;
        $this->debug = $debug;
    }

    /**
     * Make API request to Smmwiz
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws SmmwizException
     */
    private function request(string $endpoint, array $data = []): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

        $data['key'] = $this->apiKey;

        $this->lastRequest = [
            'url' => $url,
            'method' => 'POST',
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        if ($this->debug) {
            error_log("Smmwiz Request: " . json_encode($data));
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new SmmwizException("cURL Error: " . $curlError, 0);
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new SmmwizException("Invalid JSON response: " . json_last_error_msg(), $httpCode);
        }

        $this->lastResponse = [
            'http_code' => $httpCode,
            'response' => $decoded,
            'raw' => $response,
        ];

        if ($this->debug) {
            error_log("Smmwiz Response [{$httpCode}]: " . json_encode($decoded));
        }

        // Smmwiz returns error in 'error' field or 'status' field
        if (isset($decoded['error'])) {
            throw new SmmwizException(
                $decoded['error'],
                $httpCode,
                $decoded
            );
        }

        if (isset($decoded['status']) && strtolower($decoded['status']) === 'error') {
            $errorMsg = $decoded['message'] ?? $decoded['error'] ?? 'Unknown error';
            throw new SmmwizException($errorMsg, $httpCode, $decoded);
        }

        return $decoded;
    }

    /**
     * Get all services from Smmwiz
     *
     * @return array - Array of service objects
     * @throws SmmwizException
     */
    public function services(): array
    {
        $response = $this->request('services');

        // Smmwiz returns services as 'services' or 'data' key
        $services = $response['services'] ?? $response['data'] ?? [];

        // Normalize service structure
        return array_map(function ($service) {
            return [
                'service' => $service['service'] ?? $service['id'] ?? null,
                'name' => $service['name'] ?? '',
                'category' => $service['category'] ?? '',
                'min' => (int) ($service['min'] ?? $service['min_quantity'] ?? 1),
                'max' => (int) ($service['max'] ?? $service['max_quantity'] ?? 100000),
                'rate' => (float) ($service['rate'] ?? 0),
                'refill' => (int) ($service['refill'] ?? 0),
                'cancel' => (int) ($service['cancel'] ?? 0),
                'type' => $service['type'] ?? '',
            ];
        }, $services);
    }

    /**
     * Add new order
     *
     * Required: service, link, quantity
     * Optional: runs, interval, custom_comments, mentions, etc.
     *
     * @param array $orderData
     * @return array
     * @throws SmmwizException
     */
    public function order(array $orderData): array
    {
        // Validate required fields
        if (empty($orderData['service'])) {
            throw new SmmwizException("Service ID is required", 400);
        }

        if (empty($orderData['link'])) {
            throw new SmmwizException("Link/URL is required", 400);
        }

        if (empty($orderData['quantity'])) {
            throw new SmmwizException("Quantity is required", 400);
        }

        // Build request payload
        $data = [
            'service' => $orderData['service'],
            'link' => $orderData['link'],
            'quantity' => (int) $orderData['quantity'],
        ];

        // Optional fields for advanced orders
        $optionalFields = [
            'runs',           // For drip-feed orders
            'interval',       // Interval between runs (minutes)
            'custom_comments', // Custom comments array
            'custom_comments_file', // File with custom comments
            'mentions',       // Username list for mentions
            'mentions_file',  // File with usernames
            'hashtag',        // Hashtags for mentions
            'username',       // For subscribe/follow type
            'media',          // Media ID for some services
            'keyword',        // Keyword for comments
            'avatar',         // Avatar URL
        ];

        foreach ($optionalFields as $field) {
            if (isset($orderData[$field]) && !empty($orderData[$field])) {
                $data[$field] = $orderData[$field];
            }
        }

        // Handle custom comments as array - convert to newlines
        if (isset($orderData['custom_comments']) && is_array($orderData['custom_comments'])) {
            $data['custom_comments'] = implode("\n", $orderData['custom_comments']);
        }

        // Handle mentions as array - convert to newlines
        if (isset($orderData['mentions']) && is_array($orderData['mentions'])) {
            $data['mentions'] = implode("\n", $orderData['mentions']);
        }

        $response = $this->request('add', $data);

        // Normalize response
        return [
            'order_id' => $response['order'] ?? $response['order_id'] ?? null,
            'status' => $response['status'] ?? 'success',
            'charge' => (float) ($response['charge'] ?? 0),
            'start_count' => (int) ($response['start_count'] ?? 0),
            'currency' => $response['currency'] ?? 'USD',
            'message' => $response['message'] ?? null,
        ];
    }

    /**
     * Check order status
     *
     * @param int $orderId - Smmwiz order ID
     * @return array
     * @throws SmmwizException
     */
    public function status(int $orderId): array
    {
        if (empty($orderId)) {
            throw new SmmwizException("Order ID is required", 400);
        }

        $response = $this->request('status', ['order' => $orderId]);

        return [
            'order_id' => $response['order'] ?? $orderId,
            'status' => $response['status'] ?? 'unknown',
            'charge' => (float) ($response['charge'] ?? 0),
            'start_count' => (int) ($response['start_count'] ?? 0),
            'remains' => (int) ($response['remains'] ?? 0),
            'currency' => $response['currency'] ?? 'USD',
        ];
    }

    /**
     * Check multiple orders status
     *
     * @param array $orderIds - Array of order IDs
     * @return array
     * @throws SmmwizException
     */
    public function multiStatus(array $orderIds): array
    {
        if (empty($orderIds)) {
            throw new SmmwizException("Order IDs array is required", 400);
        }

        $ordersString = implode(',', $orderIds);
        $response = $this->request('orders', ['orders' => $ordersString]);

        // Smmwiz returns orders array
        $orders = $response['orders'] ?? [];

        // Normalize each order
        return array_map(function ($order) {
            return [
                'order_id' => $order['order'] ?? null,
                'status' => $order['status'] ?? 'unknown',
                'charge' => (float) ($order['charge'] ?? 0),
                'start_count' => (int) ($order['start_count'] ?? 0),
                'remains' => (int) ($order['remains'] ?? 0),
            ];
        }, $orders);
    }

    /**
     * Request refill for an order
     *
     * @param int $orderId - Smmwiz order ID
     * @return array
     * @throws SmmwizException
     */
    public function refill(int $orderId): array
    {
        if (empty($orderId)) {
            throw new SmmwizException("Order ID is required", 400);
        }

        $response = $this->request('refill', ['order' => $orderId]);

        return [
            'refill_id' => $response['refill'] ?? $response['refill_id'] ?? null,
            'status' => $response['status'] ?? 'success',
            'message' => $response['message'] ?? null,
        ];
    }

    /**
     * Request refill for multiple orders
     *
     * @param array $orderIds - Array of order IDs
     * @return array
     * @throws SmmwizException
     */
    public function multiRefill(array $orderIds): array
    {
        if (empty($orderIds)) {
            throw new SmmwizException("Order IDs array is required", 400);
        }

        $ordersString = implode(',', $orderIds);
        $response = $this->request('refill/orders', ['orders' => $ordersString]);

        // Normalize response
        $refills = $response['refills'] ?? [];

        return array_map(function ($refill) {
            return [
                'order_id' => $refill['order'] ?? null,
                'refill_id' => $refill['refill'] ?? null,
                'status' => $refill['status'] ?? 'success',
            ];
        }, $refills);
    }

    /**
     * Check refill status
     *
     * @param int $refillId - Smmwiz refill ID
     * @return array
     * @throws SmmwizException
     */
    public function refillStatus(int $refillId): array
    {
        if (empty($refillId)) {
            throw new SmmwizException("Refill ID is required", 400);
        }

        $response = $this->request('refill/status', ['refill' => $refillId]);

        return [
            'refill_id' => $refillId,
            'status' => $response['status'] ?? 'unknown',
            'message' => $response['message'] ?? null,
        ];
    }

    /**
     * Check multiple refill statuses
     *
     * @param array $refillIds - Array of refill IDs
     * @return array
     * @throws SmmwizException
     */
    public function multiRefillStatus(array $refillIds): array
    {
        if (empty($refillIds)) {
            throw new SmmwizException("Refill IDs array is required", 400);
        }

        $refillsString = implode(',', $refillIds);
        $response = $this->request('refill/statuses', ['refills' => $refillsString]);

        $refills = $response['refills'] ?? [];

        return array_map(function ($refill) {
            return [
                'refill_id' => $refill['refill'] ?? null,
                'status' => $refill['status'] ?? 'unknown',
                'message' => $refill['message'] ?? null,
            ];
        }, $refills);
    }

    /**
     * Cancel an order
     *
     * @param int $orderId - Smmwiz order ID
     * @return array
     * @throws SmmwizException
     */
    public function cancel(int $orderId): array
    {
        if (empty($orderId)) {
            throw new SmmwizException("Order ID is required", 400);
        }

        $response = $this->request('cancel', ['order' => $orderId]);

        return [
            'order_id' => $orderId,
            'status' => $response['status'] ?? 'success',
            'message' => $response['message'] ?? null,
        ];
    }

    /**
     * Cancel multiple orders
     *
     * @param array $orderIds - Array of order IDs
     * @return array
     * @throws SmmwizException
     */
    public function cancelMultiple(array $orderIds): array
    {
        if (empty($orderIds)) {
            throw new SmmwizException("Order IDs array is required", 400);
        }

        $ordersString = implode(',', $orderIds);
        $response = $this->request('cancel/orders', ['orders' => $ordersString]);

        $cancellations = $response['orders'] ?? [];

        return array_map(function ($item) {
            return [
                'order_id' => $item['order'] ?? null,
                'status' => $item['status'] ?? 'unknown',
            ];
        }, $cancellations);
    }

    /**
     * Get account balance
     *
     * @return array
     * @throws SmmwizException
     */
    public function balance(): array
    {
        $response = $this->request('balance');

        return [
            'balance' => (float) ($response['balance'] ?? 0),
            'currency' => $response['currency'] ?? 'USD',
        ];
    }

    /**
     * Get last request details (for debugging)
     *
     * @return array
     */
    public function getLastRequest(): array
    {
        return $this->lastRequest;
    }

    /**
     * Get last response details (for debugging)
     *
     * @return array
     */
    public function getLastResponse(): array
    {
        return $this->lastResponse;
    }
}

/**
 * Smmwiz Exception Class
 */
class SmmwizException extends Exception
{
    private array $response;

    public function __construct(string $message, int $code = 0, array $response = [])
    {
        parent::__construct($message, $code);
        $this->response = $response;
    }

    public function getResponse(): array
    {
        return $this->response;
    }
}

// Helper function to create client instance
function smmwiz(): SmmwizClient
{
    static $client = null;

    if ($client === null) {
        $debug = defined('APP_DEBUG') && APP_DEBUG === true;
        $client = new SmmwizClient(null, $debug);
    }

    return $client;
}