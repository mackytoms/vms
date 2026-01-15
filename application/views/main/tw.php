
<body>
    <!-- Main Kiosk Container -->
    <div class="kiosk-container">
        <!-- Header -->
        <!-- <div class="kiosk-header">
            <div class="company-logo">
                <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" 
                    alt="Toms World" 
                style="width: 40px; height: 40px; object-fit: contain; border-radius: 50%;">
            </div>
            <h1 data-translate="companyName">Welcome to TOMS WORLD</h1>
            <div class="datetime-display" id="datetime"></div>
        </div> -->

        <div class="kiosk-header">
            <button class="offcanvas-trigger" onclick="toggleOffcanvas()" aria-label="Open info panel">
                <i class="bi bi-info-circle"></i>
            </button>
            
            <div class="header-center">
                <div class="company-logo">
                    <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" 
                        alt="Toms World" 
                        style="width: 40px; height: 40px; object-fit: contain; border-radius: 50%;">
                </div>
                <h1 data-translate="companyName">Welcome to TOMS WORLD</h1>
                <div class="datetime-display" id="datetime"></div>
            </div>
        </div>
        
        <!-- Off-canvas Info Panel -->
        <div class="offcanvas-overlay" id="offcanvasOverlay" onclick="closeOffcanvas()"></div>
        <div class="offcanvas-panel" id="offcanvasPanel">
            <div class="offcanvas-header">
                <h3><i class="bi bi-info-circle"></i> Information</h3>
                <button class="offcanvas-close" onclick="closeOffcanvas()" aria-label="Close panel">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="offcanvas-content">
                <div class="offcanvas-section">
                    <h4>Visitor Check-In System</h4>
                    <p>Scan the QR code below to access our visitor portal from your mobile device.</p>
                </div>
                
                <div class="offcanvas-qr-container">
                    <img src="<?= base_url('assets/images/qr/qr_tw.png') ?>" 
                        alt="QR Code" 
                        class="offcanvas-qr-code">
                    <p class="qr-description">Scan for quick access</p>
                </div>
                
                <div class="offcanvas-section">
                    <h4><i class="bi bi-clock"></i> Operating Hours</h4>
                    <p>Monday - Wednesday: 8:00 AM - 7:00 PM<br>
                    Thursday - Friday: 8:00 AM - 6:00 PM<br>
                    Saturday - Sunday: Closed</p>
                </div>
                
                <!-- <div class="offcanvas-section">
                    <h4><i class="bi bi-telephone"></i> Need Help?</h4>
                    <p>Reception: (02) 1234-5678<br>
                    Security: (02) 8765-4321</p>
                </div> -->
                
                <div class="offcanvas-section">
                    <h4><i class="bi bi-shield-check"></i> Visitor Guidelines</h4>
                    <ul class="guidelines-list">
                        <li>Valid ID required for check-in</li>
                        <li>Please wear your visitor badge at all times</li>
                        <li>Follow all safety protocols</li>
                        <li>Photography requires prior approval</li>
                    </ul>
                </div>
            </div>
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
                        <button class="language-btn" onclick="selectLanguage('fil')">Filipino</button>
                        <button class="language-btn active" onclick="selectLanguage('en')">English</button>
                        <button class="language-btn" onclick="selectLanguage('zh-TW')">繁體中文</button>
                        <button class="language-btn" onclick="selectLanguage('zh-CN')">简体中文</button>
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

                    <!-- <div class="quick-actions">
                        <button class="quick-action-btn" onclick="showPreScheduled()">
                            <i class="bi bi-calendar-check"></i> <span data-translate="preScheduled">Pre-Scheduled Visit</span>
                        </button>
                        <button class="quick-action-btn" onclick="checkOut()">
                            <i class="bi bi-box-arrow-right"></i> <span data-translate="checkOut">Check Out</span>
                        </button>
                    </div> -->
                </div>
            </div>

            <!-- Screen 2: QR Scanner for Returning Visitors -->
            <div class="screen" id="qrScannerScreen">
                <div class="qr-scanner-container">
                    <h2 class="form-title" data-translate="scanQRTitle">Scan Your QR Code</h2>
                    <p class="text-center text-muted mb-4" data-translate="scanQRDesc">Please scan the QR code from your previous visit</p>
                    
                    <div id="qr-reader"></div>
                    
                    <!-- <div class="qr-upload-section">
                        <p class="text-muted mb-3" data-translate="orUploadQR">Or upload QR code image</p>
                        <label class="qr-upload-btn">
                            <i class="bi bi-upload"></i> <span data-translate="uploadQR">Upload QR Code</span>
                            <input type="file" accept="image/*" onchange="handleQRUpload(this)">
                        </label>
                    </div> -->

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
                                <input type="text" class="form-control form-control-lg" id="firstName">
                                <div class="invalid-feedback" data-translate="firstNameRequired">First name is required</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="lastName">Last Name *</label>
                                <input type="text" class="form-control form-control-lg" id="lastName">
                                <div class="invalid-feedback" data-translate="lastNameRequired">Last name is required</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" data-translate="email">Email Address</label>
                        <small class="text-muted">(At least one required: email or phone)</small>
                        <input type="email" class="form-control form-control-lg" id="email">
                        <div class="invalid-feedback" data-translate="emailInvalid">Please enter a valid email address</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="phone">Phone Number</label>
                                <small class="text-muted">(At least one required: email or phone)</small>
                                <div class="input-group">
                                    <span class="input-group-text">+63</span>
                                    <input 
                                        type="tel" 
                                        class="form-control form-control-lg" 
                                        id="phone"
                                        name="phone"
                                        maxlength="10"
                                        pattern="^[9][0-9]{9}$"
                                    >
                                    <div class="invalid-feedback" data-translate="phoneInvalid">
                                        Please enter a valid Philippine mobile number
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="company">Company / Branch *</label>
                                <input type="text" class="form-control form-control-lg" id="company" data-translate-placeholder="companyPlaceholder" placeholder="Your Affiliated Company">
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
                        <div style="display: flex; justify-content: center;">
                            <button class="btn-large btn-next" onclick="capturePhoto()" id="captureBtn">
                                <i class="bi bi-camera"></i> <span data-translate="takePhoto">Take Photo</span>
                            </button>
                            
                            <button class="btn-large btn-next" onclick="retakePhoto()" id="retakeBtn" style="display: none;">
                                <i class="bi bi-arrow-clockwise"></i> <span data-translate="retakePhoto">Retake Photo</span>
                            </button>
                        </div> 
                        <p class="text-muted mt-2" data-translate="photoGuide">Position your face within the oval guide</p>
                    </div>

                    <div class="nav-buttons">
                        <button class="btn-large btn-back" onclick="previousScreen()">
                            <i class="bi bi-arrow-left"></i> <span data-translate="back">Back</span>
                        </button>
                        <button class="btn-large btn-skip" onclick="nextScreen()" id="photoSkipBtn">
                            <span data-translate="skipNow">Skip for Now</span> <i class="bi bi-arrow-right"></i>
                        </button>
                        <button class="btn-large btn-next" onclick="nextScreen()" id="photoNextBtn" style="display: none;">
                            <span data-translate="continue">Continue</span> <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Screen 5: Host Selection (Enhanced) -->
            <!-- <div class="screen" id="hostScreen">
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

                    <!- ADD THIS SEARCH INPUT ->
                    <div class="employee-search-container mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" 
                                class="form-control form-control-lg" 
                                id="employeeSearch" 
                                placeholder="Search employee by name..."
                                data-translate-placeholder="searchEmployeePlaceholder"
                                oninput="filterEmployees(this.value)">
                            <button class="btn btn-outline-secondary" type="button" onclick="clearEmployeeSearch()">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    <!- END SEARCH INPUT ->

                    <div id="employeeSection" style="display: none;">
                        <label class="form-label" data-translate="selectEmployee">Select Employee</label>
                        <div class="employee-grid" id="employeeGrid">
                            <!- Employees will be populated here ->
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
            </div> -->

            <!-- Screen 5: Host Selection (Enhanced with Search) -->
            <div class="screen" id="hostScreen">
                <div class="form-screen">
                    <h2 class="form-title" data-translate="hostTitle">Who are you here to see?</h2>

                    <!-- EMPLOYEE SEARCH INPUT - Now shown immediately -->
                    <div class="employee-search-container mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" 
                                class="form-control form-control-lg" 
                                id="employeeSearch" 
                                placeholder="Search employee by name..."
                                data-translate-placeholder="searchEmployeePlaceholder"
                                oninput="filterEmployees(this.value)"
                                autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" onclick="clearEmployeeSearch()" title="Clear search">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <small class="text-muted mt-1 d-block" id="employeeCount"></small>
                    </div>
                    
                    <!-- Employee Grid -->
                    <div class="employee-grid" id="employeeGrid">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 text-muted">Loading employees...</p>
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
            <!-- <div class="screen" id="hostScreen">
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
                        
                        <!- EMPLOYEE SEARCH INPUT ->
                        <div class="employee-search-container mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" 
                                    class="form-control form-control-lg" 
                                    id="employeeSearch" 
                                    placeholder="Search employee by name..."
                                    data-translate-placeholder="searchEmployeePlaceholder"
                                    oninput="filterEmployees(this.value)"
                                    autocomplete="off">
                                <button class="btn btn-outline-secondary" type="button" onclick="clearEmployeeSearch()" title="Clear search">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <small class="text-muted mt-1 d-block" id="employeeCount"></small>
                        </div>
                        <!- END EMPLOYEE SEARCH INPUT ->
                        
                        <div class="employee-grid" id="employeeGrid">
                            <!- Employees will be populated here ->
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
            </div> -->

            <!-- Screen 6: Purpose Selection -->
            <div class="screen" id="purposeScreen">
                <div class="form-screen">
                    <h2 class="form-title" data-translate="purposeTitle">What brings you here today?</h2>
                    
                    <!-- Purpose cards will be populated dynamically -->
                    <div class="purpose-grid" id="purposeGrid">
                        <!-- Purposes loaded from database -->
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

            <!-- Screen 6: Purpose Selection -->
            <!-- <div class="screen" id="purposeScreen">
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
            </div> -->

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

            <!-- Screen 8: Success (UPDATED - NO QR CODE) -->
            <div class="screen" id="successScreen">
                <div class="success-screen">
                    <div class="success-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    
                    <h2 style="color: #27ae60; font-size: 2em; margin-bottom: 15px;" data-translate="successTitle">You're All Set!</h2>
                    <p style="font-size: 1.1em; color: #7f8c8d; margin-bottom: 20px;" data-translate="successMessage">
                        Your host has been notified of your arrival
                    </p>

                    <!-- <div class="badge-preview" id="badgePreview">
                        <h4 style="color: #f39c12; margin-bottom: 15px;" data-translate="visitorBadge">Your Visitor Badge</h4>
                        <div class="badge-photo-display" id="badgePhotoDisplay">
                            <i class="bi bi-person-circle" style="font-size: 3em; color: #dee2e6;"></i>
                        </div>
                        <div id="badgeNumber" style="font-size: 1.6em; font-weight: bold; color: #f39c12a8; margin-bottom: 10px;">
                            V-2024-1201
                        </div>
                        <div id="visitorName" style="font-size: 1.3em; margin-bottom: 8px;"></div>
                        <div id="visitorCompany" style="color: #7f8c8d; margin-bottom: 12px;"></div>
                        <hr>
                        <div style="margin-top: 12px; text-align: left;">
                            <strong data-translate="host">Host:</strong> <span id="badgeHost"></span><br>
                            <strong data-translate="validUntil">Valid Until:</strong> <span id="validUntil"></span>
                        </div>
                        
                        <!- QR CODE SECTION - NEW ->
                        <div style="margin-top: 20px; padding: 15px; background: #fff; border-radius: 8px; border: 2px dashed #f39c12;">
                            <h5 style="color: #495057; margin-bottom: 10px;">
                                <i class="bi bi-qr-code"></i> <span data-translate="saveQRCode">Save Your QR Code</span>
                            </h5>
                            <p style="font-size: 0.9em; color: #6c757d; margin-bottom: 15px;" data-translate="qrCodeDesc">
                                Scan this QR code on your next visit for faster check-in
                            </p>
                            <div id="qrCodeContainer" style="display: flex; justify-content: center; margin-bottom: 10px;"></div>
                            <!- <button class="btn btn-outline-primary btn-sm" onclick="downloadQRCode()" style="margin-top: 10px;">
                                <i class="bi bi-download"></i> <span data-translate="downloadQR">Download QR Code</span>
                            </button> ->
                        </div>
                    </div> -->

                    <div class="row g-4 align-items-start">
    
                        <!-- LEFT: Badge Details -->
                        <div class="col-md-6">
                            <div class="badge-preview" id="badgePreview" 
                                style="border: 1px solid #f39c12; border-radius: 12px; padding: 20px;">
                                
                                <h4 style="color: #f39c12; margin-bottom: 15px;" data-translate="visitorBadge">
                                    Your Visitor Badge
                                </h4>

                                <div class="badge-photo-display" id="badgePhotoDisplay" style="text-align:center;">
                                    <i class="bi bi-person-circle" style="font-size: 3em; color: #dee2e6;"></i>
                                </div>

                                <div id="badgeNumber" style="font-size: 1.6em; font-weight: bold; color: #f39c12a8; margin-bottom: 10px; text-align:center;">
                                    V-2024-1201
                                </div>

                                <div id="visitorName" style="font-size: 1.3em; margin-bottom: 8px; text-align:center;"></div>
                                <div id="visitorCompany" style="color: #7f8c8d; margin-bottom: 12px; text-align:center;"></div>
                                <hr>

                                <div style="margin-top: 12px; text-align: left;">
                                    <strong data-translate="host">Host:</strong> <span id="badgeHost"></span><br>
                                    <strong data-translate="validUntil">Valid Until:</strong> <span id="validUntil"></span>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT: QR Code Section -->
                        <div class="col-md-6">
                            <div style="padding: 20px; background: #fff; border-radius: 12px; border: 2px dashed #f39c12; margin: 25px auto;">
                                <h5 style="color: #495057; margin-bottom: 10px;">
                                    <i class="bi bi-qr-code"></i> 
                                    <span data-translate="saveQRCode">Save Your QR Code</span>
                                </h5>

                                <p style="font-size: 0.9em; color: #6c757d; margin-bottom: 15px;" data-translate="qrCodeDesc">
                                    Scan this QR code on your next visit for faster check-in
                                </p>

                                <div id="qrCodeContainer" 
                                    style="display:flex; justify-content:center; margin-bottom:10px;">
                                </div>

                                <!-- Optional download button -->
                                <!-- 
                                <button class="btn btn-outline-primary btn-sm" onclick="downloadQRCode()">
                                    <i class="bi bi-download"></i> <span data-translate="downloadQR">Download QR Code</span>
                                </button> 
                                -->
                            </div>
                        </div>

                    </div>


                    <div style="background: #e8f4fd; border-left: 4px solid #f39c12; padding: 15px; border-radius: 8px; margin: 20px 0; text-align: left;">
                        <h5 style="color: #f39c12a8; margin-bottom: 8px;">
                            <i class="bi bi-info-circle"></i> <span data-translate="nextSteps">Next Steps</span>
                        </h5>
                        <ol style="margin: 0; padding-left: 20px; color: #495057;" id="nextStepsList">
                            <!-- Next steps will be populated by JavaScript based on language -->
                        </ol>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <!-- <button class="btn-large btn-print" onclick="printBadge()">
                            <i class="bi bi-printer"></i> <span data-translate="printBadge">Print Badge</span>
                        </button> -->
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
        // // Department and Employee Data Structure
        // const departmentData = {
        //     'ADM': {
        //         name: 'Admin',
        //         employees: [
        //             { id: 'ADM001', name: 'John Smith', email: 'j.smith@company.com' },
        //             { id: 'ADM002', name: 'Sarah Johnson', email: 's.johnson@company.com' },
        //             { id: 'ADM003', name: 'Michael Chen', email: 'm.chen@company.com' }
        //         ]
        //     },
        //     'BDD': {
        //         name: 'Design & Construction',
        //         employees: [
        //             { id: 'BDD001', name: 'Emily Davis', email: 'e.davis@company.com' },
        //             { id: 'BDD002', name: 'Robert Wilson', email: 'r.wilson@company.com' }
        //         ]
        //     },
        //     'CRT': {
        //         name: 'Creatives',
        //         employees: [
        //             { id: 'CRT001', name: 'Lisa Anderson', email: 'l.anderson@company.com' },
        //             { id: 'CRT002', name: 'David Martinez', email: 'd.martinez@company.com' },
        //             { id: 'CRT003', name: 'Jessica Taylor', email: 'j.taylor@company.com' }
        //         ]
        //     },
        //     'ED': {
        //         name: 'Ent. Risk Management',
        //         employees: [
        //             { id: 'ED001', name: 'Thomas Brown', email: 't.brown@company.com' },
        //             { id: 'ED002', name: 'Jennifer White', email: 'j.white@company.com' }
        //         ]
        //     },
        //     'EXE': {
        //         name: 'Executive',
        //         employees: [
        //             { id: 'EXE001', name: 'William Garcia', email: 'w.garcia@company.com' },
        //             { id: 'EXE002', name: 'Patricia Miller', email: 'p.miller@company.com' }
        //         ]
        //     },
        //     'FIN': {
        //         name: 'Finance',
        //         employees: [
        //             { id: 'FIN001', name: 'Christopher Lee', email: 'c.lee@company.com' },
        //             { id: 'FIN002', name: 'Amanda Jones', email: 'a.jones@company.com' },
        //             { id: 'FIN003', name: 'Daniel Rodriguez', email: 'd.rodriguez@company.com' }
        //         ]
        //     },
        //     'HR': {
        //         name: 'Human Resource',
        //         employees: [
        //             { id: 'HR001', name: 'Michelle Thompson', email: 'm.thompson@company.com' },
        //             { id: 'HR002', name: 'Kevin Harris', email: 'k.harris@company.com' },
        //             { id: 'HR003', name: 'Rachel Clark', email: 'r.clark@company.com' }
        //         ]
        //     },
        //     'IMP': {
        //         name: 'Importation',
        //         employees: [
        //             { id: 'IMP001', name: 'Brian Lewis', email: 'b.lewis@company.com' },
        //             { id: 'IMP002', name: 'Sophia Walker', email: 's.walker@company.com' }
        //         ]
        //     },
        //     'ITSD': {
        //         name: 'Information Technology & Services',
        //         employees: [
        //             { id: 'ITSD001', name: 'James Hall', email: 'j.hall@company.com' },
        //             { id: 'ITSD002', name: 'Olivia Allen', email: 'o.allen@company.com' },
        //             { id: 'ITSD003', name: 'Matthew Young', email: 'm.young@company.com' },
        //             { id: 'ITSD004', name: 'Emma King', email: 'e.king@company.com' }
        //         ]
        //     },
        //     'MRK': {
        //         name: 'Marketing',
        //         employees: [
        //             { id: 'MRK001', name: 'Andrew Wright', email: 'a.wright@company.com' },
        //             { id: 'MRK002', name: 'Isabella Lopez', email: 'i.lopez@company.com' },
        //             { id: 'MRK003', name: 'Joshua Hill', email: 'j.hill@company.com' }
        //         ]
        //     },
        //     'MER': {
        //         name: 'Audit & Merchandising',
        //         employees: [
        //             { id: 'MER001', name: 'Megan Scott', email: 'm.scott@company.com' },
        //             { id: 'MER002', name: 'Ryan Green', email: 'r.green@company.com' }
        //         ]
        //     },
        //     'OP': {
        //         name: 'Operations',
        //         employees: [
        //             { id: 'OP001', name: 'Nicholas Adams', email: 'n.adams@company.com' },
        //             { id: 'OP002', name: 'Victoria Baker', email: 'v.baker@company.com' },
        //             { id: 'OP003', name: 'Alexander Nelson', email: 'a.nelson@company.com' }
        //         ]
        //     },
        //     'ODSM': {
        //         name: 'Org. Development & Strat. Mngt.',
        //         employees: [
        //             { id: 'ODSM001', name: 'Samantha Carter', email: 's.carter@company.com' },
        //             { id: 'ODSM002', name: 'Joseph Mitchell', email: 'j.mitchell@company.com' }
        //         ]
        //     },
        //     'SPD': {
        //         name: 'Special Projects',
        //         employees: [
        //             { id: 'SPD001', name: 'Lauren Perez', email: 'l.perez@company.com' },
        //             { id: 'SPD002', name: 'Charles Roberts', email: 'c.roberts@company.com' }
        //         ]
        //     },
        //     'SD': {
        //         name: 'Stocks Department',
        //         employees: [
        //             { id: 'SD001', name: 'Ashley Turner', email: 'a.turner@company.com' },
        //             { id: 'SD002', name: 'Benjamin Phillips', email: 'b.phillips@company.com' }
        //         ]
        //     },
        //     'TD': {
        //         name: 'Technical',
        //         employees: [
        //             { id: 'TD001', name: 'Nathan Campbell', email: 'n.campbell@company.com' },
        //             { id: 'TD002', name: 'Madison Parker', email: 'm.parker@company.com' }
        //         ]
        //     },
        //     'WLD': {
        //         name: 'Warehouse & Logistics',
        //         employees: [
        //             { id: 'WLD001', name: 'Eric Evans', email: 'e.evans@company.com' },
        //             { id: 'WLD002', name: 'Hannah Edwards', email: 'h.edwards@company.com' },
        //             { id: 'WLD003', name: 'Tyler Collins', email: 't.collins@company.com' }
        //         ]
        //     },
        //     'PA': {
        //         name: 'Pan Asia HR',
        //         employees: [
        //             { id: 'PA001', name: 'Grace Stewart', email: 'g.stewart@company.com' },
        //             { id: 'PA002', name: 'Dylan Sanchez', email: 'd.sanchez@company.com' }
        //         ]
        //     },
        //     'GT': {
        //         name: 'Game Test',
        //         employees: [
        //             { id: 'GT001', name: 'Angelo Ragon', email: 'ar@gmail.com' }
        //         ]
        //     }
        // };

        // Language Translations (Extended with new keys)
        const translations = {
            'en': {
                companyName: "Welcome to TOM'S WORLD",
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
                letsCheckIn: "Let's get you checked in!",
                searchPrevious: "Search Your Previous Check-ins",
                searchPlaceholder: "Start typing your name or email...",
                firstName: "First Name *",
                lastName: "Last Name *",
                email: "Email Address *",
                phone: "Phone Number *",
                emailOrPhoneRequired: "At least one required: email or phone",
                company: "Company / Branch *",
                companyPlaceholder: "Your Company Name",
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
                
                searchEmployeePlaceholder: "Search employee by name...",
                noEmployeesFound: "No employees found matching your search",

                hostSearchPlaceholder: "Start typing name...",
                popularDepts: "Popular departments:",
                sales: "Sales",
                hr: "Human Resources",
                it: "IT Support",
                reception: "Reception",
                selectedHost: "Selected Host",
                noSelection: "No one selected yet",
                purposeTitle: "What brings you here today?",
                // meeting: "Meeting",
                // interview: "Interview",
                // delivery: "Delivery",
                // service: "Service/Repair",
                // training: "Training",
                // tour: "Tour",
                // event: "Event",
                // other: "Other",
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
                nextSteps: "Next Steps",
                printBadge: "Print Badge",
                done: "Done",
                autoReset: "This screen will reset in",
                seconds: "seconds",
                processing: "Processing...",
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
                
                saveQRCode: "Save Your QR Code",
                qrCodeDesc: "Scan this QR code on your next visit for faster check-in",
                downloadQR: "Download QR Code",
                qrScanSuccess: "QR Code Scanned Successfully!",
                qrScanFailed: "Could not read QR code. Please try again or continue manually.",
                welcomeBackQR: "Welcome back! Your information has been loaded.",

                agreementContent: `
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
                    "Please Take a photo of the QR code this shall serve as your Badge",
                    "then Please Wait in the lobby area for future instructions",
                    "Your host will come to receive you shortly or Hosts' assistance shall come and guide you"
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
                emailOrPhoneRequired: "至少需要一項：電子郵件或電話",
                company: "公司 / 分支 *",
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
                                
                searchEmployeePlaceholder: "按姓名搜尋員工...",
                noEmployeesFound: "找不到符合搜尋條件的員工",

                hostSearchPlaceholder: "開始輸入姓名...",
                popularDepts: "熱門部門：",
                sales: "銷售",
                hr: "人力資源",
                it: "IT支援",
                reception: "接待處",
                selectedHost: "已選擇的接待人",
                noSelection: "尚未選擇任何人",
                purposeTitle: "今天來訪的目的是什麼？",
                // meeting: "會議",
                // interview: "面試",
                // delivery: "送貨",
                // service: "服務/維修",
                // training: "培訓",
                // tour: "參觀",
                // event: "活動",
                // other: "其他",
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
                
                saveQRCode: "保存您的二維碼",
                qrCodeDesc: "下次訪問時掃描此二維碼以快速登記",
                downloadQR: "下載二維碼",
                qrScanSuccess: "二維碼掃描成功！",
                qrScanFailed: "無法讀取二維碼。請重試或手動繼續。",
                welcomeBackQR: "歡迎回來！您的信息已加載。",
                
                agreementContent: `
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
                    "請拍下 QR Code 的照片，這將作為您的訪客證。",
                    "接著請在大廳等候後續指示。",
                    "您的接待人會前來迎接您，或將有協助人員引導您。"
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
                emailOrPhoneRequired: "至少需要一项：电子邮件或电话",
                company: "公司 / 分支 *",
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
                                
                searchEmployeePlaceholder: "按姓名搜索员工...",
                noEmployeesFound: "找不到符合搜索条件的员工",

                hostSearchPlaceholder: "开始输入姓名...",
                popularDepts: "热门部门：",
                sales: "销售",
                hr: "人力资源",
                it: "IT支援",
                reception: "接待处",
                selectedHost: "已选择的接待人",
                noSelection: "尚未选择任何人",
                purposeTitle: "今天来访的目的是什么？",
                // meeting: "会议",
                // interview: "面试",
                // delivery: "送货",
                // service: "服务/维修",
                // training: "培训",
                // tour: "参观",
                // event: "活动",
                // other: "其他",
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

                saveQRCode: "保存您的二维码",
                qrCodeDesc: "下次访问时扫描此二维码以快速登记",
                downloadQR: "下载二维码",
                qrScanSuccess: "二维码扫描成功！",
                qrScanFailed: "无法读取二维码。请重试或手动继续。",
                welcomeBackQR: "欢迎回来！您的信息已加载。",

                agreementContent: `
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
                    "请拍下二维码照片，这将作为您的访客证。",
                    "然后请在大厅等待进一步指示。",
                    "您的接待人会来迎接您，或有工作人员前来引导您。"
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
                emailOrPhoneRequired: "Kailangan ang kahit isa: email o telepono",
                company: "Kumpanya / Sangay *",
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
                
                searchEmployeePlaceholder: "Maghanap ng empleyado ayon sa pangalan...",
                noEmployeesFound: "Walang nakitang empleyado na tumutugma sa iyong paghahanap",

                hostSearchPlaceholder: "Simulang mag-type ng pangalan...",
                popularDepts: "Mga popular na departamento:",
                sales: "Benta",
                hr: "Human Resources",
                it: "IT Support",
                reception: "Reception",
                selectedHost: "Napiling Tauhan",
                noSelection: "Wala pang napili",
                purposeTitle: "Ano ang dahilan ng iyong pagbisita ngayon?",
                // meeting: "Pulong",
                // interview: "Interbyu",
                // delivery: "Paghahatid",
                // service: "Serbisyo/ Pagkukumpuni",
                // training: "Pagsasanay",
                // tour: "Paglilibot",
                // event: "Kaganapan",
                // other: "Iba pa",
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
                
                saveQRCode: "I-save ang Iyong QR Code",
                qrCodeDesc: "I-scan ang QR code na ito sa iyong susunod na pagbisita para sa mas mabilis na pag-check in",
                downloadQR: "I-download ang QR Code",
                qrScanSuccess: "Matagumpay na na-scan ang QR Code!",
                qrScanFailed: "Hindi mabasa ang QR code. Subukan muli o magpatuloy nang manu-mano.",
                welcomeBackQR: "Maligayang pagbabalik! Na-load na ang iyong impormasyon.",
    
                agreementContent: `
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
                    "Paki-kuha ng litrato ng QR code, ito ang magsisilbing badge mo.",
                    "Pagkatapos, maghintay lamang sa lobby para sa susunod na instruksiyon.",
                    "Darating ang iyong host para salubungin ka, o may staff na tutulong at gagabay sa'yo."
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
                emailOrPhoneRequired: "いずれか1つ必須：メールまたは電話",
                company: "会社 / 支店 *",
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
                
                searchEmployeePlaceholder: "名前で従業員を検索...",
                noEmployeesFound: "検索条件に一致する従業員が見つかりません",

                hostSearchPlaceholder: "名前を入力開始...",
                popularDepts: "人気の部署：",
                sales: "営業",
                hr: "人事部",
                it: "ITサポート",
                reception: "受付",
                selectedHost: "選択されたホスト",
                noSelection: "まだ選択されていません",
                purposeTitle: "本日のご訪問の目的は何ですか？",
                // meeting: "会議",
                // interview: "面接",
                // delivery: "配達",
                // service: "サービス/修理",
                // training: "トレーニング",
                // tour: "見学",
                // event: "イベント",
                // other: "その他",
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
                            
                saveQRCode: "QRコードを保存",
                qrCodeDesc: "次回の訪問時にこのQRコードをスキャンして、より速くチェックインできます",
                downloadQR: "QRコードをダウンロード",
                qrScanSuccess: "QRコードのスキャンに成功しました！",
                qrScanFailed: "QRコードを読み取れませんでした。もう一度お試しいただくか、手動で続行してください。",
                welcomeBackQR: "おかえりなさい！情報が読み込まれました。",
                
                agreementContent: `
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
                    "QRコードの写真を撮ってください。これがあなたの入館バッジになります。",
                    "その後、ロビーで指示があるまでお待ちください。",
                    "ホストが迎えに来るか、スタッフがご案内いたします。"
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
        let selectedDepartment = null;
        let countdownTimer = null;
        let videoStream = null;
        let capturedPhotoData = null;
        let html5QrCode = null;
        let photoTaken = false;
        // Global variable for QR Code instance
        let qrCodeInstance = null;
        // Add this flag near the top with other state variables
        let isProcessingQR = false;
        // Purpose
        let availablePurposes = [];
        
        // Global variable to store department data with translations
        let availableDepartments = [];

        // Add this variable near the top with other state variables
        let currentDepartmentEmployees = []; // Store fetched employees for filtering


        // Screen flow mapping
        // const screenFlow = {
        //     'new': [1, 3, 4, 5, 6, 7, 8],
        //     'returning': [1, 2, 5, 6, 7, 8],
        //     'delivery': [1, 3, 4, 5, 6, 7, 8]
        // };
        // Updated screen flow arrays
        const screenFlow = {
            'new': [1, 6, 5, 3, 4, 7, 8],        // Purpose is now step 2
            'returning': [1, 6, 5, 2, 7, 8],     // Purpose after QR scan
            'delivery': [1, 6, 5, 3, 4, 7, 8]    // Purpose auto-selected
        };
        
        let currentFlow = [];
        let currentFlowIndex = 0;

        // Sample pre-scheduled visits
        const preScheduledVisits = [
            { 
                code: 'MEET-2024-001', 
                name: 'Alice Johnson', 
                company: 'Tech Solutions Inc.', 
                host: 'John Smith', 
                time: '10:00 AM',
                purpose: 'Sales Meeting'
            }
        ];

        // State variables - make sure these are declared near the top with other state variables
        let isScannerStopping = false;

        // FIXED: Safe stop function that checks scanner state first
        async function stopQRScanner() {
            if (!html5QrCode || isScannerStopping) {
                return Promise.resolve();
            }
            
            isScannerStopping = true;
            
            return new Promise((resolve) => {
                try {
                    // Check if scanner is actually running before stopping
                    const state = html5QrCode.getState();
                    // State 2 = SCANNING, State 3 = PAUSED (may vary by version)
                    // Some versions use 1 for scanning
                    if (state === 2 || state === 1) {
                        html5QrCode.stop().then(() => {
                            isScannerStopping = false;
                            resolve();
                        }).catch((err) => {
                            console.log('Scanner stop error (ignored):', err);
                            isScannerStopping = false;
                            resolve();
                        });
                    } else {
                        // Scanner not running, just resolve
                        isScannerStopping = false;
                        resolve();
                    }
                } catch (e) {
                    // If getState() fails or any other error, just resolve
                    console.log('Scanner state check error (ignored):', e);
                    isScannerStopping = false;
                    resolve();
                }
            });
        }

        // // Auto-convert all text inputs to lowercase
        // document.addEventListener('DOMContentLoaded', function() {
        //     // Select all text inputs and textareas
        //     const textInputs = document.querySelectorAll('#firstName, #lastName, #email, #phone, #company, #visitNotes');
            
        //     textInputs.forEach(input => {
        //         // Convert to lowercase on input
        //         input.addEventListener('input', function() {
        //             this.value = this.value.toLowerCase();
        //         });
                
        //         // Also convert on blur (when user leaves the field)
        //         input.addEventListener('blur', function() {
        //             this.value = this.value.toLowerCase();
        //         });
        //     });
        // });

        
        // // Function to generate QR code data
        // function generateQRCodeData() {
        //     const qrData = {
        //         firstName: visitorData.firstName,
        //         lastName: visitorData.lastName,
        //         email: visitorData.email,
        //         phone: visitorData.phone,
        //         company: visitorData.company,
        //         timestamp: new Date().toISOString()
        //     };
        //     return JSON.stringify(qrData);
        // }

        function generateQRCodeData() {
            // Only return the badge number - much smaller QR code!
            return visitorData.badge_number;
        }

        // // Function to generate and display QR code on success screen
        // function generateVisitorQRCode() {
        //     const qrContainer = document.getElementById('qrCodeContainer');
        //     if (!qrContainer) return;
            
        //     // Clear previous QR code
        //     qrContainer.innerHTML = '';
            
        //     // Generate QR data
        //     const qrData = generateQRCodeData();
            
        //     // Store photo separately in localStorage with email as key
        //     if (visitorData.photo && visitorData.email) {
        //         try {
        //             localStorage.setItem(`visitor_photo_${visitorData.email}`, visitorData.photo);
        //         } catch (e) {
        //             console.error('Could not store photo in localStorage:', e);
        //         }
        //     }
            
        //     // Create QR code
        //     try {
        //         qrCodeInstance = new QRCode(qrContainer, {
        //             text: qrData,
        //             width: 180,
        //             height: 180,
        //             colorDark: "#2c3e50",
        //             colorLight: "#ffffff",
        //             correctLevel: QRCode.CorrectLevel.M
        //         });
        //     } catch (e) {
        //         console.error('Error generating QR code:', e);
        //         qrContainer.innerHTML = '<p class="text-danger">Could not generate QR code</p>';
        //     }
        // }

        // Function to generate and display QR code on success screen
        function generateVisitorQRCode() {
            const qrContainer = document.getElementById('qrCodeContainer');
            if (!qrContainer) return;
            
            // Clear previous QR code
            qrContainer.innerHTML = '';
            
            // Make sure we have a badge_number
            if (!visitorData.badge_number) {
                qrContainer.innerHTML = '<p class="text-danger">Badge number not available</p>';
                return;
            }
            
            // Generate simple QR data - just the badge number
            const qrData = generateQRCodeData(); // Returns just badge_number string
            
            // Store photo separately in localStorage with badge_number as key
            if (visitorData.photo && visitorData.badge_number) {
                try {
                    localStorage.setItem(`visitor_photo_${visitorData.badge_number}`, visitorData.photo);
                } catch (e) {
                    console.error('Could not store photo in localStorage:', e);
                }
            }
            
            // Create QR code with MUCH simpler data
            try {
                qrCodeInstance = new QRCode(qrContainer, {
                    text: qrData, // Just "V-2025-9859" instead of huge JSON
                    width: 180,
                    height: 180,
                    colorDark: "#2c3e50",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H // High error correction
                });
                
                console.log('QR Code generated with badge:', qrData);
            } catch (e) {
                console.error('Error generating QR code:', e);
                qrContainer.innerHTML = '<p class="text-danger">Could not generate QR code</p>';
            }
        }

        // // Function to download QR code as image
        // function downloadQRCode() {
        //     const qrContainer = document.getElementById('qrCodeContainer');
        //     const canvas = qrContainer.querySelector('canvas');
            
        //     if (canvas) {
        //         const link = document.createElement('a');
        //         link.download = `visitor-qr-${visitorData.firstName}-${visitorData.lastName}.png`;
        //         link.href = canvas.toDataURL('image/png');
        //         link.click();
        //         showNotification(translations[currentLanguage].downloadQR || 'QR Code downloaded!');
        //     } else {
        //         // Try to get image element if canvas not available
        //         const img = qrContainer.querySelector('img');
        //         if (img) {
        //             const link = document.createElement('a');
        //             link.download = `visitor-qr-${visitorData.firstName}-${visitorData.lastName}.png`;
        //             link.href = img.src;
        //             link.click();
        //             showNotification(translations[currentLanguage].downloadQR || 'QR Code downloaded!');
        //         }
        //     }
        // }

        // Local storage for visitor data
        const STORAGE_KEY = 'kioskVisitorData';
        
        // // Initialize
        // document.addEventListener('DOMContentLoaded', function() {
        //     updateDateTime();
        //     setInterval(updateDateTime, 1000);
        //     translatePage();
        //     populateDepartments();
        //     loadPurposesFromDatabase(); // ADD THIS LINE

        //     // ADD THIS SECTION - Auto-convert to lowercase
        //     const textInputs = document.querySelectorAll('#firstName, #lastName, #email, #phone, #company, #visitNotes');
        //     textInputs.forEach(input => {
        //         input.addEventListener('input', function() {
        //             this.value = this.value.toLowerCase();
        //         });
        //         input.addEventListener('blur', function() {
        //             this.value = this.value.toLowerCase();
        //         });
        //     });

        // });

        // Initialize - FIXED VERSION
        document.addEventListener('DOMContentLoaded', function() {
            updateDateTime();
            setInterval(updateDateTime, 1000);
            translatePage();
            
            // IMPORTANT: Only call these if we're NOT on the welcome screen
            // or if the elements actually exist
            if (document.getElementById('departmentSelect')) {
                populateDepartments();
            }
            
            // Only load purposes if the grid exists
            if (document.querySelector('.purpose-grid')) {
                loadPurposesFromDatabase();
            }

            // Auto-convert to lowercase
            const textInputs = document.querySelectorAll('#firstName, #lastName, #email, #phone, #company, #visitNotes');
            textInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.toLowerCase();
                });
                input.addEventListener('blur', function() {
                    this.value = this.value.toLowerCase();
                });
            });
        });

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('en-US', options);
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            document.getElementById('datetime').textContent = `${dateStr} • ${timeStr}`;
        }

        // Helper function to get translated department name
        function getTranslatedDepartmentName(department) {
            // Map JavaScript language codes to database column names
            const languageMap = {
                'en': 'name_en',
                'zh-TW': 'name_zh_tw',
                'zh-CN': 'name_zh_cn',
                'fil': 'name_fil',
                'ja': 'name_ja'
            };
            
            const columnName = languageMap[currentLanguage];
            
            // Return translated name from database, fallback to English or original name
            return department[columnName] || department.name_en || department.name;
        }

        // // Populate departments dropdown
        // function populateDepartments() {
        //     const select = document.getElementById('departmentSelect');
        //     select.innerHTML = '<option value="">Choose a department...</option>';
            
        //     Object.keys(departmentData).forEach(deptCode => {
        //         const option = document.createElement('option');
        //         option.value = deptCode;
        //         option.textContent = departmentData[deptCode].name;
        //         select.appendChild(option);
        //     });
        // }

        // // Handle department selection - fetches employees from database
        // function onDepartmentChange() {
        //     const select = document.getElementById('departmentSelect');
        //     const deptCode = select.value;
        //     const employeeSection = document.getElementById('employeeSection');
        //     const employeeGrid = document.getElementById('employeeGrid');
            
        //     if (!deptCode) {
        //         employeeSection.style.display = 'none';
        //         resetHostSelection();
        //         return;
        //     }
            
        //     // Get the department name from the selected option
        //     const deptName = select.options[select.selectedIndex].text;
        //     selectedDepartment = {
        //         code: deptCode,
        //         name: deptName
        //     };
            
        //     // Show loading indicator
        //     employeeGrid.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
        //     employeeSection.style.display = 'block';
            
        //     // Fetch employees from database
        //     fetch(`<?= base_url("kiosk/get_employees/") ?>${deptCode}`, {
        //         method: 'GET',
        //         headers: {
        //             'X-Requested-With': 'XMLHttpRequest'
        //         }
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         if (result.status === 'success') {
        //             employeeGrid.innerHTML = '';
                    
        //             if (result.employees.length === 0) {
        //                 employeeGrid.innerHTML = '<p class="text-muted text-center">No employees found in this department</p>';
        //                 return;
        //             }
                    
        //             result.employees.forEach(employee => {
        //                 const card = document.createElement('div');
        //                 card.className = 'employee-card';
        //                 card.innerHTML = `
        //                     <i class="bi bi-person-circle"></i>
        //                     <div class="employee-name">${employee.name}</div>
        //                     <div class="employee-email">${employee.email}</div>
        //                 `;
                        
        //                 // Store data as data attributes
        //                 card.dataset.employeeId = employee.employee_id;
        //                 card.dataset.employeeName = employee.name;
        //                 card.dataset.employeeEmail = employee.email;
        //                 card.dataset.deptCode = deptCode;
        //                 card.dataset.deptName = deptName;
                        
        //                 // Add click event listener (NOT onclick)
        //                 card.addEventListener('click', function(e) {
        //                     selectEmployeeFromCard(e.currentTarget);
        //                 });
                        
        //                 employeeGrid.appendChild(card);
        //             });
        //         } else {
        //             employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees</p>';
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error loading employees:', error);
        //         employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees. Please try again.</p>';
        //     });
        // }

        // // Handle department selection - fetches employees from database
        // function onDepartmentChange() {
        //     const select = document.getElementById('departmentSelect');
        //     const deptCode = select.value;
        //     const employeeSection = document.getElementById('employeeSection');
        //     const employeeGrid = document.getElementById('employeeGrid');
            
        //     if (!deptCode) {
        //         employeeSection.style.display = 'none';
        //         resetHostSelection();
        //         return;
        //     }
            
        //     // Get the department name from the selected option
        //     const deptName = select.options[select.selectedIndex].text;
        //     selectedDepartment = {
        //         code: deptCode,
        //         name: deptName
        //     };
            
        //     // Show loading indicator
        //     employeeGrid.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
        //     employeeSection.style.display = 'block';
            
        //     // Fetch employees from database - FILTERED BY COMPANY
        //     fetch(`<?= base_url("kiosk/get_employees/") ?>${deptCode}?company_visited=${encodeURIComponent(COMPANY_VISITED)}`, {
        //         method: 'GET',
        //         headers: {
        //             'X-Requested-With': 'XMLHttpRequest'
        //         }
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         if (result.status === 'success') {
        //             employeeGrid.innerHTML = '';
                    
        //             if (result.employees.length === 0) {
        //                 employeeGrid.innerHTML = '<p class="text-muted text-center">No employees found in this department</p>';
        //                 return;
        //             }
                    
        //             result.employees.forEach(employee => {
        //                 const card = document.createElement('div');
        //                 card.className = 'employee-card';
        //                 card.innerHTML = `
        //                     <i class="bi bi-person-circle"></i>
        //                     <div class="employee-name">${employee.name}</div>
        //                     <div class="employee-email">${employee.email}</div>
        //                 `;
                        
        //                 // Store data as data attributes
        //                 card.dataset.employeeId = employee.employee_id;
        //                 card.dataset.employeeName = employee.name;
        //                 card.dataset.employeeEmail = employee.email;
        //                 card.dataset.deptCode = deptCode;
        //                 card.dataset.deptName = deptName;
                        
        //                 // Add click event listener (NOT onclick)
        //                 card.addEventListener('click', function(e) {
        //                     selectEmployeeFromCard(e.currentTarget);
        //                 });
                        
        //                 employeeGrid.appendChild(card);
        //             });
        //         } else {
        //             employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees</p>';
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error loading employees:', error);
        //         employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees. Please try again.</p>';
        //     });
        // }

        // Updated: Handle department selection with translated names
        // function onDepartmentChange() {
        //     const select = document.getElementById('departmentSelect');
        //     const deptCode = select.value;
        //     const employeeSection = document.getElementById('employeeSection');
        //     const employeeGrid = document.getElementById('employeeGrid');
            
        //     if (!deptCode) {
        //         employeeSection.style.display = 'none';
        //         resetHostSelection();
        //         return;
        //     }
            
        //     // Find the department object to get translated name
        //     const deptObj = availableDepartments.find(d => d.department_code === deptCode);
        //     const deptName = deptObj ? getTranslatedDepartmentName(deptObj) : select.options[select.selectedIndex].text;
            
        //     selectedDepartment = {
        //         code: deptCode,
        //         name: deptName,
        //         originalName: deptObj ? deptObj.name : deptName // Keep original for database
        //     };
            
        //     // Show loading indicator
        //     employeeGrid.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
        //     employeeSection.style.display = 'block';
            
        //     // Fetch employees from database - FILTERED BY COMPANY
        //     fetch(`<?= base_url("kiosk/get_employees/") ?>${deptCode}?company_visited=${encodeURIComponent(COMPANY_VISITED)}`, {
        //         method: 'GET',
        //         headers: {
        //             'X-Requested-With': 'XMLHttpRequest'
        //         }
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         if (result.status === 'success') {
        //             employeeGrid.innerHTML = '';
                    
        //             if (result.employees.length === 0) {
        //                 employeeGrid.innerHTML = '<p class="text-muted text-center">No employees found in this department</p>';
        //                 return;
        //             }
                    
        //             result.employees.forEach(employee => {
        //                 const card = document.createElement('div');
        //                 card.className = 'employee-card';
        //                 card.innerHTML = `
        //                     <i class="bi bi-person-circle"></i>
        //                     <div class="employee-name">${employee.name}</div>
        //                     <div class="employee-email">${employee.email}</div>
        //                 `;
                        
        //                 // Store data as data attributes (including translated name)
        //                 card.dataset.employeeId = employee.employee_id;
        //                 card.dataset.employeeName = employee.name;
        //                 card.dataset.employeeEmail = employee.email;
        //                 card.dataset.deptCode = deptCode;
        //                 card.dataset.deptName = deptName; // Translated name for display
        //                 card.dataset.deptOriginalName = selectedDepartment.originalName; // Original for database
                        
        //                 card.addEventListener('click', function(e) {
        //                     selectEmployeeFromCard(e.currentTarget);
        //                 });
                        
        //                 employeeGrid.appendChild(card);
        //             });
        //         } else {
        //             employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees</p>';
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error loading employees:', error);
        //         employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees. Please try again.</p>';
        //     });
        // }

        // Updated: Handle department selection with translated names and employee storage
        function onDepartmentChange() {
            const select = document.getElementById('departmentSelect');
            const deptCode = select.value;
            const employeeSection = document.getElementById('employeeSection');
            const employeeGrid = document.getElementById('employeeGrid');
            const employeeSearch = document.getElementById('employeeSearch');
            const employeeCount = document.getElementById('employeeCount');
            
            // Reset search input and count
            if (employeeSearch) {
                employeeSearch.value = '';
            }
            if (employeeCount) {
                employeeCount.textContent = '';
            }
            
            // Clear stored employees
            currentDepartmentEmployees = [];
            
            if (!deptCode) {
                employeeSection.style.display = 'none';
                resetHostSelection();
                return;
            }
            
            // Find the department object to get translated name
            const deptObj = availableDepartments.find(d => d.department_code === deptCode);
            const deptName = deptObj ? getTranslatedDepartmentName(deptObj) : select.options[select.selectedIndex].text;
            
            selectedDepartment = {
                code: deptCode,
                name: deptName,
                originalName: deptObj ? deptObj.name : deptName
            };
            
            // Show loading indicator
            employeeGrid.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading employees...</p></div>';
            employeeSection.style.display = 'block';
            
            // Fetch employees from database - FILTERED BY COMPANY
            fetch(`<?= base_url("kiosk/get_employees/") ?>${deptCode}?company_visited=${encodeURIComponent(COMPANY_VISITED)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    // Store employees for filtering
                    currentDepartmentEmployees = result.employees.map(emp => ({
                        ...emp,
                        deptCode: deptCode,
                        deptName: deptName,
                        deptOriginalName: selectedDepartment.originalName
                    }));
                    
                    // Display all employees
                    displayEmployees(currentDepartmentEmployees);
                    
                    // Update employee count
                    updateEmployeeCount(currentDepartmentEmployees.length, currentDepartmentEmployees.length);
                } else {
                    employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees</p>';
                }
            })
            .catch(error => {
                console.error('Error loading employees:', error);
                employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees. Please try again.</p>';
            });
        }

        // Function to display employees in the grid
        function displayEmployees(employees) {
            const employeeGrid = document.getElementById('employeeGrid');
            employeeGrid.innerHTML = '';
            
            if (employees.length === 0) {
                const noResultsText = translations[currentLanguage]?.noEmployeesFound || 'No employees found matching your search';
                employeeGrid.innerHTML = `
                    <div class="no-results-message">
                        <i class="bi bi-search"></i>
                        <p>${noResultsText}</p>
                    </div>
                `;
                return;
            }
            
            employees.forEach(employee => {
                const card = document.createElement('div');
                card.className = 'employee-card';
                
                // Check if this employee is currently selected
                if (selectedHost && selectedHost.id === employee.employee_id) {
                    card.classList.add('selected');
                }
                
                card.innerHTML = `
                    <i class="bi bi-person-circle"></i>
                    <div class="employee-name">${employee.name}</div>
                    <div class="employee-email">${employee.email}</div>
                `;
                
                // Store data as data attributes
                card.dataset.employeeId = employee.employee_id;
                card.dataset.employeeName = employee.name;
                card.dataset.employeeEmail = employee.email;
                card.dataset.deptCode = employee.deptCode;
                card.dataset.deptName = employee.deptName;
                card.dataset.deptOriginalName = employee.deptOriginalName;
                
                card.addEventListener('click', function(e) {
                    selectEmployeeFromCard(e.currentTarget);
                });
                
                employeeGrid.appendChild(card);
            });
        }

        // // Function to display employees in the grid
        // function displayEmployees(employees) {
        //     const employeeGrid = document.getElementById('employeeGrid');
        //     employeeGrid.innerHTML = '';
            
        //     if (employees.length === 0) {
        //         const noResultsText = translations[currentLanguage]?.noEmployeesFound || 'No employees found matching your search';
        //         employeeGrid.innerHTML = `<p class="no-results-message">${noResultsText}</p>`;
        //         return;
        //     }
            
        //     employees.forEach(employee => {
        //         const card = document.createElement('div');
        //         card.className = 'employee-card';
                
        //         // Check if this employee is currently selected
        //         if (selectedHost && selectedHost.id === employee.employee_id) {
        //             card.classList.add('selected');
        //         }
                
        //         card.innerHTML = `
        //             <i class="bi bi-person-circle"></i>
        //             <div class="employee-name">${employee.name}</div>
        //             <div class="employee-email">${employee.email}</div>
        //         `;
                
        //         // Store data as data attributes
        //         card.dataset.employeeId = employee.employee_id;
        //         card.dataset.employeeName = employee.name;
        //         card.dataset.employeeEmail = employee.email;
        //         card.dataset.deptCode = employee.deptCode;
        //         card.dataset.deptName = employee.deptName;
        //         card.dataset.deptOriginalName = employee.deptOriginalName;
                
        //         card.addEventListener('click', function(e) {
        //             selectEmployeeFromCard(e.currentTarget);
        //         });
                
        //         employeeGrid.appendChild(card);
        //     });
        // }

        // Function to filter employees based on search query
        // function filterEmployees(query) {
        //     const searchTerm = query.toLowerCase().trim();
            
        //     if (!searchTerm) {
        //         // If search is empty, show all employees
        //         displayEmployees(currentDepartmentEmployees);
        //         return;
        //     }
            
        //     // Filter employees by name (case-insensitive)
        //     const filteredEmployees = currentDepartmentEmployees.filter(employee => {
        //         const name = employee.name.toLowerCase();
        //         const email = employee.email.toLowerCase();
                
        //         // Search in both name and email
        //         return name.includes(searchTerm) || email.includes(searchTerm);
        //     });
            
        //     displayEmployees(filteredEmployees);
        // }

        // Function to filter employees based on search query
        function filterEmployees(query) {
            const searchTerm = query.toLowerCase().trim();
            
            if (!searchTerm) {
                // If search is empty, show all employees
                displayEmployees(currentDepartmentEmployees);
                updateEmployeeCount(currentDepartmentEmployees.length, currentDepartmentEmployees.length);
                return;
            }
            
            // Filter employees by name and email (case-insensitive)
            const filteredEmployees = currentDepartmentEmployees.filter(employee => {
                const name = employee.name.toLowerCase();
                const email = employee.email.toLowerCase();
                
                // Search in both name and email
                return name.includes(searchTerm) || email.includes(searchTerm);
            });
            
            displayEmployees(filteredEmployees);
            updateEmployeeCount(filteredEmployees.length, currentDepartmentEmployees.length);
        }

        function updateEmployeeCount(shown, total) {
            const employeeCount = document.getElementById('employeeCount');
            if (employeeCount) {
                if (shown === total) {
                    employeeCount.textContent = `${total} employee${total !== 1 ? 's' : ''} available`;
                } else {
                    employeeCount.textContent = `Showing ${shown} of ${total} employee${total !== 1 ? 's' : ''}`;
                }
            }
        }

        // Function to clear employee search
        function clearEmployeeSearch() {
            const searchInput = document.getElementById('employeeSearch');
            if (searchInput) {
                searchInput.value = '';
                filterEmployees('');
                searchInput.focus();
            }
        }

        // // Select employee from card - receives the card DOM element
        // function selectEmployeeFromCard(cardElement) {
        //     // Safety check
        //     if (!cardElement || !cardElement.classList) {
        //         console.error('Invalid card element:', cardElement);
        //         return;
        //     }
            
        //     // Remove previous selection from all cards
        //     document.querySelectorAll('.employee-card').forEach(card => {
        //         card.classList.remove('selected');
        //     });
            
        //     // Add selection to clicked card
        //     cardElement.classList.add('selected');
            
        //     // Get data from card's data attributes
        //     const employeeId = cardElement.dataset.employeeId;
        //     const employeeName = cardElement.dataset.employeeName;
        //     const employeeEmail = cardElement.dataset.employeeEmail;
        //     const deptCode = cardElement.dataset.deptCode;
        //     const deptName = cardElement.dataset.deptName;
            
        //     // Update selectedHost object
        //     selectedHost = {
        //         id: employeeId,
        //         employeeId: employeeId,
        //         name: employeeName,
        //         email: employeeEmail,
        //         department: deptName,
        //         departmentCode: deptCode
        //     };
            
        //     // Update visitorData
        //     visitorData.host = selectedHost;
            
        //     // Update the display
        //     document.getElementById('selectedHost').innerHTML = `
        //         <div class="d-flex align-items-center gap-3">
        //             <i class="bi bi-person-circle" style="font-size: 2em;"></i>
        //             <div>
        //                 <div style="font-weight: 600;">${employeeName}</div>
        //                 <div style="font-size: 0.9em; color: #7f8c8d;">${deptName}</div>
        //             </div>
        //         </div>
        //     `;
            
        //     // Enable the next button
        //     document.getElementById('hostNextBtn').disabled = false;
        // }

        // Updated: Select employee with translated department name
        // function selectEmployeeFromCard(cardElement) {
        //     if (!cardElement || !cardElement.classList) {
        //         console.error('Invalid card element:', cardElement);
        //         return;
        //     }
            
        //     document.querySelectorAll('.employee-card').forEach(card => {
        //         card.classList.remove('selected');
        //     });
            
        //     cardElement.classList.add('selected');
            
        //     const employeeId = cardElement.dataset.employeeId;
        //     const employeeName = cardElement.dataset.employeeName;
        //     const employeeEmail = cardElement.dataset.employeeEmail;
        //     const deptCode = cardElement.dataset.deptCode;
        //     const deptName = cardElement.dataset.deptName; // Translated name
        //     const deptOriginalName = cardElement.dataset.deptOriginalName; // Original name
            
        //     selectedHost = {
        //         id: employeeId,
        //         employeeId: employeeId,
        //         name: employeeName,
        //         email: employeeEmail,
        //         department: deptName, // Use translated name for display
        //         departmentOriginal: deptOriginalName, // Keep original for database
        //         departmentCode: deptCode
        //     };
            
        //     visitorData.host = selectedHost;
            
        //     // Update the display with translated department name
        //     document.getElementById('selectedHost').innerHTML = `
        //         <div class="d-flex align-items-center gap-3">
        //             <i class="bi bi-person-circle" style="font-size: 2em;"></i>
        //             <div>
        //                 <div style="font-weight: 600;">${employeeName}</div>
        //                 <div style="font-size: 0.9em; color: #7f8c8d;">${deptName}</div>
        //             </div>
        //         </div>
        //     `;
            
        //     document.getElementById('hostNextBtn').disabled = false;
        // }

        // NEW: Load all employees (no department filter)
        function loadAllEmployees() {
            const employeeGrid = document.getElementById('employeeGrid');
            const employeeSearch = document.getElementById('employeeSearch');
            const employeeCount = document.getElementById('employeeCount');
            
            // Reset search input
            if (employeeSearch) {
                employeeSearch.value = '';
            }
            
            // Show loading indicator
            employeeGrid.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading employees...</p></div>';
            
            // Fetch all employees from database
            fetch(`<?= base_url("kiosk/get_all_employees") ?>?company_visited=${encodeURIComponent(COMPANY_VISITED)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    // Store employees for filtering
                    currentDepartmentEmployees = result.employees.map(emp => ({
                        ...emp,
                        deptCode: emp.department_code,
                        deptName: emp.department_name,
                        deptOriginalName: emp.department_name
                    }));
                    
                    // Display all employees
                    displayEmployees(currentDepartmentEmployees);
                    
                    // Update employee count
                    updateEmployeeCount(currentDepartmentEmployees.length, currentDepartmentEmployees.length);
                } else {
                    employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees</p>';
                }
            })
            .catch(error => {
                console.error('Error loading employees:', error);
                employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees. Please try again.</p>';
            });
        }

        // Updated: Select employee with translated department name (keep selection visible after filtering)
        function selectEmployeeFromCard(cardElement) {
            if (!cardElement || !cardElement.classList) {
                console.error('Invalid card element:', cardElement);
                return;
            }
            
            document.querySelectorAll('.employee-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            cardElement.classList.add('selected');
            
            const employeeId = cardElement.dataset.employeeId;
            const employeeName = cardElement.dataset.employeeName;
            const employeeEmail = cardElement.dataset.employeeEmail;
            const deptCode = cardElement.dataset.deptCode;
            const deptName = cardElement.dataset.deptName;
            const deptOriginalName = cardElement.dataset.deptOriginalName;
            
            selectedHost = {
                id: employeeId,
                employeeId: employeeId,
                name: employeeName,
                email: employeeEmail,
                department: deptName,
                departmentOriginal: deptOriginalName,
                departmentCode: deptCode
            };
            
            visitorData.host = selectedHost;
            
            // Update the display with translated department name
            document.getElementById('selectedHost').innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-person-circle" style="font-size: 2em; color: #27ae60;"></i>
                    <div>
                        <div style="font-weight: 600;">${employeeName}</div>
                        <div style="font-size: 0.9em; color: #7f8c8d;">${deptName}</div>
                    </div>
                    <i class="bi bi-check-circle-fill text-success ms-auto" style="font-size: 1.5em;"></i>
                </div>
            `;
            
            document.getElementById('hostNextBtn').disabled = false;
        }

        // // Reset host selection
        // function resetHostSelection() {
        //     selectedHost = null;
        //     document.getElementById('selectedHost').innerHTML = `
        //         <span class="text-muted">${translations[currentLanguage].noSelection || 'No one selected yet'}</span>
        //     `;
        //     document.getElementById('hostNextBtn').disabled = true;
        //     document.querySelectorAll('.employee-card').forEach(card => {
        //         card.classList.remove('selected');
        //     });
        // }

        // Updated resetHostSelection to also clear search
        // function resetHostSelection() {
        //     selectedHost = null;
        //     currentDepartmentEmployees = [];
            
        //     const searchInput = document.getElementById('employeeSearch');
        //     if (searchInput) {
        //         searchInput.value = '';
        //     }
            
        //     document.getElementById('selectedHost').innerHTML = `
        //         <span class="text-muted">${translations[currentLanguage]?.noSelection || 'No one selected yet'}</span>
        //     `;
        //     document.getElementById('hostNextBtn').disabled = true;
        //     document.querySelectorAll('.employee-card').forEach(card => {
        //         card.classList.remove('selected');
        //     });
        // }
        
        function resetHostSelection() {
            selectedHost = null;
            currentDepartmentEmployees = [];
            
            const searchInput = document.getElementById('employeeSearch');
            if (searchInput) {
                searchInput.value = '';
            }
            
            const employeeCount = document.getElementById('employeeCount');
            if (employeeCount) {
                employeeCount.textContent = '';
            }
            
            document.getElementById('selectedHost').innerHTML = `
                <span class="text-muted">${translations[currentLanguage]?.noSelection || 'No one selected yet'}</span>
            `;
            document.getElementById('hostNextBtn').disabled = true;
            document.querySelectorAll('.employee-card').forEach(card => {
                card.classList.remove('selected');
            });
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

        // // Start check-in process
        // function startCheckIn(type) {
        //     visitorData.type = type;
        //     currentFlow = screenFlow[type];
        //     currentFlowIndex = 1;
            
        //     if (type === 'returning') {
        //         showScreen(2);
        //         initQRScanner();
        //     } else {
        //         showScreen(3);
        //     }
        // }

        // // Start check-in process
        // function startCheckIn(type) {
        //     visitorData.type = type;
        //     currentFlow = screenFlow[type];
        //     currentFlowIndex = 1;
            
        //     if (type === 'returning') {
        //         showScreen(2);
        //         initQRScanner();
        //     } else {
        //         showScreen(3);
        //     }
        // }

        // // Initialize QR Scanner
        // function initQRScanner() {
        //     if (html5QrCode) {
        //         html5QrCode.stop();
        //     }
            
        //     html5QrCode = new Html5Qrcode("qr-reader");
            
        //     const config = { 
        //         fps: 10, 
        //         qrbox: { width: 250, height: 250 },
        //         aspectRatio: 1.0,
        //         disableFlip: false,
        //         experimentalFeatures: {
        //             useBarCodeDetectorIfSupported: true
        //         }
        //     };
            
        //     html5QrCode.start(
        //         { facingMode: "environment" },
        //         config,
        //         (decodedText) => {
        //             console.log('QR scanned:', decodedText);
        //             handleQRCodeSuccess(decodedText);
        //         },
        //         (error) => {
        //             // Ignore continuous scan errors
        //         }
        //     ).catch((err) => {
        //         console.error("Unable to start QR scanner:", err);
                
        //         html5QrCode.start(
        //             { facingMode: "user" },
        //             config,
        //             (decodedText) => {
        //                 handleQRCodeSuccess(decodedText);
        //             },
        //             (error) => {}
        //         ).catch((err2) => {
        //             showNotification("Camera not available for QR scanning");
        //         });
        //     });
        // }
        
        function initQRScanner() {
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
            }
            
            html5QrCode = new Html5Qrcode("qr-reader");
            
            // OPTIMIZED CONFIG - More flexible QR reading
            const config = { 
                fps: 15, // Increased from 10 for faster detection
                qrbox: { width: 300, height: 300 }, // Larger scanning area
                aspectRatio: 1.0,
                disableFlip: false,
                // ENHANCED EXPERIMENTAL FEATURES
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                },
                // ADD THESE FOR BETTER DETECTION:
                formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
                // More lenient settings
                rememberLastUsedCamera: true,
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
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
                
                // Fallback to front camera with same optimized config
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

        // // Handle QR code success
        // function handleQRCodeSuccess(decodedText) {
        //     try {
        //         let qrData;
                
        //         try {
        //             qrData = JSON.parse(decodedText);
        //         } catch (e) {
        //             qrData = JSON.parse(atob(decodedText));
        //         }
                
        //         if (qrData.email) {
        //             if (html5QrCode) {
        //                 html5QrCode.stop();
        //             }
                    
        //             if (qrData.id) {
        //                 const storedPhoto = localStorage.getItem(`visitor_photo_${qrData.id}`);
        //                 if (storedPhoto) {
        //                     qrData.photo = storedPhoto;
        //                 }
        //             }
                    
        //             visitorData = {
        //                 ...visitorData,
        //                 ...qrData
        //             };
                    
        //             Swal.fire({
        //                 title: translations[currentLanguage].qrValidatedTitle || 'Welcome Back!',
        //                 text: translations[currentLanguage].qrValidatedMessage || 'Your QR code has been validated successfully',
        //                 icon: 'success',
        //                 confirmButtonColor: '#27ae60'
        //             });
                    
        //             showScreen(5);
        //         }
        //     } catch (e) {
        //         console.error('QR decode error:', e);
        //         showNotification(translations[currentLanguage].invalidQRMessage || 'Invalid QR Code');
        //     }
        // }

        // // Handle QR upload
        // function handleQRUpload(input) {
        //     const file = input.files[0];
        //     if (file) {
        //         const reader = new FileReader();
        //         reader.onload = function(e) {
        //             if (html5QrCode) {
        //                 html5QrCode.scanFile(file, true)
        //                     .then(decodedText => {
        //                         handleQRCodeSuccess(decodedText);
        //                     })
        //                     .catch(err => {
        //                         showNotification(translations[currentLanguage].invalidQRMessage || 'Invalid QR Code');
        //                     });
        //             }
        //         };
        //         reader.readAsDataURL(file);
        //     }
        // }

        // // FIXED: Handle QR code success for returning visitors
        // function handleQRCodeSuccess(decodedText) {
        //     // Prevent multiple calls
        //     if (isProcessingQR) {
        //         return;
        //     }
        //     isProcessingQR = true;
            
        //     try {
        //         let qrData;
                
        //         // Try to parse as JSON first
        //         try {
        //             qrData = JSON.parse(decodedText);
        //         } catch (e) {
        //             try {
        //                 qrData = JSON.parse(atob(decodedText));
        //             } catch (e2) {
        //                 isProcessingQR = false;
        //                 throw new Error('Invalid QR format');
        //             }
        //         }
                
        //         // Validate QR data has required fields
        //         if (!qrData.email || !qrData.firstName || !qrData.lastName) {
        //             isProcessingQR = false;
        //             throw new Error('Missing required fields in QR code');
        //         }
                
        //         // Stop QR scanner safely (don't await, let it happen in background)
        //         stopQRScanner();
                
        //         // Retrieve stored photo from localStorage using email as key
        //         const storedPhoto = localStorage.getItem(`visitor_photo_${qrData.email}`);
                
        //         // Populate visitor data from QR code - this skips basicInfoScreen & photoScreen
        //         visitorData = {
        //             ...visitorData,
        //             firstName: qrData.firstName,
        //             lastName: qrData.lastName,
        //             email: qrData.email,
        //             phone: qrData.phone || '',
        //             company: qrData.company || '',
        //             photo: storedPhoto || null,
        //             type: 'returning'
        //         };
                
        //         // Update flow for returning visitor - skips screens 3 (basicInfo) and 4 (photo)
        //         // Flow: Welcome(1) -> QR(2) -> Host(5) -> Purpose(6) -> Agreement(7) -> Success(8)
        //         currentFlow = [1, 2, 5, 6, 7, 8];
        //         currentFlowIndex = 2; // Position 2 in array = screen 5 (hostScreen)
                
        //         // Show success message and then navigate to hostScreen
        //         Swal.fire({
        //             title: translations[currentLanguage].qrScanSuccess || 'QR Code Scanned!',
        //             html: `<p>${translations[currentLanguage].welcomeBackQR || 'Welcome back!'}</p>
        //                 <p><strong>${qrData.firstName} ${qrData.lastName}</strong></p>
        //                 <p>${qrData.company || ''}</p>`,
        //             icon: 'success',
        //             confirmButtonColor: '#27ae60',
        //             timer: 3000,
        //             timerProgressBar: true,
        //             showConfirmButton: true,
        //             confirmButtonText: 'OK'
        //         }).then(() => {
        //             // Reset the processing flag
        //             isProcessingQR = false;
        //             // Navigate to hostScreen (screen 5) - skipping basicInfo and photo screens
        //             showScreen(5);
        //         });
                
        //     } catch (e) {
        //         console.error('QR decode error:', e);
        //         isProcessingQR = false;
                
        //         Swal.fire({
        //             title: translations[currentLanguage].invalidQRMessage || 'Invalid QR Code',
        //             text: translations[currentLanguage].qrScanFailed || 'Could not read QR code.',
        //             icon: 'error',
        //             showCancelButton: true,
        //             confirmButtonColor: '#3498db',
        //             cancelButtonColor: '#95a5a6',
        //             confirmButtonText: 'Try Again',
        //             cancelButtonText: 'Continue Manually'
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 initQRScanner();
        //             } else {
        //                 skipQRScan();
        //             }
        //         });
        //     }
        // }

        // // FIXED: Handle QR code success for returning visitors
        // function handleQRCodeSuccess(decodedText) {
        //     // Prevent multiple calls
        //     if (isProcessingQR) {
        //         return;
        //     }
        //     isProcessingQR = true;
            
        //     console.log('QR Scanned:', decodedText);
            
        //     // Stop QR scanner safely
        //     stopQRScanner();
            
        //     // Show loading
        //     showLoading();
            
        //     // The decoded text should be just the badge number (e.g., "V-2025-9859")
        //     const badgeNumber = decodedText.trim();
            
        //     // Validate badge number format
        //     const badgePattern = /^V-\d{4}-\d{4}$/;
        //     if (!badgePattern.test(badgeNumber)) {
        //         hideLoading();
        //         isProcessingQR = false;
                
        //         Swal.fire({
        //             title: translations[currentLanguage].invalidQRMessage || 'Invalid QR Code',
        //             text: 'QR code does not contain a valid badge number.',
        //             icon: 'error',
        //             showCancelButton: true,
        //             confirmButtonColor: '#3498db',
        //             cancelButtonColor: '#95a5a6',
        //             confirmButtonText: 'Try Again',
        //             cancelButtonText: 'Continue Manually'
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 initQRScanner();
        //             } else {
        //                 skipQRScan();
        //             }
        //         });
        //         return;
        //     }
            
        //     // Fetch visitor data from database using badge number
        //     fetch('<?= base_url("kiosk/get_visitor_by_badge") ?>', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-Requested-With': 'XMLHttpRequest'
        //         },
        //         body: JSON.stringify({ badge_number: badgeNumber })
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         hideLoading();
                
        //         if (result.status === 'success' && result.visitor) {
        //             const visitor = result.visitor;
                    
        //             // Retrieve stored photo from localStorage using badge number
        //             const storedPhoto = localStorage.getItem(`visitor_photo_${badgeNumber}`);
                    
        //             // Populate visitor data from database
        //             visitorData = {
        //                 ...visitorData,
        //                 visitor_id: visitor.visitor_id,
        //                 firstName: visitor.first_name,
        //                 lastName: visitor.last_name,
        //                 email: visitor.email,
        //                 phone: visitor.phone || '',
        //                 company: visitor.company || '',
        //                 photo: storedPhoto || visitor.photo || null,
        //                 type: 'returning',
        //                 total_visits: visitor.total_visits || 0
        //             };
                    
        //             // Update flow for returning visitor
        //             currentFlow = [1, 2, 5, 6, 7, 8];
        //             currentFlowIndex = 2;
                    
        //             // Show welcome back message
        //             Swal.fire({
        //                 title: translations[currentLanguage].qrScanSuccess || 'QR Code Scanned!',
        //                 html: `
        //                     <p>${translations[currentLanguage].welcomeBackQR || 'Welcome back!'}</p>
        //                     <p><strong>${visitor.first_name} ${visitor.last_name}</strong></p>
        //                     <p>${visitor.company || ''}</p>
        //                     <p class="text-muted">Total Visits: ${visitor.total_visits || 1}</p>
        //                 `,
        //                 icon: 'success',
        //                 confirmButtonColor: '#27ae60',
        //                 timer: 3000,
        //                 timerProgressBar: true,
        //                 showConfirmButton: true,
        //                 confirmButtonText: 'OK'
        //             }).then(() => {
        //                 isProcessingQR = false;
        //                 showScreen(5);
        //             });
                    
        //         } else {
        //             isProcessingQR = false;
                    
        //             Swal.fire({
        //                 title: 'Visitor Not Found',
        //                 text: result.message || 'This badge number was not found in our records.',
        //                 icon: 'warning',
        //                 showCancelButton: true,
        //                 confirmButtonColor: '#3498db',
        //                 cancelButtonColor: '#95a5a6',
        //                 confirmButtonText: 'Try Again',
        //                 cancelButtonText: 'Continue as New Visitor'
        //             }).then((result) => {
        //                 if (result.isConfirmed) {
        //                     initQRScanner();
        //                 } else {
        //                     skipQRScan();
        //                 }
        //             });
        //         }
        //     })
        //     .catch(error => {
        //         hideLoading();
        //         isProcessingQR = false;
        //         console.error('Error fetching visitor data:', error);
                
        //         Swal.fire({
        //             title: 'Connection Error',
        //             text: 'Unable to retrieve visitor information. Please try again or continue manually.',
        //             icon: 'error',
        //             showCancelButton: true,
        //             confirmButtonColor: '#3498db',
        //             cancelButtonColor: '#95a5a6',
        //             confirmButtonText: 'Try Again',
        //             cancelButtonText: 'Continue Manually'
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 initQRScanner();
        //             } else {
        //                 skipQRScan();
        //             }
        //         });
        //     });
        // }

        // UPDATED: Handle QR code success with ACTIVE VISIT CHECK
        function handleQRCodeSuccess(decodedText) {
            // Prevent multiple calls
            if (isProcessingQR) {
                return;
            }
            isProcessingQR = true;
            
            console.log('QR Scanned:', decodedText);
            
            // Stop QR scanner safely
            stopQRScanner();
            
            // Show loading
            showLoading();
            
            // The decoded text should be just the badge number (e.g., "V-2025-9859")
            const badgeNumber = decodedText.trim();
            
            // Validate badge number format
            const badgePattern = /^V-\d{4}-\d{4}$/;
            if (!badgePattern.test(badgeNumber)) {
                hideLoading();
                isProcessingQR = false;
                
                Swal.fire({
                    title: translations[currentLanguage].invalidQRMessage || 'Invalid QR Code',
                    text: 'QR code does not contain a valid badge number.',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3498db',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Try Again',
                    cancelButtonText: 'Continue Manually'
                }).then((result) => {
                    if (result.isConfirmed) {
                        initQRScanner();
                    } else {
                        skipQRScan();
                    }
                });
                return;
            }
            
            // Fetch visitor data from database using badge number
            fetch('<?= base_url("kiosk/get_visitor_by_badge") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ badge_number: badgeNumber })
            })
            .then(response => response.json())
            .then(result => {
                hideLoading();
                
                if (result.status === 'success' && result.visitor) {
                    const visitor = result.visitor;
                    
                    // ========================================
                    // NEW: CHECK FOR ACTIVE VISIT
                    // ========================================
                    if (result.has_active_visit === true) {
                        isProcessingQR = false;
                        
                        // Show denial message with active visit details
                        Swal.fire({
                            title: '⚠️ Already Checked In',
                            html: `
                                <div style="text-align: left; padding: 10px;">
                                    <p style="font-size: 1.1em; margin-bottom: 15px; text-align: center;">
                                        <strong>${visitor.first_name} ${visitor.last_name}</strong>, 
                                        you are currently checked in at the premises.
                                    </p>

                                    <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #f39c12; margin-bottom: 15px;">
                                        <strong style="color: #f39c12;">Active Visit Details:</strong><br>
                                        <div style="margin-top: 10px; line-height: 1.8;">
                                            <strong>Badge:</strong> ${result.active_visit.badge_number}<br>
                                            <strong>Host:</strong> ${result.active_visit.host_name}<br>
                                            <strong>Department:</strong> ${result.active_visit.department}<br>
                                            <strong>Check-in Time:</strong> ${new Date(result.active_visit.check_in_time).toLocaleString('en-US', { 
                                                hour: '2-digit', 
                                                minute: '2-digit',
                                                hour12: true,
                                                month: 'short',
                                                day: 'numeric'
                                            })}<br>
                                            <strong>Valid Until:</strong> ${new Date(result.active_visit.valid_until).toLocaleString('en-US', { 
                                                hour: '2-digit', 
                                                minute: '2-digit',
                                                hour12: true
                                            })}
                                        </div>
                                    </div>
                                    <p style="color: #e74c3c; font-weight: 600; text-align: center;">
                                        <i class="bi bi-exclamation-triangle"></i> 
                                        Please check out first before checking in again.
                                    </p>
                                </div>
                            `,
                            icon: 'warning',
                            confirmButtonColor: '#f39c12',
                            confirmButtonText: 'OK, I Understand',
                            allowOutsideClick: false,
                            customClass: {
                                popup: 'swal-wide'
                            }
                        }).then(() => {
                            // Return to welcome screen
                            resetKiosk();
                        });
                        
                        return; // Stop execution here
                    }
                    // ========================================
                    // END: ACTIVE VISIT CHECK
                    // ========================================
                    
                    // If no active visit, continue with normal flow
                    // Retrieve stored photo from localStorage using badge number
                    const storedPhoto = localStorage.getItem(`visitor_photo_${badgeNumber}`);
                    
                    // Populate visitor data from database
                    visitorData = {
                        ...visitorData,
                        visitor_id: visitor.visitor_id,
                        firstName: visitor.first_name,
                        lastName: visitor.last_name,
                        email: visitor.email,
                        phone: visitor.phone || '',
                        company: visitor.company || '',
                        photo: storedPhoto || visitor.photo || null,
                        type: 'returning',
                        total_visits: visitor.total_visits || 0
                    };
                    
                    // Update flow for returning visitor
                    currentFlow = [1, 2, 6, 5, 7, 8];
                    currentFlowIndex = 2;
                    showScreen(6); // Go to Purpose screen
                    
                    
                    // Show welcome back message
                    Swal.fire({
                        title: translations[currentLanguage].qrScanSuccess || 'QR Code Scanned!',
                        html: `
                            <p>${translations[currentLanguage].welcomeBackQR || 'Welcome back!'}</p>
                            <p><strong>${visitor.first_name} ${visitor.last_name}</strong></p>
                            <p>${visitor.company || ''}</p>
                            <p class="text-muted">Total Visits: ${visitor.total_visits || 1}</p>
                        `,
                        icon: 'success',
                        confirmButtonColor: '#27ae60',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        isProcessingQR = false;
                        showScreen(6);
                    });
                    
                } else {
                    isProcessingQR = false;
                    
                    Swal.fire({
                        title: 'Visitor Not Found',
                        text: result.message || 'This badge number was not found in our records.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3498db',
                        cancelButtonColor: '#95a5a6',
                        confirmButtonText: 'Try Again',
                        cancelButtonText: 'Continue as New Visitor'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            initQRScanner();
                        } else {
                            skipQRScan();
                        }
                    });
                }
            })
            .catch(error => {
                hideLoading();
                isProcessingQR = false;
                console.error('Error fetching visitor data:', error);
                
                Swal.fire({
                    title: 'Connection Error',
                    text: 'Unable to retrieve visitor information. Please try again or continue manually.',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3498db',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Try Again',
                    cancelButtonText: 'Continue Manually'
                }).then((result) => {
                    if (result.isConfirmed) {
                        initQRScanner();
                    } else {
                        skipQRScan();
                    }
                });
            });
        }

        // Add custom CSS for wider Swal popup (add this to your CSS or style section)
        const style = document.createElement('style');
        style.textContent = `
            .swal-wide {
                width: 600px !important;
                max-width: 90% !important;
            }
        `;
        document.head.appendChild(style);

        // FIXED: Handle QR upload for returning visitors
        function handleQRUpload(input) {
            const file = input.files[0];
            if (file) {
                // Show loading
                showLoading();
                
                // Initialize scanner if not already
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("qr-reader");
                }
                
                html5QrCode.scanFile(file, true)
                    .then(decodedText => {
                        hideLoading();
                        handleQRCodeSuccess(decodedText);
                    })
                    .catch(err => {
                        hideLoading();
                        console.error('QR file scan error:', err);
                        Swal.fire({
                            title: translations[currentLanguage].invalidQRMessage || 'Invalid QR Code',
                            text: translations[currentLanguage].qrScanFailed || 'Could not read QR code from image.',
                            icon: 'error',
                            confirmButtonColor: '#e74c3c'
                        });
                    });
                
                // Reset file input
                input.value = '';
            }
        }

        // UPDATED: Skip QR scan - redirect to basic info like first-time visitor
        // function skipQRScan() {
        //     if (html5QrCode) {
        //         try {
        //             const state = html5QrCode.getState();
        //             // Only stop if scanner is running (state 2) or paused (state 3)
        //             if (state === 2 || state === 3) {
        //                 html5QrCode.stop().catch(() => {});
        //             }
        //         } catch (e) {
        //             // Scanner not initialized or already stopped, ignore
        //         }
        //     }
            
        //     // Treat as new visitor since no QR code
        //     visitorData.type = 'returning';
        //     currentFlow = screenFlow['new']; // Use new visitor flow for manual entry
        //     currentFlowIndex = 1;
        //     showScreen(3); // Go to basic info screen
        // }

        // // Skip QR scan
        // function skipQRScan() {
        //     if (html5QrCode) {
        //         html5QrCode.stop();
        //     }
        //     showScreen(3);
        // }

        // // FIXED: Skip QR scan - redirect to basic info like first-time visitor
        // function skipQRScan() {
        //     isProcessingQR = false;
        //     isScannerStopping = false;
            
        //     stopQRScanner().then(() => {
        //         // Treat as new visitor since no QR code - use new visitor flow for manual entry
        //         visitorData.type = 'returning';
        //         currentFlow = screenFlow['new']; // [1, 3, 4, 5, 6, 7, 8]
        //         currentFlowIndex = 1;
        //         showScreen(3); // Go to basic info screen
        //     });
        // }

        // Updated skipQRScan for returning visitors
        function skipQRScan() {
            visitorData.type = 'returning';
            currentFlow = screenFlow['new'];
            currentFlowIndex = 1;
            showScreen(6); // Go to Purpose screen
        }

        // Add this new function to check for existing visitors
        async function checkExistingVisitor() {
            showLoading();
            
            try {
                const response = await fetch('<?= base_url("kiosk/check_duplicate_visitor") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: visitorData.email,
                        phone: visitorData.phone
                    })
                });
                
                const result = await response.json();
                hideLoading();
                
                if (result.status === 'found') {
                    // Visitor with similar contact info exists
                    const choice = await Swal.fire({
                        title: 'Returning Visitor Detected',
                        html: `
                            <p>We found an existing visitor record:</p>
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">
                                <strong>${result.visitor.first_name} ${result.visitor.last_name}</strong><br>
                                <span style="color: #7f8c8d;">
                                    ${result.visitor.company}<br>
                                    Email: ${result.visitor.email || 'Not provided'}<br>
                                    Phone: ${result.visitor.phone || 'Not provided'}<br>
                                    Previous visits: ${result.visitor.total_visits}
                                </span>
                            </div>
                            <p><strong>Is this you?</strong></p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#27ae60',
                        cancelButtonColor: '#3498db',
                        confirmButtonText: 'Yes, that\'s me',
                        cancelButtonText: 'No, I\'m a new visitor'
                    });
                    
                    if (choice.isConfirmed) {
                        // Use existing visitor record
                        visitorData.visitor_id = result.visitor.visitor_id;
                        visitorData.type = 'returning';
                        
                        // Optionally update their contact info
                        const updateInfo = await Swal.fire({
                            title: 'Update Contact Information?',
                            text: 'Would you like to update your contact details?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, update',
                            cancelButtonText: 'No, keep as is'
                        });
                        
                        if (updateInfo.isConfirmed) {
                            visitorData.update_contact_info = true;
                        }
                        
                        return true; // Proceed with check-in as returning visitor
                    } else {
                        // Continue as new visitor
                        return true;
                    }
                }
                
                return true; // No duplicate found, proceed normally
                
            } catch (error) {
                hideLoading();
                console.error('Error checking for duplicate visitor:', error);
                return true; // On error, allow check-in to proceed
            }
        }

        // // Screen navigation
        // function showScreen(screenNumber) {
        //     if (currentScreen === 2 && html5QrCode) {
        //         html5QrCode.stop();
        //     }
        //     if (currentScreen === 4 && screenNumber !== 4) {
        //         stopCamera();
        //     }
            
        //     document.querySelectorAll('.screen').forEach(screen => screen.classList.remove('active'));
            
        //     const screens = ['', 'welcomeScreen', 'qrScannerScreen', 'basicInfoScreen', 'photoScreen', 
        //                    'hostScreen', 'purposeScreen', 'agreementScreen', 'successScreen'];
            
        //     if (screens[screenNumber]) {
        //         document.getElementById(screens[screenNumber]).classList.add('active');
        //     }
            
        //     if (screenNumber === 4) startCamera();
        //     if (screenNumber === 7) {
        //         document.getElementById('agreementText').innerHTML = translations[currentLanguage].agreementContent;
        //     }
        //     if (screenNumber === 8) {
        //         const steps = translations[currentLanguage].nextStepsContent;
        //         document.getElementById('nextStepsList').innerHTML = steps.map(step => `<li>${step}</li>`).join('');
        //     }
            
        //     updateStepIndicator(screenNumber);
        //     currentScreen = screenNumber;
        // }

        // // Updated showScreen function - load departments/purposes only when needed
        // function showScreen(screenNumber) {
        //     // Stop QR scanner when leaving screen 2
        //     if (currentScreen === 2 && screenNumber !== 2) {
        //         stopQRScanner();
        //     }
            
        //     if (currentScreen === 4 && screenNumber !== 4) {
        //         stopCamera();
        //     }
            
        //     document.querySelectorAll('.screen').forEach(screen => screen.classList.remove('active'));
            
        //     const screens = ['', 'welcomeScreen', 'qrScannerScreen', 'basicInfoScreen', 'photoScreen', 
        //                 'hostScreen', 'purposeScreen', 'agreementScreen', 'successScreen'];
            
        //     if (screens[screenNumber]) {
        //         document.getElementById(screens[screenNumber]).classList.add('active');
        //     }
            
        //     if (screenNumber === 4) startCamera();
        //     if (screenNumber === 7) {
        //         document.getElementById('agreementText').innerHTML = translations[currentLanguage].agreementContent;
        //     }
        //     if (screenNumber === 8) {
        //         const steps = translations[currentLanguage].nextStepsContent;
        //         document.getElementById('nextStepsList').innerHTML = steps.map(step => `<li>${step}</li>`).join('');
        //     }
            
        //     // NEW: Load departments when entering host screen (screen 5)
        //     if (screenNumber === 5) {
        //         // Load employees for all departments
        //         loadAllEmployees();
                
        //         // Also ensure departments are loaded (in case they weren't loaded initially)
        //         if (availableDepartments.length === 0 && document.getElementById('departmentSelect')) {
        //             populateDepartments();
        //         }
        //     }

        //     // NEW: Load purposes when entering purpose screen (screen 6)
        //     if (screenNumber === 6) {
        //         // Ensure purposes are loaded (in case they weren't loaded initially)
        //         if (availablePurposes.length === 0 && document.querySelector('.purpose-grid')) {
        //             loadPurposesFromDatabase();
        //         }
                
        //         // Handle delivery auto-selection
        //         if (visitorData.type === 'delivery') {
        //             setTimeout(() => {
        //                 const deliveryCard = Array.from(document.querySelectorAll('.purpose-card'))
        //                     .find(card => card.getAttribute('onclick')?.includes("'delivery'"));
                        
        //                 if (deliveryCard) {
        //                     deliveryCard.classList.add('selected');
        //                     selectedPurpose = 'delivery';
        //                     visitorData.purpose = 'delivery';
        //                     document.getElementById('purposeNextBtn').disabled = false;
                            
        //                     document.querySelectorAll('.purpose-card').forEach(card => {
        //                         if (card !== deliveryCard) {
        //                             card.classList.add('disabled');
        //                             card.style.opacity = '0.4';
        //                             card.style.cursor = 'not-allowed';
        //                             card.style.pointerEvents = 'none';
        //                         }
        //                     });
                            
        //                     showNotification('Delivery purpose auto-selected based on your visit type');
        //                 }
        //             }, 100);
        //         }
        //     }
            
        //     updateStepIndicator(screenNumber);
        //     currentScreen = screenNumber;
        // }

        // FIXED: showScreen function with QR scanner reinitialization
        function showScreen(screenNumber) {
            // Stop QR scanner when leaving screen 2
            if (currentScreen === 2 && screenNumber !== 2) {
                stopQRScanner();
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
            
            // IMPORTANT: Initialize QR scanner when entering screen 2
            if (screenNumber === 2) {
                // Small delay to ensure DOM is ready
                setTimeout(() => {
                    initQRScanner();
                }, 100);
            }
            
            if (screenNumber === 4) startCamera();
            if (screenNumber === 7) {
                document.getElementById('agreementText').innerHTML = translations[currentLanguage].agreementContent;
            }
            if (screenNumber === 8) {
                const steps = translations[currentLanguage].nextStepsContent;
                document.getElementById('nextStepsList').innerHTML = steps.map(step => `<li>${step}</li>`).join('');
            }
            
            // Load departments when entering host screen (screen 5)
            if (screenNumber === 5) {
                loadAllEmployees();
                
                if (availableDepartments.length === 0 && document.getElementById('departmentSelect')) {
                    populateDepartments();
                }
            }

            // Load purposes when entering purpose screen (screen 6)
            if (screenNumber === 6) {
                if (availablePurposes.length === 0 && document.querySelector('.purpose-grid')) {
                    loadPurposesFromDatabase();
                }
                
                if (visitorData.type === 'delivery') {
                    setTimeout(() => {
                        const deliveryCard = Array.from(document.querySelectorAll('.purpose-card'))
                            .find(card => card.getAttribute('onclick')?.includes("'delivery'"));
                        
                        if (deliveryCard) {
                            deliveryCard.classList.add('selected');
                            selectedPurpose = 'delivery';
                            visitorData.purpose = 'delivery';
                            document.getElementById('purposeNextBtn').disabled = false;
                            
                            document.querySelectorAll('.purpose-card').forEach(card => {
                                if (card !== deliveryCard) {
                                    card.classList.add('disabled');
                                    card.style.opacity = '0.4';
                                    card.style.cursor = 'not-allowed';
                                    card.style.pointerEvents = 'none';
                                }
                            });
                            
                            showNotification('Delivery purpose auto-selected based on your visit type');
                        }
                    }, 100);
                }
            }
            
            updateStepIndicator(screenNumber);
            currentScreen = screenNumber;
        }

        // // FIXED: showScreen function - update the part that handles screen 2
        // function showScreen(screenNumber) {
        //     // Stop QR scanner when leaving screen 2 (but don't throw error)
        //     if (currentScreen === 2 && screenNumber !== 2) {
        //         stopQRScanner(); // This now safely handles already-stopped scanner
        //     }
            
        //     if (currentScreen === 4 && screenNumber !== 4) {
        //         stopCamera();
        //     }
            
        //     document.querySelectorAll('.screen').forEach(screen => screen.classList.remove('active'));
            
        //     const screens = ['', 'welcomeScreen', 'qrScannerScreen', 'basicInfoScreen', 'photoScreen', 
        //                 'hostScreen', 'purposeScreen', 'agreementScreen', 'successScreen'];
            
        //     if (screens[screenNumber]) {
        //         document.getElementById(screens[screenNumber]).classList.add('active');
        //     }
            
        //     if (screenNumber === 4) startCamera();
        //     if (screenNumber === 7) {
        //         document.getElementById('agreementText').innerHTML = translations[currentLanguage].agreementContent;
        //     }
        //     if (screenNumber === 8) {
        //         const steps = translations[currentLanguage].nextStepsContent;
        //         document.getElementById('nextStepsList').innerHTML = steps.map(step => `<li>${step}</li>`).join('');
        //     }
            
        //     // // AUTO-SELECT DELIVERY PURPOSE IF VISITOR TYPE IS DELIVERY
        //     // if (screenNumber === 6) { // Purpose screen
        //     //     if (visitorData.type === 'delivery') {
        //     //         setTimeout(() => {
        //     //             const deliveryCard = Array.from(document.querySelectorAll('.purpose-card'))
        //     //                 .find(card => card.getAttribute('onclick').includes("'delivery'"));
                        
        //     //             if (deliveryCard) {
        //     //                 document.querySelectorAll('.purpose-card').forEach(card => card.classList.remove('selected'));
        //     //                 deliveryCard.classList.add('selected');
        //     //                 selectedPurpose = 'delivery';
        //     //                 visitorData.purpose = 'delivery';
        //     //                 document.getElementById('purposeNextBtn').disabled = false;
        //     //                 showNotification('Delivery purpose auto-selected based on your visit type');
        //     //             }
        //     //         }, 100);
        //     //     }
        //     // }
            
        //     // NEW: Load all employees when entering host selection screen
        //     if (screenNumber === 5) {
        //         loadAllEmployees();
        //     }

        //     // Replace the existing screen 6 handling with this enhanced version:
        //     if (screenNumber === 6) { // Purpose screen
        //         // Reset all cards first
        //         document.querySelectorAll('.purpose-card').forEach(card => {
        //             card.classList.remove('selected', 'disabled');
        //             card.style.opacity = '1';
        //             card.style.cursor = 'pointer';
        //             card.style.pointerEvents = 'auto';
        //         });
                
        //         if (visitorData.type === 'delivery') {
        //             setTimeout(() => {
        //                 const deliveryCard = Array.from(document.querySelectorAll('.purpose-card'))
        //                     .find(card => card.getAttribute('onclick')?.includes("'delivery'"));
                        
        //                 if (deliveryCard) {
        //                     // Auto-select delivery
        //                     deliveryCard.classList.add('selected');
        //                     selectedPurpose = 'delivery';
        //                     visitorData.purpose = 'delivery';
        //                     document.getElementById('purposeNextBtn').disabled = false;
                            
        //                     // Disable all other purpose cards
        //                     document.querySelectorAll('.purpose-card').forEach(card => {
        //                         if (card !== deliveryCard) {
        //                             card.classList.add('disabled');
        //                             card.style.opacity = '0.4';
        //                             card.style.cursor = 'not-allowed';
        //                             card.style.pointerEvents = 'none';
        //                         }
        //                     });
                            
        //                     showNotification('Delivery purpose auto-selected based on your visit type');
        //                 }
        //             }, 100);
        //         }
        //     }
            
        //     updateStepIndicator(screenNumber);
        //     currentScreen = screenNumber;
        // }

        // // Alternative approach: Modify the startCheckIn function to store the initial selection
        // function startCheckIn(type) {
        //     visitorData.type = type;
        //     currentFlow = screenFlow[type];
        //     currentFlowIndex = 1;
            
        //     // Store the initial purpose if it's a delivery type
        //     if (type === 'delivery') {
        //         visitorData.initialPurpose = 'delivery';
        //     }
            
        //     if (type === 'returning') {
        //         showScreen(2);
        //         initQRScanner();
        //     } else {
        //         showScreen(3);
        //     }
        // }

        // // Updated startCheckIn function
        // function startCheckIn(type) {
        //     visitorData.type = type;
        //     currentFlow = screenFlow[type];
        //     currentFlowIndex = 1;
            
        //     if (type === 'returning') {
        //         showScreen(2); // QR Scanner
        //         initQRScanner();
        //     } else {
        //         showScreen(6); // Purpose screen FIRST
        //     }
        // }

        // FIXED: startCheckIn function
        function startCheckIn(type) {
            visitorData.type = type;
            currentFlow = screenFlow[type];
            currentFlowIndex = 1;
            
            if (type === 'returning') {
                showScreen(2); // This will now trigger QR scanner initialization
                // Don't call initQRScanner() here - let showScreen() handle it
            } else {
                showScreen(6); // Purpose screen FIRST for new visitors
            }
        }

        // Enhanced nextScreen function to handle auto-selection
        function nextScreen() {
            if (validateCurrentScreen()) {
                if (currentFlow.length > 0) {
                    currentFlowIndex++;
                    if (currentFlowIndex < currentFlow.length) {
                        const nextScreenNumber = currentFlow[currentFlowIndex];
                        
                        // Check if we're about to show the purpose screen and have an initial purpose
                        if (nextScreenNumber === 6 && visitorData.initialPurpose === 'delivery') {
                            showScreen(nextScreenNumber);
                            // Auto-selection will be handled by the showScreen function
                        } else {
                            showScreen(nextScreenNumber);
                        }
                    }
                } else {
                    showScreen(currentScreen + 1);
                }
            }
        }

        // // Optional: Modify the selectPurpose function to handle pre-selection better
        // function selectPurpose(purpose, element) {
        //     // Clear all selections
        //     document.querySelectorAll('.purpose-card').forEach(card => card.classList.remove('selected'));
            
        //     // Add selection to clicked element
        //     if (element) {
        //         element.classList.add('selected');
        //     } else {
        //         // If no element provided (auto-selection), find and select the card
        //         const card = Array.from(document.querySelectorAll('.purpose-card'))
        //             .find(c => c.getAttribute('onclick').includes(`'${purpose}'`));
        //         if (card) {
        //             card.classList.add('selected');
        //         }
        //     }
            
        //     selectedPurpose = purpose;
        //     visitorData.purpose = purpose;
        //     document.getElementById('purposeNextBtn').disabled = false;
        // }

        // Add this function to load purposes from database
        function loadPurposesFromDatabase() {
            fetch('<?= base_url("kiosk/get_purposes") ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    availablePurposes = result.purposes;
                    populatePurposeGrid();
                } else {
                    console.error('Failed to load purposes');
                    // Fallback to default purposes if API fails
                    loadDefaultPurposes();
                }
            })
            .catch(error => {
                console.error('Error loading purposes:', error);
                loadDefaultPurposes();
            });
        }

        // // Populate the purpose grid with database purposes
        // function populatePurposeGrid() {
        //     const purposeGrid = document.querySelector('.purpose-grid');
        //     if (!purposeGrid) return;
            
        //     purposeGrid.innerHTML = '';
            
        //     availablePurposes.forEach(purpose => {
        //         const card = document.createElement('div');
        //         card.className = 'purpose-card';
        //         card.setAttribute('onclick', `selectPurpose('${purpose.purpose_code}', this)`);
                
        //         card.innerHTML = `
        //             <i class="bi ${purpose.icon_class} ${purpose.color_class}"></i>
        //             <h5 data-translate="${purpose.purpose_code}">${purpose.purpose_name}</h5>
        //         `;
                
        //         purposeGrid.appendChild(card);
        //     });
        // }

        // Updated function to populate purpose grid with database translations
        function populatePurposeGrid() {
            const purposeGrid = document.querySelector('.purpose-grid');
            if (!purposeGrid) return;
            
            purposeGrid.innerHTML = '';
            
            availablePurposes.forEach(purpose => {
                const card = document.createElement('div');
                card.className = 'purpose-card';
                card.setAttribute('onclick', `selectPurpose('${purpose.purpose_code}', this)`);
                
                // Get translated name based on current language
                const translatedName = getTranslatedPurposeName(purpose);
                
                card.innerHTML = `
                    <i class="bi ${purpose.icon_class} ${purpose.color_class}"></i>
                    <h5>${translatedName}</h5>
                `;
                
                purposeGrid.appendChild(card);
            });
        }

        // Helper function to get translated purpose name
        function getTranslatedPurposeName(purpose) {
            // Map JavaScript language codes to database column names
            const languageMap = {
                'en': 'name_en',
                'zh-TW': 'name_zh_tw',
                'zh-CN': 'name_zh_cn',
                'fil': 'name_fil',
                'ja': 'name_ja'
            };
            
            const columnName = languageMap[currentLanguage];
            
            // Return translated name from database, fallback to English
            return purpose[columnName] || purpose.name_en || purpose.purpose_name;
        }

        // Updated translatePage function to also update department names when language changes
        function translatePage() {
            // Existing translation code for other elements
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
            
            // Update purpose cards if loaded
            if (availablePurposes.length > 0 && document.querySelector('.purpose-grid')) {
                populatePurposeGrid();
            }
            
            // NEW: Update department dropdown if loaded
            if (availableDepartments.length > 0) {
                const select = document.getElementById('departmentSelect');
                const currentValue = select.value; // Preserve selection
                
                select.innerHTML = '<option value="">Choose a department...</option>';
                
                availableDepartments.forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept.department_code;
                    option.textContent = getTranslatedDepartmentName(dept);
                    if (dept.department_code === currentValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
                
                // Update selected host display if there's a selection
                if (selectedHost && selectedHost.departmentCode === currentValue) {
                    const deptObj = availableDepartments.find(d => d.department_code === currentValue);
                    if (deptObj) {
                        const translatedDeptName = getTranslatedDepartmentName(deptObj);
                        selectedHost.department = translatedDeptName;
                        
                        document.getElementById('selectedHost').innerHTML = `
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-person-circle" style="font-size: 2em;"></i>
                                <div>
                                    <div style="font-weight: 600;">${selectedHost.name}</div>
                                    <div style="font-size: 0.9em; color: #7f8c8d;">${translatedDeptName}</div>
                                </div>
                            </div>
                        `;
                    }
                }
            }
        }

        // Fallback function for default purposes (in case API fails)
        function loadDefaultPurposes() {
            availablePurposes = [
                { purpose_code: 'meeting', purpose_name: 'Meeting', icon_class: 'bi-people', color_class: 'text-primary' },
                { purpose_code: 'interview', purpose_name: 'Interview', icon_class: 'bi-briefcase', color_class: 'text-success' },
                { purpose_code: 'delivery', purpose_name: 'Delivery', icon_class: 'bi-box', color_class: 'text-warning' },
                { purpose_code: 'service', purpose_name: 'Service/Repair', icon_class: 'bi-tools', color_class: 'text-info' },
                { purpose_code: 'training', purpose_name: 'Training', icon_class: 'bi-mortarboard', color_class: 'text-danger' },
                { purpose_code: 'tour', purpose_name: 'Tour', icon_class: 'bi-map', color_class: 'text-secondary' },
                { purpose_code: 'event', purpose_name: 'Event', icon_class: 'bi-calendar-event', color_class: 'text-purple' },
                { purpose_code: 'other', purpose_name: 'Other', icon_class: 'bi-three-dots', color_class: 'text-dark' }
            ];
            populatePurposeGrid();
        }
        
        // Update selectPurpose function to prevent selection when disabled:
        function selectPurpose(purpose, element) {
            // Check if card is disabled
            if (element && element.classList.contains('disabled')) {
                return; // Do nothing if disabled
            }
            
            // Clear all selections (but keep disabled state)
            document.querySelectorAll('.purpose-card').forEach(card => {
                if (!card.classList.contains('disabled')) {
                    card.classList.remove('selected');
                }
            });
            
            // Add selection to clicked element
            if (element) {
                element.classList.add('selected');
            } else {
                // If no element provided (auto-selection), find and select the card
                const card = Array.from(document.querySelectorAll('.purpose-card'))
                    .find(c => c.getAttribute('onclick')?.includes(`'${purpose}'`));
                if (card && !card.classList.contains('disabled')) {
                    card.classList.add('selected');
                }
            }
            
            selectedPurpose = purpose;
            visitorData.purpose = purpose;
            document.getElementById('purposeNextBtn').disabled = false;
        }

        // function nextScreen() {
        //     if (validateCurrentScreen()) {
        //         if (currentFlow.length > 0) {
        //             currentFlowIndex++;
        //             if (currentFlowIndex < currentFlow.length) {
        //                 showScreen(currentFlow[currentFlowIndex]);
        //             }
        //         } else {
        //             showScreen(currentScreen + 1);
        //         }
        //     }
        // }

        function previousScreen() {
            if (currentFlow.length > 0 && currentFlowIndex > 0) {
                currentFlowIndex--;
                showScreen(currentFlow[currentFlowIndex]);
            } else {
                showScreen(currentScreen - 1);
            }
        }

        // Update step indicator - FIXED VERSION
        function updateStepIndicator(step) {
            // // Map screen number to step number (7 total steps shown)
            // const screenToStep = {
            //     1: 1,  // Welcome
            //     2: 2,  // QR Scanner
            //     3: 2,  // Basic Info (same step as QR for returning visitors)
            //     4: 3,  // Photo
            //     5: 4,  // Host
            //     6: 5,  // Purpose
            //     7: 6,  // Agreement
            //     8: 7   // Success
            // };

            // Updated step indicator mapping
            const screenToStep = {
                1: 1,  // Welcome
                2: 2,  // QR Scanner (returning)
                6: 2,  // Purpose (new visitors) - FIRST STEP
                5: 3,  // Host
                3: 4,  // Basic Info
                4: 5,  // Photo
                7: 6,  // Agreement
                8: 7   // Success
            };
            
            const actualStep = screenToStep[step] || step;
            
            document.querySelectorAll('.step-dot').forEach((dot, index) => {
                dot.classList.remove('active', 'completed');
                if (index + 1 < actualStep) {
                    dot.classList.add('completed');
                } else if (index + 1 === actualStep) {
                    dot.classList.add('active');
                }
            });
        }

        // Validation functions
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

        // Validate current screen
        function validateCurrentScreen() {
            switch(currentScreen) {
                case 3:
                    let isValid = true;
                    
                    const firstName = document.getElementById('firstName');
                    const firstNameValue = firstName.value.trim();
                    if (!firstNameValue || !validateName(firstNameValue)) {
                        firstName.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        firstName.classList.remove('is-invalid');
                        visitorData.firstName = firstNameValue;
                    }
                    
                    const lastName = document.getElementById('lastName');
                    const lastNameValue = lastName.value.trim();
                    if (!lastNameValue || !validateName(lastNameValue)) {
                        lastName.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        lastName.classList.remove('is-invalid');
                        visitorData.lastName = lastNameValue;
                    }
                    
                    // EMAIL AND PHONE VALIDATION - At least one required
                    const email = document.getElementById('email');
                    const emailValue = email.value.trim();
                    const isEmailProvided = emailValue.length > 0;
                    const isEmailValid = isEmailProvided && validateEmail(emailValue);
                    
                    const phone = document.getElementById('phone');
                    const phoneValue = phone.value.trim();
                    const isPhoneProvided = phoneValue.length > 0;
                    const isPhoneValid = isPhoneProvided && validatePhone(phoneValue);
                    
                    // Check if at least one is provided
                    if (!isEmailProvided && !isPhoneProvided) {
                        // Both are empty - show error
                        email.classList.add('is-invalid');
                        phone.classList.add('is-invalid');
                        showNotification('Please provide either email address or phone number');
                        isValid = false;
                    } else {
                        // At least one is provided - validate each one that has input
                        
                        // Validate email if provided
                        if (isEmailProvided) {
                            if (!isEmailValid) {
                                email.classList.add('is-invalid');
                                isValid = false;
                            } else {
                                email.classList.remove('is-invalid');
                                visitorData.email = emailValue.toLowerCase();
                            }
                        } else {
                            // Email not provided but phone is - that's okay
                            email.classList.remove('is-invalid');
                            visitorData.email = null;
                        }
                        
                        // Validate phone if provided
                        if (isPhoneProvided) {
                            if (!isPhoneValid) {
                                phone.classList.add('is-invalid');
                                isValid = false;
                            } else {
                                phone.classList.remove('is-invalid');
                                visitorData.phone = phoneValue;
                            }
                        } else {
                            // Phone not provided but email is - that's okay
                            phone.classList.remove('is-invalid');
                            visitorData.phone = null;
                        }
                    }
                    
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
                    
                    // NEW: Check for duplicate before proceeding
                    // This returns a promise, so we need to handle it differently
                    return true; // Will check duplicates in nextScreen instead
                    
                    
                case 4:
                    visitorData.photo = capturedPhotoData;
                    return true;
                    
                case 5:
                    if (!selectedHost) {
                        showNotification('Please select who you are here to see');
                        return false;
                    }
                    return true;
                    
                case 6:
                    if (!selectedPurpose) {
                        showNotification('Please select the purpose of your visit');
                        return false;
                    }
                    const notes = document.getElementById('visitNotes').value;
                    if (notes) visitorData.notes = notes;
                    return true;
                    
                case 7:
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

        // MODIFY nextScreen function:
        async function nextScreen() {
            if (!validateCurrentScreen()) {
                return;
            }
            
            // NEW: If moving from basic info screen, check for duplicates first
            if (currentScreen === 3) {
                const canProceed = await checkExistingVisitor();
                if (!canProceed) {
                    return;
                }
            }
            
            // Rest of existing nextScreen code...
            if (currentFlow.length > 0) {
                currentFlowIndex++;
                if (currentFlowIndex < currentFlow.length) {
                    const nextScreenNumber = currentFlow[currentFlowIndex];
                    
                    if (nextScreenNumber === 6 && visitorData.initialPurpose === 'delivery') {
                        showScreen(nextScreenNumber);
                    } else {
                        showScreen(nextScreenNumber);
                    }
                }
            } else {
                showScreen(currentScreen + 1);
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

        // // Capture photo - FIXED for mobile orientation
        // function capturePhoto() {
        //     const video = document.getElementById('videoElement');
        //     const image = document.getElementById('capturedImage');
        //     const canvas = document.createElement('canvas');
            
        //     // Get video dimensions
        //     const videoWidth = video.videoWidth;
        //     const videoHeight = video.videoHeight;
            
        //     // Set canvas size to match video's actual dimensions
        //     canvas.width = videoWidth;
        //     canvas.height = videoHeight;
            
        //     const ctx = canvas.getContext('2d');
            
        //     // Draw the video frame directly without any transforms
        //     // The video element already handles orientation correctly
        //     ctx.drawImage(video, 0, 0, videoWidth, videoHeight);
            
        //     capturedPhotoData = canvas.toDataURL('image/jpeg', 0.8);
            
        //     image.src = capturedPhotoData;
        //     image.style.display = 'block';
        //     video.style.display = 'none';
            
        //     document.getElementById('captureBtn').style.display = 'none';
        //     document.getElementById('retakeBtn').style.display = 'block';
            
        //     // IMPORTANT: Change Skip button to Continue button when photo is taken
        //     document.getElementById('photoSkipBtn').style.display = 'none';
        //     document.getElementById('photoNextBtn').style.display = 'block';
            
        //     visitorData.photo = capturedPhotoData;
        //     photoTaken = true;
        //     showNotification('Photo captured successfully!');
        // }

        // Capture photo - WITH 3-SECOND COUNTDOWN
        function capturePhoto() {
            const video = document.getElementById('videoElement');
            const captureBtn = document.getElementById('captureBtn');
            
            // Disable button during countdown
            captureBtn.disabled = true;
            
            // Create countdown overlay
            const countdownOverlay = document.createElement('div');
            countdownOverlay.id = 'countdownOverlay';
            countdownOverlay.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1000;
                font-size: 120px;
                font-weight: bold;
                color: #fff;
                text-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
            `;
            
            const cameraView = document.querySelector('.camera-view');
            cameraView.appendChild(countdownOverlay);
            
            let countdown = 3;
            countdownOverlay.textContent = countdown;
            
            // Play countdown sound effect (optional - browser beep)
            const beep = () => {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = 800;
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.1);
            };
            
            beep(); // First beep
            
            const countdownInterval = setInterval(() => {
                countdown--;
                
                if (countdown > 0) {
                    countdownOverlay.textContent = countdown;
                    beep();
                } else {
                    clearInterval(countdownInterval);
                    countdownOverlay.remove();
                    
                    // Now capture the photo
                    performPhotoCapture();
                }
            }, 1000);
        }
        
        // Actual photo capture logic separated into its own function
        function performPhotoCapture() {
            const video = document.getElementById('videoElement');
            const image = document.getElementById('capturedImage');
            const canvas = document.createElement('canvas');
            
            // Get video dimensions
            const videoWidth = video.videoWidth;
            const videoHeight = video.videoHeight;
            
            // Set canvas size to match video's actual dimensions
            canvas.width = videoWidth;
            canvas.height = videoHeight;
            
            const ctx = canvas.getContext('2d');
            
            // Draw the video frame directly without any transforms
            // The video element already handles orientation correctly
            ctx.drawImage(video, 0, 0, videoWidth, videoHeight);
            
            capturedPhotoData = canvas.toDataURL('image/jpeg', 0.8);
            
            image.src = capturedPhotoData;
            image.style.display = 'block';
            video.style.display = 'none';
            
            document.getElementById('captureBtn').style.display = 'none';
            document.getElementById('captureBtn').disabled = false; // Re-enable for next time
            document.getElementById('retakeBtn').style.display = 'block';
            
            // IMPORTANT: Change Skip button to Continue button when photo is taken
            document.getElementById('photoSkipBtn').style.display = 'none';
            document.getElementById('photoNextBtn').style.display = 'block';
            
            visitorData.photo = capturedPhotoData;
            photoTaken = true;
            showNotification('Photo captured successfully!');
        }

        // Retake photo - Updated
        function retakePhoto() {
            const video = document.getElementById('videoElement');
            const image = document.getElementById('capturedImage');
            
            video.style.display = 'block';
            image.style.display = 'none';
            
            document.getElementById('captureBtn').style.display = 'block';
            document.getElementById('retakeBtn').style.display = 'none';
            
            // Revert to Skip button when retaking
            document.getElementById('photoSkipBtn').style.display = 'block';
            document.getElementById('photoNextBtn').style.display = 'none';
            
            capturedPhotoData = null;
            photoTaken = false;
        }

        // // Purpose selection
        // function selectPurpose(purpose, element) {
        //     document.querySelectorAll('.purpose-card').forEach(card => card.classList.remove('selected'));
        //     element.classList.add('selected');
        //     selectedPurpose = purpose;
        //     visitorData.purpose = purpose;
        //     document.getElementById('purposeNextBtn').disabled = false;
        // }

        // Agreement check
        function checkAgreement() {
            const terms = document.getElementById('agreeTerms').checked;
            const photo = document.getElementById('agreePhoto').checked;
            document.getElementById('agreeNextBtn').disabled = !(terms && photo);
        }

        // Storage functions
        function getStoredVisitors() {
            const stored = localStorage.getItem(STORAGE_KEY);
            return stored ? JSON.parse(stored) : [];
        }

        function storeVisitor(visitor) {
            const visitors = getStoredVisitors();
            visitor.id = Date.now();
            visitor.lastVisit = new Date().toISOString();
            
            visitors.unshift(visitor);
            if (visitors.length > 100) visitors.splice(100);
            
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(visitors));
            } catch (e) {
                console.error('Storage error:', e);
            }
            
            return visitor;
        }

        // // Complete check-in - UPDATED WITHOUT QR CODE GENERATION
        // function completeCheckIn() {
        //     showLoading();
            
        //     // Update step indicator to show final step
        //     updateStepIndicator(8);
            
        //     const storedVisitor = storeVisitor(visitorData);
            
        //     setTimeout(() => {
        //         hideLoading();
                
        //         const badgeNumber = 'V-' + new Date().getFullYear() + '-' + 
        //                            String(Math.floor(Math.random() * 10000)).padStart(4, '0');
                
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
                
        //         showScreen(8);
        //         startCountdown();
                
        //         console.log('Check-in complete:', storedVisitor);
        //     }, 2000);
        // }

        // FIXED: Complete check-in function
        function completeCheckIn() {
            showLoading();
            
            // Get current time in Philippines timezone
            const now = new Date();
            const phTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
            
            // Format as MySQL datetime: YYYY-MM-DD HH:MM:SS
            const formatDateTime = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            };
            
            // Prepare data for database insertion
            const checkInData = {
                firstName: visitorData.firstName,
                lastName: visitorData.lastName,
                email: visitorData.email,
                phone: visitorData.phone,
                company: visitorData.company,
                photo: visitorData.photo || null,
                type: visitorData.type,
                host: {
                    id: selectedHost.id || selectedHost.employeeId,
                    name: selectedHost.name,
                    email: selectedHost.email,
                    department: selectedHost.department,
                    departmentCode: selectedHost.departmentCode
                },
                purpose: selectedPurpose,
                notes: visitorData.notes || null,
                booking_code: visitorData.booking_code || null,
                company_visited: COMPANY_VISITED,
                check_in_time: formatDateTime(phTime),
                client_timezone: 'Asia/Manila',
                timezone_offset: now.getTimezoneOffset()
            };
            
            console.log('Sending check-in data:', checkInData);
            
            // Send data to server for database insertion
            fetch('<?= base_url("kiosk/complete_checkin") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(checkInData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                console.log('Check-in response:', result);
                hideLoading();
                
                if (result.status === 'success') {
                    // Store visit data before updating screen
                    visitorData.visit_id = result.data.visit_id;
                    visitorData.badge_number = result.data.badge_number;
                    
                    console.log('Check-in successful, updating screen...');
                    
                    // Update success screen with actual data from database
                    updateSuccessScreen(result.data);
                    
                    // Update step indicator to show final step
                    updateStepIndicator(8);
                    
                    // Show success screen
                    showScreen(8);
                    
                    // Start countdown timer
                    startCountdown();
                    
                    console.log('Success screen displayed');
                    
                } else {
                    console.error('Check-in failed:', result.message);
                    // Show error message
                    Swal.fire({
                        title: 'Check-in Failed',
                        text: result.message || 'An error occurred during check-in. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#e74c3c'
                    });
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Check-in error:', error);
                
                Swal.fire({
                    title: 'Connection Error',
                    text: 'Unable to connect to the server. Please check your connection and try again.',
                    icon: 'error',
                    confirmButtonColor: '#e74c3c'
                });
            });
        }

        // FIXED: Countdown timer with better logging
        function startCountdown() {
            console.log('Starting countdown timer...');
            
            let seconds = 60;
            const countdownEl = document.getElementById('countdown');
            
            if (!countdownEl) {
                console.error('Countdown element not found');
                return;
            }
            
            countdownEl.textContent = seconds;
            
            countdownTimer = setInterval(() => {
                seconds--;
                countdownEl.textContent = seconds;
                
                if (seconds <= 0) {
                    clearInterval(countdownTimer);
                    console.log('Countdown complete, resetting kiosk...');
                    resetKiosk();
                }
            }, 1000);
        }

        // // Countdown timer
        // function startCountdown() {
        //     let seconds = 60;
        //     countdownTimer = setInterval(() => {
        //         seconds--;
        //         document.getElementById('countdown').textContent = seconds;
                
        //         if (seconds <= 0) {
        //             clearInterval(countdownTimer);
        //             resetKiosk();
        //         }
        //     }, 1000);
        // }

        // // Reset kiosk
        // function resetKiosk() {
        //     clearInterval(countdownTimer);
        //     stopCamera();
            
        //     // Reset flags
        //     isProcessingQR = false;
        //     isScannerStopping = false;
            
        //     // Stop scanner safely
        //     stopQRScanner();
            
        //     // Clear QR code instance
        //     if (qrCodeInstance) {
        //         const qrContainer = document.getElementById('qrCodeContainer');
        //         if (qrContainer) {
        //             qrContainer.innerHTML = '';
        //         }
        //         qrCodeInstance = null;
        //     }
            
        //     visitorData = {};
        //     selectedHost = null;
        //     selectedPurpose = null;
        //     selectedDepartment = null;
        //     capturedPhotoData = null;
        //     photoTaken = false;
        //     currentFlow = [];
        //     currentFlowIndex = 0;
            
        //     document.querySelectorAll('input').forEach(input => {
        //         if (input.type !== 'checkbox') {
        //             input.value = '';
        //             input.classList.remove('is-invalid');
        //         } else {
        //             input.checked = false;
        //         }
        //     });
            
        //     document.querySelectorAll('textarea').forEach(textarea => {
        //         textarea.value = '';
        //     });
            
        //     document.querySelectorAll('.purpose-card').forEach(card => {
        //         card.classList.remove('selected');
        //     });
            
        //     document.getElementById('departmentSelect').value = '';
        //     document.getElementById('employeeSection').style.display = 'none';
        //     document.getElementById('selectedHost').innerHTML = `<span class="text-muted">${translations[currentLanguage].noSelection || 'No one selected yet'}</span>`;
        //     document.getElementById('captureBtn').style.display = 'block';
        //     document.getElementById('retakeBtn').style.display = 'none';
        //     document.getElementById('capturedImage').style.display = 'none';
        //     document.getElementById('photoSkipBtn').style.display = 'block';
        //     document.getElementById('photoNextBtn').style.display = 'none';
        //     document.getElementById('hostNextBtn').disabled = true;
        //     document.getElementById('purposeNextBtn').disabled = true;
        //     document.getElementById('agreeNextBtn').disabled = true;

        //     // Hard refresh the page
        //     window.location.href = window.location.href.split('?')[0]; // Removes any query parameters
        //     // OR use this for a complete reload:
        //     // window.location.reload(true); // true forces reload from server, not cache
            
        //     showScreen(1);
        // }

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

        // Emergency call - FOR TOM'S WORLD
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
                    const emergencyData = {
                        visitor_name: visitorData.firstName && visitorData.lastName 
                            ? `${visitorData.firstName} ${visitorData.lastName}` 
                            : 'Anonymous Visitor',
                        location: 'Tom\'s World Kiosk',
                        company_visited: 'Toms World'
                    };
                    
                    fetch('<?= base_url("kiosk/emergency_alert") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(emergencyData)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            Swal.fire({
                                title: translations[currentLanguage].emergencyNotified || 'Security has been notified!',
                                html: `
                                    <p>${translations[currentLanguage].emergencyMessage || 'Help is on the way. Please stay where you are.'}</p>
                                    <div style="margin-top: 15px; padding: 10px; background: #fff3cd; border-radius: 8px; border: 2px solid #f39c12;">
                                        <strong style="color: #f39c12;">
                                            <i class="bi bi-building"></i> Tom's World Security Team Notified
                                        </strong>
                                    </div>
                                `,
                                icon: 'success',
                                confirmButtonColor: '#27ae60',
                                allowOutsideClick: false
                            });
                        } else {
                            Swal.fire({
                                title: 'Notification Failed',
                                text: 'Please contact Tom\'s World staff directly at the reception desk.',
                                icon: 'error',
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Emergency alert error:', error);
                        Swal.fire({
                            title: 'Connection Error',
                            text: 'Please contact Tom\'s World staff directly at the reception desk.',
                            icon: 'error',
                            confirmButtonColor: '#e74c3c'
                        });
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



        // // Updated completeCheckIn function to connect with database
        // function completeCheckIn() {
        //     showLoading();
            
        //     // Prepare data for database insertion
        //     const checkInData = {
        //         firstName: visitorData.firstName,
        //         lastName: visitorData.lastName,
        //         email: visitorData.email,
        //         phone: visitorData.phone,
        //         company: visitorData.company,
        //         photo: visitorData.photo || null,
        //         type: visitorData.type,
        //         host: {
        //             id: selectedHost.id || selectedHost.employeeId,
        //             name: selectedHost.name,
        //             email: selectedHost.email,
        //             department: selectedHost.department,
        //             departmentCode: selectedHost.departmentCode
        //         },
        //         purpose: selectedPurpose,
        //         notes: visitorData.notes || null,
        //         booking_code: visitorData.booking_code || null
        //     };
            
        //     // Send data to server for database insertion
        //     fetch('<?= base_url("kiosk/complete_checkin") ?>', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-Requested-With': 'XMLHttpRequest'
        //         },
        //         body: JSON.stringify(checkInData)
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         hideLoading();
                
        //         if (result.status === 'success') {
        //             // Update success screen with actual data from database
        //             updateSuccessScreen(result.data);
                    
        //             // Update step indicator to show final step
        //             updateStepIndicator(8);
                    
        //             // Show success screen
        //             showScreen(8);
                    
        //             // Start countdown timer
        //             startCountdown();
                    
        //             console.log('Check-in successful:', result.data);
                    
        //             // Store photo locally if available (for QR code reference)
        //             if (visitorData.photo && result.data.visit_id) {
        //                 try {
        //                     localStorage.setItem(`visitor_photo_${result.data.visit_id}`, visitorData.photo);
        //                 } catch (e) {
        //                     console.error('Could not store photo:', e);
        //                 }
        //             }
        //         } else {
        //             // Show error message
        //             Swal.fire({
        //                 title: 'Check-in Failed',
        //                 text: result.message || 'An error occurred during check-in. Please try again.',
        //                 icon: 'error',
        //                 confirmButtonColor: '#e74c3c'
        //             });
        //         }
        //     })
        //     .catch(error => {
        //         hideLoading();
        //         console.error('Check-in error:', error);
                
        //         Swal.fire({
        //             title: 'Connection Error',
        //             text: 'Unable to connect to the server. Please check your connection and try again.',
        //             icon: 'error',
        //             confirmButtonColor: '#e74c3c'
        //         });
        //     });
        // }

        // // Update success screen with actual database data
        // function updateSuccessScreen(data) {
        //     // Update badge number
        //     document.getElementById('badgeNumber').textContent = data.badge_number;
            
        //     // Update visitor information
        //     document.getElementById('visitorName').textContent = data.visitor_name;
        //     document.getElementById('visitorCompany').textContent = data.company;
            
        //     // Update host information
        //     document.getElementById('badgeHost').textContent = data.host_name;
            
        //     // Update valid until time
        //     const validUntilDate = new Date(data.valid_until);
        //     document.getElementById('validUntil').textContent = 
        //         validUntilDate.toLocaleTimeString('en-US', { 
        //             hour: '2-digit', 
        //             minute: '2-digit', 
        //             hour12: true 
        //         });
            
        //     // Update badge photo if available
        //     const badgePhotoDiv = document.getElementById('badgePhotoDisplay');
        //     if (visitorData.photo) {
        //         badgePhotoDiv.innerHTML = `<img src="${visitorData.photo}" alt="Visitor Photo">`;
        //     } else {
        //         badgePhotoDiv.innerHTML = '<i class="bi bi-person-circle" style="font-size: 3em; color: #dee2e6;"></i>';
        //     }
            
        //     // Store visit ID for potential future reference
        //     visitorData.visit_id = data.visit_id;
        //     visitorData.badge_number = data.badge_number;
        // }

        // // UPDATED: Update success screen to include QR code generation
        // function updateSuccessScreen(data) {
        //     // Update badge number
        //     document.getElementById('badgeNumber').textContent = data.badge_number;
            
        //     // Update visitor information
        //     document.getElementById('visitorName').textContent = data.visitor_name;
        //     document.getElementById('visitorCompany').textContent = data.company;
            
        //     // Update host information
        //     document.getElementById('badgeHost').textContent = data.host_name;
            
        //     // Update valid until time
        //     const validUntilDate = new Date(data.valid_until);
        //     document.getElementById('validUntil').textContent = 
        //         validUntilDate.toLocaleTimeString('en-US', { 
        //             hour: '2-digit', 
        //             minute: '2-digit', 
        //             hour12: true 
        //         });
            
        //     // Update badge photo if available
        //     const badgePhotoDiv = document.getElementById('badgePhotoDisplay');
        //     if (visitorData.photo) {
        //         badgePhotoDiv.innerHTML = `<img src="${visitorData.photo}" alt="Visitor Photo" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">`;
        //     } else {
        //         badgePhotoDiv.innerHTML = '<i class="bi bi-person-circle" style="font-size: 3em; color: #dee2e6;"></i>';
        //     }
            
        //     // Store visit ID and badge number FIRST
        //     visitorData.visit_id = data.visit_id;
        //     visitorData.badge_number = data.badge_number;

        //     // THEN generate QR code (needs badge_number to be set)
        //     generateVisitorQRCode();
        // }

        // FIXED: Update success screen function
        function updateSuccessScreen(data) {
            console.log('Updating success screen with data:', data);
            
            // Update badge number
            const badgeNumberEl = document.getElementById('badgeNumber');
            if (badgeNumberEl) {
                badgeNumberEl.textContent = data.badge_number;
            }
            
            // Update visitor information
            const visitorNameEl = document.getElementById('visitorName');
            if (visitorNameEl) {
                visitorNameEl.textContent = data.visitor_name;
            }
            
            const visitorCompanyEl = document.getElementById('visitorCompany');
            if (visitorCompanyEl) {
                visitorCompanyEl.textContent = data.company;
            }
            
            // Update host information
            const badgeHostEl = document.getElementById('badgeHost');
            if (badgeHostEl) {
                badgeHostEl.textContent = data.host_name;
            }
            
            // Update valid until time
            const validUntilEl = document.getElementById('validUntil');
            if (validUntilEl) {
                const validUntilDate = new Date(data.valid_until);
                validUntilEl.textContent = validUntilDate.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    hour12: true 
                });
            }
            
            // Update badge photo if available
            const badgePhotoDiv = document.getElementById('badgePhotoDisplay');
            if (badgePhotoDiv) {
                if (visitorData.photo) {
                    badgePhotoDiv.innerHTML = `<img src="${visitorData.photo}" alt="Visitor Photo" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">`;
                } else {
                    badgePhotoDiv.innerHTML = '<i class="bi bi-person-circle" style="font-size: 3em; color: #dee2e6;"></i>';
                }
            }
            
            // Generate QR code (needs badge_number to be set first)
            console.log('Generating QR code...');
            generateVisitorQRCode();
            
            console.log('Success screen update complete');
        }

        // // Updated department loading function to fetch from database
        // function populateDepartments() {
        //     fetch('<?= base_url("kiosk/get_departments") ?>', {
        //         method: 'GET',
        //         headers: {
        //             'X-Requested-With': 'XMLHttpRequest'
        //         }
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         if (result.status === 'success') {
        //             const select = document.getElementById('departmentSelect');
        //             select.innerHTML = '<option value="">Choose a department...</option>';
                    
        //             result.departments.forEach(dept => {
        //                 const option = document.createElement('option');
        //                 option.value = dept.department_code;
        //                 option.textContent = dept.name;
        //                 select.appendChild(option);
        //             });
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error loading departments:', error);
        //         // Fallback to hardcoded departments if API fails
        //         populateDepartmentsStatic();
        //     });
        // }

        // // Updated: Populate departments with translations
        // function populateDepartments() {
        //     fetch('<?= base_url("kiosk/get_departments") ?>', {
        //         method: 'GET',
        //         headers: {
        //             'X-Requested-With': 'XMLHttpRequest'
        //         }
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         if (result.status === 'success') {
        //             // Store departments globally for translation
        //             availableDepartments = result.departments;
                    
        //             const select = document.getElementById('departmentSelect');
        //             select.innerHTML = '<option value="">Choose a department...</option>';
                    
        //             result.departments.forEach(dept => {
        //                 const option = document.createElement('option');
        //                 option.value = dept.department_code;
        //                 // Use translated name
        //                 option.textContent = getTranslatedDepartmentName(dept);
        //                 select.appendChild(option);
        //             });
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error loading departments:', error);
        //         // Fallback to hardcoded departments if API fails
        //         populateDepartmentsStatic();
        //     });
        // }

        // Updated: Populate departments with translations - WITH NULL CHECK
        function populateDepartments() {
            const select = document.getElementById('departmentSelect');
            
            // CRITICAL: Check if element exists
            if (!select) {
                console.warn('Department select element not found - skipping population');
                return;
            }
            
            fetch('<?= base_url("kiosk/get_departments") ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    // Store departments globally for translation
                    availableDepartments = result.departments;
                    
                    select.innerHTML = '<option value="">Choose a department...</option>';
                    
                    result.departments.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.department_code;
                        // Use translated name
                        option.textContent = getTranslatedDepartmentName(dept);
                        select.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                // Fallback to static departments if API fails
                populateDepartmentsStatic();
            });
        }

        // Updated employee loading function to fetch from database
        // function onDepartmentChange() {
        //     const deptCode = document.getElementById('departmentSelect').value;
        //     const employeeSection = document.getElementById('employeeSection');
        //     const employeeGrid = document.getElementById('employeeGrid');
            
        //     if (!deptCode) {
        //         employeeSection.style.display = 'none';
        //         resetHostSelection();
        //         return;
        //     }
            
        //     // Show loading indicator
        //     employeeGrid.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
        //     employeeSection.style.display = 'block';
            
        //     // Fetch employees from database
        //     fetch(`<?= base_url("kiosk/get_employees/") ?>${deptCode}`, {
        //         method: 'GET',
        //         headers: {
        //             'X-Requested-With': 'XMLHttpRequest'
        //         }
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         if (result.status === 'success') {
        //             employeeGrid.innerHTML = '';
                    
        //             result.employees.forEach(employee => {
        //                 const card = document.createElement('div');
        //                 card.className = 'employee-card';
        //                 card.innerHTML = `
        //                     <i class="bi bi-person-circle"></i>
        //                     <div class="employee-name">${employee.name}</div>
        //                     <div class="employee-email">${employee.email}</div>
        //                 `;
        //                 card.onclick = () => selectEmployeeFromCard({
        //                     id: employee.employee_id,
        //                     employeeId: employee.employee_id,
        //                     name: employee.name,
        //                     email: employee.email
        //                 }, deptCode);
        //                 employeeGrid.appendChild(card);
        //             });
                    
        //             if (result.employees.length === 0) {
        //                 employeeGrid.innerHTML = '<p class="text-muted text-center">No employees found in this department</p>';
        //             }
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error loading employees:', error);
        //         employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees. Please try again.</p>';
        //     });
        // }

        // // Handle QR code for returning visitors
        // function handleQRCodeSuccess(decodedText) {
        //     try {
        //         let qrData;
                
        //         try {
        //             qrData = JSON.parse(decodedText);
        //         } catch (e) {
        //             qrData = JSON.parse(atob(decodedText));
        //         }
                
        //         if (qrData.email) {
        //             if (html5QrCode) {
        //                 html5QrCode.stop();
        //             }
                    
        //             // Search for visitor in database
        //             fetch('<?= base_url("kiosk/search_visitor") ?>', {
        //                 method: 'POST',
        //                 headers: {
        //                     'Content-Type': 'application/json',
        //                     'X-Requested-With': 'XMLHttpRequest'
        //                 },
        //                 body: JSON.stringify({ email: qrData.email })
        //             })
        //             .then(response => response.json())
        //             .then(result => {
        //                 if (result.status === 'success' && result.visitor) {
        //                     // Populate form with visitor data
        //                     visitorData = {
        //                         ...visitorData,
        //                         firstName: result.visitor.first_name,
        //                         lastName: result.visitor.last_name,
        //                         email: result.visitor.email,
        //                         phone: result.visitor.phone,
        //                         company: result.visitor.company,
        //                         photo: result.visitor.photo,
        //                         visitor_id: result.visitor.visitor_id,
        //                         total_visits: result.visitor.total_visits
        //                     };
                            
        //                     // Pre-fill the form if moving to basic info screen
        //                     if (document.getElementById('firstName')) {
        //                         document.getElementById('firstName').value = result.visitor.first_name;
        //                         document.getElementById('lastName').value = result.visitor.last_name;
        //                         document.getElementById('email').value = result.visitor.email;
        //                         document.getElementById('phone').value = result.visitor.phone;
        //                         document.getElementById('company').value = result.visitor.company;
        //                     }
                            
        //                     Swal.fire({
        //                         title: `Welcome Back!`,
        //                         text: `Welcome back, ${result.visitor.first_name}! You've visited us ${result.visitor.total_visits} time(s) before.`,
        //                         icon: 'success',
        //                         confirmButtonColor: '#27ae60'
        //                     });
                            
        //                     // Skip to host selection for returning visitors
        //                     showScreen(5);
        //                 } else {
        //                     showNotification('Visitor not found. Please complete full registration.');
        //                     showScreen(3);
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error searching visitor:', error);
        //                 showNotification('Error processing QR code. Please continue with manual entry.');
        //                 showScreen(3);
        //             });
        //         }
        //     } catch (e) {
        //         console.error('QR decode error:', e);
        //         showNotification('Invalid QR Code');
        //     }
        // }

        // Load pre-scheduled visits from database
        function loadPreScheduledVisits() {
            const resultsDiv = document.getElementById('bookingResults');
            resultsDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
            
            fetch('<?= base_url("kiosk/get_prescheduled") ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    resultsDiv.innerHTML = '';
                    
                    if (result.visits.length === 0) {
                        resultsDiv.innerHTML = '<p class="text-muted text-center">No pre-scheduled visits found for today</p>';
                        return;
                    }
                    
                    result.visits.forEach(visit => {
                        const scheduledTime = new Date(visit.scheduled_time);
                        const timeStr = scheduledTime.toLocaleTimeString('en-US', { 
                            hour: '2-digit', 
                            minute: '2-digit', 
                            hour12: true 
                        });
                        
                        const item = document.createElement('div');
                        item.className = 'booking-item';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="booking-code">${visit.booking_code}</div>
                                    <div class="mt-2">
                                        <strong>${visit.visitor_name}</strong> - ${visit.visitor_company || 'N/A'}
                                    </div>
                                    <div class="text-muted">
                                        Host: ${visit.host_name} (${visit.department}) | Time: ${timeStr}
                                    </div>
                                    <div class="text-primary mt-1">
                                        <i class="bi bi-calendar-check"></i> ${visit.purpose}
                                    </div>
                                </div>
                                <button class="btn btn-primary" onclick="selectPreScheduled('${visit.booking_code}')">
                                    Check In
                                </button>
                            </div>
                        `;
                        resultsDiv.appendChild(item);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading pre-scheduled visits:', error);
                resultsDiv.innerHTML = '<p class="text-danger text-center">Error loading scheduled visits. Please try again.</p>';
            });
        }

        // Search pre-scheduled visits
        function searchBookings(query) {
            if (query.length < 2) {
                loadPreScheduledVisits();
                return;
            }
            
            const resultsDiv = document.getElementById('bookingResults');
            resultsDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
            
            fetch(`<?= base_url("kiosk/get_prescheduled") ?>?search=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    resultsDiv.innerHTML = '';
                    
                    const filtered = result.visits.filter(visit =>
                        visit.booking_code.toLowerCase().includes(query.toLowerCase()) ||
                        visit.visitor_name.toLowerCase().includes(query.toLowerCase())
                    );
                    
                    if (filtered.length === 0) {
                        resultsDiv.innerHTML = '<p class="text-muted text-center">No matching bookings found</p>';
                        return;
                    }
                    
                    filtered.forEach(visit => {
                        const scheduledTime = new Date(visit.scheduled_time);
                        const timeStr = scheduledTime.toLocaleTimeString('en-US', { 
                            hour: '2-digit', 
                            minute: '2-digit', 
                            hour12: true 
                        });
                        
                        const item = document.createElement('div');
                        item.className = 'booking-item';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="booking-code">${visit.booking_code}</div>
                                    <div class="mt-2">
                                        <strong>${visit.visitor_name}</strong> - ${visit.visitor_company || 'N/A'}
                                    </div>
                                    <div class="text-muted">
                                        Host: ${visit.host_name} (${visit.department}) | Time: ${timeStr}
                                    </div>
                                    <div class="text-primary mt-1">
                                        <i class="bi bi-calendar-check"></i> ${visit.purpose}
                                    </div>
                                </div>
                                <button class="btn btn-primary" onclick="selectPreScheduled('${visit.booking_code}')">
                                    Check In
                                </button>
                            </div>
                        `;
                        resultsDiv.appendChild(item);
                    });
                }
            })
            .catch(error => {
                console.error('Error searching bookings:', error);
                resultsDiv.innerHTML = '<p class="text-danger text-center">Error searching bookings. Please try again.</p>';
            });
        }

        // Implement actual check-out functionality
        function checkOut() {
            Swal.fire({
                title: 'Check Out',
                input: 'text',
                inputLabel: 'Enter your badge number',
                inputPlaceholder: 'V-2024-XXXX',
                showCancelButton: true,
                confirmButtonColor: '#f39c12',
                confirmButtonText: 'Check Out',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Please enter your badge number';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= base_url("kiosk/checkout") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ badge_number: result.value })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Checked Out Successfully',
                                text: 'Thank you for visiting. Have a great day!',
                                icon: 'success',
                                confirmButtonColor: '#27ae60'
                            });
                        } else {
                            Swal.fire({
                                title: 'Check Out Failed',
                                text: data.message || 'Invalid badge number or already checked out',
                                icon: 'error',
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Checkout error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Unable to process check-out. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#e74c3c'
                        });
                    });
                }
            });
        }

        // // Keep the static populate function as fallback
        // function populateDepartmentsStatic() {
        //     const select = document.getElementById('departmentSelect');
        //     select.innerHTML = '<option value="">Choose a department...</option>';
            
        //     // Use the original departmentData object as fallback
        //     Object.keys(departmentData).forEach(deptCode => {
        //         const option = document.createElement('option');
        //         option.value = deptCode;
        //         option.textContent = departmentData[deptCode].name;
        //         select.appendChild(option);
        //     });
        // }

        // Keep the static populate function as fallback - WITH NULL CHECK
        function populateDepartmentsStatic() {
            const select = document.getElementById('departmentSelect');
            
            // CRITICAL: Check if element exists
            if (!select) {
                console.warn('Department select element not found - skipping static population');
                return;
            }
            
            select.innerHTML = '<option value="">Choose a department...</option>';
            
            // Use a simple fallback if departmentData doesn't exist
            const fallbackDepartments = [
                { code: 'ITSD', name: 'Information Technology & Services' },
                { code: 'HR', name: 'Human Resources' },
                { code: 'FIN', name: 'Finance' },
                { code: 'ADM', name: 'Admin' }
            ];
            
            fallbackDepartments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept.code;
                option.textContent = dept.name;
                select.appendChild(option);
            });
        }


        
        // Add this constant at the top of the script section (after the departmentData declaration)
        const COMPANY_VISITED = 'Toms World';

        // // Update the completeCheckIn function - find this function and replace it:
        // function completeCheckIn() {
        //     showLoading();
            
        //     // Prepare data for database insertion
        //     const checkInData = {
        //         firstName: visitorData.firstName,
        //         lastName: visitorData.lastName,
        //         email: visitorData.email,
        //         phone: visitorData.phone,
        //         company: visitorData.company,
        //         photo: visitorData.photo || null,
        //         type: visitorData.type,
        //         host: {
        //             id: selectedHost.id || selectedHost.employeeId,
        //             name: selectedHost.name,
        //             email: selectedHost.email,
        //             department: selectedHost.department,
        //             departmentCode: selectedHost.departmentCode
        //         },
        //         purpose: selectedPurpose,
        //         notes: visitorData.notes || null,
        //         booking_code: visitorData.booking_code || null,
        //         company_visited: COMPANY_VISITED  // ADD THIS LINE
        //     };
            
        //     // Send data to server for database insertion
        //     fetch('<?= base_url("kiosk/complete_checkin") ?>', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-Requested-With': 'XMLHttpRequest'
        //         },
        //         body: JSON.stringify(checkInData)
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         hideLoading();
                
        //         if (result.status === 'success') {
        //             // Update success screen with actual data from database
        //             updateSuccessScreen(result.data);
                    
        //             // Update step indicator to show final step
        //             updateStepIndicator(8);
                    
        //             // Show success screen
        //             showScreen(8);
                    
        //             // Start countdown timer
        //             startCountdown();
                    
        //             console.log('Check-in successful:', result.data);
                    
        //             // Store photo locally if available (for QR code reference)
        //             if (visitorData.photo && result.data.visit_id) {
        //                 try {
        //                     localStorage.setItem(`visitor_photo_${result.data.visit_id}`, visitorData.photo);
        //                 } catch (e) {
        //                     console.error('Could not store photo:', e);
        //                 }
        //             }
        //         } else {
        //             // Show error message
        //             Swal.fire({
        //                 title: 'Check-in Failed',
        //                 text: result.message || 'An error occurred during check-in. Please try again.',
        //                 icon: 'error',
        //                 confirmButtonColor: '#e74c3c'
        //             });
        //         }
        //     })
        //     .catch(error => {
        //         hideLoading();
        //         console.error('Check-in error:', error);
                
        //         Swal.fire({
        //             title: 'Connection Error',
        //             text: 'Unable to connect to the server. Please check your connection and try again.',
        //             icon: 'error',
        //             confirmButtonColor: '#e74c3c'
        //         });
        //     });
        // }

        // FIXED: Complete check-in function
        function completeCheckIn() {
            showLoading();
            
            // Get current time in Philippines timezone
            const now = new Date();
            const phTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
            
            // Format as MySQL datetime: YYYY-MM-DD HH:MM:SS
            const formatDateTime = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            };
            
            // Prepare data for database insertion
            const checkInData = {
                firstName: visitorData.firstName,
                lastName: visitorData.lastName,
                email: visitorData.email,
                phone: visitorData.phone,
                company: visitorData.company,
                photo: visitorData.photo || null,
                type: visitorData.type,
                host: {
                    id: selectedHost.id || selectedHost.employeeId,
                    name: selectedHost.name,
                    email: selectedHost.email,
                    department: selectedHost.department,
                    departmentCode: selectedHost.departmentCode
                },
                purpose: selectedPurpose,
                notes: visitorData.notes || null,
                booking_code: visitorData.booking_code || null,
                company_visited: COMPANY_VISITED,
                check_in_time: formatDateTime(phTime),
                client_timezone: 'Asia/Manila',
                timezone_offset: now.getTimezoneOffset()
            };
            
            console.log('Sending check-in data:', checkInData);
            
            // Send data to server for database insertion
            fetch('<?= base_url("kiosk/complete_checkin") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(checkInData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                console.log('Check-in response:', result);
                hideLoading();
                
                if (result.status === 'success') {
                    // Store visit data before updating screen
                    visitorData.visit_id = result.data.visit_id;
                    visitorData.badge_number = result.data.badge_number;
                    
                    console.log('Check-in successful, updating screen...');
                    
                    // Update success screen with actual data from database
                    updateSuccessScreen(result.data);
                    
                    // Update step indicator to show final step
                    updateStepIndicator(8);
                    
                    // Show success screen
                    showScreen(8);
                    
                    // Start countdown timer
                    startCountdown();
                    
                    console.log('Success screen displayed');
                    
                } else {
                    console.error('Check-in failed:', result.message);
                    // Show error message
                    Swal.fire({
                        title: 'Check-in Failed',
                        text: result.message || 'An error occurred during check-in. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#e74c3c'
                    });
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Check-in error:', error);
                
                Swal.fire({
                    title: 'Connection Error',
                    text: 'Unable to connect to the server. Please check your connection and try again.',
                    icon: 'error',
                    confirmButtonColor: '#e74c3c'
                });
            });
        }

        // function completeCheckIn() {
        //     showLoading();
            
        //     // Get current time in Philippines timezone
        //     const now = new Date();
        //     const phTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
            
        //     // Format as MySQL datetime: YYYY-MM-DD HH:MM:SS
        //     const formatDateTime = (date) => {
        //         const year = date.getFullYear();
        //         const month = String(date.getMonth() + 1).padStart(2, '0');
        //         const day = String(date.getDate()).padStart(2, '0');
        //         const hours = String(date.getHours()).padStart(2, '0');
        //         const minutes = String(date.getMinutes()).padStart(2, '0');
        //         const seconds = String(date.getSeconds()).padStart(2, '0');
        //         return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        //     };
            
        //     // Prepare data for database insertion
        //     const checkInData = {
        //         firstName: visitorData.firstName,
        //         lastName: visitorData.lastName,
        //         email: visitorData.email,
        //         phone: visitorData.phone,
        //         company: visitorData.company,
        //         photo: visitorData.photo || null,
        //         type: visitorData.type,
        //         host: {
        //             id: selectedHost.id || selectedHost.employeeId,
        //             name: selectedHost.name,
        //             email: selectedHost.email,
        //             department: selectedHost.department,
        //             departmentCode: selectedHost.departmentCode
        //         },
        //         purpose: selectedPurpose,
        //         notes: visitorData.notes || null,
        //         booking_code: visitorData.booking_code || null,
        //         company_visited: COMPANY_VISITED,
        //         // ADD THESE LINES FOR TIMEZONE FIX:
        //         check_in_time: formatDateTime(phTime),
        //         client_timezone: 'Asia/Manila',
        //         timezone_offset: now.getTimezoneOffset()
        //     };
            
        //     // Debug: Log times to console
        //     console.log('Browser time:', now.toString());
        //     console.log('Philippines time:', phTime.toString());
        //     console.log('Sending to DB:', checkInData.check_in_time);
            
        //     // Send data to server for database insertion
        //     fetch('<?= base_url("kiosk/complete_checkin") ?>', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-Requested-With': 'XMLHttpRequest'
        //         },
        //         body: JSON.stringify(checkInData)
        //     })
        //     .then(response => response.json())
        //     .then(result => {
        //         hideLoading();
                
        //         if (result.status === 'success') {
        //             // Update success screen with actual data from database
        //             updateSuccessScreen(result.data);
                    
        //             // Update step indicator to show final step
        //             updateStepIndicator(8);
                    
        //             // Show success screen
        //             showScreen(8);
                    
        //             // Start countdown timer
        //             startCountdown();
                    
        //             console.log('Check-in successful:', result.data);
                    
        //         } else {
        //             // Show error message
        //             Swal.fire({
        //                 title: 'Check-in Failed',
        //                 text: result.message || 'An error occurred during check-in. Please try again.',
        //                 icon: 'error',
        //                 confirmButtonColor: '#e74c3c'
        //             });
        //         }
        //     })
        //     .catch(error => {
        //         hideLoading();
        //         console.error('Check-in error:', error);
                
        //         Swal.fire({
        //             title: 'Connection Error',
        //             text: 'Unable to connect to the server. Please check your connection and try again.',
        //             icon: 'error',
        //             confirmButtonColor: '#e74c3c'
        //         });
        //     });
        // }

        // ENHANCED: resetKiosk with better scanner cleanup
        function resetKiosk() {
            console.log('Starting kiosk reset...');
            
            // Clear countdown timer
            if (countdownTimer) {
                clearInterval(countdownTimer);
                countdownTimer = null;
            }
            
            // Stop camera safely
            stopCamera();
            
            // Reset flags
            isProcessingQR = false;
            isScannerStopping = false;
            
            // Stop scanner and clear instance
            stopQRScanner().then(() => {
                html5QrCode = null; // IMPORTANT: Clear the scanner instance
                console.log('QR Scanner cleared');
            }).catch(() => {
                html5QrCode = null;
                console.log('Scanner clear error (ignored)');
            });
            
            // Clear QR code instance
            if (qrCodeInstance) {
                const qrContainer = document.getElementById('qrCodeContainer');
                if (qrContainer) {
                    qrContainer.innerHTML = '';
                }
                qrCodeInstance = null;
            }
            
            // Reset state variables
            visitorData = {};
            selectedHost = null;
            selectedPurpose = null;
            selectedDepartment = null;
            capturedPhotoData = null;
            photoTaken = false;
            currentFlow = [];
            currentFlowIndex = 0;
            currentDepartmentEmployees = []; // Also clear employee list
            
            // Reset form inputs safely
            try {
                document.querySelectorAll('input').forEach(input => {
                    if (input.type !== 'checkbox' && input.type !== 'file') {
                        input.value = '';
                        input.classList.remove('is-invalid');
                    } else if (input.type === 'checkbox') {
                        input.checked = false;
                    }
                });
                
                document.querySelectorAll('textarea').forEach(textarea => {
                    textarea.value = '';
                });
                
                document.querySelectorAll('.purpose-card').forEach(card => {
                    card.classList.remove('selected', 'disabled');
                    card.style.opacity = '1';
                    card.style.cursor = 'pointer';
                    card.style.pointerEvents = 'auto';
                });
                
                // Reset department select if it exists
                const deptSelect = document.getElementById('departmentSelect');
                if (deptSelect) {
                    deptSelect.value = '';
                }
                
                // Reset employee section if it exists
                const employeeSection = document.getElementById('employeeSection');
                if (employeeSection) {
                    employeeSection.style.display = 'none';
                }
                
                // Clear employee search
                const employeeSearch = document.getElementById('employeeSearch');
                if (employeeSearch) {
                    employeeSearch.value = '';
                }
                
                const employeeCount = document.getElementById('employeeCount');
                if (employeeCount) {
                    employeeCount.textContent = '';
                }
                
                // Reset selected host display if it exists
                const selectedHostEl = document.getElementById('selectedHost');
                if (selectedHostEl) {
                    selectedHostEl.innerHTML = `<span class="text-muted">${translations[currentLanguage].noSelection || 'No one selected yet'}</span>`;
                }
                
                // Reset photo capture buttons if they exist
                const captureBtn = document.getElementById('captureBtn');
                const retakeBtn = document.getElementById('retakeBtn');
                const capturedImage = document.getElementById('capturedImage');
                const photoSkipBtn = document.getElementById('photoSkipBtn');
                const photoNextBtn = document.getElementById('photoNextBtn');
                
                if (captureBtn) captureBtn.style.display = 'block';
                if (retakeBtn) retakeBtn.style.display = 'none';
                if (capturedImage) capturedImage.style.display = 'none';
                if (photoSkipBtn) photoSkipBtn.style.display = 'block';
                if (photoNextBtn) photoNextBtn.style.display = 'none';
                
                // Reset navigation buttons if they exist
                const hostNextBtn = document.getElementById('hostNextBtn');
                const purposeNextBtn = document.getElementById('purposeNextBtn');
                const agreeNextBtn = document.getElementById('agreeNextBtn');
                
                if (hostNextBtn) hostNextBtn.disabled = true;
                if (purposeNextBtn) purposeNextBtn.disabled = true;
                if (agreeNextBtn) agreeNextBtn.disabled = true;
                
            } catch (error) {
                console.error('Error during form reset:', error);
            }
            
            console.log('Reset complete, showing welcome screen...');
            
            // Show welcome screen
            showScreen(1);
            
            console.log('Kiosk reset successful');
        }
        
        // // FIXED: resetKiosk function
        // function resetKiosk() {
        //     clearInterval(countdownTimer);
        //     stopCamera();
            
        //     // Reset flags
        //     isProcessingQR = false;
        //     isScannerStopping = false;
            
        //     // Stop scanner safely (won't throw error if already stopped)
        //     stopQRScanner();
            
        //     // Clear QR code instance
        //     if (qrCodeInstance) {
        //         const qrContainer = document.getElementById('qrCodeContainer');
        //         if (qrContainer) {
        //             qrContainer.innerHTML = '';
        //         }
        //         qrCodeInstance = null;
        //     }
            
        //     visitorData = {};
        //     selectedHost = null;
        //     selectedPurpose = null;
        //     selectedDepartment = null;
        //     capturedPhotoData = null;
        //     photoTaken = false;
        //     currentFlow = [];
        //     currentFlowIndex = 0;
            
        //     document.querySelectorAll('input').forEach(input => {
        //         if (input.type !== 'checkbox') {
        //             input.value = '';
        //             input.classList.remove('is-invalid');
        //         } else {
        //             input.checked = false;
        //         }
        //     });
            
        //     document.querySelectorAll('textarea').forEach(textarea => {
        //         textarea.value = '';
        //     });
            
        //     document.querySelectorAll('.purpose-card').forEach(card => {
        //         card.classList.remove('selected');
        //     });
            
        //     document.getElementById('departmentSelect').value = '';
        //     document.getElementById('employeeSection').style.display = 'none';
        //     document.getElementById('selectedHost').innerHTML = `<span class="text-muted">${translations[currentLanguage].noSelection || 'No one selected yet'}</span>`;
        //     document.getElementById('captureBtn').style.display = 'block';
        //     document.getElementById('retakeBtn').style.display = 'none';
        //     document.getElementById('capturedImage').style.display = 'none';
        //     document.getElementById('photoSkipBtn').style.display = 'block';
        //     document.getElementById('photoNextBtn').style.display = 'none';
        //     document.getElementById('hostNextBtn').disabled = true;
        //     document.getElementById('purposeNextBtn').disabled = true;
        //     document.getElementById('agreeNextBtn').disabled = true;            

        //     // Hard refresh the page
        //     // window.location.href = window.location.href.split('?')[0]; // Removes any query parameters
        //     // OR use this for a complete reload:
        //     window.location.reload(true); // true forces reload from server, not cache
        
        //     showScreen(1);
        // }

        // Add this function to load purposes from database
        function loadPurposesFromDatabase() {
            // Send company_visited parameter to filter purposes
            fetch(`<?= base_url("kiosk/get_purposes") ?>?company_visited=${encodeURIComponent(COMPANY_VISITED)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    availablePurposes = result.purposes;
                    populatePurposeGrid();
                } else {
                    console.error('Failed to load purposes');
                    // Fallback to default purposes if API fails
                    loadDefaultPurposes();
                }
            })
            .catch(error => {
                console.error('Error loading purposes:', error);
                loadDefaultPurposes();
            });
        }

        // Off-canvas Panel Functions
        let touchStartX = 0;
        let touchEndX = 0;
        let isOffcanvasOpen = false;

        function toggleOffcanvas() {
            const panel = document.getElementById('offcanvasPanel');
            const overlay = document.getElementById('offcanvasOverlay');
            
            isOffcanvasOpen = !isOffcanvasOpen;
            
            if (isOffcanvasOpen) {
                panel.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            } else {
                closeOffcanvas();
            }
        }

        function closeOffcanvas() {
            const panel = document.getElementById('offcanvasPanel');
            const overlay = document.getElementById('offcanvasOverlay');
            
            panel.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            isOffcanvasOpen = false;
        }

        // Touch Swipe Support
        document.addEventListener('DOMContentLoaded', function() {
            const panel = document.getElementById('offcanvasPanel');
            
            // Swipe to open from left edge
            document.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            document.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
            
            function handleSwipe() {
                const swipeThreshold = 50;
                const swipeDistance = touchEndX - touchStartX;
                
                // Swipe right to open (from left edge)
                if (!isOffcanvasOpen && touchStartX < 50 && swipeDistance > swipeThreshold) {
                    toggleOffcanvas();
                }
                
                // Swipe left to close
                if (isOffcanvasOpen && swipeDistance < -swipeThreshold) {
                    closeOffcanvas();
                }
            }
            
            // Close on ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && isOffcanvasOpen) {
                    closeOffcanvas();
                }
            });
            
            // Prevent body scroll when offcanvas is open
            panel.addEventListener('touchmove', (e) => {
                if (isOffcanvasOpen) {
                    e.stopPropagation();
                }
            });
        });

    </script>