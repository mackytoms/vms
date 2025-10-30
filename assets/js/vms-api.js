/**
 * VMS API Integration Library
 * This JavaScript file shows how to connect your frontend to the CodeIgniter backend
 * Place this in your frontend assets folder
 */

// API Configuration
const API_BASE_URL = 'http://localhost/vms'; // Update this to your actual URL
const API_ENDPOINTS = {
    // Authentication
    login: '/api/auth/login',
    logout: '/api/auth/logout',
    checkAuth: '/api/auth/check',
    register: '/api/auth/register',
    changePassword: '/api/auth/change-password',
    forgotPassword: '/api/auth/forgot-password',
    resetPassword: '/api/auth/reset-password',
    
    // Visitors
    checkin: '/api/visitors/checkin',
    checkout: '/api/visitors/checkout',
    activeVisitors: '/api/visitors/active',
    todaysVisits: '/api/visitors/today',
    searchVisitors: '/api/visitors/search',
    visitorHistory: '/api/visitors/history',
    uploadPhoto: '/api/visitors/upload-photo',
    uploadSignature: '/api/visitors/upload-signature',
    
    // Schedule
    scheduleVisit: '/api/schedule/create',
    scheduledVisits: '/api/schedule/list',
    approveSchedule: '/api/schedule/approve',
    rejectSchedule: '/api/schedule/reject',
    cancelSchedule: '/api/schedule/cancel',
    
    // Employees
    employees: '/api/employees',
    searchEmployees: '/api/employees/search',
    
    // Reference Data
    purposes: '/api/purposes',
    departments: '/api/departments',
    companies: '/api/companies',
    
    // Admin
    statistics: '/api/admin/statistics',
    settings: '/api/admin/settings',
    
    // Notifications
    notifications: '/api/notifications',
    unreadNotifications: '/api/notifications/unread',
    markNotificationRead: '/api/notifications/mark-read',
    
    // Kiosk
    kioskCheckin: '/api/kiosk/checkin',
    kioskCheckout: '/api/kiosk/checkout',
    findHost: '/api/kiosk/find-host'
};

/**
 * VMS API Client Class
 */
class VMSApi {
    constructor() {
        this.baseUrl = API_BASE_URL;
        this.token = localStorage.getItem('vms_token');
        this.sessionId = localStorage.getItem('vms_session_id');
    }

    /**
     * Make API request
     */
    async request(endpoint, options = {}) {
        const url = this.baseUrl + endpoint;
        
        const defaultHeaders = {
            'Content-Type': 'application/json',
        };
        
        // Add session ID if available
        if (this.sessionId) {
            defaultHeaders['X-Session-ID'] = this.sessionId;
        }
        
        // Add authorization token if available
        if (this.token) {
            defaultHeaders['Authorization'] = `Bearer ${this.token}`;
        }
        
        const config = {
            ...options,
            headers: {
                ...defaultHeaders,
                ...(options.headers || {})
            }
        };
        
        try {
            const response = await fetch(url, config);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || 'Request failed');
            }
            
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    /**
     * Authentication Methods
     */
    async login(username, password, remember = false) {
        const response = await this.request(API_ENDPOINTS.login, {
            method: 'POST',
            body: JSON.stringify({ username, password, remember })
        });
        
        if (response.success && response.data) {
            // Store session info
            if (response.data.session_id) {
                this.sessionId = response.data.session_id;
                localStorage.setItem('vms_session_id', this.sessionId);
            }
            
            if (response.data.remember_token) {
                this.token = response.data.remember_token;
                localStorage.setItem('vms_token', this.token);
            }
            
            // Store user info
            localStorage.setItem('vms_user', JSON.stringify(response.data));
        }
        
        return response;
    }

    async logout() {
        const response = await this.request(API_ENDPOINTS.logout, {
            method: 'POST'
        });
        
        // Clear stored data
        localStorage.removeItem('vms_session_id');
        localStorage.removeItem('vms_token');
        localStorage.removeItem('vms_user');
        
        this.sessionId = null;
        this.token = null;
        
        return response;
    }

    async checkAuth() {
        return await this.request(API_ENDPOINTS.checkAuth);
    }

