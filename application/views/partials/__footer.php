</div> <!-- End of main-content container -->
    
    <!-- Footer -->
    <footer class="footbar bg-light text-center py-3">
        <div class="container">
            <p class="mb-0 text-dark">
                Copyright &copy; <?php echo date('Y'); ?> Tom's World Philippines
            </p>
        </div>

    </footer>

    <!-- Updated HTML -->
    <a href="#" class="back-to-top">
        <i class="fa fa-arrow-up"></i>
    </a>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Template Main JS File -->
    <script src="<?= base_url('assets/js/main.js') ?>"></script>

    <!-- Custom JS (optional) -->
    <script>
        // Add any custom JavaScript here
        console.log('HURIS System Loaded');

        // Helper functions
        const select = (selector) => document.querySelector(selector);

        // Back to top button
        let backtotop = select('.back-to-top');
        if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY > 50) {
            backtotop.classList.add('active');
            } else {
            backtotop.classList.remove('active');
            }
        }
        window.addEventListener('load', toggleBacktotop);
        window.addEventListener('scroll', toggleBacktotop);
        }
    </script>
</body>
</html>