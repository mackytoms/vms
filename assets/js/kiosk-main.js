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
        companyName: "Welcome to TOMS WORLD",
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
        qrCodeNote: "Save this QR code for faster check-in on your next visit",
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
            "Your host will come to receive you shortly",
            "Check your email for your QR code for next visit"
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

// Update step indicator
function updateStepIndicator(step) {
    const actualStep = currentFlow.length > 0 ? currentFlowIndex + 1 : step;
    document.querySelectorAll('.step-dot').forEach((dot, index) => {
        dot.classList.remove('active', 'completed');
        if (index + 1 < actualStep) dot.classList.add('completed');
        else if (index + 1 === actualStep) dot.classList.add('active');
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
    visitor.qrCode = generateVisitorQRData(visitor);
    
    visitors.unshift(visitor);
    if (visitors.length > 100) visitors.splice(100);
    
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(visitors));
    } catch (e) {
        console.error('Storage error:', e);
        cleanupOldPhotos();
    }
    
    return visitor;
}

function cleanupOldPhotos() {
    const keys = Object.keys(localStorage);
    const photoKeys = keys.filter(k => k.startsWith('visitor_photo_'));
    const thirtyDaysAgo = Date.now() - (30 * 24 * 60 * 60 * 1000);
    
    photoKeys.forEach(key => {
        const id = key.replace('visitor_photo_', '');
        if (parseInt(id) < thirtyDaysAgo) {
            localStorage.removeItem(key);
        }
    });
}

function generateVisitorQRData(visitor) {
    const qrData = {
        id: visitor.id || Date.now(),
        firstName: visitor.firstName,
        lastName: visitor.lastName,
        email: visitor.email,
        company: visitor.company,
        phone: visitor.phone,
        timestamp: new Date().toISOString()
    };
    
    if (visitor.photo) {
        try {
            localStorage.setItem(`visitor_photo_${qrData.id}`, visitor.photo);
        } catch (e) {
            console.warn('Could not store photo in localStorage:', e);
        }
    }
    
    return JSON.stringify(qrData);
}

// Complete check-in
function completeCheckIn() {
    showLoading();
    
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
        
        const qrContainer = document.getElementById('qrcode');
        generateQRCodeAlternative(qrContainer, storedVisitor.qrCode);
        
        showScreen(8);
        startCountdown();
        
        console.log('Check-in complete:', storedVisitor);
    }, 2000);
}

function generateQRCodeAlternative(container, text) {
    container.innerHTML = '';
    
    if (text.length > 500) {
        console.warn('QR code data is very large:', text.length, 'characters');
        const simplified = {
            id: visitorData.id || Date.now(),
            email: visitorData.email,
            name: `${visitorData.firstName} ${visitorData.lastName}`
        };
        text = JSON.stringify(simplified);
    }
    
    const canvas = document.createElement('canvas');
    canvas.width = 200;
    canvas.height = 200;
    container.appendChild(canvas);
    
    try {
        QrCreator.render({
            text: text,
            radius: 0.0,
            ecLevel: 'L',
            fill: '#000000',
            background: '#ffffff',
            size: 200
        }, canvas);
        
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

// Prevent context menu (right-click) for kiosk mode
// document.addEventListener('contextmenu', e => e.preventDefault());