    async changePassword(currentPassword, newPassword) {
        return await this.request(API_ENDPOINTS.changePassword, {
            method: 'POST',
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword
            })
        });
    }

    /**
     * Visitor Check-in/Check-out Methods
     */
    async checkinVisitor(visitorData) {
        return await this.request(API_ENDPOINTS.checkin, {
            method: 'POST',
            body: JSON.stringify(visitorData)
        });
    }

    async checkoutVisitor(visitId) {
        return await this.request(`${API_ENDPOINTS.checkout}/${visitId}`, {
            method: 'POST'
        });
    }

    async getActiveVisitors() {
        return await this.request(API_ENDPOINTS.activeVisitors);
    }

    async getTodaysVisits() {
        return await this.request(API_ENDPOINTS.todaysVisits);
    }

    async searchVisitors(query) {
        return await this.request(`${API_ENDPOINTS.searchVisitors}?q=${encodeURIComponent(query)}`);
    }

    async getVisitorHistory(visitorId) {
        return await this.request(`${API_ENDPOINTS.visitorHistory}/${visitorId}`);
    }

    /**
     * Photo and Signature Upload
     */
    async uploadPhoto(photoFile) {
        const formData = new FormData();
        formData.append('photo', photoFile);
        
        return await this.request(API_ENDPOINTS.uploadPhoto, {
            method: 'POST',
            headers: {}, // Let browser set Content-Type for FormData
            body: formData
        });
    }

    async uploadSignature(signatureDataUrl) {
        return await this.request(API_ENDPOINTS.uploadSignature, {
            method: 'POST',
            body: JSON.stringify({ signature: signatureDataUrl })
        });
    }

    /**
     * Schedule Methods
     */
    async scheduleVisit(scheduleData) {
        return await this.request(API_ENDPOINTS.scheduleVisit, {
            method: 'POST',
            body: JSON.stringify(scheduleData)
        });
    }

    async getScheduledVisits(date = null) {
        let url = API_ENDPOINTS.scheduledVisits;
        if (date) {
            url += `?date=${date}`;
        }
        return await this.request(url);
    }

    /**
     * Employee Methods
     */
    async getEmployees() {
        return await this.request(API_ENDPOINTS.employees);
    }

    async searchEmployees(query) {
        return await this.request(`${API_ENDPOINTS.searchEmployees}?q=${encodeURIComponent(query)}`);
    }

    /**
     * Reference Data Methods
     */
    async getPurposes() {
        return await this.request(API_ENDPOINTS.purposes);
    }

    async getDepartments() {
        return await this.request(API_ENDPOINTS.departments);
    }

    async getCompanies() {
        return await this.request(API_ENDPOINTS.companies);
    }

    /**
     * Statistics and Reports
     */
    async getStatistics(dateFrom = null, dateTo = null) {
        let url = API_ENDPOINTS.statistics;
        const params = [];
        
        if (dateFrom) params.push(`from=${dateFrom}`);
        if (dateTo) params.push(`to=${dateTo}`);
        
        if (params.length > 0) {
            url += '?' + params.join('&');
        }
        
        return await this.request(url);
    }

    /**
     * Settings Methods
     */
    async getSettings() {
        return await this.request(API_ENDPOINTS.settings);
    }

    async updateSettings(settings) {
        return await this.request(API_ENDPOINTS.settings + '/update', {
            method: 'POST',
            body: JSON.stringify(settings)
        });
    }
}

// ===============================================
// Usage Examples
// ===============================================

// Initialize API client
const vmsApi = new VMSApi();

// Example: Login
async function loginExample() {
    try {
        const response = await vmsApi.login('admin@company.com', 'password', true);
        if (response.success) {
            console.log('Login successful:', response.data);
            // Redirect to dashboard
            window.location.href = '/dashboard';
        }
    } catch (error) {
        console.error('Login failed:', error.message);
        alert('Login failed: ' + error.message);
    }
}

