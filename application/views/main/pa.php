
<body>
    <!-- Main Kiosk Container -->
    <div class="kiosk-container">
        <!-- Header -->
        <div class="kiosk-header">
            <div class="company-logo">
                <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" 
                    alt="Pan-Asia" 
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
                        <label class="form-label" data-translate="email">Email Address *</label>
                        <input type="email" class="form-control form-control-lg" id="email">
                        <div class="invalid-feedback" data-translate="emailInvalid">Please enter a valid email address</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="phone">Phone Number *</label>
                                <input type="tel" class="form-control form-control-lg" id="phone">
                                <div class="invalid-feedback" data-translate="phoneInvalid">Please enter a valid phone number</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" data-translate="company">Company *</label>
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

                    <div class="badge-preview" id="badgePreview">
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
        // Department and Employee Data Structure
        const departmentData = {
            'ADM': {
                name: 'Admin',
                employees: [
                    { id: 'ADM001', name: 'John Smith', email: 'j.smith@company.com' },
                    { id: 'ADM002', name: 'Sarah Johnson', email: 's.johnson@company.com' },
                    { id: 'ADM003', name: 'Michael Chen', email: 'm.chen@company.com' }
                ]
            },
            'BDD': {
                name: 'Design & Construction',
                employees: [
                    { id: 'BDD001', name: 'Emily Davis', email: 'e.davis@company.com' },
                    { id: 'BDD002', name: 'Robert Wilson', email: 'r.wilson@company.com' }
                ]
            },
            'CRT': {
                name: 'Creatives',
                employees: [
                    { id: 'CRT001', name: 'Lisa Anderson', email: 'l.anderson@company.com' },
                    { id: 'CRT002', name: 'David Martinez', email: 'd.martinez@company.com' },
                    { id: 'CRT003', name: 'Jessica Taylor', email: 'j.taylor@company.com' }
                ]
            },
            'ED': {
                name: 'Ent. Risk Management',
                employees: [
                    { id: 'ED001', name: 'Thomas Brown', email: 't.brown@company.com' },
                    { id: 'ED002', name: 'Jennifer White', email: 'j.white@company.com' }
                ]
            },
            'EXE': {
                name: 'Executive',
                employees: [
                    { id: 'EXE001', name: 'William Garcia', email: 'w.garcia@company.com' },
                    { id: 'EXE002', name: 'Patricia Miller', email: 'p.miller@company.com' }
                ]
            },
            'FIN': {
                name: 'Finance',
                employees: [
                    { id: 'FIN001', name: 'Christopher Lee', email: 'c.lee@company.com' },
                    { id: 'FIN002', name: 'Amanda Jones', email: 'a.jones@company.com' },
                    { id: 'FIN003', name: 'Daniel Rodriguez', email: 'd.rodriguez@company.com' }
                ]
            },
            'HR': {
                name: 'Human Resource',
                employees: [
                    { id: 'HR001', name: 'Michelle Thompson', email: 'm.thompson@company.com' },
                    { id: 'HR002', name: 'Kevin Harris', email: 'k.harris@company.com' },
                    { id: 'HR003', name: 'Rachel Clark', email: 'r.clark@company.com' }
                ]
            },
            'IMP': {
                name: 'Importation',
                employees: [
                    { id: 'IMP001', name: 'Brian Lewis', email: 'b.lewis@company.com' },
                    { id: 'IMP002', name: 'Sophia Walker', email: 's.walker@company.com' }
                ]
            },
            'ITSD': {
                name: 'Information Technology & Services',
                employees: [
                    { id: 'ITSD001', name: 'James Hall', email: 'j.hall@company.com' },
                    { id: 'ITSD002', name: 'Olivia Allen', email: 'o.allen@company.com' },
                    { id: 'ITSD003', name: 'Matthew Young', email: 'm.young@company.com' },
                    { id: 'ITSD004', name: 'Emma King', email: 'e.king@company.com' }
                ]
            },
            'MRK': {
                name: 'Marketing',
                employees: [
                    { id: 'MRK001', name: 'Andrew Wright', email: 'a.wright@company.com' },
                    { id: 'MRK002', name: 'Isabella Lopez', email: 'i.lopez@company.com' },
                    { id: 'MRK003', name: 'Joshua Hill', email: 'j.hill@company.com' }
                ]
            },
            'MER': {
                name: 'Audit & Merchandising',
                employees: [
                    { id: 'MER001', name: 'Megan Scott', email: 'm.scott@company.com' },
                    { id: 'MER002', name: 'Ryan Green', email: 'r.green@company.com' }
                ]
            },
            'OP': {
                name: 'Operations',
                employees: [
                    { id: 'OP001', name: 'Nicholas Adams', email: 'n.adams@company.com' },
                    { id: 'OP002', name: 'Victoria Baker', email: 'v.baker@company.com' },
                    { id: 'OP003', name: 'Alexander Nelson', email: 'a.nelson@company.com' }
                ]
            },
            'ODSM': {
                name: 'Org. Development & Strat. Mngt.',
                employees: [
                    { id: 'ODSM001', name: 'Samantha Carter', email: 's.carter@company.com' },
                    { id: 'ODSM002', name: 'Joseph Mitchell', email: 'j.mitchell@company.com' }
                ]
            },
            'SPD': {
                name: 'Special Projects',
                employees: [
                    { id: 'SPD001', name: 'Lauren Perez', email: 'l.perez@company.com' },
                    { id: 'SPD002', name: 'Charles Roberts', email: 'c.roberts@company.com' }
                ]
            },
            'SD': {
                name: 'Stocks Department',
                employees: [
                    { id: 'SD001', name: 'Ashley Turner', email: 'a.turner@company.com' },
                    { id: 'SD002', name: 'Benjamin Phillips', email: 'b.phillips@company.com' }
                ]
            },
            'TD': {
                name: 'Technical',
                employees: [
                    { id: 'TD001', name: 'Nathan Campbell', email: 'n.campbell@company.com' },
                    { id: 'TD002', name: 'Madison Parker', email: 'm.parker@company.com' }
                ]
            },
            'WLD': {
                name: 'Warehouse & Logistics',
                employees: [
                    { id: 'WLD001', name: 'Eric Evans', email: 'e.evans@company.com' },
                    { id: 'WLD002', name: 'Hannah Edwards', email: 'h.edwards@company.com' },
                    { id: 'WLD003', name: 'Tyler Collins', email: 't.collins@company.com' }
                ]
            },
            'PA': {
                name: 'Pan Asia HR',
                employees: [
                    { id: 'PA001', name: 'Grace Stewart', email: 'g.stewart@company.com' },
                    { id: 'PA002', name: 'Dylan Sanchez', email: 'd.sanchez@company.com' }
                ]
            }
        };

        // Language Translations (Extended with new keys)
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
                letsCheckIn: "Let's get you checked in!",
                searchPrevious: "Search Your Previous Check-ins",
                searchPlaceholder: "Start typing your name or email...",
                firstName: "First Name *",
                lastName: "Last Name *",
                email: "Email Address *",
                phone: "Phone Number *",
                company: "Company *",
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
                    "Your host will come to receive you shortly"
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
        let selectedDepartment = null;
        let countdownTimer = null;
        let videoStream = null;
        let capturedPhotoData = null;
        let html5QrCode = null;
        let photoTaken = false;

        // Screen flow mapping
        const screenFlow = {
            'new': [1, 3, 4, 5, 6, 7, 8],
            'returning': [1, 2, 5, 6, 7, 8],
            'delivery': [1, 3, 4, 5, 6, 7, 8]
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

        // Local storage for visitor data
        const STORAGE_KEY = 'kioskVisitorData';
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateDateTime();
            setInterval(updateDateTime, 1000);
            translatePage();
            populateDepartments();
        });

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('en-US', options);
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            document.getElementById('datetime').textContent = `${dateStr} • ${timeStr}`;
        }

        // Populate departments dropdown
        function populateDepartments() {
            const select = document.getElementById('departmentSelect');
            select.innerHTML = '<option value="">Choose a department...</option>';
            
            Object.keys(departmentData).forEach(deptCode => {
                const option = document.createElement('option');
                option.value = deptCode;
                option.textContent = departmentData[deptCode].name;
                select.appendChild(option);
            });
        }

        // Handle department selection
        function onDepartmentChange() {
            const deptCode = document.getElementById('departmentSelect').value;
            const employeeSection = document.getElementById('employeeSection');
            const employeeGrid = document.getElementById('employeeGrid');
            
            if (!deptCode) {
                employeeSection.style.display = 'none';
                resetHostSelection();
                return;
            }
            
            selectedDepartment = deptCode;
            const dept = departmentData[deptCode];
            
            employeeGrid.innerHTML = '';
            
            dept.employees.forEach(employee => {
                const card = document.createElement('div');
                card.className = 'employee-card';
                card.innerHTML = `
                    <i class="bi bi-person-circle"></i>
                    <div class="employee-name">${employee.name}</div>
                    <div class="employee-email">${employee.email}</div>
                `;
                card.onclick = () => selectEmployeeFromCard(employee, deptCode);
                employeeGrid.appendChild(card);
            });
            
            employeeSection.style.display = 'block';
        }

        // Select employee from card
        function selectEmployeeFromCard(employee, deptCode) {
            // Remove previous selection
            document.querySelectorAll('.employee-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selection to clicked card
            event.currentTarget.classList.add('selected');
            
            selectedHost = {
                ...employee,
                department: departmentData[deptCode].name,
                departmentCode: deptCode
            };
            
            visitorData.host = selectedHost;
            
            document.getElementById('selectedHost').innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-person-circle" style="font-size: 2em;"></i>
                    <div>
                        <div style="font-weight: 600;">${employee.name}</div>
                        <div style="font-size: 0.9em; color: #7f8c8d;">${departmentData[deptCode].name}</div>
                    </div>
                </div>
            `;
            
            document.getElementById('hostNextBtn').disabled = false;
        }

        // Reset host selection
        function resetHostSelection() {
            selectedHost = null;
            document.getElementById('selectedHost').innerHTML = `
                <span class="text-muted">${translations[currentLanguage].noSelection || 'No one selected yet'}</span>
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

        // Start check-in process
        function startCheckIn(type) {
            visitorData.type = type;
            currentFlow = screenFlow[type];
            currentFlowIndex = 1;
            
            if (type === 'returning') {
                showScreen(2);
                initQRScanner();
            } else {
                showScreen(3);
            }
        }

        // Initialize QR Scanner
        function initQRScanner() {
            if (html5QrCode) {
                html5QrCode.stop();
            }
            
            html5QrCode = new Html5Qrcode("qr-reader");
            
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

        // Handle QR code success
        function handleQRCodeSuccess(decodedText) {
            try {
                let qrData;
                
                try {
                    qrData = JSON.parse(decodedText);
                } catch (e) {
                    qrData = JSON.parse(atob(decodedText));
                }
                
                if (qrData.email) {
                    if (html5QrCode) {
                        html5QrCode.stop();
                    }
                    
                    if (qrData.id) {
                        const storedPhoto = localStorage.getItem(`visitor_photo_${qrData.id}`);
                        if (storedPhoto) {
                            qrData.photo = storedPhoto;
                        }
                    }
                    
                    visitorData = {
                        ...visitorData,
                        ...qrData
                    };
                    
                    Swal.fire({
                        title: translations[currentLanguage].qrValidatedTitle || 'Welcome Back!',
                        text: translations[currentLanguage].qrValidatedMessage || 'Your QR code has been validated successfully',
                        icon: 'success',
                        confirmButtonColor: '#27ae60'
                    });
                    
                    showScreen(5);
                }
            } catch (e) {
                console.error('QR decode error:', e);
                showNotification(translations[currentLanguage].invalidQRMessage || 'Invalid QR Code');
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
                                showNotification(translations[currentLanguage].invalidQRMessage || 'Invalid QR Code');
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
            showScreen(3);
        }

        // Screen navigation
        function showScreen(screenNumber) {
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

        // Update step indicator - FIXED VERSION
        function updateStepIndicator(step) {
            // Map screen number to step number (7 total steps shown)
            const screenToStep = {
                1: 1,  // Welcome
                2: 2,  // QR Scanner
                3: 2,  // Basic Info (same step as QR for returning visitors)
                4: 3,  // Photo
                5: 4,  // Host
                6: 5,  // Purpose
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
                    
                    const email = document.getElementById('email');
                    const emailValue = email.value.trim();
                    if (!emailValue || !validateEmail(emailValue)) {
                        email.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        email.classList.remove('is-invalid');
                        visitorData.email = emailValue.toLowerCase();
                    }
                    
                    const phone = document.getElementById('phone');
                    const phoneValue = phone.value.trim();
                    if (!phoneValue || !validatePhone(phoneValue)) {
                        phone.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        phone.classList.remove('is-invalid');
                        visitorData.phone = phoneValue;
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

        // Capture photo - Updated
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

        // Complete check-in - UPDATED WITHOUT QR CODE GENERATION
        function completeCheckIn() {
            showLoading();
            
            // Update step indicator to show final step
            updateStepIndicator(8);
            
            const storedVisitor = storeVisitor(visitorData);
            
            setTimeout(() => {
                hideLoading();
                
                const badgeNumber = 'V-' + new Date().getFullYear() + '-' + 
                                   String(Math.floor(Math.random() * 10000)).padStart(4, '0');
                
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
                
                showScreen(8);
                startCountdown();
                
                console.log('Check-in complete:', storedVisitor);
            }, 2000);
        }

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
            
            visitorData = {};
            selectedHost = null;
            selectedPurpose = null;
            selectedDepartment = null;
            capturedPhotoData = null;
            photoTaken = false;
            currentFlow = [];
            currentFlowIndex = 0;
            
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
            
            document.getElementById('departmentSelect').value = '';
            document.getElementById('employeeSection').style.display = 'none';
            document.getElementById('selectedHost').innerHTML = `<span class="text-muted">${translations[currentLanguage].noSelection || 'No one selected yet'}</span>`;
            document.getElementById('captureBtn').style.display = 'block';
            document.getElementById('retakeBtn').style.display = 'none';
            document.getElementById('capturedImage').style.display = 'none';
            document.getElementById('photoSkipBtn').style.display = 'block';
            document.getElementById('photoNextBtn').style.display = 'none';
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

        // Updated completeCheckIn function to connect with database
        function completeCheckIn() {
            showLoading();
            
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
                booking_code: visitorData.booking_code || null
            };
            
            // Send data to server for database insertion
            fetch('<?= base_url("kiosk/complete_checkin") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(checkInData)
            })
            .then(response => response.json())
            .then(result => {
                hideLoading();
                
                if (result.status === 'success') {
                    // Update success screen with actual data from database
                    updateSuccessScreen(result.data);
                    
                    // Update step indicator to show final step
                    updateStepIndicator(8);
                    
                    // Show success screen
                    showScreen(8);
                    
                    // Start countdown timer
                    startCountdown();
                    
                    console.log('Check-in successful:', result.data);
                    
                    // Store photo locally if available (for QR code reference)
                    if (visitorData.photo && result.data.visit_id) {
                        try {
                            localStorage.setItem(`visitor_photo_${result.data.visit_id}`, visitorData.photo);
                        } catch (e) {
                            console.error('Could not store photo:', e);
                        }
                    }
                } else {
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

        // Update success screen with actual database data
        function updateSuccessScreen(data) {
            // Update badge number
            document.getElementById('badgeNumber').textContent = data.badge_number;
            
            // Update visitor information
            document.getElementById('visitorName').textContent = data.visitor_name;
            document.getElementById('visitorCompany').textContent = data.company;
            
            // Update host information
            document.getElementById('badgeHost').textContent = data.host_name;
            
            // Update valid until time
            const validUntilDate = new Date(data.valid_until);
            document.getElementById('validUntil').textContent = 
                validUntilDate.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    hour12: true 
                });
            
            // Update badge photo if available
            const badgePhotoDiv = document.getElementById('badgePhotoDisplay');
            if (visitorData.photo) {
                badgePhotoDiv.innerHTML = `<img src="${visitorData.photo}" alt="Visitor Photo">`;
            } else {
                badgePhotoDiv.innerHTML = '<i class="bi bi-person-circle" style="font-size: 3em; color: #dee2e6;"></i>';
            }
            
            // Store visit ID for potential future reference
            visitorData.visit_id = data.visit_id;
            visitorData.badge_number = data.badge_number;
        }

        // Updated department loading function to fetch from database
        function populateDepartments() {
            fetch('<?= base_url("kiosk/get_departments") ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    const select = document.getElementById('departmentSelect');
                    select.innerHTML = '<option value="">Choose a department...</option>';
                    
                    result.departments.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.department_code;
                        option.textContent = dept.name;
                        select.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                // Fallback to hardcoded departments if API fails
                populateDepartmentsStatic();
            });
        }

        // Updated employee loading function to fetch from database
        function onDepartmentChange() {
            const deptCode = document.getElementById('departmentSelect').value;
            const employeeSection = document.getElementById('employeeSection');
            const employeeGrid = document.getElementById('employeeGrid');
            
            if (!deptCode) {
                employeeSection.style.display = 'none';
                resetHostSelection();
                return;
            }
            
            // Show loading indicator
            employeeGrid.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
            employeeSection.style.display = 'block';
            
            // Fetch employees from database
            fetch(`<?= base_url("kiosk/get_employees/") ?>${deptCode}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    employeeGrid.innerHTML = '';
                    
                    result.employees.forEach(employee => {
                        const card = document.createElement('div');
                        card.className = 'employee-card';
                        card.innerHTML = `
                            <i class="bi bi-person-circle"></i>
                            <div class="employee-name">${employee.name}</div>
                            <div class="employee-email">${employee.email}</div>
                        `;
                        card.onclick = () => selectEmployeeFromCard({
                            id: employee.employee_id,
                            employeeId: employee.employee_id,
                            name: employee.name,
                            email: employee.email
                        }, deptCode);
                        employeeGrid.appendChild(card);
                    });
                    
                    if (result.employees.length === 0) {
                        employeeGrid.innerHTML = '<p class="text-muted text-center">No employees found in this department</p>';
                    }
                }
            })
            .catch(error => {
                console.error('Error loading employees:', error);
                employeeGrid.innerHTML = '<p class="text-danger text-center">Error loading employees. Please try again.</p>';
            });
        }

        // Handle QR code for returning visitors
        function handleQRCodeSuccess(decodedText) {
            try {
                let qrData;
                
                try {
                    qrData = JSON.parse(decodedText);
                } catch (e) {
                    qrData = JSON.parse(atob(decodedText));
                }
                
                if (qrData.email) {
                    if (html5QrCode) {
                        html5QrCode.stop();
                    }
                    
                    // Search for visitor in database
                    fetch('<?= base_url("kiosk/search_visitor") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ email: qrData.email })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success' && result.visitor) {
                            // Populate form with visitor data
                            visitorData = {
                                ...visitorData,
                                firstName: result.visitor.first_name,
                                lastName: result.visitor.last_name,
                                email: result.visitor.email,
                                phone: result.visitor.phone,
                                company: result.visitor.company,
                                photo: result.visitor.photo,
                                visitor_id: result.visitor.visitor_id,
                                total_visits: result.visitor.total_visits
                            };
                            
                            // Pre-fill the form if moving to basic info screen
                            if (document.getElementById('firstName')) {
                                document.getElementById('firstName').value = result.visitor.first_name;
                                document.getElementById('lastName').value = result.visitor.last_name;
                                document.getElementById('email').value = result.visitor.email;
                                document.getElementById('phone').value = result.visitor.phone;
                                document.getElementById('company').value = result.visitor.company;
                            }
                            
                            Swal.fire({
                                title: `Welcome Back!`,
                                text: `Welcome back, ${result.visitor.first_name}! You've visited us ${result.visitor.total_visits} time(s) before.`,
                                icon: 'success',
                                confirmButtonColor: '#27ae60'
                            });
                            
                            // Skip to host selection for returning visitors
                            showScreen(5);
                        } else {
                            showNotification('Visitor not found. Please complete full registration.');
                            showScreen(3);
                        }
                    })
                    .catch(error => {
                        console.error('Error searching visitor:', error);
                        showNotification('Error processing QR code. Please continue with manual entry.');
                        showScreen(3);
                    });
                }
            } catch (e) {
                console.error('QR decode error:', e);
                showNotification('Invalid QR Code');
            }
        }

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

        // Keep the static populate function as fallback
        function populateDepartmentsStatic() {
            const select = document.getElementById('departmentSelect');
            select.innerHTML = '<option value="">Choose a department...</option>';
            
            // Use the original departmentData object as fallback
            Object.keys(departmentData).forEach(deptCode => {
                const option = document.createElement('option');
                option.value = deptCode;
                option.textContent = departmentData[deptCode].name;
                select.appendChild(option);
            });
        }
    </script>