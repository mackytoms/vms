<?php
// File: api/routes.php - API routing

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Initialize database and controllers
$database = new Database();
$db = $database->getConnection();
$revisitController = new RevisitController($db);

// Parse request body
$request_body = json_decode(file_get_contents('php://input'), true);

// Route handling
switch (true) {
    case preg_match('/\/api\/revisit\/scan/', $request_uri) && $request_method == 'POST':
        echo $revisitController->scanQRCode($request_body);
        break;
        
    case preg_match('/\/api\/revisit\/lookup/', $request_uri) && $request_method == 'POST':
        echo $revisitController->lookupVisitor($request_body);
        break;
        
    case preg_match('/\/api\/revisit\/send-qr/', $request_uri) && $request_method == 'POST':
        echo $revisitController->sendQRCode($request_body);
        break;
        
    case preg_match('/\/api\/visits\/checkout/', $request_uri) && $request_method == 'POST':
        echo $revisitController->checkoutWithQR($request_body);
        break;
        
    case preg_match('/\/api\/delivery\/quick-register/', $request_uri) && $request_method == 'POST':
        echo $revisitController->handleDeliveryQuickRegister($request_body);
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
        break;
}
?>