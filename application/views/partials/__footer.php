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
    <script src="<?= base_url('assets/js/kiosk-main.js') ?>"></script>
</body>
</html>