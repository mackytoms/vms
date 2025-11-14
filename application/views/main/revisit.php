<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMS Revisit Feature - QR Code System</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #f39c12;
            --success-color: #27ae60;
            --info-color: #3498db;
        }

        .qr-scanner-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .qr-code-display {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }

        .visitor-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
            background: rgba(52, 152, 219, 0.1);
            color: var(--info-color);
        }

        .returning-badge {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .duplicate-warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .express-checkin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin: 20px 0;
        }

        .qr-scan-button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s;
        }

        .qr-scan-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
        }

        #reader {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .visitor-info-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 2em;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <!-- Check-In Options Modal -->
    <div class="modal fade" id="checkInOptionsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Welcome to Tom's World & Pan-Asia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="express-checkin">
                        <h3><i class="bi bi-qr-code-scan"></i> Express Check-In</h3>
                        <p>Returning visitor? Scan your QR code for quick access</p>
                        <button class="qr-scan-button" onclick="startQRScanner()">
                            <i class="bi bi-camera"></i> Scan QR Code
                        </button>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="bi bi-person-plus" style="font-size: 3em; color: var(--info-color);"></i>
                                    <h5 class="card-title mt-3">First Time Visitor</h5>
                                    <p class="card-text">New here? Register for your visit</p>
                                    <button class="btn btn-primary" onclick="showNewVisitorForm()">Register</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="bi bi-search" style="font-size: 3em; color: var(--success-color);"></i>
                                    <h5 class="card-title mt-3">Forgot QR Code?</h5>
                                    <p class="card-text">Find your record using email or phone</p>
                                    <button class="btn btn-success" onclick="showLookupForm()">Look Up</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Modal -->
    <div class="modal fade" id="qrScannerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan Your QR Code</h5>
                    <button type="button" class="btn-close" onclick="stopQRScanner()"></button>
                </div>
                <div class="modal-body">
                    <div class="qr-scanner-container">
                        <div id="reader"></div>
                        <div class="mt-3 text-center">
                            <button class="btn btn-secondary" onclick="stopQRScanner()">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitor Lookup Modal -->
    <div class="modal fade" id="visitorLookupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Find Your Visitor Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="lookupForm" onsubmit="lookupVisitor(event)">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="lookupEmail" placeholder="your@email.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="lookupPhone" placeholder="09XXXXXXXXX">
                        </div>
                        <p class="text-muted small">Enter at least one to search for your record</p>
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicate Detection Modal -->
    <div class="modal fade" id="duplicateDetectionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Existing Record Found</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="duplicate-warning">
                        <h6>We found an existing visitor record that matches your information:</h6>
                    </div>
                    <div id="duplicateVisitorInfo"></div>
                    <div class="mt-4">
                        <h6>What would you like to do?</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" onclick="useExistingRecord()">
                                <i class="bi bi-person-check"></i> Use Existing Record
                            </button>
                            <button class="btn btn-primary" onclick="requestQRCode()">
                                <i class="bi bi-qr-code"></i> Send Me My QR Code
                            </button>
                            <button class="btn btn-outline-secondary" onclick="createNewRecord()">
                                <i class="bi bi-person-plus"></i> Create New Record Anyway
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-Out with QR Generation -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Check-Out Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="bi bi-check-circle" style="font-size: 4em; color: var(--success-color);"></i>
                        <h4 class="mt-3">Thank you for visiting!</h4>
                        <p>Your visit has been successfully logged.</p>
                    </div>
                    
                    <div class="qr-code-display">
                        <h5>Your Express Check-In QR Code</h5>
                        <div id="checkoutQRCode"></div>
                        <p class="mt-3 mb-0"><strong>QR Code:</strong> <span id="qrCodeText"></span></p>
                        <p class="text-muted small">Save this QR code for faster check-in on your next visit</p>
                        <button class="btn btn-primary" onclick="downloadQRCode()">
                            <i class="bi bi-download"></i> Download QR Code
                        </button>
                        <button class="btn btn-success" onclick="emailQRCode()">
                            <i class="bi bi-envelope"></i> Email Me
                        </button>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        A copy of this QR code has been sent to your registered email address.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // QR Scanner instance
    let html5QrCode = null;
    let currentVisitorData = null;

    // Initialize QR Scanner
    function startQRScanner() {
        const modal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
        modal.show();

        html5QrCode = new Html5Qrcode("reader");
        
        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 } 
        };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                handleQRCodeScan(decodedText);
                stopQRScanner();
            },
            (errorMessage) => {
                // Parse error, could be ignored
            }
        ).catch((err) => {
            console.error("Unable to start scanning", err);
            Swal.fire({
                icon: 'error',
                title: 'Camera Error',
                text: 'Unable to access camera. Please check permissions.'
            });
        });
    }

    // Stop QR Scanner
    function stopQRScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
                if (modal) modal.hide();
            }).catch((err) => console.error(err));
        }
    }

    // Handle QR Code Scan
    async function handleQRCodeScan(qrCode) {
        try {
            const response = await fetch('/api/revisit/scan', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ qr_code: qrCode })
            });

            const data = await response.json();

            if (data.success) {
                currentVisitorData = data.visitor;
                showReturningVisitorInfo(data.visitor, data.stats);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid QR Code',
                    text: 'This QR code is not recognized. Please register as a new visitor.'
                });
            }
        } catch (error) {
            console.error('QR scan error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Scan Error',
                text: 'Unable to process QR code. Please try again.'
            });
        }
    }

    // Show Returning Visitor Information
    function showReturningVisitorInfo(visitor, stats) {
        Swal.fire({
            title: `Welcome Back, ${visitor.first_name}!`,
            html: `
                <div class="visitor-info-card">
                    <p><strong>Name:</strong> ${visitor.first_name} ${visitor.last_name}</p>
                    <p><strong>Company:</strong> ${visitor.company}</p>
                    <p><strong>Email:</strong> ${visitor.email}</p>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value">${stats.total_visits}</div>
                            <div class="stat-label">Total Visits</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">${stats.last_visit_days}</div>
                            <div class="stat-label">Days Since Last Visit</div>
                        </div>
                    </div>
                    <span class="returning-badge">
                        <i class="bi bi-person-check"></i> Returning Visitor
                    </span>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Proceed to Check-In',
            confirmButtonColor: '#27ae60',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                quickCheckIn(visitor);
            }
        });
    }

    // Quick Check-In for Returning Visitors
    async function quickCheckIn(visitor) {
        // Pre-fill the check-in form with visitor data
        const checkInData = {
            visitor_id: visitor.visitor_id,
            first_name: visitor.first_name,
            last_name: visitor.last_name,
            email: visitor.email,
            phone: visitor.phone,
            company: visitor.company,
            is_returning: true
        };

        // Show host selection
        const { value: hostData } = await Swal.fire({
            title: 'Select Your Host',
            html: `
                <select id="hostSelect" class="form-select mb-3">
                    <option value="">Select Department/Host</option>
                    ${generateHostOptions()}
                </select>
                <select id="purposeSelect" class="form-select">
                    <option value="meeting">Meeting</option>
                    <option value="interview">Interview</option>
                    <option value="delivery">Delivery</option>
                    <option value="service">Service</option>
                    <option value="training">Training</option>
                    <option value="tour">Tour</option>
                    <option value="event">Event</option>
                    <option value="other">Other</option>
                </select>
            `,
            showCancelButton: true,
            confirmButtonText: 'Check In',
            preConfirm: () => {
                const host = document.getElementById('hostSelect').value;
                const purpose = document.getElementById('purposeSelect').value;
                
                if (!host) {
                    Swal.showValidationMessage('Please select a host');
                    return false;
                }
                
                return { host, purpose };
            }
        });

        if (hostData) {
            completeCheckIn({
                ...checkInData,
                host_employee_id: hostData.host,
                purpose: hostData.purpose
            });
        }
    }

    // Visitor Lookup
    async function lookupVisitor(event) {
        event.preventDefault();
        
        const email = document.getElementById('lookupEmail').value;
        const phone = document.getElementById('lookupPhone').value;
        
        if (!email && !phone) {
            Swal.fire({
                icon: 'warning',
                title: 'Input Required',
                text: 'Please enter either email or phone number'
            });
            return;
        }

        try {
            const response = await fetch('/api/revisit/lookup', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, phone })
            });

            const data = await response.json();

            if (data.success && data.visitors.length > 0) {
                handleDuplicateDetection(data.visitors);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'No Record Found',
                    text: 'No visitor record found. Please register as a new visitor.',
                    confirmButtonText: 'Register Now'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showNewVisitorForm();
                    }
                });
            }
        } catch (error) {
            console.error('Lookup error:', error);
        }
    }

    // Handle Duplicate Detection
    function handleDuplicateDetection(visitors) {
        const visitor = visitors[0]; // Use the most recent record
        
        document.getElementById('duplicateVisitorInfo').innerHTML = `
            <div class="visitor-info-card">
                <h6>${visitor.first_name} ${visitor.last_name}</h6>
                <p><strong>Company:</strong> ${visitor.company}</p>
                <p><strong>Email:</strong> ${visitor.email}</p>
                <p><strong>Phone:</strong> ${visitor.phone}</p>
                <p><strong>Last Visit:</strong> ${new Date(visitor.last_visit_date).toLocaleDateString()}</p>
                <p><strong>Total Visits:</strong> ${visitor.total_visits_count}</p>
                ${visitor.qr_code ? 
                    `<p><strong>QR Code:</strong> ${visitor.qr_code} <span class="badge bg-success">Active</span></p>` : 
                    `<p class="text-warning"><i class="bi bi-exclamation-circle"></i> No QR Code generated yet</p>`
                }
            </div>
        `;
        
        currentVisitorData = visitor;
        
        const modal = new bootstrap.Modal(document.getElementById('duplicateDetectionModal'));
        modal.show();
    }

    // Use Existing Record
    function useExistingRecord() {
        if (currentVisitorData) {
            bootstrap.Modal.getInstance(document.getElementById('duplicateDetectionModal')).hide();
            
            if (currentVisitorData.qr_code) {
                showReturningVisitorInfo(currentVisitorData, {
                    total_visits: currentVisitorData.total_visits_count,
                    last_visit_days: calculateDaysSince(currentVisitorData.last_visit_date)
                });
            } else {
                // Generate QR code for existing visitor
                generateAndSendQRCode(currentVisitorData.visitor_id);
            }
        }
    }

    // Request QR Code via Email
    async function requestQRCode() {
        if (!currentVisitorData) return;
        
        try {
            const response = await fetch('/api/revisit/send-qr', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ visitor_id: currentVisitorData.visitor_id })
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'QR Code Sent!',
                    text: `Your QR code has been sent to ${currentVisitorData.email}`,
                    confirmButtonColor: '#27ae60'
                });
                bootstrap.Modal.getInstance(document.getElementById('duplicateDetectionModal')).hide();
            }
        } catch (error) {
            console.error('QR request error:', error);
        }
    }

    // Generate QR Code on Check-Out
    function generateCheckoutQRCode(visitorData, qrCodeString) {
        const qrContainer = document.getElementById('checkoutQRCode');
        qrContainer.innerHTML = ''; // Clear previous QR code
        
        QRCode.toCanvas(qrCodeString, { 
            width: 200,
            margin: 2,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        }, function (err, canvas) {
            if (err) {
                console.error(err);
                return;
            }
            qrContainer.appendChild(canvas);
        });
        
        document.getElementById('qrCodeText').textContent = qrCodeString;
        
        // Store for download
        window.currentQRCanvas = qrContainer.querySelector('canvas');
    }

    // Download QR Code
    function downloadQRCode() {
        if (window.currentQRCanvas) {
            const link = document.createElement('a');
            link.download = `visitor-qr-${Date.now()}.png`;
            link.href = window.currentQRCanvas.toDataURL();
            link.click();
        }
    }

    // Email QR Code
    async function emailQRCode() {
        if (!currentVisitorData) return;
        
        Swal.fire({
            icon: 'info',
            title: 'Sending Email...',
            text: 'Your QR code is being sent to your email',
            timer: 2000,
            showConfirmButton: false
        });
        
        // API call would go here
        await requestQRCode();
    }

    // Complete Check-In Process
    async function completeCheckIn(checkInData) {
        try {
            const response = await fetch('/api/visits/checkin', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(checkInData)
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Check-In Successful!',
                    html: `
                        <p>Badge Number: <strong>${data.badge_number}</strong></p>
                        <p>Valid Until: ${new Date(data.valid_until).toLocaleString()}</p>
                    `,
                    confirmButtonText: 'Print Badge',
                    confirmButtonColor: '#27ae60'
                });
            }
        } catch (error) {
            console.error('Check-in error:', error);
        }
    }

    // Check-Out with QR Generation
    async function checkOutVisitor(visitId) {
        try {
            const response = await fetch('/api/visits/checkout', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ visit_id: visitId })
            });

            const data = await response.json();

            if (data.success) {
                // Show checkout modal with QR code
                generateCheckoutQRCode(data.visitor, data.qr_code);
                const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
                modal.show();
            }
        } catch (error) {
            console.error('Check-out error:', error);
        }
    }

    // Helper Functions
    function generateHostOptions() {
        // This would be populated from your employee/department data
        return `
            <optgroup label="Admin">
                <option value="ADM001">John Smith - Admin</option>
            </optgroup>
            <optgroup label="Human Resource">
                <option value="HR001">Michelle Thompson - HR</option>
                <option value="HR002">Kevin Harris - HR</option>
            </optgroup>
            <optgroup label="Marketing">
                <option value="MRK001">Andrew Wright - Marketing</option>
            </optgroup>
            <optgroup label="IT Services">
                <option value="ITSD001">James Hall - IT</option>
            </optgroup>
        `;
    }

    function calculateDaysSince(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        const diffTime = Math.abs(today - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    }

    function showNewVisitorForm() {
        // Hide modals and show new visitor registration form
        bootstrap.Modal.getInstance(document.getElementById('checkInOptionsModal'))?.hide();
        bootstrap.Modal.getInstance(document.getElementById('visitorLookupModal'))?.hide();
        // Redirect to new visitor registration
        window.location.href = '/visitor/register';
    }

    function showLookupForm() {
        bootstrap.Modal.getInstance(document.getElementById('checkInOptionsModal')).hide();
        const modal = new bootstrap.Modal(document.getElementById('visitorLookupModal'));
        modal.show();
    }

    function createNewRecord() {
        // Alert about duplicate but proceed with new record
        Swal.fire({
            icon: 'warning',
            title: 'Creating Duplicate Record',
            text: 'A new visitor record will be created. This may result in duplicate entries.',
            showCancelButton: true,
            confirmButtonText: 'Proceed',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showNewVisitorForm();
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Show check-in options when visiting registration page
        if (window.location.pathname === '/visitor/register' || window.location.pathname === '/') {
            const modal = new bootstrap.Modal(document.getElementById('checkInOptionsModal'));
            modal.show();
        }
    });

    // Delivery Personnel Special Handling
    async function handleDeliveryPersonnel(data) {
        // Check for company-wide QR codes for delivery companies
        const deliveryCompanies = ['LBC', 'JRS Express', '2GO', 'Grab', 'Lalamove'];
        
        if (deliveryCompanies.includes(data.company)) {
            // Special handling for delivery personnel
            const response = await fetch('/api/delivery/quick-register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    company: data.company,
                    driver_name: data.first_name + ' ' + data.last_name,
                    phone: data.phone
                })
            });

            if (response.ok) {
                const result = await response.json();
                if (result.existing_qr) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Delivery Personnel Detected',
                        html: `
                            <p>Company QR Code Available: <strong>${result.company_qr}</strong></p>
                            <p>This QR code can be shared with all ${data.company} delivery personnel.</p>
                        `,
                        confirmButtonText: 'Use Company QR'
                    });
                }
            }
        }
    }
    </script>
</body>
</html>