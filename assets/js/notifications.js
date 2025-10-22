// notifications.js

// Initialize event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    
    // Attach click handlers to all notification buttons
    const notificationBtns = document.querySelectorAll('.notification-btn');
    notificationBtns.forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.stopPropagation();
            
            // Determine which dropdown to toggle based on screen size
            const isMobile = window.innerWidth < 1200;
            const dropdownId = isMobile ? 'notificationDropdown' : 'notificationDropdownDesktop';
            const dropdown = document.getElementById(dropdownId);
            
            // Close other dropdown if open
            const otherDropdownId = isMobile ? 'notificationDropdownDesktop' : 'notificationDropdown';
            const otherDropdown = document.getElementById(otherDropdownId);
            if (otherDropdown) {
                otherDropdown.classList.remove('show');
            }
            
            // Toggle current dropdown
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        });
    });
    
    // Close notification dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.notification-dropdown');
        const isClickInsideNotification = event.target.closest('.notification-wrapper');
        
        if (!isClickInsideNotification) {
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    // Prevent dropdown from closing when clicking inside it
    document.querySelectorAll('.notification-dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });

    // Mark all as read functionality
    document.querySelectorAll('.mark-all-read').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            
            // Remove all badges
            document.querySelectorAll('.notification-badge').forEach(badge => {
                badge.textContent = '0';
                badge.style.display = 'none';
            });
            
            console.log('All notifications marked as read');
        });
    });

    // Handle individual notification item clicks
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
            console.log('Notification clicked:', this.querySelector('.notification-text').textContent);
        });
    });

    // View all notifications link
    document.querySelectorAll('.view-all-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            console.log('View all notifications');
        });
    });
});