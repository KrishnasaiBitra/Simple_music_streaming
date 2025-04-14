<?php
// Start session at the very top
session_start();

// Include database connection
require_once 'includes/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your existing head content -->
</head>
<body>
    <!-- Your existing HTML content -->

    <!-- Modify the login/register modals to use PHP -->
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login to MeloStream</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="api/auth.php" method="POST">
                        <input type="hidden" name="action" value="login">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" action="api/auth.php" method="POST">
                        <input type="hidden" name="action" value="register">
                        <div class="mb-3">
                            <label for="registerUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="registerUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="registerPassword" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="registerConfirmPassword" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Your existing footer and player bar -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Modified JavaScript to work with PHP backend
        document.addEventListener('DOMContentLoaded', () => {
            // Check if user is logged in from PHP session
            <?php if(isset($_SESSION['user'])): ?>
                currentUser = {
                    id: <?php echo $_SESSION['user']['id']; ?>,
                    username: "<?php echo $_SESSION['user']['username']; ?>",
                    email: "<?php echo $_SESSION['user']['email']; ?>"
                };
                updateUserUI();
            <?php endif; ?>
        });

        // Modified login form handler to use fetch API
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const response = await fetch('api/auth.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Store user data and token
                currentUser = data.user;
                localStorage.setItem('melodystream_token', data.token);
                
                // Update UI
                updateUserUI();
                
                // Close modal
                const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                loginModal.hide();
                
                // Show success message
                showAlert('Login successful!', 'success');
                
                // Reload to update session
                window.location.reload();
            } else {
                showAlert(data.message, 'danger');
            }
        });

        // Similar modifications for register form
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (document.getElementById('registerPassword').value !== 
                document.getElementById('registerConfirmPassword').value) {
                showAlert('Passwords do not match', 'danger');
                return;
            }
            
            const formData = new FormData(e.target);
            const response = await fetch('api/auth.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Store user data and token
                currentUser = data.user;
                localStorage.setItem('melodystream_token', data.token);
                
                // Update UI
                updateUserUI();
                
                // Close modal
                const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                registerModal.hide();
                
                // Show success message
                showAlert('Registration successful!', 'success');
                
                // Reload to update session
                window.location.reload();
            } else {
                showAlert(data.message, 'danger');
            }
        });

        // Rest of your existing JavaScript...
    </script>
</body>
</html>