// Example: Check-in a visitor
async function checkinVisitorExample() {
    const visitorData = {
        first_name: 'John',
        last_name: 'Doe',
        email: 'john.doe@example.com',
        phone: '123-456-7890',
        company_name: 'Example Corp',
        host_id: 1, // Employee ID
        purpose_id: 1, // Meeting
        purpose_details: 'Business meeting with Sales team',
        expected_duration: 60, // minutes
        badge_number: 'V001',
        vehicle_number: 'ABC-123',
        items_carried: 'Laptop, Phone',
        floor_access: '3rd Floor'
    };
    
    try {
        const response = await vmsApi.checkinVisitor(visitorData);
        if (response.success) {
            console.log('Visitor checked in:', response.data);
            alert('Check-in successful! Visit Code: ' + response.data.visit_code);
        }
    } catch (error) {
        console.error('Check-in failed:', error.message);
        alert('Check-in failed: ' + error.message);
    }
}

// Example: Get active visitors and display in table
async function displayActiveVisitors() {
    try {
        const response = await vmsApi.getActiveVisitors();
        if (response.success) {
            const visitors = response.data;
            
            // Build table HTML
            let tableHtml = `
                <table class="table">
                    <thead>
                        <tr>
                            <th>Visit Code</th>
                            <th>Visitor Name</th>
                            <th>Company</th>
                            <th>Host</th>
                            <th>Check-in Time</th>
                            <th>Badge #</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            visitors.forEach(visitor => {
                tableHtml += `
                    <tr>
                        <td>${visitor.visit_code}</td>
                        <td>${visitor.visitor_name}</td>
                        <td>${visitor.company || '-'}</td>
                        <td>${visitor.host_name || '-'}</td>
                        <td>${new Date(visitor.check_in_time).toLocaleString()}</td>
                        <td>${visitor.badge_number || '-'}</td>
                        <td>
                            <button onclick="checkoutVisitor(${visitor.id})" class="btn btn-sm btn-danger">
                                Check Out
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            tableHtml += '</tbody></table>';
            
            // Display table
            document.getElementById('active-visitors-table').innerHTML = tableHtml;
        }
    } catch (error) {
        console.error('Failed to load active visitors:', error);
    }
}

// Example: Check out visitor
async function checkoutVisitor(visitId) {
    if (confirm('Are you sure you want to check out this visitor?')) {
        try {
            const response = await vmsApi.checkoutVisitor(visitId);
            if (response.success) {
                alert('Visitor checked out successfully');
                // Refresh the list
                displayActiveVisitors();
            }
        } catch (error) {
            alert('Failed to check out visitor: ' + error.message);
        }
    }
}

// Example: Search visitors with autocomplete
async function setupVisitorSearch() {
    const searchInput = document.getElementById('visitor-search');
    let searchTimeout;
    
    searchInput.addEventListener('input', async (e) => {
        clearTimeout(searchTimeout);
        const query = e.target.value;
        
        if (query.length < 2) {
            document.getElementById('search-results').innerHTML = '';
            return;
        }
        
        searchTimeout = setTimeout(async () => {
            try {
                const response = await vmsApi.searchVisitors(query);
                if (response.success) {
                    displaySearchResults(response.data);
                }
            } catch (error) {
                console.error('Search failed:', error);
            }
        }, 300); // Debounce 300ms
    });
}

// Example: Upload visitor photo from camera
async function captureAndUploadPhoto() {
    const video = document.getElementById('camera-preview');
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    
    // Set canvas size to video size
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Draw video frame to canvas
    context.drawImage(video, 0, 0);
    
    // Convert to blob
    canvas.toBlob(async (blob) => {
        const file = new File([blob], 'visitor_photo.jpg', { type: 'image/jpeg' });
        
        try {
            const response = await vmsApi.uploadPhoto(file);
            if (response.success) {
                console.log('Photo uploaded:', response.data.photo_path);
                // Store photo path for visitor record
                document.getElementById('photo_path').value = response.data.photo_path;
            }
        } catch (error) {
            alert('Failed to upload photo: ' + error.message);
        }
    }, 'image/jpeg', 0.8);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    // Check authentication
    vmsApi.checkAuth().then(response => {
        if (!response.success) {
            // Redirect to login if not authenticated
            window.location.href = '/login';
        }
    }).catch(() => {
        window.location.href = '/login';
    });
    
    // Load initial data
    if (document.getElementById('active-visitors-table')) {
        displayActiveVisitors();
        // Refresh every 30 seconds
        setInterval(displayActiveVisitors, 30000);
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = VMSApi;
}