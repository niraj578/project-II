<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
require_once 'db.php';

// Simple AI chatbot for car rental
class CarRentalChatbot {
    private $responses;
    
    public function __construct() {
        $this->responses = [
            'greeting' => [
                'Hello! Welcome to our car rental service. How can I assist you today?',
                'Hi there! I\'m here to help with your car rental needs. What would you like to know?',
                'Welcome! I can help you with booking, pricing, and any questions about our car rental service.'
            ],
            'booking' => [
                'To book a car, you can visit our booking page or use the "Book Now" button on our website. What type of vehicle are you looking for?',
                'Great! I can help you find the perfect car. What dates do you need the vehicle for?',
                'Booking is easy! Just select your dates, choose a vehicle, and complete the reservation. Need help with anything specific?'
            ],
            'pricing' => [
                'Our pricing varies by vehicle type and rental duration. Economy cars start from $25/day, while luxury vehicles range from $80-150/day.',
                'We offer competitive rates! Daily rates start at $25 for economy cars, $45 for mid-size, and $80+ for luxury vehicles.',
                'Prices depend on the car category and rental period. Would you like to know about any specific vehicle type?'
            ],
            'availability' => [
                'You can check our current availability on the "Available Cars" page. What type of vehicle are you looking for?',
                'We have a wide selection of vehicles available. Are you interested in economy, mid-size, or luxury cars?',
                'Check our inventory online or tell me your preferred dates and vehicle type for real-time availability.'
            ],
            'support' => [
                'For immediate support, you can call us at (555) 123-4567 or email support@carrental.com. What specific issue can I help with?',
                'I\'m here to help! You can reach our support team 24/7. What do you need assistance with?',
                'Our customer support is available around the clock. How can I assist you today?'
            ],
            'payment' => [
                'We accept all major credit cards, PayPal, and bank transfers. Payment is due at the time of booking.',
                'Payment options include Visa, MasterCard, American Express, PayPal, and bank transfers.',
                'Secure payment processing is available with multiple options. Do you have a preferred payment method?'
            ],
            'insurance' => [
                'We offer comprehensive insurance coverage options. Basic coverage is included, with upgrade options available.',
                'Insurance coverage varies by package. We can discuss the best option for your needs.',
                'All rentals include basic insurance. Additional coverage options are available for peace of mind.'
            ],
            'location' => [
                'We have multiple locations throughout the city. Our main office is downtown with pickup/dropoff services.',
                'You can pick up and return vehicles at our main location or arrange for delivery service.',
                'We offer convenient pickup and dropoff locations. Where would you like to collect your vehicle?'
            ],
            'hours' => [
                'We\'re open Monday-Friday 8AM-8PM, Saturday 9AM-6PM, and Sunday 10AM-5PM. Emergency support available 24/7.',
                'Our business hours are 8AM-8PM weekdays, 9AM-6PM Saturday, and 10AM-5PM Sunday.',
                'Regular hours are 8AM-8PM weekdays. We also offer 24/7 emergency support for existing customers.'
            ],
            'cancellation' => [
                'Cancellations made 24 hours before pickup are free. Cancellations within 24 hours may incur a fee.',
                'You can cancel your booking up to 24 hours in advance without charge. Same-day cancellations may have fees.',
                'Our cancellation policy allows free cancellation 24 hours prior to pickup. Need to cancel your booking?'
            ],
            'default' => [
                'I understand you\'re asking about car rental services. Could you be more specific about what you need help with?',
                'I\'m here to help with car rental questions. You can ask about booking, pricing, availability, or support.',
                'That\'s an interesting question! I can help with booking cars, pricing, availability, and general support.',
                'I\'m designed to help with car rental inquiries. Feel free to ask about our services, vehicles, or booking process.'
            ]
        ];
    }
    
    public function processMessage($message) {
        $message = strtolower(trim($message));
        
        // Check for keywords and return appropriate response
        if ($this->containsKeywords($message, ['hello', 'hi', 'hey', 'good morning', 'good afternoon'])) {
            return $this->getRandomResponse('greeting');
        }
        
        if ($this->containsKeywords($message, ['book', 'booking', 'reserve', 'rent', 'rental'])) {
            return $this->getRandomResponse('booking');
        }
        
        if ($this->containsKeywords($message, ['price', 'cost', 'rate', 'how much', 'pricing', 'expensive'])) {
            return $this->getRandomResponse('pricing');
        }
        
        if ($this->containsKeywords($message, ['available', 'availability', 'in stock', 'cars', 'vehicles'])) {
            return $this->getRandomResponse('availability');
        }
        
        if ($this->containsKeywords($message, ['support', 'help', 'contact', 'problem', 'issue', 'assistance'])) {
            return $this->getRandomResponse('support');
        }
        
        if ($this->containsKeywords($message, ['payment', 'pay', 'credit card', 'paypal', 'cash'])) {
            return $this->getRandomResponse('payment');
        }
        
        if ($this->containsKeywords($message, ['insurance', 'coverage', 'protection', 'damage'])) {
            return $this->getRandomResponse('insurance');
        }
        
        if ($this->containsKeywords($message, ['location', 'where', 'address', 'pickup', 'dropoff'])) {
            return $this->getRandomResponse('location');
        }
        
        if ($this->containsKeywords($message, ['hours', 'time', 'open', 'close', 'when'])) {
            return $this->getRandomResponse('hours');
        }
        
        if ($this->containsKeywords($message, ['cancel', 'cancellation', 'refund', 'change'])) {
            return $this->getRandomResponse('cancellation');
        }
        
        // Default response
        return $this->getRandomResponse('default');
    }
    
    private function containsKeywords($message, $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }
    
    private function getRandomResponse($category) {
        $responses = $this->responses[$category];
        return $responses[array_rand($responses)];
    }
}

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['message']) && !empty($input['message'])) {
        $chatbot = new CarRentalChatbot();
        $response = $chatbot->processMessage($input['message']);
        
        // Save message to database
        $user_message = $input['message'];
        $user_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $session_id = session_id();
        
        try {
            $stmt = $pdo->prepare("INSERT INTO chatbot_messages (user_message, bot_response, user_ip, session_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_message, $response, $user_ip, $session_id]);
            $message_id = $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            // Return error in response for debugging
            echo json_encode([
                'response' => $response,
                'timestamp' => date('Y-m-d H:i:s'),
                'error' => 'Database error: ' . $e->getMessage(),
                'message_id' => null
            ]);
            exit();
        }
        
        echo json_encode([
            'response' => $response,
            'timestamp' => date('Y-m-d H:i:s'),
            'message_id' => $message_id
        ]);
    } else {
        echo json_encode([
            'response' => 'Please send a valid message.',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
} else {
    echo json_encode([
        'response' => 'Invalid request method.',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
