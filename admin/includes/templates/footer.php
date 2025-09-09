      </div>
    </main>
  </div>
</div>

<!-- JavaScript for Admin Panel -->
<script>
function toggleSidebar() {
  const sidebar = document.querySelector('.fixed.inset-y-0');
  sidebar.classList.toggle('-translate-x-full');
}

function toggleDropdown() {
  const dropdown = document.getElementById('admin-dropdown');
  const icon = document.getElementById('dropdown-icon');
  dropdown.classList.toggle('hidden');
  icon.classList.toggle('rotate-180');
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
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

// Confirm delete actions
document.addEventListener('DOMContentLoaded', function() {
  const confirmButtons = document.querySelectorAll('.confirm');
  confirmButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to delete this item?')) {
        e.preventDefault();
      }
    });
  });
});
</script>
</body>
</html>