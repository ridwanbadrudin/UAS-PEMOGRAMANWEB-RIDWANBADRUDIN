<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    Login
                </div>
                <div class="card-body">
                    <form id="loginForm">
                        <div class="mb-3 mx-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3 mx-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="showPasswordBtn" onclick="togglePassword()">Show</button>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mx-3">
                            <button type="submit" class="btn btn-dark">Login</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="pages/register.php">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'utility/footer.php' ?>
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

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            if (!username.trim() || !password.trim()) {
                alert('Please enter both username and password');
                return;
            }

            login(username, password);
        });

        function login(username, password) {
            if (!username.trim() || !password.trim()) {
                alert('Please fill in all fields.');
                return false;
            } else {
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                const formData = new FormData();
                formData.append("username", username);
                formData.append("password", password);
                axios.post('https://siakadlite.000webhostapp.com/database/user/login.php', formData)
                    .then(response => {
                        if (response.data.status == 'success') {
                            sessionStorage.setItem('session', response.data.session);
                            window.location.href = 'pages/main.php';
                            alert(response.data.message);
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