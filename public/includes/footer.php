    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p style="margin-bottom: 1rem;">Narayana Hotel is a premium beach resort located in the beautiful Karimunjawa Islands, offering world-class hospitality and unforgettable experiences.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?= BASE_URL ?>">Home</a></li>
                        <li><a href="<?= BASE_URL ?>/rooms.php">Room Types</a></li>
                        <li><a href="<?= BASE_URL ?>/booking.php">Book Now</a></li>
                        <li><a href="<?= BASE_URL ?>/contact.php">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Information</h3>
                    <ul>
                        <li><i class="fas fa-phone"></i> <?= htmlspecialchars(BUSINESS_PHONE) ?></li>
                        <li><i class="fas fa-envelope"></i> <?= htmlspecialchars(BUSINESS_EMAIL) ?></li>
                        <li><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars(BUSINESS_ADDRESS) ?></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <ul>
                        <li><a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i> Twitter</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME) ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <?php if (!empty($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
        <script src="<?= BASE_URL ?>/assets/js/<?= htmlspecialchars($js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>
        feather.replace();
        
        // Show Notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type}`;
            notification.textContent = message;
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.minWidth = '300px';
            notification.style.zIndex = '9999';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
        
        // API Call Helper
        function apiCall(url, method = 'GET', data = null) {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            
            if (data && method !== 'GET') {
                options.body = JSON.stringify(data);
            }
            
            return fetch(url, options).then(response => response.json());
        }
        
        // Format Currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }
        
        // Format Date
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        
        // Email Validation
        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
        
        // Phone Validation
        function isValidPhone(phone) {
            return /^(\+62|0)[0-9]{9,12}$/.test(phone.replace(/\s/g, ''));
        }
    </script>
</body>
</html>
