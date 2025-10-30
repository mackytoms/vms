<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMS Kiosk System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        /* Keep existing styles */
        .kiosk-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .kiosk-header {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .kiosk-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            min-height: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .screen {
            display: none;
        }
        
        .screen.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 10px;
        }
        
        .step-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #dee2e6;
            transition: all 0.3s ease;
        }
        
        .step-dot.active {
            background: #f39c12;
            transform: scale(1.3);
        }
        
        .step-dot.completed {
            background: #27ae60;
        }
        
        .action-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-color: #f39c12;
        }
        
        .action-card i {
            font-size: 3em;
            margin-bottom: 15px;
        }
        
        .language-selector {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        
        .language-btn {
            padding: 10px 20px;
            border: 2px solid #dee2e6;
            background: white;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .language-btn:hover {
            background: #f8f9fa;
        }
        
        .language-btn.active {
            background: #f39c12;
            color: white;
            border-color: #f39c12;
        }
        
        .btn-large {
            padding: 12px 30px;
            font-size: 1.1em;
            border-radius: 25px;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-next {
            background: #f39c12;
            color: white;
        }
        
        .btn-next:hover:not(:disabled) {
            background: #e67e22;
            transform: translateY(-2px);
        }
        
        .btn-back {
            background: #95a5a6;
            color: white;
        }
        
        .btn-back:hover {
            background: #7f8c8d;
        }
        
        .btn-next:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #495057;
        }
        
        .form-control-lg {
            border-radius: 10px;
            border: 2px solid #dee2e6;
        }
        
        .form-control-lg:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
        }
        
        .employee-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
        }
        
        .employee-card {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .employee-card:hover {
            border-color: #f39c12;
            background: #fff9e6;
        }
        
        .employee-card.selected {
            border-color: #27ae60;
            background: #d4edda;
        }
        
        .purpose-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .purpose-card {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .purpose-card:hover {
            border-color: #f39c12;
            transform: translateY(-3px);
        }
        
        .purpose-card.selected {
            border-color: #27ae60;
            background: #d4edda;
        }
        
        .purpose-card i {
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .camera-view {
            position: relative;
            width: 100%;
            max-width: 400px;
            height: 300px;
            margin: 0 auto;
            background: #000;
            border-radius: 10px;
            overflow: hidden;
        }
        
        #videoElement, #capturedImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        #capturedImage {
            display: none;
        }
        
        .face-guide {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 250px;
            border: 3px dashed rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .badge-preview {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 15px;
            padding: 30px;
            max-width: 400px;
            margin: 20px auto;
        }
        
        .badge-photo-display img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .qr-code-display {
            text-align: center;
            margin: 20px 0;
        }
        
        #qrcode {
            display: inline-block;
            padding: 10px;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 10px;
        }
        
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        
        .loading-overlay.active {
            display: flex;
        }
        
        .loading-spinner {
            color: white;
            font-size: 3em;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="loading-spinner">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <p class="text-white mt-3" data-translate="processing">Processing...</p>
        </div>
    </div>

    <!-- Main Kiosk Container -->
    <div class="kiosk-container">
        <!-- Header -->
        <div class="kiosk-header">
            <div class="company-logo">
                <img src="<?= base_url('assets/images/icons/logo.png') ?>" 
                    alt="Toms World" 
                    style="width: 40px; height: 40px; object-fit: contain; border-radius: 50%;">
            </div>
            <h1 data-translate="companyName">Welcome to TOMS WORLD</h1>
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
                        <button class="language-btn active" onclick="selectLanguage('en', event)">English</button>
                        <button class="language-btn" onclick="selectLanguage('zh-TW', event)">繁體中文</button>
                        <button class="language-btn" onclick="selectLanguage('zh-CN', event)">简体中文</button>
                        <button class="language-btn" onclick="selectLanguage('fil', event)">Filipino</button>
                        <button class="language-btn" onclick="selectLanguage('ja', event)">日本語</button>
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
                </div>
            </div>

            <!-- Screen 2: QR Scanner -->
            <div class="screen" id="qrScannerScreen">
                <div class="qr-scanner-container">
                    <h2 class="form-title" data-translate="scanQRTitle">Scan Your QR Code</h2>
                    <p class="text-center text-muted mb-4" data-translate="scanQRDesc">Please scan the QR code from your previous visit</p>
                    
                    <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                    
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
                                <input type="text" class="form-control form-control-lg" id="firstName" data-translate-placeholder="firstNamePlaceholder">
                                <div class="invalid-feedback" data-translate="firstNameRequired">First name is required</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="lastName">Last Name *</label>
                                <input type="text" class="form-control form-control-lg" id="lastName" data-translate-placeholder="lastNamePlaceholder">
                                <div class="invalid-feedback" data-translate="lastNameRequired">Last name is required</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" data-translate="email">Email Address *</label>
                        <input type="email" class="form-control form-control-lg" id="email" data-translate-placeholder="emailPlaceholder">
                        <div class="invalid-feedback" data-translate="emailInvalid">Please enter a valid email address</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="phone">Phone Number *</label>
                                <input type="tel" class="form-control form-control-lg" id="phone" data-translate-placeholder="phonePlaceholder">
                                <div class="invalid-feedback" data-translate="phoneInvalid">Please enter a valid phone number</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="company">Company *</label>
                                <input type="text" class="form-control form-control-lg" id="company" data-translate-placeholder="companyPlaceholder">
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
                        </div>
                        <button class="btn-large btn-next mt-3" onclick="capturePhoto()" id="captureBtn">
                            <i class="bi bi-camera"></i> <span data-translate="takePhoto">Take Photo</span>
                        </button>
                        <button class="btn-large btn-next mt-3" onclick="retakePhoto()" id="retakeBtn" style="display: none;">
                            <i class="bi bi-arrow-clockwise"></i> <span data-translate="retakePhoto">Retake Photo</span>
                        </button>
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

                    <div class="department-selection">
                        <div class="form-group">
                            <label class="form-label" data-translate="selectDepartment">Select Department</label>
                            <select class="form-select form-select-lg" id="departmentSelect" onchange="onDepartmentChange()">
                                <option value="" data-translate="chooseDepartment">Choose a department...</option>
                            </select>
                        </div>
                    </div>

                    <div id="employeeSection" style="display: none;">
                        <label class="form-label" data-translate="selectEmployee">Select Employee</label>
                        <div class="employee-grid" id="employeeGrid">
                            <!-- Employees will be populated here -->
                        </div>
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
                    
                    <div class="purpose-grid" id="purposeGrid">
                        <!-- Purposes will be populated here -->
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label" data-translate="additionalNotes">Additional notes (optional)</label>
                        <textarea class="form-control form-control-lg" id="visitNotes" rows="2" 
                                  data-translate-placeholder="notesPlaceholder"></textarea>
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
                            <!-- Agreement content will be populated by JavaScript -->
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
                    <div class="success-icon text-center">
                        <i class="bi bi-check-lg" style="font-size: 4em; color: #27ae60;"></i>
                    </div>
                    
                    <h2 class="text-center" style="color: #27ae60;" data-translate="successTitle">You're All Set!</h2>
                    <p class="text-center" data-translate="successMessage">Your host has been notified of your arrival</p>

                    <div class="badge-preview">
                        <h4 class="text-center" data-translate="visitorBadge">Your Visitor Badge</h4>
                        <div class="text-center badge-photo-display" id="badgePhotoDisplay">
                            <i class="bi bi-person-circle" style="font-size: 3em;"></i>
                        </div>
                        <div class="text-center" id="badgeNumber" style="font-size: 1.6em; font-weight: bold;">V-2025-0001</div>
                        <div class="text-center" id="visitorName"></div>
                        <div class="text-center" id="visitorCompany"></div>
                        <hr>
                        <div>
                            <strong data-translate="host">Host:</strong> <span id="badgeHost"></span><br>
                            <strong data-translate="validUntil">Valid Until:</strong> <span id="validUntil"></span>
                        </div>
                    </div>

                    <div class="qr-code-display">
                        <h5 data-translate="yourQRCode">Your QR Code for Next Visit</h5>
                        <div id="qrcode"></div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn-large btn-next" onclick="resetKiosk()">
                            <i class="bi bi-check-circle"></i> <span data-translate="done">Done</span>
                        </button>
                    </div>

                    <p class="text-center mt-3">
                        <span data-translate="autoReset">This screen will reset in</span> 
                        <span id="countdown">60</span> 
                        <span data-translate="seconds">seconds</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- QRCode Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <!-- Html5-QRCode for scanning -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        // Configuration
        const baseUrl = "<?= base_url(); ?>";
        
        // Complete Language Translations
        const translations = {
            'en': {
                companyName: "Welcome to TOMS WORLD",
                selectLanguage: "Please select your preferred language",
                firstTimeVisitor: "First Time Visitor",
                firstTimeDesc: "I'm visiting for the first time",
                returningVisitor: "Returning Visitor",
                returningDesc: "I've been here before",
                deliveryPickup: "Delivery / Pickup",
                deliveryDesc: "I have a delivery or pickup",
                scanQRTitle: "Scan Your QR Code",
                scanQRDesc: "Please scan the QR code from your previous visit",
                noQRCode: "I don't have my QR code",
                letsCheckIn: "Let's get you checked in!",
                firstName: "First Name *",
                firstNamePlaceholder: "John",
                lastName: "Last Name *",
                lastNamePlaceholder: "Smith",
                email: "Email Address *",
                emailPlaceholder: "john.smith@company.com",
                phone: "Phone Number *",
                phonePlaceholder: "(555) 123-4567",
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
                skipNow: "Skip for Now",
                hostTitle: "Who are you here to see?",
                selectDepartment: "Select Department",
                chooseDepartment: "Choose a department...",
                selectEmployee: "Select Employee",
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
                done: "Done",
                autoReset: "This screen will reset in",
                seconds: "seconds",
                processing: "Processing..."
            },
            'zh-TW': {
                companyName: "歡迎來到 TOMS WORLD",
                selectLanguage: "請選擇您的語言偏好",
                firstTimeVisitor: "首次訪客",
                firstTimeDesc: "我是第一次來訪",
                returningVisitor: "回訪訪客",
                returningDesc: "我之前來過",
                deliveryPickup: "送貨/取貨",
                deliveryDesc: "我有送貨或取貨",
                scanQRTitle: "掃描您的QR碼",
                scanQRDesc: "請掃描您上次訪問的QR碼",
                noQRCode: "我沒有QR碼",
                letsCheckIn: "讓我們為您辦理登記！",
                firstName: "名字 *",
                firstNamePlaceholder: "名字",
                lastName: "姓氏 *",
                lastNamePlaceholder: "姓氏",
                email: "電子郵件地址 *",
                emailPlaceholder: "example@company.com",
                phone: "電話號碼 *",
                phonePlaceholder: "電話號碼",
                company: "公司 *",
                companyPlaceholder: "您的公司名稱",
                firstNameRequired: "請輸入名字",
                lastNameRequired: "請輸入姓氏",
                emailInvalid: "請輸入有效的電子郵件地址",
                phoneInvalid: "請輸入有效的電話號碼",
                companyRequired: "請輸入公司名稱",
                back: "返回",
                continue: "繼續",
                photoTitle: "讓我們拍攝您的照片",
                photoDesc: "這有助於我們的員工識別您並確保建築安全",
                takePhoto: "拍照",
                retakePhoto: "重新拍照",
                skipNow: "暫時跳過",
                hostTitle: "您要見誰？",
                selectDepartment: "選擇部門",
                chooseDepartment: "選擇一個部門...",
                selectEmployee: "選擇員工",
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
                yourQRCode: "您下次訪問的QR碼",
                done: "完成",
                autoReset: "此畫面將在",
                seconds: "秒後重置",
                processing: "處理中..."
            },
            'zh-CN': {
                companyName: "欢迎来到 TOMS WORLD",
                selectLanguage: "请选择您的语言偏好",
                firstTimeVisitor: "首次访客",
                firstTimeDesc: "我是第一次来访",
                returningVisitor: "回访访客",
                returningDesc: "我之前来过",
                deliveryPickup: "送货/取货",
                deliveryDesc: "我有送货或取货",
                scanQRTitle: "扫描您的QR码",
                scanQRDesc: "请扫描您上次访问的QR码",
                noQRCode: "我没有QR码",
                letsCheckIn: "让我们为您办理登记！",
                firstName: "名字 *",
                firstNamePlaceholder: "名字",
                lastName: "姓氏 *",
                lastNamePlaceholder: "姓氏",
                email: "电子邮件地址 *",
                emailPlaceholder: "example@company.com",
                phone: "电话号码 *",
                phonePlaceholder: "电话号码",
                company: "公司 *",
                companyPlaceholder: "您的公司名称",
                firstNameRequired: "请输入名字",
                lastNameRequired: "请输入姓氏",
                emailInvalid: "请输入有效的电子邮件地址",
                phoneInvalid: "请输入有效的电话号码",
                companyRequired: "请输入公司名称",
                back: "返回",
                continue: "继续",
                photoTitle: "让我们拍摄您的照片",
                photoDesc: "这有助于我们的员工识别您并确保建筑安全",
                takePhoto: "拍照",
                retakePhoto: "重新拍照",
                skipNow: "暂时跳过",
                hostTitle: "您要见谁？",
                selectDepartment: "选择部门",
                chooseDepartment: "选择一个部门...",
                selectEmployee: "选择员工",
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
                yourQRCode: "您下次访问的QR码",
                done: "完成",
                autoReset: "此画面将在",
                seconds: "秒后重置",
                processing: "处理中..."
            },
            'fil': {
                companyName: "Maligayang pagdating sa TOMS WORLD",
                selectLanguage: "Mangyaring piliin ang iyong gustong wika",
                firstTimeVisitor: "Unang Bisita",
                firstTimeDesc: "Unang pagkakataon kong bumisita",
                returningVisitor: "Bumabalik na Bisita",
                returningDesc: "Nakarating na ako dito dati",
                deliveryPickup: "Paghahatid / Pagkuha",
                deliveryDesc: "May dala akong paghahatid o pagkuha",
                scanQRTitle: "I-scan ang Iyong QR Code",
                scanQRDesc: "Pakiscan ang QR code mula sa iyong nakaraang pagbisita",
                noQRCode: "Wala akong QR code",
                letsCheckIn: "Simulan natin ang iyong pag-check in!",
                firstName: "Unang Pangalan *",
                firstNamePlaceholder: "Juan",
                lastName: "Apelyido *",
                lastNamePlaceholder: "Dela Cruz",
                email: "Email Address *",
                emailPlaceholder: "juan@company.com",
                phone: "Numero ng Telepono *",
                phonePlaceholder: "(555) 123-4567",
                company: "Kumpanya *",
                companyPlaceholder: "Pangalan ng Kumpanya",
                firstNameRequired: "Kailangan ang unang pangalan",
                lastNameRequired: "Kailangan ang apelyido",
                emailInvalid: "Mangyaring maglagay ng valid na email address",
                phoneInvalid: "Mangyaring maglagay ng valid na numero ng telepono",
                companyRequired: "Kailangan ang pangalan ng kumpanya",
                back: "Bumalik",
                continue: "Magpatuloy",
                photoTitle: "Kumuha tayo ng larawan",
                photoDesc: "Ito ay tumutulong sa aming mga tauhan na kilalanin ka at nagsisiguro ng seguridad",
                takePhoto: "Kumuha ng Larawan",
                retakePhoto: "Ulitin ang Pagkuha",
                skipNow: "Laktawan Muna",
                hostTitle: "Sino ang iyong gustong makita?",
                selectDepartment: "Piliin ang Departamento",
                chooseDepartment: "Pumili ng departamento...",
                selectEmployee: "Piliin ang Empleyado",
                selectedHost: "Napiling Tauhan",
                noSelection: "Wala pang napili",
                purposeTitle: "Ano ang dahilan ng iyong pagbisita ngayon?",
                meeting: "Pulong",
                interview: "Interbyu",
                delivery: "Paghahatid",
                service: "Serbisyo/Pagkumpuni",
                training: "Pagsasanay",
                tour: "Paglilibot",
                event: "Kaganapan",
                other: "Iba pa",
                additionalNotes: "Karagdagang tala (opsyonal)",
                notesPlaceholder: "Anumang karagdagang impormasyon...",
                termsTitle: "Mga Tuntunin at Kasunduan",
                agreeTerms: "Nabasa ko at sumasang-ayon ako sa lahat ng tuntunin at kondisyon",
                agreePhoto: "Pumapayag akong gamitin ang aking larawan para sa seguridad",
                completeCheckIn: "Kumpletuhin ang Check-In",
                successTitle: "Handa Ka Na!",
                successMessage: "Naabisuhan na ang iyong tauhan tungkol sa iyong pagdating",
                visitorBadge: "Ang Iyong Visitor Badge",
                host: "Tauhan",
                validUntil: "Balido Hanggang",
                yourQRCode: "Ang Iyong QR Code para sa Susunod na Pagbisita",
                done: "Tapos",
                autoReset: "Ang screen na ito ay mag-reset sa",
                seconds: "segundo",
                processing: "Pinoproseso..."
            },
            'ja': {
                companyName: "TOMS WORLDへようこそ",
                selectLanguage: "ご希望の言語を選択してください",
                firstTimeVisitor: "初回訪問者",
                firstTimeDesc: "初めて訪問します",
                returningVisitor: "再訪問者",
                returningDesc: "以前に来たことがあります",
                deliveryPickup: "配達/受取",
                deliveryDesc: "配達または受取があります",
                scanQRTitle: "QRコードをスキャン",
                scanQRDesc: "前回の訪問のQRコードをスキャンしてください",
                noQRCode: "QRコードがありません",
                letsCheckIn: "チェックインを始めましょう！",
                firstName: "名 *",
                firstNamePlaceholder: "太郎",
                lastName: "姓 *",
                lastNamePlaceholder: "山田",
                email: "メールアドレス *",
                emailPlaceholder: "example@company.com",
                phone: "電話番号 *",
                phonePlaceholder: "090-1234-5678",
                company: "会社名 *",
                companyPlaceholder: "会社名を入力",
                firstNameRequired: "名前を入力してください",
                lastNameRequired: "姓を入力してください",
                emailInvalid: "有効なメールアドレスを入力してください",
                phoneInvalid: "有効な電話番号を入力してください",
                companyRequired: "会社名を入力してください",
                back: "戻る",
                continue: "続ける",
                photoTitle: "写真を撮影しましょう",
                photoDesc: "これはスタッフがあなたを識別し、セキュリティを確保するのに役立ちます",
                takePhoto: "写真を撮る",
                retakePhoto: "撮り直し",
                skipNow: "今はスキップ",
                hostTitle: "どなたにお会いになりますか？",
                selectDepartment: "部署を選択",
                chooseDepartment: "部署を選択してください...",
                selectEmployee: "従業員を選択",
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
                agreeTerms: "すべての規約と条件を読み、同意します",
                agreePhoto: "セキュリティ目的で写真を使用することに同意します",
                completeCheckIn: "チェックインを完了",
                successTitle: "準備完了です！",
                successMessage: "ホストに到着が通知されました",
                visitorBadge: "訪問者バッジ",
                host: "ホスト",
                validUntil: "有効期限",
                yourQRCode: "次回訪問用のQRコード",
                done: "完了",
                autoReset: "この画面は",
                seconds: "秒後にリセットされます",
                processing: "処理中..."
            }
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
        let departments = [];
        let employees = [];
        let purposes = [];

        // Initialize on DOM ready
        $(document).ready(function() {
            updateDateTime();
            setInterval(updateDateTime, 1000);
            translatePage();
            loadDepartments();
            loadPurposes();
        });

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('en-US', options);
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            document.getElementById('datetime').textContent = `${dateStr} • ${timeStr}`;
        }

        // Language selection - Fixed
        function selectLanguage(lang, event) {
            currentLanguage = lang;
            document.querySelectorAll('.language-btn').forEach(btn => btn.classList.remove('active'));
            if (event && event.target) {
                event.target.classList.add('active');
            }
            translatePage();
        }

        // Translate page - Enhanced
        function translatePage() {
            // Translate text content
            const elements = document.querySelectorAll('[data-translate]');
            elements.forEach(el => {
                const key = el.getAttribute('data-translate');
                if (translations[currentLanguage] && translations[currentLanguage][key]) {
                    el.textContent = translations[currentLanguage][key];
                }
            });

            // Translate placeholders
            const placeholderElements = document.querySelectorAll('[data-translate-placeholder]');
            placeholderElements.forEach(el => {
                const key = el.getAttribute('data-translate-placeholder');
                if (translations[currentLanguage] && translations[currentLanguage][key]) {
                    el.placeholder = translations[currentLanguage][key];
                }
            });
        }

        // Load departments from database
        function loadDepartments() {
            $.ajax({
                url: baseUrl + "Visitor/getDepartments",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        departments = response.data;
                        populateDepartmentDropdown();
                    }
                },
                error: function() {
                    console.error("Failed to load departments");
                }
            });
        }

        // Populate department dropdown
        function populateDepartmentDropdown() {
            const select = document.getElementById('departmentSelect');
            select.innerHTML = '<option value="">' + (translations[currentLanguage].chooseDepartment || 'Choose a department...') + '</option>';
            
            departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept.dept_id;
                option.textContent = dept.department;
                select.appendChild(option);
            });
        }

        // Load employees by department
        function onDepartmentChange() {
            const deptId = document.getElementById('departmentSelect').value;
            
            if (!deptId) {
                document.getElementById('employeeSection').style.display = 'none';
                return;
            }
            
            $.ajax({
                url: baseUrl + "Visitor/getEmployeesByDepartment",
                method: "POST",
                data: { department_id: deptId },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        employees = response.data;
                        populateEmployeeGrid();
                        document.getElementById('employeeSection').style.display = 'block';
                    }
                },
                error: function() {
                    console.error("Failed to load employees");
                }
            });
        }

        // Populate employee grid
        function populateEmployeeGrid() {
            const grid = document.getElementById('employeeGrid');
            grid.innerHTML = '';
            
            employees.forEach(emp => {
                const card = document.createElement('div');
                card.className = 'employee-card';
                card.innerHTML = `
                    <i class="bi bi-person-circle" style="font-size: 2em;"></i>
                    <div>${emp.f_name} ${emp.l_name}</div>
                    <small class="text-muted">${emp.position || 'Staff'}</small>
                `;
                card.onclick = () => selectEmployee(emp);
                grid.appendChild(card);
            });
        }

        // Select employee
        function selectEmployee(employee) {
            document.querySelectorAll('.employee-card').forEach(card => card.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            
            selectedHost = employee;
            visitorData.host_id = employee.emp_id;
            
            document.getElementById('selectedHost').innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-person-circle" style="font-size: 2em;"></i>
                    <div>
                        <div style="font-weight: 600;">${employee.f_name} ${employee.l_name}</div>
                        <div style="font-size: 0.9em; color: #7f8c8d;">${employee.position || 'Staff'}</div>
                    </div>
                </div>
            `;
            
            document.getElementById('hostNextBtn').disabled = false;
        }

        // Load visit purposes
        function loadPurposes() {
            $.ajax({
                url: baseUrl + "Visitor/getVisitPurposes",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        purposes = response.data;
                        populatePurposeGrid();
                    }
                },
                error: function() {
                    console.error("Failed to load purposes");
                }
            });
        }

        // Populate purpose grid
        function populatePurposeGrid() {
            const grid = document.getElementById('purposeGrid');
            grid.innerHTML = '';
            
            const icons = {
                'Meeting': 'bi-people',
                'Interview': 'bi-briefcase',
                'Delivery': 'bi-box',
                'Service/Maintenance': 'bi-tools',
                'Training': 'bi-mortarboard',
                'Facility Tour': 'bi-map',
                'Event': 'bi-calendar-event',
                'Other': 'bi-three-dots'
            };
            
            purposes.forEach(purpose => {
                const card = document.createElement('div');
                card.className = 'purpose-card';
                const icon = icons[purpose.purpose_name] || 'bi-circle';
                card.innerHTML = `
                    <i class="bi ${icon}" style="font-size: 2em;"></i>
                    <h5>${purpose.purpose_name}</h5>
                `;
                card.onclick = () => selectPurpose(purpose, card);
                grid.appendChild(card);
            });
        }

        // Select purpose
        function selectPurpose(purpose, element) {
            document.querySelectorAll('.purpose-card').forEach(card => card.classList.remove('selected'));
            element.classList.add('selected');
            selectedPurpose = purpose;
            visitorData.purpose_id = purpose.id;
            document.getElementById('purposeNextBtn').disabled = false;
        }

        // Screen navigation
        function showScreen(screenNumber) {
            // Stop camera/QR scanner if leaving those screens
            if (currentScreen === 2 && html5QrCode) {
                html5QrCode.stop().catch(() => {});
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
            
            updateStepIndicator(screenNumber);
            currentScreen = screenNumber;
        }

        function nextScreen() {
            if (validateCurrentScreen()) {
                showScreen(currentScreen + 1);
            }
        }

        function previousScreen() {
            showScreen(currentScreen - 1);
        }

        // Update step indicator
        function updateStepIndicator(step) {
            document.querySelectorAll('.step-dot').forEach((dot, index) => {
                dot.classList.remove('active', 'completed');
                if (index + 1 < step) dot.classList.add('completed');
                else if (index + 1 === step) dot.classList.add('active');
            });
        }

        // Start check-in
        function startCheckIn(type) {
            visitorData.type = type;
            
            if (type === 'returning') {
                showScreen(2); // QR scanner
                initQRScanner();
            } else {
                showScreen(3); // Basic info
            }
        }

        // Initialize QR Scanner
        function initQRScanner() {
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
            }
            
            html5QrCode = new Html5Qrcode("qr-reader");
            
            const config = { 
                fps: 10, 
                qrbox: { width: 250, height: 250 }
            };
            
            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    handleQRCodeSuccess(decodedText);
                },
                (error) => {
                    // Ignore scan errors
                }
            ).catch((err) => {
                console.error("Unable to start QR scanner:", err);
                showNotification("Camera not available for QR scanning");
            });
        }

        // Handle QR code success
        function handleQRCodeSuccess(decodedText) {
            try {
                const qrData = JSON.parse(decodedText);
                
                if (qrData.visitor_id) {
                    // Load visitor data from database
                    $.ajax({
                        url: baseUrl + "Visitor/getVisitorById",
                        method: "POST",
                        data: { visitor_id: qrData.visitor_id },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                const visitor = response.data;
                                document.getElementById('firstName').value = visitor.first_name;
                                document.getElementById('lastName').value = visitor.last_name;
                                document.getElementById('email').value = visitor.email;
                                document.getElementById('phone').value = visitor.phone;
                                document.getElementById('company').value = visitor.company_name || '';
                                
                                visitorData.visitor_id = visitor.id;
                                
                                if (html5QrCode) {
                                    html5QrCode.stop().catch(() => {});
                                }
                                
                                showScreen(3); // Skip to basic info for confirmation
                                showNotification("Welcome back! Please confirm your information.");
                            }
                        }
                    });
                }
            } catch (e) {
                showNotification("Invalid QR code");
            }
        }

        // Skip QR scan
        function skipQRScan() {
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
            }
            showScreen(3);
        }

        // Validate current screen
        function validateCurrentScreen() {
            switch(currentScreen) {
                case 3: // Basic Info
                    const firstName = document.getElementById('firstName').value.trim();
                    const lastName = document.getElementById('lastName').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const phone = document.getElementById('phone').value.trim();
                    const company = document.getElementById('company').value.trim();
                    
                    if (!firstName || !lastName || !email || !phone || !company) {
                        showNotification('Please fill in all required fields');
                        return false;
                    }
                    
                    visitorData.firstName = firstName;
                    visitorData.lastName = lastName;
                    visitorData.email = email;
                    visitorData.phone = phone;
                    visitorData.company = company;
                    return true;
                    
                case 4: // Photo
                    visitorData.photo = capturedPhotoData;
                    return true;
                    
                case 5: // Host
                    if (!selectedHost) {
                        showNotification('Please select who you are here to see');
                        return false;
                    }
                    return true;
                    
                case 6: // Purpose
                    if (!selectedPurpose) {
                        showNotification('Please select the purpose of your visit');
                        return false;
                    }
                    visitorData.notes = document.getElementById('visitNotes').value;
                    return true;
                    
                case 7: // Agreement
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
        }

        function retakePhoto() {
            const video = document.getElementById('videoElement');
            const image = document.getElementById('capturedImage');
            
            video.style.display = 'block';
            image.style.display = 'none';
            
            document.getElementById('captureBtn').style.display = 'block';
            document.getElementById('retakeBtn').style.display = 'none';
            
            capturedPhotoData = null;
        }

        // Check agreement
        function checkAgreement() {
            const terms = document.getElementById('agreeTerms').checked;
            const photo = document.getElementById('agreePhoto').checked;
            document.getElementById('agreeNextBtn').disabled = !(terms && photo);
        }

        // Complete check-in with QR generation and email
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
                    confirmButtonColor: '#f39c12',
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
                confirmButtonColor: '#f39c12'
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
                background: #f39c12;
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