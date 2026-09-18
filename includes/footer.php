<?php
// ==========================================================
// Campus Connect — Common Footer
// File: includes/footer.php
// Purpose: Renders standard footer, quick links, and scripts
// ==========================================================
$prefix = isset($path_prefix) ? $path_prefix : './';
?>
<!-- Footer Section -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-brand">
                <a href="<?php echo $prefix; ?>index.php" class="brand-logo">
                    <div class="logo-badge">CC</div>
                    <div class="brand-name">Campus <span>Connect</span></div>
                </a>
                <p>
                    <strong>Connect. Participate. Celebrate.</strong><br>
                    Empowering students to discover, engage, and excel across college events and activities.
                </p>
            </div>

            <!-- Quick Navigation -->
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo $prefix; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $prefix; ?>events.php">Browse Events</a></li>
                    <li><a href="<?php echo $prefix; ?>about.php">About Project</a></li>
                    <?php if (function_exists('is_student_logged_in') && is_student_logged_in()): ?>
                        <li><a href="<?php echo $prefix; ?>student/dashboard.php">Student Dashboard</a></li>
                        <li><a href="<?php echo $prefix; ?>student/my_events.php">My Registrations</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo $prefix; ?>login.php">Student Login</a></li>
                        <li><a href="<?php echo $prefix; ?>register.php">Create Account</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Event Categories -->
            <div class="footer-col">
                <h4>Categories</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo $prefix; ?>events.php">Cultural & Arts</a></li>
                    <li><a href="<?php echo $prefix; ?>events.php">Technical & Coding</a></li>
                    <li><a href="<?php echo $prefix; ?>events.php">Sports & Athletics</a></li>
                    <li><a href="<?php echo $prefix; ?>events.php">Industry Workshops</a></li>
                    <li><a href="<?php echo $prefix; ?>events.php">Competitions</a></li>
                </ul>
            </div>

            <!-- Admin & College Info -->
            <div class="footer-col">
                <h4>Administration</h4>
                <ul class="footer-links">
                    <li>
                        <a href="<?php echo $prefix; ?>admin/login.php"
                            style="display: inline-flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Admin Portal
                        </a>
                    </li>
                    <li><a href="<?php echo $prefix; ?>admin/dashboard.php">Admin Dashboard</a></li>
                    <li><span style="color: var(--text-dim); font-size: 0.85rem;">BCA Event Committee</span></li>
                    <li><span style="color: var(--text-dim); font-size: 0.85rem;">Computer Applications Dept.</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="footer-bottom">
            <div>
                © <?php echo date('Y'); ?> Campus Connect.
            </div>
            <div>
                Built with Pure HTML5, CSS3, JavaScript, PHP & MySQL
            </div>
        </div>
    </div>
</footer>

<!-- Interactive JavaScript -->
<script src="<?php echo $prefix; ?>assets/js/script.js"></script>
</body>

</html>