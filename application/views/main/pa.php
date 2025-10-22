<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Check-In Kiosk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- QR Code Library -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/qrcodejs2@0.0.2/qrcode.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/qr-creator/dist/qr-creator.min.js"></script>
    <!-- QR Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .kiosk-container {
            height: 100vh;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            background: white;
        }

        /* Header */
        .kiosk-header {
            background: linear-gradient(135deg, #1e9338a8 0%, #1e9338 100%);
            color: white;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .company-logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .kiosk-header h1 {
            font-size: 2em;
            font-weight: 800;
            margin: 8px 0;
            letter-spacing: 1px;
        }

        .datetime-display {
            font-size: 1em;
            opacity: 0.9;
        }

        /* Main Content Area */
        .kiosk-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #f8f9fa;
            position: relative;
            overflow-y: auto;
        }

        /* Step Indicator */
        .step-indicator {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .step-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #dee2e6;
            transition: all 0.3s ease;
        }

        .step-dot.active {
            background: #1e9338;
            transform: scale(1.5);
        }

        .step-dot.completed {
            background: #27ae60;
        }

        /* Screen Containers */
        .screen {
            display: none;
            animation: slideIn 0.4s ease;
            width: 100%;
            max-width: 680px;
        }

        .screen.active {
            display: block;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Welcome Screen */
        .welcome-screen {
            text-align: center;
        }

        .welcome-icon {
            font-size: 80px;
            color: #1e9338;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        .welcome-message {
            font-size: 2.2em;
            color: #1e9338a8;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .welcome-submessage {
            font-size: 1.2em;
            color: #7f8c8d;
            margin-bottom: 25px;
        }

        .language-selector {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .language-btn {
            padding: 8px 20px;
            border: 2px solid #1e9338;
            background: white;
            color: #1e9338;
            border-radius: 20px;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .language-btn:hover, .language-btn.active {
            background: #1e9338;
            color: white;
            transform: translateY(-2px);
        }

        /* Action Buttons */
        .action-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: #1e9338;
        }

        .action-card i {
            font-size: 3em;
            margin-bottom: 15px;
            display: block;
        }

        .action-card h3 {
            font-size: 1.4em;
            margin-bottom: 8px;
            color: #1e9338a8;
        }

        .action-card p {
            color: #7f8c8d;
            font-size: 0.95em;
        }

        /* Form Styles */
        .form-screen {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .form-title {
            font-size: 1.8em;
            color: #1e9338a8;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 1.1em;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .form-control-lg {
            font-size: 1.2em;
            padding: 12px 15px;
            border-radius: 10px;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control-lg:focus {
            border-color: #1e9338;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .form-control-lg.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: none;
            font-size: 0.875em;
            color: #dc3545;
            margin-top: 0.25rem;
        }

        .form-control-lg.is-invalid ~ .invalid-feedback {
            display: block;
        }

        /* QR Scanner Styles */
        .qr-scanner-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        #qr-reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        #qr-reader video {
            border-radius: 10px;
        }

        .qr-upload-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #dee2e6;
        }

        .qr-upload-btn {
            position: relative;
            display: inline-block;
            padding: 12px 30px;
            background: white;
            border: 2px solid #1e9338;
            color: #1e9338;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .qr-upload-btn:hover {
            background: #1e9338;
            color: white;
        }

        .qr-upload-btn input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        /* QR Code Display */
        .qr-code-display {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .qr-code-display canvas {
            margin: 0 auto;
        }

        /* Photo Capture */
        .photo-capture-container {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin: 15px 0;
        }

        .camera-view {
            width: 280px;
            height: 210px;
            background: #1e9338a8;
            margin: 0 auto 15px;
            border-radius: 12px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        #videoElement, #capturedImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        #capturedImage {
            display: none;
        }

        .camera-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 35%, rgba(0,0,0,0.3) 100%);
            pointer-events: none;
        }

        .face-guide {
            width: 150px;
            height: 180px;
            border: 3px dashed rgba(255,255,255,0.5);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 2s infinite;
        }

        /* Host Selection */
        .host-search {
            position: relative;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #1e9338;
            border-radius: 10px;
            margin-top: 5px;
            max-height: 250px;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .search-result-item {
            padding: 12px 15px;
            border-bottom: 1px solid #ecf0f1;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-result-item:hover {
            background: #1e9338;
            color: white;
        }

        .employee-info {
            flex: 1;
        }

        .employee-name {
            font-weight: 600;
            font-size: 1em;
        }

        .employee-dept {
            font-size: 0.85em;
            opacity: 0.7;
        }

        /* Purpose Selection */
        .purpose-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .purpose-card {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 20px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .purpose-card:hover {
            border-color: #1e9338;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .purpose-card.selected {
            background: #1e9338;
            color: white;
            border-color: #1e9338;
        }

        .purpose-card i {
            font-size: 2.5em;
            margin-bottom: 8px;
        }

        .purpose-card h5 {
            font-size: 0.95em;
            margin: 0;
        }

        /* Agreement Screen */
        .agreement-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 15px 0;
            max-height: 300px;
            overflow-y: auto;
        }

        .agreement-text {
            font-size: 1em;
            line-height: 1.6;
            color: #495057;
        }

        .agreement-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            margin: 15px 0;
            cursor: pointer;
        }

        .agreement-checkbox input[type="checkbox"] {
            width: 24px;
            height: 24px;
            cursor: pointer;
            margin-top: 3px;
        }

        .agreement-checkbox label {
            flex: 1;
            font-size: 1.05em;
            cursor: pointer;
        }

        /* Success Screen */
        .success-screen {
            text-align: center;
            padding: 30px;
            margin-top: 280px;
        }

        .success-icon {
            width: 120px;
            height: 120px;
            background: #27ae60;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-icon i {
            color: white;
            font-size: 60px;
        }

        .badge-preview {
            background: white;
            border: 3px solid #1e9338;
            border-radius: 12px;
            padding: 25px;
            max-width: 350px;
            margin: 25px auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .badge-photo-display {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 15px;
            border: 3px solid #1e9338;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-photo-display img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 15px;
        }

        .btn-large {
            padding: 15px 30px;
            font-size: 1.2em;
            border-radius: 10px;
            min-width: 150px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-back {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-back:hover {
            background: #d5dbde;
            transform: translateX(-3px);
        }

        .btn-next {
            background: linear-gradient(135deg, #1e9338, #1e9338a8);
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .btn-next:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-print {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.3);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(155, 89, 182, 0.4);
        }

        /* Loading Spinner */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #1e9338;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            color: white;
            font-size: 1.3em;
            margin-top: 15px;
        }

        /* Emergency Button */
        .emergency-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8em;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .emergency-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.5);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 15px;
        }

        .quick-action-btn {
            padding: 10px 20px;
            background: white;
            border: 2px solid #1e9338;
            color: #1e9338;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1em;
        }

        .quick-action-btn:hover {
            background: #1e9338;
            color: white;
            transform: translateY(-2px);
        }

        /* Modal Styles */
        .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .booking-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .booking-item:hover {
            border-color: #1e9338;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .booking-code {
            font-family: monospace;
            font-size: 1.2em;
            font-weight: bold;
            color: #1e9338;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .badge-preview, .badge-preview * {
                visibility: visible;
            }
            .badge-preview {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
            }
        }
    </style>
</head>
<body>
    <!-- Main Kiosk Container -->
    <div class="kiosk-container">
        <!-- Header -->
        <div class="kiosk-header">
            <div class="company-logo">
                <img src="<?= base_url('assets/images/icons/473762608_905226608452197_3072891570387687458_n.jpg') ?>" 
                    alt="PAN-ASIA" 
                style="width: 40px; height: 40px; object-fit: contain; border-radius: 50%;">
            </div>
            <h1 data-translate="companyName">Welcome to PAN-ASIA</h1>
            <div class="datetime-display" id="datetime"></div>
        </div>

        <!-- Content Area -->
        <div class="kiosk-content">
            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step-dot active"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
            </div>

            <!-- Screen 1: Welcome -->
            <div class="screen active" id="welcomeScreen">
                <div class="welcome-screen">
                    <p class="welcome-submessage" data-translate="selectLanguage">Please select your preferred language</p>
                    
                    <div class="language-selector">
                        <button class="language-btn active" onclick="selectLanguage('en')">English</button>
                        <button class="language-btn" onclick="selectLanguage('zh-TW')">繁體中文</button>
                        <button class="language-btn" onclick="selectLanguage('zh-CN')">简体中文</button>
                        <button class="language-btn" onclick="selectLanguage('fil')">Filipino</button>
                        <button class="language-btn" onclick="selectLanguage('ja')">日本語</button>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="action-card" onclick="startCheckIn('new')">
                                <i class="bi bi-person-plus text-primary"></i>
                                <h3 data-translate="firstTimeVisitor">First Time Visitor</h3>
                                <p data-translate="firstTimeDesc">I'm visiting for the first time</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="action-card" onclick="startCheckIn('returning')">
                                <i class="bi bi-person-check text-success"></i>
                                <h3 data-translate="returningVisitor">Returning Visitor</h3>
                                <p data-translate="returningDesc">I've been here before</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="action-card" onclick="startCheckIn('delivery')">
                                <i class="bi bi-truck text-warning"></i>
                                <h3 data-translate="deliveryPickup">Delivery / Pickup</h3>
                                <p data-translate="deliveryDesc">I have a delivery or pickup</p>
                            </div>
                        </div>
                    </div>

                    <div class="quick-actions">
                        <button class="quick-action-btn" onclick="showPreScheduled()">
                            <i class="bi bi-calendar-check"></i> <span data-translate="preScheduled">Pre-Scheduled Visit</span>
                        </button>
                        <button class="quick-action-btn" onclick="checkOut()">
                            <i class="bi bi-box-arrow-right"></i> <span data-translate="checkOut">Check Out</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Screen 2: QR Scanner for Returning Visitors -->
            <div class="screen" id="qrScannerScreen">
                <div class="qr-scanner-container">
                    <h2 class="form-title" data-translate="scanQRTitle">Scan Your QR Code</h2>
                    <p class="text-center text-muted mb-4" data-translate="scanQRDesc">Please scan the QR code from your previous visit</p>
                    
                    <div id="qr-reader"></div>
                    
                    <div class="qr-upload-section">
                        <p class="text-muted mb-3" data-translate="orUploadQR">Or upload QR code image</p>
                        <label class="qr-upload-btn">
                            <i class="bi bi-upload"></i> <span data-translate="uploadQR">Upload QR Code</span>
                            <input type="file" accept="image/*" onchange="handleQRUpload(this)">
                        </label>
                    </div>

                    <div class="nav-buttons">
                        <button class="btn-large btn-back" onclick="previousScreen()">
                            <i class="bi bi-arrow-left"></i> <span data-translate="back">Back</span>
                        </button>
                        <button class="btn-large btn-next" onclick="skipQRScan()">
                            <span data-translate="noQRCode">I don't have my QR code</span> <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Screen 3: Basic Information -->
            <div class="screen" id="basicInfoScreen">
                <div class="form-screen">
                    <h2 class="form-title" data-translate="letsCheckIn">Let's get you checked in!</h2>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="firstName">First Name *</label>
                                <input type="text" class="form-control form-control-lg" id="firstName" placeholder="John">
                                <div class="invalid-feedback" data-translate="firstNameRequired">First name is required</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="lastName">Last Name *</label>
                                <input type="text" class="form-control form-control-lg" id="lastName" placeholder="Smith">
                                <div class="invalid-feedback" data-translate="lastNameRequired">Last name is required</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" data-translate="email">Email Address *</label>
                        <input type="email" class="form-control form-control-lg" id="email" placeholder="john.smith@company.com">
                        <div class="invalid-feedback" data-translate="emailInvalid">Please enter a valid email address</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="phone">Phone Number *</label>
                                <input type="tel" class="form-control form-control-lg" id="phone" placeholder="(555) 123-4567">
                                <div class="invalid-feedback" data-translate="phoneInvalid">Please enter a valid phone number</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="company">Company *</label>
                                <input type="text" class="form-control form-control-lg" id="company" data-translate-placeholder="companyPlaceholder" placeholder="Your Company Name">
                                <div class="invalid-feedback" data-translate="companyRequired">Company name is required</div>
                            </div>
                        </div>
                    </div>

                    <div class="nav-buttons">
                        <button class="btn-large btn-back" onclick="previousScreen()">
                            <i class="bi bi-arrow-left"></i> <span data-translate="back">Back</span>
                        </button>
                        <button class="btn-large btn-next" onclick="nextScreen()">
                            <span data-translate="continue">Continue</span> <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Screen 4: Photo Capture -->
            <div class="screen" id="photoScreen">
                <div class="form-screen">
                    <h2 class="form-title" data-translate="photoTitle">Let's take your photo</h2>
                    <p class="text-center text-muted mb-3" data-translate="photoDesc">This helps our staff identify you and ensures building security</p>
                    
                    <div class="photo-capture-container">
                        <div class="camera-view">
                            <video id="videoElement" autoplay></video>
                            <img id="capturedImage" alt="Captured photo">
                            <div class="face-guide"></div>
                            <div class="camera-overlay"></div>
                        </div>
                        <button class="btn-large btn-next" onclick="capturePhoto()" id="captureBtn">
                            <i class="bi bi-camera"></i> <span data-translate="takePhoto">Take Photo</span>
                        </button>
                        <button class="btn-large btn-next" onclick="retakePhoto()" id="retakeBtn" style="display: none;">
                            <i class="bi bi-arrow-clockwise"></i> <span data-translate="retakePhoto">Retake Photo</span>
                        </button>
                        <p class="text-muted mt-2" data-translate="photoGuide">Position your face within the oval guide</p>
                    </div>

                    <div class="nav-buttons">
                        <button class="btn-large btn-back" onclick="previousScreen()">
                            <i class="bi bi-arrow-left"></i> <span data-translate="back">Back</span>
                        </button>
                        <button class="btn-large btn-next" onclick="nextScreen()">
                            <span data-translate="skipNow">Skip for Now</span> <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Screen 5: Host Selection -->
            <div class="screen" id="hostScreen">
                <div class="form-screen">
                    <h2 class="form-title" data-translate="hostTitle">Who are you here to see?</h2>

                    <div class="mt-3">
                        <p class="text-muted" data-translate="popularDepts">Popular departments:</p>
                        <div class="quick-actions">
                            <button class="quick-action-btn" onclick="selectDepartment('Sales')"><span data-translate="sales">Sales</span></button>
                            <button class="quick-action-btn" onclick="selectDepartment('HR')"><span data-translate="hr">Human Resources</span></button>
                            <button class="quick-action-btn" onclick="selectDepartment('IT')"><span data-translate="it">IT Support</span></button>
                            <button class="quick-action-btn" onclick="selectDepartment('Reception')"><span data-translate="reception">Reception</span></button>
                        </div>
                    </div>
                    
                    <div class="form-group host-search" style="margin-top: 15px;">
                        <label class="form-label" data-translate="searchHost">Search by name or department</label>
                        <input type="text" class="form-control form-control-lg" id="hostSearch" 
                               data-translate-placeholder="hostSearchPlaceholder"
                               placeholder="Start typing name..." oninput="searchHost(this.value)">
                        
                        <div class="search-results" id="searchResults" style="display: none;"></div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label" data-translate="selectedHost">Selected Host</label>
                        <div class="form-control form-control-lg" id="selectedHost" style="background: #f8f9fa;">
                            <span class="text-muted" data-translate="noSelection">No one selected yet</span>
                        </div>
                    </div>

                    <div class="nav-buttons">
                        <button class="btn-large btn-back" onclick="previousScreen()">
                            <i class="bi bi-arrow-left"></i> <span data-translate="back">Back</span>
                        </button>
                        <button class="btn-large btn-next" onclick="nextScreen()" disabled id="hostNextBtn">
                            <span data-translate="continue">Continue</span> <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Screen 6: Purpose Selection -->
            <div class="screen" id="purposeScreen">
                <div class="form-screen">
                    <h2 class="form-title" data-translate="purposeTitle">What brings you here today?</h2>
                    
                    <div class="purpose-grid">
                        <div class="purpose-card" onclick="selectPurpose('meeting', this)">
                            <i class="bi bi-people text-primary"></i>
                            <h5 data-translate="meeting">Meeting</h5>
                        </div>
                        <div class="purpose-card" onclick="selectPurpose('interview', this)">
                            <i class="bi bi-briefcase text-success"></i>
                            <h5 data-translate="interview">Interview</h5>
                        </div>
                        <div class="purpose-card" onclick="selectPurpose('delivery', this)">
                            <i class="bi bi-box text-warning"></i>
                            <h5 data-translate="delivery">Delivery</h5>
                        </div>
                        <div class="purpose-card" onclick="selectPurpose('service', this)">
                            <i class="bi bi-tools text-info"></i>
                            <h5 data-translate="service">Service/Repair</h5>
                        </div>
                        <div class="purpose-card" onclick="selectPurpose('training', this)">
                            <i class="bi bi-mortarboard text-danger"></i>
                            <h5 data-translate="training">Training</h5>
                        </div>
                        <div class="purpose-card" onclick="selectPurpose('tour', this)">
                            <i class="bi bi-map text-secondary"></i>
                            <h5 data-translate="tour">Tour</h5>
                        </div>
                        <div class="purpose-card" onclick="selectPurpose('event', this)">
                            <i class="bi bi-calendar-event" style="color: purple;"></i>
                            <h5 data-translate="event">Event</h5>
                        </div>
                        <div class="purpose-card" onclick="selectPurpose('other', this)">
                            <i class="bi bi-three-dots text-dark"></i>
                            <h5 data-translate="other">Other</h5>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label" data-translate="additionalNotes">Additional notes (optional)</label>
                        <textarea class="form-control form-control-lg" id="visitNotes" rows="2" 
                                  data-translate-placeholder="notesPlaceholder"
                                  placeholder="Any additional information..."></textarea>
                    </div>

                    <div class="nav-buttons">
                        <button class="btn-large btn-back" onclick="previousScreen()">
                            <i class="bi bi-arrow-left"></i> <span data-translate="back">Back</span>
                        </button>
                        <button class="btn-large btn-next" onclick="nextScreen()" disabled id="purposeNextBtn">
                            <span data-translate="continue">Continue</span> <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Screen 7: Agreements -->
            <div class="screen" id="agreementScreen">
                <div class="form-screen">
                    <h2 class="form-title" data-translate="termsTitle">Terms & Agreements</h2>
                    
                    <div class="agreement-container">
                        <div class="agreement-text" id="agreementText">
                            <!-- Agreement content will be populated by JavaScript based on language -->
                        </div>
                    </div>

                    <div class="agreement-checkbox">
                        <input type="checkbox" id="agreeTerms" onchange="checkAgreement()">
                        <label for="agreeTerms" data-translate="agreeTerms">
                            I have read and agree to all terms, conditions, and guidelines
                        </label>
                    </div>

                    <div class="agreement-checkbox">
                        <input type="checkbox" id="agreePhoto" onchange="checkAgreement()">
                        <label for="agreePhoto" data-translate="agreePhoto">
                            I consent to my photo being used for security purposes
                        </label>
                    </div>

                    <div class="nav-buttons">
                        <button class="btn-large btn-back" onclick="previousScreen()">
                            <i class="bi bi-arrow-left"></i> <span data-translate="back">Back</span>
                        </button>
                        <button class="btn-large btn-next" onclick="completeCheckIn()" disabled id="agreeNextBtn">
                            <span data-translate="completeCheckIn">Complete Check-In</span> <i class="bi bi-check-circle"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Screen 8: Success -->
            <div class="screen" id="successScreen">
                <div class="success-screen">
                    <div class="success-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    
                    <h2 style="color: #27ae60; font-size: 2em; margin-bottom: 15px;" data-translate="successTitle">You're All Set!</h2>
                    <p style="font-size: 1.1em; color: #7f8c8d; margin-bottom: 20px;" data-translate="successMessage">
                        Your host has been notified of your arrival
                    </p>

                    <div class="badge-preview" id="badgePreview">
                        <h4 style="color: #1e9338; margin-bottom: 15px;" data-translate="visitorBadge">Your Visitor Badge</h4>
                        <div class="badge-photo-display" id="badgePhotoDisplay">
                            <i class="bi bi-person-circle" style="font-size: 3em; color: #dee2e6;"></i>
                        </div>
                        <div id="badgeNumber" style="font-size: 1.6em; font-weight: bold; color: #1e9338a8; margin-bottom: 10px;">
                            V-2024-1201
                        </div>
                        <div id="visitorName" style="font-size: 1.3em; margin-bottom: 8px;"></div>
                        <div id="visitorCompany" style="color: #7f8c8d; margin-bottom: 12px;"></div>
                        <hr>
                        <div style="margin-top: 12px; text-align: left;">
                            <strong data-translate="host">Host:</strong> <span id="badgeHost"></span><br>
                            <strong data-translate="validUntil">Valid Until:</strong> <span id="validUntil"></span>
                        </div>
                    </div>

                    <div class="qr-code-display">
                        <h5 style="color: #1e9338a8; margin-bottom: 10px;" data-translate="yourQRCode">Your QR Code for Next Visit</h5>
                        <div style="display: flex; justify-content: center; flex-direction: column;" id="qrcode"></div>
                        <p style="margin-top: 10px; font-size: 0.9em; color: #7f8c8d;" data-translate="qrCodeNote">
                            Save this QR code for faster check-in on your next visit
                        </p>
                    </div>

                    <div style="background: #e8f4fd; border-left: 4px solid #1e9338; padding: 15px; border-radius: 8px; margin: 20px 0; text-align: left;">
                        <h5 style="color: #1e9338a8; margin-bottom: 8px;">
                            <i class="bi bi-info-circle"></i> <span data-translate="nextSteps">Next Steps</span>
                        </h5>
                        <ol style="margin: 0; padding-left: 20px; color: #495057;" id="nextStepsList">
                            <!-- Next steps will be populated by JavaScript based on language -->
                        </ol>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn-large btn-print" onclick="printBadge()">
                            <i class="bi bi-printer"></i> <span data-translate="printBadge">Print Badge</span>
                        </button>
                        <button class="btn-large btn-next" onclick="resetKiosk()">
                            <i class="bi bi-check-circle"></i> <span data-translate="done">Done</span>
                        </button>
                    </div>

                    <p style="margin-top: 15px; color: #95a5a6;">
                        <span data-translate="autoReset">This screen will reset in</span> <span id="countdown">60</span> <span data-translate="seconds">seconds</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pre-Scheduled Visit Modal -->
    <div class="modal fade" id="preScheduledModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" data-translate="preScheduledTitle">Pre-Scheduled Visit Check-In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label" data-translate="enterCode">Enter your booking code or search by name:</label>
                        <input type="text" class="form-control form-control-lg" id="bookingSearch" 
                               onkeyup="searchBookings(this.value)"
                               data-translate-placeholder="bookingSearchPlaceholder"
                               placeholder="Enter booking code or name...">
                    </div>
                    
                    <div id="bookingResults">
                        <!-- Sample pre-scheduled visits will be populated here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-translate="close">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Button -->
    <div class="emergency-btn" onclick="callEmergency()">
        <i class="bi bi-telephone-fill"></i>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner"></div>
            <div class="loading-text" data-translate="processing">Processing...</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Language Translations (Extended)
        const translations = {
            'en': {
                companyName: "Welcome to PAN-ASIA",
                welcome: "Welcome!",
                selectLanguage: "Please select your preferred language",
                firstTimeVisitor: "First Time Visitor",
                firstTimeDesc: "I'm visiting for the first time",
                returningVisitor: "Returning Visitor",
                returningDesc: "I've been here before",
                deliveryPickup: "Delivery / Pickup",
                deliveryDesc: "I have a delivery or pickup",
                preScheduled: "Pre-Scheduled Visit",
                checkOut: "Check Out",
                scanQRTitle: "Scan Your QR Code",
                scanQRDesc: "Please scan the QR code from your previous visit",
                orUploadQR: "Or upload QR code image",
                uploadQR: "Upload QR Code",
                noQRCode: "I don't have my QR code",
                letsCheckIn: "Let's get you checked in!",
                firstName: "First Name *",
                lastName: "Last Name *",
                email: "Email Address *",
                phone: "Phone Number *",
                company: "Company *",
                companyPlaceholder: "Your Company Name",
                firstNameRequired: "First name is required",
                lastNameRequired: "Last name is required",
                emailInvalid: "Please enter a valid email address",
                phoneInvalid: "Please enter a valid phone number",
                companyRequired: "Company name is required",
                back: "Back",
                continue: "Continue",
                photoTitle: "Let's take your photo",
                photoDesc: "This helps our staff identify you and ensures building security",
                takePhoto: "Take Photo",
                retakePhoto: "Retake Photo",
                photoGuide: "Position your face within the oval guide",
                skipNow: "Skip for Now",
                hostTitle: "Who are you here to see?",
                searchHost: "Search by name or department",
                hostSearchPlaceholder: "Start typing name...",
                popularDepts: "Popular departments:",
                sales: "Sales",
                hr: "Human Resources",
                it: "IT Support",
                reception: "Reception",
                selectedHost: "Selected Host",
                noSelection: "No one selected yet",
                purposeTitle: "What brings you here today?",
                meeting: "Meeting",
                interview: "Interview",
                delivery: "Delivery",
                service: "Service/Repair",
                training: "Training",
                tour: "Tour",
                event: "Event",
                other: "Other",
                additionalNotes: "Additional notes (optional)",
                notesPlaceholder: "Any additional information...",
                termsTitle: "Terms & Agreements",
                agreeTerms: "I have read and agree to all terms, conditions, and guidelines",
                agreePhoto: "I consent to my photo being used for security purposes",
                completeCheckIn: "Complete Check-In",
                successTitle: "You're All Set!",
                successMessage: "Your host has been notified of your arrival",
                visitorBadge: "Your Visitor Badge",
                host: "Host",
                validUntil: "Valid Until",
                yourQRCode: "Your QR Code for Next Visit",
                qrCodeNote: "Save this QR code for faster check-in on your next visit",
                nextSteps: "Next Steps",
                printBadge: "Print Badge",
                done: "Done",
                autoReset: "This screen will reset in",
                seconds: "seconds",
                processing: "Processing...",
                emailSentTitle: "QR Code Sent!",
                emailSentMessage: "Your QR code has been sent to your email address",
                qrValidatedTitle: "Welcome Back!",
                qrValidatedMessage: "Your QR code has been validated successfully",
                invalidQRTitle: "Invalid QR Code",
                invalidQRMessage: "The QR code is not valid or has expired",
                preScheduledTitle: "Pre-Scheduled Visit Check-In",
                enterCode: "Enter your booking code or search by name:",
                bookingSearchPlaceholder: "Enter booking code or name...",
                close: "Close",
                emergencyTitle: "Emergency Assistance",
                emergencyText: "Are you sure you want to call for security/emergency assistance?",
                emergencyConfirm: "Yes, Call Now",
                emergencyCancel: "Cancel",
                emergencyNotified: "Security has been notified!",
                emergencyMessage: "Help is on the way. Please stay where you are.",
                checkOutTitle: "Check Out",
                checkOutMessage: "Check-out functionality coming soon",
                agreementContent: `
                    <h5>Visitor Guidelines</h5>
                    <p>By entering our premises, you agree to:</p>
                    <ul>
                        <li>Wear your visitor badge visibly at all times</li>
                        <li>Remain in authorized areas only</li>
                        <li>Be escorted in restricted areas</li>
                        <li>Follow all safety and security protocols</li>
                        <li>Return your badge when leaving</li>
                    </ul>
                    <h5 class="mt-3">Health & Safety</h5>
                    <p>I confirm that:</p>
                    <ul>
                        <li>I am not experiencing any symptoms of illness</li>
                        <li>I will follow all posted safety guidelines</li>
                        <li>I will report any incidents immediately</li>
                    </ul>
                    <h5 class="mt-3">Data Privacy</h5>
                    <p>We collect your information for security and safety purposes. Your data will be handled in accordance with our privacy policy and deleted after 90 days unless required for compliance purposes.</p>
                `,
                nextStepsContent: [
                    "Please collect your printed badge from the printer",
                    "Wait in the lobby area",
                    "Your host will come to receive you shortly",
                    "Check your email for your QR code for next visit"
                ]
            },
            'zh-TW': {
                companyName: "歡迎來到 PAN-ASIA",
                welcome: "歡迎！",
                selectLanguage: "請選擇您的語言偏好",
                firstTimeVisitor: "首次訪客",
                firstTimeDesc: "我是第一次來訪",
                returningVisitor: "回訪訪客",
                returningDesc: "我之前來過",
                deliveryPickup: "送貨/取貨",
                deliveryDesc: "我有送貨或取貨",
                preScheduled: "預約訪問",
                checkOut: "簽出",
                letsCheckIn: "讓我們為您辦理登記！",
                searchPrevious: "搜索您之前的登記記錄",
                searchPlaceholder: "開始輸入您的姓名或電子郵件...",
                firstName: "名字 *",
                lastName: "姓氏 *",
                email: "電子郵件地址 *",
                phone: "電話號碼 *",
                company: "公司 *",
                companyPlaceholder: "您的公司名稱",
                back: "返回",
                continue: "繼續",
                photoTitle: "讓我們拍攝您的照片",
                photoDesc: "這有助於我們的員工識別您並確保建築安全",
                takePhoto: "拍照",
                retakePhoto: "重新拍照",
                photoGuide: "請將您的臉部置於橢圓形指引內",
                skipNow: "暫時跳過",
                hostTitle: "您要見誰？",
                searchHost: "按姓名或部門搜索",
                hostSearchPlaceholder: "開始輸入姓名...",
                popularDepts: "熱門部門：",
                sales: "銷售",
                hr: "人力資源",
                it: "IT支援",
                reception: "接待處",
                selectedHost: "已選擇的接待人",
                noSelection: "尚未選擇任何人",
                purposeTitle: "今天來訪的目的是什麼？",
                meeting: "會議",
                interview: "面試",
                delivery: "送貨",
                service: "服務/維修",
                training: "培訓",
                tour: "參觀",
                event: "活動",
                other: "其他",
                additionalNotes: "附加說明（可選）",
                notesPlaceholder: "任何額外信息...",
                termsTitle: "條款和協議",
                agreeTerms: "我已閱讀並同意所有條款、條件和準則",
                agreePhoto: "我同意將我的照片用於安全目的",
                completeCheckIn: "完成登記",
                successTitle: "一切就緒！",
                successMessage: "您的接待人已收到您到達的通知",
                visitorBadge: "您的訪客證",
                host: "接待人",
                validUntil: "有效期至",
                nextSteps: "下一步",
                printBadge: "列印訪客證",
                done: "完成",
                autoReset: "此畫面將在",
                seconds: "秒後重置",
                processing: "處理中...",
                preScheduledTitle: "預約訪問登記",
                enterCode: "輸入您的預約代碼或按姓名搜索：",
                bookingSearchPlaceholder: "輸入預約代碼或姓名...",
                close: "關閉",
                emergencyTitle: "緊急協助",
                emergencyText: "您確定要呼叫保安/緊急協助嗎？",
                emergencyConfirm: "是的，立即呼叫",
                emergencyCancel: "取消",
                emergencyNotified: "已通知保安！",
                emergencyMessage: "救援正在路上。請留在原地。",
                checkOutTitle: "簽出",
                checkOutMessage: "簽出功能即將推出",
                agreementContent: `
                    <h5>訪客指引</h5>
                    <p>進入我們的場所，您同意：</p>
                    <ul>
                        <li>隨時明顯佩戴您的訪客證</li>
                        <li>僅停留在授權區域</li>
                        <li>在限制區域需要陪同</li>
                        <li>遵守所有安全和保安協議</li>
                        <li>離開時歸還您的訪客證</li>
                    </ul>
                    <h5 class="mt-3">健康與安全</h5>
                    <p>我確認：</p>
                    <ul>
                        <li>我沒有任何疾病症狀</li>
                        <li>我會遵守所有張貼的安全指引</li>
                        <li>我會立即報告任何事件</li>
                    </ul>
                    <h5 class="mt-3">數據隱私</h5>
                    <p>我們收集您的信息用於安全和保安目的。您的數據將根據我們的隱私政策處理，並在90天後刪除，除非出於合規目的需要保留。</p>
                `,
                nextStepsContent: [
                    "請從打印機收取您打印的訪客證",
                    "在大堂區等候",
                    "您的接待人很快就會來接您"
                ]
            },
            'zh-CN': {
                companyName: "欢迎来到 PAN-ASIA",
                welcome: "欢迎！",
                selectLanguage: "请选择您的语言偏好",
                firstTimeVisitor: "首次访客",
                firstTimeDesc: "我是第一次来访",
                returningVisitor: "回访访客",
                returningDesc: "我之前来过",
                deliveryPickup: "送货/取货",
                deliveryDesc: "我有送货或取货",
                preScheduled: "预约访问",
                checkOut: "签出",
                letsCheckIn: "让我们为您办理登记！",
                searchPrevious: "搜索您之前的登记记录",
                searchPlaceholder: "开始输入您的姓名或电子邮件...",
                firstName: "名字 *",
                lastName: "姓氏 *",
                email: "电子邮件地址 *",
                phone: "电话号码 *",
                company: "公司 *",
                companyPlaceholder: "您的公司名称",
                back: "返回",
                continue: "继续",
                photoTitle: "让我们拍摄您的照片",
                photoDesc: "这有助于我们的员工识别您并确保建筑安全",
                takePhoto: "拍照",
                retakePhoto: "重新拍照",
                photoGuide: "请将您的脸部置于椭圆形指引内",
                skipNow: "暂时跳过",
                hostTitle: "您要见谁？",
                searchHost: "按姓名或部门搜索",
                hostSearchPlaceholder: "开始输入姓名...",
                popularDepts: "热门部门：",
                sales: "销售",
                hr: "人力资源",
                it: "IT支援",
                reception: "接待处",
                selectedHost: "已选择的接待人",
                noSelection: "尚未选择任何人",
                purposeTitle: "今天来访的目的是什么？",
                meeting: "会议",
                interview: "面试",
                delivery: "送货",
                service: "服务/维修",
                training: "培训",
                tour: "参观",
                event: "活动",
                other: "其他",
                additionalNotes: "附加说明（可选）",
                notesPlaceholder: "任何额外信息...",
                termsTitle: "条款和协议",
                agreeTerms: "我已阅读并同意所有条款、条件和准则",
                agreePhoto: "我同意将我的照片用于安全目的",
                completeCheckIn: "完成登记",
                successTitle: "一切就绪！",
                successMessage: "您的接待人已收到您到达的通知",
                visitorBadge: "您的访客证",
                host: "接待人",
                validUntil: "有效期至",
                nextSteps: "下一步",
                printBadge: "打印访客证",
                done: "完成",
                autoReset: "此画面将在",
                seconds: "秒后重置",
                processing: "处理中...",
                preScheduledTitle: "预约访问登记",
                enterCode: "输入您的预约代码或按姓名搜索：",
                bookingSearchPlaceholder: "输入预约代码或姓名...",
                close: "关闭",
                emergencyTitle: "紧急协助",
                emergencyText: "您确定要呼叫保安/紧急协助吗？",
                emergencyConfirm: "是的，立即呼叫",
                emergencyCancel: "取消",
                emergencyNotified: "已通知保安！",
                emergencyMessage: "救援正在路上。请留在原地。",
                checkOutTitle: "签出",
                checkOutMessage: "签出功能即将推出",
                agreementContent: `
                    <h5>访客指引</h5>
                    <p>进入我们的场所，您同意：</p>
                    <ul>
                        <li>随时明显佩戴您的访客证</li>
                        <li>仅停留在授权区域</li>
                        <li>在限制区域需要陪同</li>
                        <li>遵守所有安全和保安协议</li>
                        <li>离开时归还您的访客证</li>
                    </ul>
                    <h5 class="mt-3">健康与安全</h5>
                    <p>我确认：</p>
                    <ul>
                        <li>我没有任何疾病症状</li>
                        <li>我会遵守所有张贴的安全指引</li>
                        <li>我会立即报告任何事件</li>
                    </ul>
                    <h5 class="mt-3">数据隐私</h5>
                    <p>我们收集您的信息用于安全和保安目的。您的数据将根据我们的隐私政策处理，并在90天后删除，除非出于合规目的需要保留。</p>
                `,
                nextStepsContent: [
                    "请从打印机收取您打印的访客证",
                    "在大堂区等候",
                    "您的接待人很快就会来接您"
                ]
            },
            'fil': {
                companyName: "Maligayang pagdating sa PAN-ASIA",
                welcome: "Maligayang pagdating!",
                selectLanguage: "Mangyaring piliin ang iyong gustong wika",
                firstTimeVisitor: "Unang Bisita",
                firstTimeDesc: "Unang pagkakataon kong bumisita",
                returningVisitor: "Bumabalik na Bisita",
                returningDesc: "Nakarating na ako dito dati",
                deliveryPickup: "Paghahatid / Pagkuha",
                deliveryDesc: "May dala akong paghahatid o pagkuha",
                preScheduled: "Naka-iskedyul na Pagbisita",
                checkOut: "Mag-Check Out",
                letsCheckIn: "Simulan natin ang iyong pag-check in!",
                searchPrevious: "Hanapin ang iyong nakaraang pag-check in",
                searchPlaceholder: "Simulang mag-type ng pangalan o email...",
                firstName: "Unang Pangalan *",
                lastName: "Apelyido *",
                email: "Email Address *",
                phone: "Numero ng Telepono *",
                company: "Kumpanya *",
                companyPlaceholder: "Pangalan ng Kumpanya",
                back: "Bumalik",
                continue: "Magpatuloy",
                photoTitle: "Kumuha tayo ng larawan",
                photoDesc: "Ito ay tumutulong sa aming mga tauhan na kilalanin ka at nagsisiguro ng seguridad ng gusali",
                takePhoto: "Kumuha ng Larawan",
                retakePhoto: "Ulitin ang Pagkuha",
                photoGuide: "Iposisyon ang iyong mukha sa loob ng oval na gabay",
                skipNow: "Laktawan Muna",
                hostTitle: "Sino ang iyong gustong makita?",
                searchHost: "Maghanap ayon sa pangalan o departamento",
                hostSearchPlaceholder: "Simulang mag-type ng pangalan...",
                popularDepts: "Mga popular na departamento:",
                sales: "Benta",
                hr: "Human Resources",
                it: "IT Support",
                reception: "Reception",
                selectedHost: "Napiling Tauhan",
                noSelection: "Wala pang napili",
                purposeTitle: "Ano ang dahilan ng iyong pagbisita ngayon?",
                meeting: "Pulong",
                interview: "Interbyu",
                delivery: "Paghahatid",
                service: "Serbisyo/ Pagkukumpuni",
                training: "Pagsasanay",
                tour: "Paglilibot",
                event: "Kaganapan",
                other: "Iba pa",
                additionalNotes: "Karagdagang tala (opsyonal)",
                notesPlaceholder: "Anumang karagdagang impormasyon...",
                termsTitle: "Mga Tuntunin at Kasunduan",
                agreeTerms: "Nabasa ko at sumasang-ayon ako sa lahat ng tuntunin, kondisyon, at gabay",
                agreePhoto: "Pumapayag akong gamitin ang aking larawan para sa layuning pangkaligtasan",
                completeCheckIn: "Kumpletuhin ang Check-In",
                successTitle: "Handa Ka Na!",
                successMessage: "Naabisuhan na ang iyong tauhan tungkol sa iyong pagdating",
                visitorBadge: "Ang Iyong Visitor Badge",
                host: "Tauhan",
                validUntil: "Balido Hanggang",
                nextSteps: "Susunod na Hakbang",
                printBadge: "I-print ang Badge",
                done: "Tapos",
                autoReset: "Ang screen na ito ay mag-reset sa",
                seconds: "segundo",
                processing: "Pinoproseso...",
                preScheduledTitle: "Naka-iskedyul na Pagbisita Check-In",
                enterCode: "Ilagay ang iyong booking code o maghanap ayon sa pangalan:",
                bookingSearchPlaceholder: "Ilagay ang booking code o pangalan...",
                close: "Isara",
                emergencyTitle: "Emergency na Tulong",
                emergencyText: "Sigurado ka bang gusto mong tumawag ng seguridad/emergency assistance?",
                emergencyConfirm: "Oo, Tumawag Ngayon",
                emergencyCancel: "Kanselahin",
                emergencyNotified: "Naabisuhan na ang seguridad!",
                emergencyMessage: "Paparating na ang tulong. Mangyaring manatili kung nasaan ka.",
                checkOutTitle: "Check Out",
                checkOutMessage: "Paparating na ang check-out functionality",
                agreementContent: `
                    <h5>Mga Gabay para sa Bisita</h5>
                    <p>Sa pagpasok sa aming lugar, sumasang-ayon ka na:</p>
                    <ul>
                        <li>Laging isuot ang visitor badge na nakikita</li>
                        <li>Manatili lamang sa mga awtorisadong lugar</li>
                        <li>Kailangan ng kasamang tauhan sa mga restricted na lugar</li>
                        <li>Sundin ang lahat ng protokol sa kaligtasan at seguridad</li>
                        <li>Ibalik ang badge kapag aalis</li>
                    </ul>
                    <h5 class="mt-3">Kalusugan at Kaligtasan</h5>
                    <p>Kumpirma ko na:</p>
                    <ul>
                        <li>Wala akong nararamdamang sintomas ng sakit</li>
                        <li>Susundin ko ang lahat ng naka-post na gabay sa kaligtasan</li>
                        <li>Agad kong irereport ang anumang insidente</li>
                    </ul>
                    <h5 class="mt-3">Privacy ng Data</h5>
                    <p>Kinokolekta namin ang iyong impormasyon para sa layuning pangkaligtasan at seguridad. Ang iyong data ay hahawakan ayon sa aming privacy policy at tatanggalin pagkatapos ng 90 araw maliban kung kinakailangan para sa compliance.</p>
                `,
                nextStepsContent: [
                    "Mangyaring kunin ang iyong na-print na badge mula sa printer",
                    "Maghintay sa lobby area",
                    "Darating ang iyong tauhan upang salubungin ka"
                ]
            },
            'ja': {
                companyName: "PAN-ASIAへようこそ",
                welcome: "ようこそ！",
                selectLanguage: "ご希望の言語を選択してください",
                firstTimeVisitor: "初回訪問者",
                firstTimeDesc: "初めて訪問します",
                returningVisitor: "再訪問者",
                returningDesc: "以前に来たことがあります",
                deliveryPickup: "配達/受取",
                deliveryDesc: "配達または受取があります",
                preScheduled: "事前予約済み訪問",
                checkOut: "チェックアウト",
                letsCheckIn: "チェックインを始めましょう！",
                searchPrevious: "以前のチェックイン記録を検索",
                searchPlaceholder: "名前またはメールアドレスを入力...",
                firstName: "名 *",
                lastName: "姓 *",
                email: "メールアドレス *",
                phone: "電話番号 *",
                company: "会社名 *",
                companyPlaceholder: "会社名を入力",
                back: "戻る",
                continue: "続ける",
                photoTitle: "写真を撮影しましょう",
                photoDesc: "これはスタッフがあなたを識別し、建物のセキュリティを確保するのに役立ちます",
                takePhoto: "写真を撮る",
                retakePhoto: "撮り直し",
                photoGuide: "楕円形のガイド内に顔を配置してください",
                skipNow: "今はスキップ",
                hostTitle: "どなたにお会いになりますか？",
                searchHost: "名前または部署で検索",
                hostSearchPlaceholder: "名前を入力開始...",
                popularDepts: "人気の部署：",
                sales: "営業",
                hr: "人事部",
                it: "ITサポート",
                reception: "受付",
                selectedHost: "選択されたホスト",
                noSelection: "まだ選択されていません",
                purposeTitle: "本日のご訪問の目的は何ですか？",
                meeting: "会議",
                interview: "面接",
                delivery: "配達",
                service: "サービス/修理",
                training: "トレーニング",
                tour: "見学",
                event: "イベント",
                other: "その他",
                additionalNotes: "追加メモ（オプション）",
                notesPlaceholder: "追加情報...",
                termsTitle: "規約と同意事項",
                agreeTerms: "すべての規約、条件、ガイドラインを読み、同意します",
                agreePhoto: "セキュリティ目的で写真を使用することに同意します",
                completeCheckIn: "チェックインを完了",
                successTitle: "準備完了です！",
                successMessage: "ホストに到着が通知されました",
                visitorBadge: "訪問者バッジ",
                host: "ホスト",
                validUntil: "有効期限",
                nextSteps: "次のステップ",
                printBadge: "バッジを印刷",
                done: "完了",
                autoReset: "この画面は",
                seconds: "秒後にリセットされます",
                processing: "処理中...",
                preScheduledTitle: "事前予約訪問チェックイン",
                enterCode: "予約コードを入力または名前で検索：",
                bookingSearchPlaceholder: "予約コードまたは名前を入力...",
                close: "閉じる",
                emergencyTitle: "緊急支援",
                emergencyText: "本当にセキュリティ/緊急支援を呼びますか？",
                emergencyConfirm: "はい、今すぐ呼ぶ",
                emergencyCancel: "キャンセル",
                emergencyNotified: "セキュリティに通知されました！",
                emergencyMessage: "助けが向かっています。その場でお待ちください。",
                checkOutTitle: "チェックアウト",
                checkOutMessage: "チェックアウト機能は近日公開予定",
                agreementContent: `
                    <h5>訪問者ガイドライン</h5>
                    <p>施設に入場することで、以下に同意します：</p>
                    <ul>
                        <li>訪問者バッジを常に目に見える場所に着用する</li>
                        <li>許可されたエリアのみに滞在する</li>
                        <li>制限エリアでは同行が必要</li>
                        <li>すべての安全およびセキュリティプロトコルに従う</li>
                        <li>退出時にバッジを返却する</li>
                    </ul>
                    <h5 class="mt-3">健康と安全</h5>
                    <p>以下を確認します：</p>
                    <ul>
                        <li>病気の症状がないこと</li>
                        <li>掲示されたすべての安全ガイドラインに従います</li>
                        <li>事故があった場合は直ちに報告します</li>
                    </ul>
                    <h5 class="mt-3">データプライバシー</h5>
                    <p>セキュリティおよび安全目的で情報を収集します。データはプライバシーポリシーに従って処理され、コンプライアンス目的で必要な場合を除き、90日後に削除されます。</p>
                `,
                nextStepsContent: [
                    "プリンターから印刷されたバッジを受け取ってください",
                    "ロビーエリアでお待ちください",
                    "ホストがまもなくお迎えに来ます"
                ]
            }
            // Add other language translations similarly...
        };

        // State Management
        let currentLanguage = 'en';
        let currentScreen = 1;
        let visitorData = {};
        let selectedPurpose = null;
        let selectedHost = null;
        let countdownTimer = null;
        let videoStream = null;
        let capturedPhotoData = null;
        let html5QrCode = null;

        // Screen flow mapping
        const screenFlow = {
            'new': [1, 3, 4, 5, 6, 7, 8],      // Welcome -> Basic Info -> Photo -> Host -> Purpose -> Agreement -> Success
            'returning': [1, 2, 5, 6, 7, 8],    // Welcome -> QR Scanner -> Host -> Purpose -> Agreement -> Success
            'delivery': [1, 3, 4, 5, 6, 7, 8]   // Same as new visitor
        };
        
        let currentFlow = [];
        let currentFlowIndex = 0;

        // Sample data
        const employees = [
            { id: 1, name: 'John Anderson', department: 'Sales', email: 'j.anderson@company.com' },
            { id: 2, name: 'Sarah Williams', department: 'Human Resources', email: 's.williams@company.com' },
            { id: 3, name: 'Michael Chen', department: 'IT Support', email: 'm.chen@company.com' },
            { id: 4, name: 'Emily Johnson', department: 'Marketing', email: 'e.johnson@company.com' },
            { id: 5, name: 'David Brown', department: 'Finance', email: 'd.brown@company.com' },
            { id: 6, name: 'Lisa Martinez', department: 'Operations', email: 'l.martinez@company.com' },
            { id: 7, name: 'Robert Taylor', department: 'Legal', email: 'r.taylor@company.com' },
            { id: 8, name: 'Jennifer Davis', department: 'Customer Service', email: 'j.davis@company.com' }
        ];

        // Sample pre-scheduled visits
        const preScheduledVisits = [
            { 
                code: 'MEET-2024-001', 
                name: 'Alice Johnson', 
                company: 'Tech Solutions Inc.', 
                host: 'John Anderson', 
                time: '10:00 AM',
                purpose: 'Sales Meeting'
            }
        ];

        // Local storage for visitor data
        const STORAGE_KEY = 'kioskVisitorData';
        
        // Get stored visitors
        function getStoredVisitors() {
            const stored = localStorage.getItem(STORAGE_KEY);
            return stored ? JSON.parse(stored) : [];
        }
        
        // // Store visitor data
        // function storeVisitor(visitor) {
        //     const visitors = getStoredVisitors();
        //     visitor.id = Date.now();
        //     visitor.lastVisit = new Date().toISOString();
        //     visitor.qrCode = generateVisitorQRData(visitor);
        //     visitors.unshift(visitor);
        //     if (visitors.length > 100) visitors.splice(100);
        //     localStorage.setItem(STORAGE_KEY, JSON.stringify(visitors));
        //     return visitor;
        // }

        // UPDATED: Store visitor with optimized QR data
        function storeVisitor(visitor) {
            const visitors = getStoredVisitors();
            visitor.id = Date.now();
            visitor.lastVisit = new Date().toISOString();
            
            // Generate optimized QR code data
            visitor.qrCode = generateVisitorQRData(visitor);
            
            visitors.unshift(visitor);
            if (visitors.length > 100) visitors.splice(100);
            
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(visitors));
            } catch (e) {
                console.error('Storage error:', e);
                // If storage is full, remove old photos
                cleanupOldPhotos();
            }
            
            return visitor;
        }

        // Helper function to cleanup old photos from localStorage
        function cleanupOldPhotos() {
            const keys = Object.keys(localStorage);
            const photoKeys = keys.filter(k => k.startsWith('visitor_photo_'));
            
            // Remove photos older than 30 days
            const thirtyDaysAgo = Date.now() - (30 * 24 * 60 * 60 * 1000);
            
            photoKeys.forEach(key => {
                const id = key.replace('visitor_photo_', '');
                if (parseInt(id) < thirtyDaysAgo) {
                    localStorage.removeItem(key);
                }
            });
        }

        // // Generate QR data
        // function generateVisitorQRData(visitor) {
        //     const qrData = {
        //         id: visitor.id,
        //         firstName: visitor.firstName,
        //         lastName: visitor.lastName,
        //         email: visitor.email,
        //         company: visitor.company,
        //         phone: visitor.phone,
        //         photo: visitor.photo,
        //         timestamp: new Date().toISOString()
        //     };
        //     return btoa(JSON.stringify(qrData));
        // }

        // UPDATED: Generate QR data without photo to reduce size
        function generateVisitorQRData(visitor) {
            // Don't include photo in QR code - it makes the code too dense
            const qrData = {
                id: visitor.id || Date.now(),
                firstName: visitor.firstName,
                lastName: visitor.lastName,
                email: visitor.email,
                company: visitor.company,
                phone: visitor.phone,
                // Remove photo from QR data - store it separately in localStorage
                timestamp: new Date().toISOString()
            };
            
            // Store photo separately in localStorage with visitor ID
            if (visitor.photo) {
                try {
                    localStorage.setItem(`visitor_photo_${qrData.id}`, visitor.photo);
                } catch (e) {
                    console.warn('Could not store photo in localStorage:', e);
                }
            }
            
            // Create a shorter QR code string
            return JSON.stringify(qrData); // Don't base64 encode - unnecessary overhead
        }  

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateDateTime();
            setInterval(updateDateTime, 1000);
            translatePage();
        });

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('en-US', options);
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            document.getElementById('datetime').textContent = `${dateStr} • ${timeStr}`;
        }

        // Language selection
        function selectLanguage(lang) {
            currentLanguage = lang;
            document.querySelectorAll('.language-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            translatePage();
        }

        // Translate page
        function translatePage() {
            const elements = document.querySelectorAll('[data-translate]');
            elements.forEach(el => {
                const key = el.getAttribute('data-translate');
                if (translations[currentLanguage] && translations[currentLanguage][key]) {
                    el.textContent = translations[currentLanguage][key];
                }
            });

            const placeholderElements = document.querySelectorAll('[data-translate-placeholder]');
            placeholderElements.forEach(el => {
                const key = el.getAttribute('data-translate-placeholder');
                if (translations[currentLanguage] && translations[currentLanguage][key]) {
                    el.placeholder = translations[currentLanguage][key];
                }
            });

            if (document.getElementById('agreementText')) {
                document.getElementById('agreementText').innerHTML = translations[currentLanguage].agreementContent;
            }

            if (document.getElementById('nextStepsList')) {
                const steps = translations[currentLanguage].nextStepsContent;
                document.getElementById('nextStepsList').innerHTML = steps.map(step => `<li>${step}</li>`).join('');
            }
        }

        // Start check-in process
        function startCheckIn(type) {
            visitorData.type = type;
            currentFlow = screenFlow[type];
            currentFlowIndex = 1;
            
            if (type === 'returning') {
                showScreen(2); // Go to QR scanner
                initQRScanner();
            } else {
                showScreen(3); // Go to basic info
            }
        }

        // // Initialize QR Scanner
        // function initQRScanner() {
        //     if (html5QrCode) {
        //         html5QrCode.stop();
        //     }
            
        //     html5QrCode = new Html5Qrcode("qr-reader");
            
        //     const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
        //     html5QrCode.start(
        //         { facingMode: "environment" },
        //         config,
        //         (decodedText) => {
        //             handleQRCodeSuccess(decodedText);
        //         },
        //         (error) => {
        //             // Ignore scan errors
        //         }
        //     ).catch((err) => {
        //         console.error("Unable to start QR scanner:", err);
        //         showNotification("Camera not available for QR scanning");
        //     });
        // }

        // UPDATED: Initialize QR Scanner with better configuration
        function initQRScanner() {
            if (html5QrCode) {
                html5QrCode.stop();
            }
            
            html5QrCode = new Html5Qrcode("qr-reader");
            
            // Optimized config for better scanning
            const config = { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0,
                disableFlip: false,
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                }
            };
            
            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    console.log('QR scanned:', decodedText);
                    handleQRCodeSuccess(decodedText);
                },
                (error) => {
                    // Ignore continuous scan errors
                }
            ).catch((err) => {
                console.error("Unable to start QR scanner:", err);
                
                // Try with user-facing camera if environment camera fails
                html5QrCode.start(
                    { facingMode: "user" },
                    config,
                    (decodedText) => {
                        handleQRCodeSuccess(decodedText);
                    },
                    (error) => {}
                ).catch((err2) => {
                    showNotification("Camera not available for QR scanning");
                });
            });
        }

        // // Test function to verify QR generation and reading
        // function testQRGeneration() {
        //     const testData = {
        //         id: Date.now(),
        //         firstName: "Test",
        //         lastName: "User",
        //         email: "test@example.com",
        //         company: "Test Company",
        //         phone: "1234567890"
        //     };
            
        //     const qrData = generateVisitorQRData(testData);
        //     console.log('Generated QR data:', qrData);
        //     console.log('QR data length:', qrData.length);
            
        //     // Try to decode it
        //     try {
        //         const decoded = JSON.parse(qrData);
        //         console.log('Successfully decoded:', decoded);
        //     } catch (e) {
        //         console.error('Decode failed:', e);
        //     }
        // }

        // // Handle QR code success
        // function handleQRCodeSuccess(decodedText) {
        //     try {
        //         const qrData = JSON.parse(atob(decodedText));
                
        //         if (qrData.email) {
        //             // Valid QR code found
        //             if (html5QrCode) {
        //                 html5QrCode.stop();
        //             }
                    
        //             // Pre-fill visitor data
        //             visitorData = {
        //                 ...visitorData,
        //                 ...qrData
        //             };
                    
        //             Swal.fire({
        //                 title: translations[currentLanguage].qrValidatedTitle,
        //                 text: translations[currentLanguage].qrValidatedMessage,
        //                 icon: 'success',
        //                 confirmButtonColor: '#27ae60'
        //             });
                    
        //             // Skip to host selection
        //             showScreen(5);
        //         }
        //     } catch (e) {
        //         showNotification(translations[currentLanguage].invalidQRMessage);
        //     }
        // }

        // UPDATED: Handle QR code success with new format
        function handleQRCodeSuccess(decodedText) {
            try {
                let qrData;
                
                // Try to parse as JSON first (new format)
                try {
                    qrData = JSON.parse(decodedText);
                } catch (e) {
                    // Fallback to base64 decode for old QR codes
                    qrData = JSON.parse(atob(decodedText));
                }
                
                if (qrData.email) {
                    // Valid QR code found
                    if (html5QrCode) {
                        html5QrCode.stop();
                    }
                    
                    // Try to retrieve photo from localStorage
                    if (qrData.id) {
                        const storedPhoto = localStorage.getItem(`visitor_photo_${qrData.id}`);
                        if (storedPhoto) {
                            qrData.photo = storedPhoto;
                        }
                    }
                    
                    // Pre-fill visitor data
                    visitorData = {
                        ...visitorData,
                        ...qrData
                    };
                    
                    Swal.fire({
                        title: translations[currentLanguage].qrValidatedTitle,
                        text: translations[currentLanguage].qrValidatedMessage,
                        icon: 'success',
                        confirmButtonColor: '#27ae60'
                    });
                    
                    // Skip to host selection
                    showScreen(5);
                }
            } catch (e) {
                console.error('QR decode error:', e);
                showNotification(translations[currentLanguage].invalidQRMessage);
            }
        }

        // Handle QR upload
        function handleQRUpload(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (html5QrCode) {
                        html5QrCode.scanFile(file, true)
                            .then(decodedText => {
                                handleQRCodeSuccess(decodedText);
                            })
                            .catch(err => {
                                showNotification(translations[currentLanguage].invalidQRMessage);
                            });
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        // Skip QR scan
        function skipQRScan() {
            if (html5QrCode) {
                html5QrCode.stop();
            }
            showScreen(3); // Go to basic info form
        }

        // Screen navigation
        function showScreen(screenNumber) {
            // Stop camera/QR scanner if leaving those screens
            if (currentScreen === 2 && html5QrCode) {
                html5QrCode.stop();
            }
            if (currentScreen === 4 && screenNumber !== 4) {
                stopCamera();
            }
            
            document.querySelectorAll('.screen').forEach(screen => screen.classList.remove('active'));
            
            const screens = ['', 'welcomeScreen', 'qrScannerScreen', 'basicInfoScreen', 'photoScreen', 
                           'hostScreen', 'purposeScreen', 'agreementScreen', 'successScreen'];
            
            if (screens[screenNumber]) {
                document.getElementById(screens[screenNumber]).classList.add('active');
            }
            
            // Initialize screen-specific features
            if (screenNumber === 4) startCamera();
            if (screenNumber === 7) {
                document.getElementById('agreementText').innerHTML = translations[currentLanguage].agreementContent;
            }
            if (screenNumber === 8) {
                const steps = translations[currentLanguage].nextStepsContent;
                document.getElementById('nextStepsList').innerHTML = steps.map(step => `<li>${step}</li>`).join('');
            }
            
            updateStepIndicator(screenNumber);
            currentScreen = screenNumber;
        }

        function nextScreen() {
            if (validateCurrentScreen()) {
                if (currentFlow.length > 0) {
                    currentFlowIndex++;
                    if (currentFlowIndex < currentFlow.length) {
                        showScreen(currentFlow[currentFlowIndex]);
                    }
                } else {
                    showScreen(currentScreen + 1);
                }
            }
        }

        function previousScreen() {
            if (currentFlow.length > 0 && currentFlowIndex > 0) {
                currentFlowIndex--;
                showScreen(currentFlow[currentFlowIndex]);
            } else {
                showScreen(currentScreen - 1);
            }
        }

        // Update step indicator
        function updateStepIndicator(step) {
            const actualStep = currentFlow.length > 0 ? currentFlowIndex + 1 : step;
            document.querySelectorAll('.step-dot').forEach((dot, index) => {
                dot.classList.remove('active', 'completed');
                if (index + 1 < actualStep) dot.classList.add('completed');
                else if (index + 1 === actualStep) dot.classList.add('active');
            });
        }

        // Input validation functions
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validatePhone(phone) {
            const cleaned = phone.replace(/\D/g, '');
            return cleaned.length >= 10;
        }

        function validateName(name) {
            return name.trim().length >= 2 && /^[a-zA-Z\s\-']+$/.test(name);
        }

        function validateCompany(company) {
            return company.trim().length >= 2;
        }

        // Validate current screen with enhanced validation
        function validateCurrentScreen() {
            switch(currentScreen) {
                case 3: // Basic Info Screen
                    let isValid = true;
                    
                    // First Name validation
                    const firstName = document.getElementById('firstName');
                    const firstNameValue = firstName.value.trim();
                    if (!firstNameValue || !validateName(firstNameValue)) {
                        firstName.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        firstName.classList.remove('is-invalid');
                        visitorData.firstName = firstNameValue;
                    }
                    
                    // Last Name validation
                    const lastName = document.getElementById('lastName');
                    const lastNameValue = lastName.value.trim();
                    if (!lastNameValue || !validateName(lastNameValue)) {
                        lastName.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        lastName.classList.remove('is-invalid');
                        visitorData.lastName = lastNameValue;
                    }
                    
                    // Email validation
                    const email = document.getElementById('email');
                    const emailValue = email.value.trim();
                    if (!emailValue || !validateEmail(emailValue)) {
                        email.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        email.classList.remove('is-invalid');
                        visitorData.email = emailValue.toLowerCase();
                    }
                    
                    // Phone validation
                    const phone = document.getElementById('phone');
                    const phoneValue = phone.value.trim();
                    if (!phoneValue || !validatePhone(phoneValue)) {
                        phone.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        phone.classList.remove('is-invalid');
                        visitorData.phone = phoneValue;
                    }
                    
                    // Company validation
                    const company = document.getElementById('company');
                    const companyValue = company.value.trim();
                    if (!companyValue || !validateCompany(companyValue)) {
                        company.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        company.classList.remove('is-invalid');
                        visitorData.company = companyValue;
                    }
                    
                    if (!isValid) {
                        showNotification('Please correct the highlighted fields');
                    }
                    return isValid;
                    
                case 4: // Photo Screen
                    visitorData.photo = capturedPhotoData;
                    return true;
                    
                case 5: // Host Screen
                    if (!selectedHost) {
                        showNotification('Please select who you are here to see');
                        return false;
                    }
                    return true;
                    
                case 6: // Purpose Screen
                    if (!selectedPurpose) {
                        showNotification('Please select the purpose of your visit');
                        return false;
                    }
                    const notes = document.getElementById('visitNotes').value;
                    if (notes) visitorData.notes = notes;
                    return true;
                    
                case 7: // Agreement Screen
                    const terms = document.getElementById('agreeTerms').checked;
                    const photo = document.getElementById('agreePhoto').checked;
                    if (!terms || !photo) {
                        showNotification('Please accept all terms and conditions');
                        return false;
                    }
                    return true;
                    
                default:
                    return true;
            }
        }

        // Clear validation errors on input
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control-lg');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid')) {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        });

        // Camera functions
        async function startCamera() {
            try {
                const video = document.getElementById('videoElement');
                const constraints = {
                    video: { width: { ideal: 320 }, height: { ideal: 240 }, facingMode: 'user' }
                };
                
                videoStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = videoStream;
                video.style.display = 'block';
                document.getElementById('capturedImage').style.display = 'none';
            } catch (err) {
                console.error('Camera access denied:', err);
                showNotification('Camera not available. You can skip this step.');
            }
        }

        function stopCamera() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
        }

        // Capture photo
        function capturePhoto() {
            const video = document.getElementById('videoElement');
            const image = document.getElementById('capturedImage');
            const canvas = document.createElement('canvas');
            
            canvas.width = 320;
            canvas.height = 240;
            
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            capturedPhotoData = canvas.toDataURL('image/jpeg', 0.8);
            
            image.src = capturedPhotoData;
            image.style.display = 'block';
            video.style.display = 'none';
            
            document.getElementById('captureBtn').style.display = 'none';
            document.getElementById('retakeBtn').style.display = 'block';
            
            visitorData.photo = capturedPhotoData;
            showNotification('Photo captured successfully!');
        }

        // Retake photo
        function retakePhoto() {
            const video = document.getElementById('videoElement');
            const image = document.getElementById('capturedImage');
            
            video.style.display = 'block';
            image.style.display = 'none';
            
            document.getElementById('captureBtn').style.display = 'block';
            document.getElementById('retakeBtn').style.display = 'none';
            
            capturedPhotoData = null;
        }

        // Host search
        function searchHost(query) {
            const resultsDiv = document.getElementById('searchResults');
            
            if (query.length < 2) {
                resultsDiv.style.display = 'none';
                return;
            }
            
            const filtered = employees.filter(emp => 
                emp.name.toLowerCase().includes(query.toLowerCase()) ||
                emp.department.toLowerCase().includes(query.toLowerCase())
            );
            
            resultsDiv.innerHTML = '';
            filtered.forEach(emp => {
                const item = document.createElement('div');
                item.className = 'search-result-item';
                item.innerHTML = `
                    <i class="bi bi-person-circle" style="font-size: 2em;"></i>
                    <div class="employee-info">
                        <div class="employee-name">${emp.name}</div>
                        <div class="employee-dept">${emp.department}</div>
                    </div>
                `;
                item.onclick = () => selectHost(emp);
                resultsDiv.appendChild(item);
            });
            
            resultsDiv.style.display = filtered.length > 0 ? 'block' : 'none';
        }

        function selectHost(employee) {
            selectedHost = employee;
            visitorData.host = employee;
            
            document.getElementById('selectedHost').innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-person-circle" style="font-size: 2em;"></i>
                    <div>
                        <div style="font-weight: 600;">${employee.name}</div>
                        <div style="font-size: 0.9em; color: #7f8c8d;">${employee.department}</div>
                    </div>
                </div>
            `;
            
            document.getElementById('hostNextBtn').disabled = false;
            document.getElementById('searchResults').style.display = 'none';
            document.getElementById('hostSearch').value = '';
        }

        function selectDepartment(dept) {
            document.getElementById('hostSearch').value = dept;
            searchHost(dept);
        }

        // Purpose selection
        function selectPurpose(purpose, element) {
            document.querySelectorAll('.purpose-card').forEach(card => card.classList.remove('selected'));
            element.classList.add('selected');
            selectedPurpose = purpose;
            visitorData.purpose = purpose;
            document.getElementById('purposeNextBtn').disabled = false;
        }

        // Agreement check
        function checkAgreement() {
            const terms = document.getElementById('agreeTerms').checked;
            const photo = document.getElementById('agreePhoto').checked;
            document.getElementById('agreeNextBtn').disabled = !(terms && photo);
        }

        // Complete check-in with QR generation and email
        // Modified completeCheckIn function with better QR code handling
        // function completeCheckIn() {
        //     showLoading();
            
        //     // Store visitor and get QR data
        //     const storedVisitor = storeVisitor(visitorData);
            
        //     setTimeout(() => {
        //         hideLoading();
                
        //         const badgeNumber = 'V-' + new Date().getFullYear() + '-' + 
        //                         String(Math.floor(Math.random() * 10000)).padStart(4, '0');
                
        //         // Update badge display
        //         document.getElementById('badgeNumber').textContent = badgeNumber;
        //         document.getElementById('visitorName').textContent = visitorData.firstName + ' ' + visitorData.lastName;
        //         document.getElementById('visitorCompany').textContent = visitorData.company;
        //         document.getElementById('badgeHost').textContent = visitorData.host.name;
                
        //         const badgePhotoDiv = document.getElementById('badgePhotoDisplay');
        //         if (visitorData.photo) {
        //             badgePhotoDiv.innerHTML = `<img src="${visitorData.photo}" alt="Visitor Photo">`;
        //         } else {
        //             badgePhotoDiv.innerHTML = '<i class="bi bi-person-circle" style="font-size: 3em; color: #dee2e6;"></i>';
        //         }
                
        //         const validUntil = new Date();
        //         validUntil.setHours(validUntil.getHours() + 8);
        //         document.getElementById('validUntil').textContent = 
        //             validUntil.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                
        //         // Generate QR Code with error handling
        //         const qrContainer = document.getElementById('qrcode');
        //         qrContainer.innerHTML = ''; // Clear any existing content
                
        //         try {
        //             // Check if QRCode is available
        //             if (typeof QRCode !== 'undefined') {
        //                 new QRCode(qrContainer, {
        //                     text: storedVisitor.qrCode,
        //                     width: 200,
        //                     height: 200,
        //                     colorDark: "#000000",
        //                     colorLight: "#ffffff",
        //                     correctLevel: QRCode.CorrectLevel.M
        //                 });
        //             } else {
        //                 // Fallback if QRCode library fails to load
        //                 console.error('QRCode library not loaded');
        //                 qrContainer.innerHTML = '<div style="width: 200px; height: 200px; border: 2px solid #ddd; display: flex; align-items: center; justify-content: center; color: #999;">QR Code Generation Failed</div>';
        //             }
        //         } catch (error) {
        //             console.error('Error generating QR code:', error);
        //             qrContainer.innerHTML = '<div style="width: 200px; height: 200px; border: 2px solid #ddd; display: flex; align-items: center; justify-content: center; color: #999;">QR Code Error</div>';
        //         }
                
        //         // Simulate sending email
        //         sendQRCodeEmail(storedVisitor);
                
        //         showScreen(8);
        //         startCountdown();
                
        //         console.log('Check-in complete:', storedVisitor);
        //     }, 2000);
        // }
        function completeCheckIn() {
            showLoading();
            
            // Store visitor and get QR data
            const storedVisitor = storeVisitor(visitorData);
            
            setTimeout(() => {
                hideLoading();
                
                const badgeNumber = 'V-' + new Date().getFullYear() + '-' + 
                                   String(Math.floor(Math.random() * 10000)).padStart(4, '0');
                
                // Update badge display
                document.getElementById('badgeNumber').textContent = badgeNumber;
                document.getElementById('visitorName').textContent = visitorData.firstName + ' ' + visitorData.lastName;
                document.getElementById('visitorCompany').textContent = visitorData.company;
                document.getElementById('badgeHost').textContent = visitorData.host.name;
                
                const badgePhotoDiv = document.getElementById('badgePhotoDisplay');
                if (visitorData.photo) {
                    badgePhotoDiv.innerHTML = `<img src="${visitorData.photo}" alt="Visitor Photo">`;
                } else {
                    badgePhotoDiv.innerHTML = '<i class="bi bi-person-circle" style="font-size: 3em; color: #dee2e6;"></i>';
                }
                
                const validUntil = new Date();
                validUntil.setHours(validUntil.getHours() + 8);
                document.getElementById('validUntil').textContent = 
                    validUntil.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                
                // // Generate QR Code (Fixed version)
                // const qrContainer = document.getElementById('qrcode');
                // qrContainer.innerHTML = '';
                // new QRCode(qrContainer, {
                //     text: storedVisitor.qrCode,
                //     width: 200,
                //     height: 200,
                //     colorDark: "#000000",
                //     colorLight: "#ffffff"
                // });
                const qrContainer = document.getElementById('qrcode');
                generateQRCodeAlternative(qrContainer, storedVisitor.qrCode);
                
                // Simulate sending email
                sendQRCodeEmail(storedVisitor);
                
                showScreen(8);
                startCountdown();
                
                console.log('Check-in complete:', storedVisitor);
            }, 2000);
        }


        // function generateQRCodeAlternative(container, text) {
        //     // Clear container
        //     container.innerHTML = '';
            
        //     // Create canvas element
        //     const canvas = document.createElement('canvas');
        //     canvas.width = 200;
        //     canvas.height = 200;
        //     container.appendChild(canvas);
            
        //     // Generate QR code
        //     QrCreator.render({
        //         text: text,
        //         radius: 0.5, // 0.0 to 0.5
        //         ecLevel: 'M', // L, M, Q, H
        //         fill: '#000000',
        //         background: '#ffffff',
        //         size: 200
        //     }, canvas);
        // }

        // UPDATED: Alternative QR generation with size optimization
        function generateQRCodeAlternative(container, text) {
            // Clear container
            container.innerHTML = '';
            
            // If text is too long, show warning
            if (text.length > 500) {
                console.warn('QR code data is very large:', text.length, 'characters');
                
                // Create a simpler QR with just essential data
                const simplified = {
                    id: visitorData.id || Date.now(),
                    email: visitorData.email,
                    name: `${visitorData.firstName} ${visitorData.lastName}`
                };
                text = JSON.stringify(simplified);
            }
            
            // Create canvas element
            const canvas = document.createElement('canvas');
            canvas.width = 200;
            canvas.height = 200;
            container.appendChild(canvas);
            
            try {
                // Generate QR code with appropriate error correction
                QrCreator.render({
                    text: text,
                    radius: 0.0, // Sharp corners for better scanning
                    ecLevel: 'L', // Lower error correction for simpler QR
                    fill: '#000000',
                    background: '#ffffff',
                    size: 200
                }, canvas);
                
                // Add download button as backup
                const downloadBtn = document.createElement('button');
                downloadBtn.className = 'btn btn-sm btn-outline-primary mt-2';
                downloadBtn.innerHTML = '<i class="bi bi-download"></i> Download QR';
                downloadBtn.onclick = function() {
                    const link = document.createElement('a');
                    link.download = `visitor-qr-${Date.now()}.png`;
                    link.href = canvas.toDataURL();
                    link.click();
                };
                container.appendChild(downloadBtn);
                
            } catch (error) {
                console.error('QR generation error:', error);
                container.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> QR generation issue
                        <br><small>Visitor ID: ${visitorData.id || 'N/A'}</small>
                    </div>
                `;
            }
        }

        const API_BASE_URL = 'http://localhost:3000'; // Change this to your backend URL

        // Updated function to actually send QR code via email
        async function sendQRCodeEmail(visitor) {
            try {
                // Generate badge number
                const badgeNumber = 'V-' + new Date().getFullYear() + '-' + 
                                String(Math.floor(Math.random() * 10000)).padStart(4, '0');
                
                // Prepare the data to send
                const emailData = {
                    visitor: visitor,
                    qrData: visitor.qrCode,
                    badgeNumber: badgeNumber
                };
                
                // Show loading state
                console.log('Sending QR code to:', visitor.email);
                
                // Make API call to backend
                const response = await fetch(`${API_BASE_URL}/api/send-visitor-qr`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(emailData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Email sent successfully
                    Swal.fire({
                        title: translations[currentLanguage].emailSentTitle || 'QR Code Sent!',
                        text: translations[currentLanguage].emailSentMessage || 'Your QR code has been sent to your email address',
                        icon: 'success',
                        confirmButtonColor: '#27ae60',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    
                    console.log('Email sent successfully to:', result.emailSent);
                } else {
                    // Email failed to send
                    throw new Error(result.message || 'Failed to send email');
                }
                
            } catch (error) {
                console.error('Email sending error:', error);
                
                // Show error message to user
                Swal.fire({
                    title: 'Email Not Sent',
                    html: `
                        <p>We couldn't send the QR code to your email at this moment.</p>
                        <p style="color: #666; font-size: 14px; margin-top: 10px;">
                            Please take a photo of the QR code on screen or ask reception for assistance.
                        </p>
                        <p style="color: #999; font-size: 12px; margin-top: 15px;">
                            Error: ${error.message}
                        </p>
                    `,
                    icon: 'warning',
                    confirmButtonColor: '#1e9338',
                    confirmButtonText: 'OK, I\'ll save it manually'
                });
            }
        }

        // Alternative: If you're using PHP backend instead, here's a simpler version:
        async function sendQRCodeEmailPHP(visitor) {
            try {
                const formData = new FormData();
                formData.append('email', visitor.email);
                formData.append('firstName', visitor.firstName);
                formData.append('lastName', visitor.lastName);
                formData.append('company', visitor.company);
                formData.append('host', visitor.host?.name || 'Reception');
                formData.append('purpose', visitor.purpose || 'Meeting');
                formData.append('qrData', visitor.qrCode);
                formData.append('photo', visitor.photo || '');
                
                const response = await fetch('send-email.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        title: 'QR Code Sent!',
                        text: 'Check your email for your visitor pass and QR code',
                        icon: 'success',
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error('Email error:', error);
                Swal.fire({
                    title: 'Email Issue',
                    text: 'Please save the QR code from the screen',
                    icon: 'warning'
                });
            }
        }

        // Add this function to allow manual email sending from success screen
        function resendEmail() {
            if (visitorData && visitorData.email) {
                sendQRCodeEmail(visitorData);
            }
        }

        // Optional: Add a download QR code function as backup
        function downloadQRCode() {
            const canvas = document.querySelector('#qrcode canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = `visitor-qr-${Date.now()}.png`;
                link.href = canvas.toDataURL();
                link.click();
                
                Swal.fire({
                    title: 'QR Code Downloaded',
                    text: 'Your QR code has been saved to your device',
                    icon: 'success',
                    timer: 2000
                });
            }
        }

        // // Simulate sending QR code via email
        // function sendQRCodeEmail(visitor) {
        //     // In production, this would make an API call to your backend
        //     console.log('Sending QR code to:', visitor.email);
            
        //     setTimeout(() => {
        //         Swal.fire({
        //             title: translations[currentLanguage].emailSentTitle || 'QR Code Sent!',
        //             text: translations[currentLanguage].emailSentMessage || 'Your QR code has been sent to your email address',
        //             icon: 'success',
        //             confirmButtonColor: '#27ae60',
        //             timer: 3000,
        //             timerProgressBar: true
        //         });
        //     }, 1000);
        // }

        // Countdown timer
        function startCountdown() {
            let seconds = 60;
            countdownTimer = setInterval(() => {
                seconds--;
                document.getElementById('countdown').textContent = seconds;
                
                if (seconds <= 0) {
                    clearInterval(countdownTimer);
                    resetKiosk();
                }
            }, 1000);
        }

        // Reset kiosk
        function resetKiosk() {
            clearInterval(countdownTimer);
            stopCamera();
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
            }
            
            // Reset all data
            visitorData = {};
            selectedHost = null;
            selectedPurpose = null;
            capturedPhotoData = null;
            currentFlow = [];
            currentFlowIndex = 0;
            
            // Reset form fields
            document.querySelectorAll('input').forEach(input => {
                if (input.type !== 'checkbox') {
                    input.value = '';
                    input.classList.remove('is-invalid');
                } else {
                    input.checked = false;
                }
            });
            
            document.querySelectorAll('textarea').forEach(textarea => {
                textarea.value = '';
            });
            
            document.querySelectorAll('.purpose-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            document.getElementById('selectedHost').innerHTML = `<span class="text-muted">${translations[currentLanguage].noSelection || 'No one selected yet'}</span>`;
            document.getElementById('captureBtn').style.display = 'block';
            document.getElementById('retakeBtn').style.display = 'none';
            document.getElementById('capturedImage').style.display = 'none';
            document.getElementById('hostNextBtn').disabled = true;
            document.getElementById('purposeNextBtn').disabled = true;
            document.getElementById('agreeNextBtn').disabled = true;
            
            showScreen(1);
        }

        // Pre-scheduled visit functions
        function showPreScheduled() {
            loadPreScheduledVisits();
            const modal = new bootstrap.Modal(document.getElementById('preScheduledModal'));
            modal.show();
        }

        function loadPreScheduledVisits() {
            const resultsDiv = document.getElementById('bookingResults');
            resultsDiv.innerHTML = '';
            
            preScheduledVisits.forEach(visit => {
                const item = document.createElement('div');
                item.className = 'booking-item';
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="booking-code">${visit.code}</div>
                            <div class="mt-2">
                                <strong>${visit.name}</strong> - ${visit.company}
                            </div>
                            <div class="text-muted">
                                Host: ${visit.host} | Time: ${visit.time}
                            </div>
                            <div class="text-primary mt-1">
                                <i class="bi bi-calendar-check"></i> ${visit.purpose}
                            </div>
                        </div>
                        <button class="btn btn-primary" onclick="selectPreScheduled('${visit.code}')">
                            Check In
                        </button>
                    </div>
                `;
                resultsDiv.appendChild(item);
            });
        }

        function searchBookings(query) {
            if (query.length < 2) {
                loadPreScheduledVisits();
                return;
            }
            
            const filtered = preScheduledVisits.filter(visit =>
                visit.code.toLowerCase().includes(query.toLowerCase()) ||
                visit.name.toLowerCase().includes(query.toLowerCase())
            );
            
            const resultsDiv = document.getElementById('bookingResults');
            resultsDiv.innerHTML = '';
            
            if (filtered.length === 0) {
                resultsDiv.innerHTML = '<p class="text-muted text-center">No matching bookings found</p>';
                return;
            }
            
            filtered.forEach(visit => {
                const item = document.createElement('div');
                item.className = 'booking-item';
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="booking-code">${visit.code}</div>
                            <div class="mt-2">
                                <strong>${visit.name}</strong> - ${visit.company}
                            </div>
                            <div class="text-muted">
                                Host: ${visit.host} | Time: ${visit.time}
                            </div>
                            <div class="text-primary mt-1">
                                <i class="bi bi-calendar-check"></i> ${visit.purpose}
                            </div>
                        </div>
                        <button class="btn btn-primary" onclick="selectPreScheduled('${visit.code}')">
                            Check In
                        </button>
                    </div>
                `;
                resultsDiv.appendChild(item);
            });
        }

        function selectPreScheduled(code) {
            const visit = preScheduledVisits.find(v => v.code === code);
            if (visit) {
                const names = visit.name.split(' ');
                document.getElementById('firstName').value = names[0] || '';
                document.getElementById('lastName').value = names.slice(1).join(' ') || '';
                document.getElementById('company').value = visit.company;
                
                const hostEmployee = employees.find(emp => emp.name === visit.host);
                if (hostEmployee) {
                    selectHost(hostEmployee);
                }
                
                selectedPurpose = visit.purpose.toLowerCase().replace(' ', '');
                visitorData.purpose = selectedPurpose;
                
                bootstrap.Modal.getInstance(document.getElementById('preScheduledModal')).hide();
                
                currentFlow = screenFlow['new'];
                currentFlowIndex = 1;
                showScreen(3);
                
                showNotification(`Pre-scheduled visit ${code} loaded successfully`);
            }
        }

        // Check out
        function checkOut() {
            Swal.fire({
                title: translations[currentLanguage].checkOutTitle || 'Check Out',
                text: translations[currentLanguage].checkOutMessage || 'Check-out functionality coming soon',
                icon: 'info',
                confirmButtonColor: '#1e9338'
            });
        }

        // Emergency call
        function callEmergency() {
            Swal.fire({
                title: translations[currentLanguage].emergencyTitle || 'Emergency Assistance',
                text: translations[currentLanguage].emergencyText || 'Are you sure you want to call for security/emergency assistance?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: translations[currentLanguage].emergencyConfirm || 'Yes, Call Now',
                cancelButtonText: translations[currentLanguage].emergencyCancel || 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: translations[currentLanguage].emergencyNotified || 'Security has been notified!',
                        text: translations[currentLanguage].emergencyMessage || 'Help is on the way. Please stay where you are.',
                        icon: 'success',
                        confirmButtonColor: '#27ae60'
                    });
                }
            });
        }

        // Print badge
        function printBadge() {
            window.print();
            showNotification('Badge sent to printer');
        }

        // Loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        // Show notification
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #1e9338;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.remove(), 3000);
        }

        // Prevent context menu for kiosk mode
        // document.addEventListener('contextmenu', e => e.preventDefault());
    </script>
</body>
</html>