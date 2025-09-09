                </div>
            <?php
            // Close conditional layout structure
            $currentPage = basename($_SERVER['PHP_SELF']);
            $showSidebar = ($currentPage === 'index.php' || $currentPage === 'categories.php');
            if ($showSidebar): ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Modern Footer with Gradient -->
        <footer class="bg-gradient-to-r from-gray-800 via-gray-900 to-black text-white mt-16 relative overflow-hidden">
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center">
                    <div class="border-t border-gray-700 pt-6">
                        <p class="text-gray-400">&copy; 2025 eCommerce Store. All rights reserved for this custom build.</p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- JavaScript for dropdown functionality -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown functionality
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                const button = dropdown.querySelector('button');
                const menu = dropdown.querySelector('.dropdown-menu');

                button.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!dropdown.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });

            // Auto-hide alerts
            setTimeout(function() {
                var alerts = document.querySelectorAll('.auto-hide');
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = 0;
                    setTimeout(function() {
                        if(alert.parentNode) alert.parentNode.removeChild(alert);
                    }, 500);
                });
            }, 3000);
        });
        </script>
    </body>
</html>