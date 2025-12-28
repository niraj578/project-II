<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['session_id']) && !empty($input['session_id'])) {
        $session_id = $input['session_id'];
        
        try {
            // Get messages with admin replies for this session
            $stmt = $pdo->prepare("SELECT id, user_message, admin_reply, admin_replied_at, status 
                                  FROM chatbot_messages 
                                  WHERE session_id = ? AND admin_reply IS NOT NULL 
                                  ORDER BY admin_replied_at DESC");
            $stmt->execute([$session_id]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error fetching admin replies'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Session ID required'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
