<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    Register
                </div>
                <div class="card-body">
                    <form id="registerForm">
                        <div class="mb-3 mx-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3 mx-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3 mx-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="showPasswordBtn" onclick="togglePassword()">Show</button>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mx-3">
                            <button type="submit" class="btn btn-dark">Register</button>
                        </div>
                    </form>
                    <div class="text-center mt-3 ">
                        <a href="../index.php">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../utility/footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var showPasswordBtn = document.getElementById('showPasswordBtn');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                showPasswordBtn.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                showPasswordBtn.textContent = "Show";
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var username = document.getElementById('username').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            if (!username.trim() || !email.trim() || !password.trim()) {
                alert('Please fill out all fields');
                return;
            }

            register(username, email, password);
        });

        function register(username, email, password) {
            if (!username.trim() || !email.trim() || !password.trim()) {
                alert('Please fill in all fields.');
                return false;
            } else {
                const username = document.getElementById('username').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                const formData = new FormData();
                formData.append("username", username);
                formData.append("email", email);
                formData.append("password", password);
                axios.post('https://siakadlite.000webhostapp.com/database/user/register.php', formData)
                    .then(response => {
                        if (response.data.status == 'success') {
                            alert(response.data.message);
                            window.location.href = '../index.php';
                        } else {
                            alert(response.data.message);
                        }
                    })
                    .catch(error => {
                        alert(error);
                    });
            }
        }
    </script>
</body>

</html>