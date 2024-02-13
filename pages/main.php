<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
    <!-- data tables -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- export excel pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <!-- Logout Button -->
                <li class="nav-item">
                    <a class="nav-link" role="button">
                        <span class="pe-2" id="namaUser">Nama</span>
                        <i class="fas fa-sign-out-alt" onclick="logout()"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light">Dashboard</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="showDashboard()">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <!-- Mahasiswa Link -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="showMahasiswa()">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Mahasiswa
                                </p>
                            </a>
                        </li>
                        <!-- Profile Link -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="showProfile()">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Profile
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" id="content-wrapper">

        </div>
        <!-- /.content-wrapper -->
        <?php include '../utility/footer.php' ?>

    </div>

    <!-- ./wrapper -->



    <script>
        showDashboard();
        // showMahasiswa();
        // showProfile();
        setUser();
        var currentPage = 1;
        var totalRecords = 0;

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                updateTableData();
            }
        }

        function nextPage() {
            if ((currentPage * 10) < totalRecords) {
                currentPage++;
                updateTableData();
            }
        }

        function updateTableData() {
            var table = $('#mahasiswaTable').DataTable();
            table.ajax.url('../database/mahasiswa/read.php' + (currentPage - 1) * 10).load();
            $('#prevButton').prop('disabled', currentPage === 1);
            $('#nextButton').prop('disabled', (currentPage * 10) >= totalRecords);
        }

        function showDashboard() {
            $.get('menu/dashboard.html', function(data) {
                $('#content-wrapper').html(data);
            });
            const formData = new FormData();
            formData.append('userid', sessionStorage.getItem('userid'));
            axios.post('https://siakadlite.000webhostapp.com/database/mahasiswa/read.php', formData).then(response => {
                if (response.data.status === 'success') {
                    const jumlahMahasiswa = document.getElementById('jumlahMahasiswa');
                    if (jumlahMahasiswa) {
                        jumlahMahasiswa.textContent = response.data.mahasiswa.length;
                    }
                } else {
                    alert(response.data.message);
                }
            }).catch(error => {
                alert(response.data.message);
            });
        }

        function showMahasiswa() {
            $(document).ready(function() {
                $.get('menu/mahasiswa.html', function(data) {
                    $('#content-wrapper').html(data);
                    // Inisialisasi DataTables di luar permintaan Ajax
                    loadTable(data);
                });
            });
        }

        function loadTable(data) {
            var table = $('#mahasiswaTable');
            table.DataTable().destroy();

            table = $('#mahasiswaTable').DataTable({
                columns: [{
                        data: 'id'
                    }, // Kolom nomor
                    {
                        data: 'nama'
                    }, // Kolom nama
                    {
                        data: 'npm'
                    }, // Kolom NPM
                    {
                        data: 'kelas'
                    }, // Kolom kelas
                    { // Kolom gambar
                        data: 'gambar',
                        render: function(data, type, row) {
                            return '<img src="../upload/' + data + '" alt="image" style="max-width: 100px; max-height: 100px;">';
                        }
                    },
                    { // Kolom action
                        data: null,
                        render: function(data, type, row) {
                            var parameters = row.id + ',' + row.nama + ',' + row.npm + ',' + row.kelas + ',' + row.gambar;
                            return '<button type="button" class="btn btn-primary me-2" onclick="editMahasiswa(\'' + parameters + '\')">Edit</button>' +
                                '<button type="button" class="btn btn-danger" onclick="deleteMahasiswa(' + row.id + ')">Hapus</button>';

                        }
                    }
                ]
            });
            table.clear();
            const formData = new FormData();
            formData.append('userid', sessionStorage.getItem('userid'));
            axios.post('https://siakadlite.000webhostapp.com/database/mahasiswa/read.php', formData)
                .then(function(response) {
                    if (response.data.status === 'success') {
                        var mahasiswaData = response.data.mahasiswa;
                        mahasiswaData.forEach(function(mahasiswa) {
                            table.row.add(mahasiswa).draw();
                        });
                    } else {
                        alert(response.data.message);
                    }
                })
                .catch(function(error) {
                    alert(error);
                });
            table.draw();
        }

        function showProfile() {
            $.get('menu/profile.html', function(data) {
                $('#content-wrapper').html(data);
            });
            setUserProfile();
        }


        function togglePassword(inputId) {
            var passwordInput = document.getElementById(inputId);
            var toggleButton = document.querySelector('[onclick="togglePassword(\'' + inputId + '\')"]');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleButton.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                toggleButton.textContent = "Show";
            }
        }

        function validateProfileForm() {
            var currentPassword = document.getElementById('currentPassword').value;
            var newPassword = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;

            if (!currentPassword.trim() || !newPassword.trim() || !confirmPassword.trim()) {
                alert('Please fill in all fields.');
                return false;
            }

            if (newPassword !== confirmPassword) {
                alert('New password and confirm password do not match.');
                return false;
            }

            return true;
        }

        function logout() {
            if (confirm('Apakah anda yakin ingin logout?')) {
                const session = sessionStorage.getItem("session");
                const formData = new FormData();
                formData.append("session", session);
                axios.post('https://siakadlite.000webhostapp.com/database/user/logout.php', formData)
                    .then(response => {
                        if (response.data.status == 'success') {
                            alert(response.data.message);
                            sessionStorage.removeItem('session');
                            sessionStorage.removeItem("nama");
                            sessionStorage.removeItem("userid");
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

        function setUser() {
            const formData = new FormData();
            formData.append('session', sessionStorage.getItem('session'));
            axios.post('https://siakadlite.000webhostapp.com/database/user/get.php', formData).then(response => {
                if (response.data.status === 'success') {
                    const user = response.data.user;
                    if (user.nama === null) {
                        sessionStorage.setItem("nama", user.username);
                    } else {
                        sessionStorage.setItem("nama", user.nama);
                    }
                    const name = sessionStorage.getItem('nama');
                    sessionStorage.setItem('userid', user.id);
                    const namaUserElement = document.getElementById('namaUser');
                    if (namaUserElement) {
                        namaUserElement.textContent = `Selamat datang, ${name}`;
                    }
                } else {
                    alert(response.data.message);
                }
            }).catch(error => {
                alert(error);
            });
        }

        function setUserProfile() {
            const formData = new FormData();
            formData.append('session', sessionStorage.getItem('session'));
            axios.post('https://siakadlite.000webhostapp.com/database/user/get.php', formData).then(response => {
                if (response.data.status === 'success') {
                    const user = response.data.user;
                    const emailElementField = document.getElementById('currentEmail');
                    const nameElementField = document.getElementById('currentName');
                    if (emailElementField || nameElementField) {
                        emailElementField.value = user.email;
                        nameElementField.value = user.nama;
                    }
                } else {
                    alert(response.data.message);
                }
            }).catch(error => {
                alert(error);
            });
        }


        function previewImage(input) {
            var preview = document.getElementById('preview');
            var file = input.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
                preview.style.display = 'none';
            }
        }

        function uploadMahasiswa() {
            const field = document.getElementById('fieldIdMahasiswa').innerText;
            const nama = document.getElementById('nama').value;
            const npm = document.getElementById('npm').value;
            const kelas = document.getElementById('kelas').value;
            const gambarInput = document.getElementById('gambar');

            if (!nama || !npm || !kelas) {
                return;
            }

            const gambar = gambarInput.files[0];
            const formData = new FormData();
            formData.append("nama", nama);
            formData.append("npm", npm);
            formData.append("kelas", kelas);
            formData.append("gambar", gambar);
            formData.append("userid", sessionStorage.getItem('userid'));

            $update = false;

            if (field) {
                if (confirm(`Apakah anda ingin merubah data dengan id : ${field}`)) {
                    $update = true;
                    formData.append("id", field);
                } else {
                    $update = false;
                    batalEditMahasiswa();
                }
            }

            $post = '../database/mahasiswa/create.php';
            if ($update) {
                $post = '../database/mahasiswa/update.php';
            }
            axios.post($post, formData, {
                    headers: {
                        'Content-type': 'multipart/form-data',
                    }
                })
                .then(response => {
                    if (response.data.status === 'success') {
                        alert(response.data.mesage);
                        loadTable();
                        batalEditMahasiswa();
                    } else {
                        alert(response.data.mesage);
                    }
                })
                .catch(error => alert(error));
        }

        function editMahasiswa(parameterString) {
            const button = document.getElementById('batalEdit');
            const field = document.getElementById('fieldIdMahasiswa');
            button.style.display = '';
            var parameters = parameterString.split(',');
            var id = parameters[0];
            var nama = parameters[1];
            var npm = parameters[2];
            var kelas = parameters[3];
            var gambar = parameters[4];

            const namaMahasiswa = document.getElementById('nama');
            const npmMahasiswa = document.getElementById('npm');
            const kelasMahasiswa = document.getElementById('kelas');
            const gambarMahasiswa = document.getElementById('preview');
            if (namaMahasiswa || npmMahasiswa || kelasMahasiswa || gambarMahasiswa) {
                field.textContent = id;
                namaMahasiswa.value = nama;
                npmMahasiswa.value = npm;
                kelasMahasiswa.value = kelas;
                gambarMahasiswa.src = `../upload/${gambar}`;
                gambarMahasiswa.style.display = 'block';
            }
            $('html, body').animate({
                scrollTop: $("#wrapper").offset().top
            }, 100);
        }

        function batalEditMahasiswa() {
            const button = document.getElementById('batalEdit');
            const field = document.getElementById('fieldIdMahasiswa');
            button.style.display = 'none';

            const namaMahasiswa = document.getElementById('nama');
            const npmMahasiswa = document.getElementById('npm');
            const kelasMahasiswa = document.getElementById('kelas');
            const gambarMahasiswa = document.getElementById('preview');
            if (namaMahasiswa || npmMahasiswa || kelasMahasiswa || gambarMahasiswa) {
                field.textContent = '';
                namaMahasiswa.value = '';
                npmMahasiswa.value = '';
                kelasMahasiswa.value = '';
                gambarMahasiswa.src = '';
                gambarMahasiswa.style.display = 'none';
            }
        }

        function deleteMahasiswa(item) {
            const formData = new FormData();
            formData.append("id", item);
            if (confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')) {
                axios.post('https://siakadlite.000webhostapp.com/database/mahasiswa/delete.php', formData)
                    .then(response => {
                        if (response.data.status === 'success') {
                            alert(response.data.mesage);
                            loadTable();
                        } else {
                            alert(response.data.mesage);
                        }
                    })
                    .catch(error => alert(error));
            }
        }

        function changeEmail() {
            var email = document.getElementById('newEmail').value;
            var currentPassword = document.getElementById('currentPassword1').value;

            const formData = new FormData();
            formData.append('session', sessionStorage.getItem('session'));
            formData.append('email', email);
            formData.append('currentPassword', currentPassword);
            if (currentPassword === "") {
                alert('Harap masukkan password lama');
            } else {
                axios.post('https://siakadlite.000webhostapp.com/database/user/update.php', formData).then(response => {
                    if (response.data.status === 'success') {
                        alert(response.data.message);
                        showProfile();
                    } else {
                        alert(response.data.message);
                    }
                }).catch(error => {
                    alert(error);
                });
            }
        }

        function changeName() {
            var name = document.getElementById('newName').value;
            var currentPassword = document.getElementById('currentPassword2').value;

            const formData = new FormData();
            formData.append('session', sessionStorage.getItem('session'));
            formData.append('name', name);
            formData.append('currentPassword', currentPassword);
            if (currentPassword === "") {
                alert('Harap masukkan password lama');
            } else {
                axios.post('https://siakadlite.000webhostapp.com/database/user/update.php', formData).then(response => {
                    if (response.data.status === 'success') {
                        alert(response.data.message);
                        setUser();
                        showProfile();
                    } else {
                        alert(response.data.message);
                    }
                }).catch(error => {
                    alert(error);
                });
            }
        }

        function changePassword() {
            var newPassword = document.getElementById('newPassword').value;
            var currentPassword = document.getElementById('currentPassword0').value;

            const formData = new FormData();
            formData.append('session', sessionStorage.getItem('session'));
            formData.append('newPassword', newPassword);
            formData.append('currentPassword', currentPassword);
            if (currentPassword === "") {
                alert('Harap masukkan password lama');
            } else {
                axios.post('https://siakadlite.000webhostapp.com/database/user/update.php', formData).then(response => {
                    if (response.data.status === 'success') {
                        alert(response.data.message);
                        showProfile();
                    } else {
                        alert(response.data.message);
                    }
                }).catch(error => {
                    alert(error);
                });
            }
        }

        function deleteAccount() {
            const currentPassword = document.getElementById('currentPassword').value;
            const formData = new FormData();
            formData.append("currentPassword", currentPassword);
            formData.append("session", sessionStorage.getItem('session'));
            if (confirm('Apakah Anda yakin ingin menghapus akun ini?')) {
                axios.post('https://siakadlite.000webhostapp.com/database/user/delete.php', formData)
                    .then(response => {
                        if (response.data.status === 'success') {
                            alert(response.data.mesage);
                            window.location.href = '../index.php';
                        } else {
                            alert(response.data.mesage);
                        }
                    })
                    .catch(error => alert(error));
            }
        }

        function exportExcel() {
            var userid = sessionStorage.getItem('userid');
            const fd = new FormData();
            fd.append('userid', userid);
            axios.post('https://siakadlite.000webhostapp.com/database/mahasiswa/read.php', fd)
                .then(response => {
                    if (response.data.status === 'success') {
                        const data = response.data.mahasiswa;

                        const exportData = data.map(data => {
                            return [data.id, data.nama, data.npm, data.kelas, data.gambar];
                        });

                        const ws = XLSX.utils.aoa_to_sheet([
                            ['ID', 'Nama', 'NPM', 'Kelas', 'Nama Gambar'],
                            ...exportData
                        ]);

                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

                        XLSX.writeFile(wb, "data.xlsx");
                    } else {
                        alert(response.data.mesage);
                    }
                })
                .catch(error => alert(error));
        }

        function exportPdf() {
            var userid = sessionStorage.getItem('userid');
            const fd = new FormData();
            fd.append('userid', userid);
            axios.post('https://siakadlite.000webhostapp.com/database/mahasiswa/read.php', fd)
                .then(response => {
                    if (response.data.status === 'success') {
                        const data = response.data.mahasiswa;
                        const exportData = data.map(data => {
                            return [data.id, data.nama, data.npm, data.kelas, data.gambar];
                        });

                        const table = document.createElement('table');
                        table.classList.add('table');

                        const tableHead = document.createElement('thead');
                        const headRow = document.createElement('tr');
                        const headers = ['ID', 'Nama', 'NPM', 'Kelas', 'Nama Gambar'];
                        headers.forEach(headerText => {
                            const headCell = document.createElement('th');
                            headCell.textContent = headerText;
                            headRow.appendChild(headCell);
                        });
                        tableHead.appendChild(headRow);
                        table.appendChild(tableHead);

                        const tableBody = document.createElement('tbody');
                        exportData.forEach(data => {
                            const row = document.createElement('tr');
                            Object.values(data).forEach(val => {
                                const cell = document.createElement('td');
                                cell.textContent = val;
                                row.appendChild(cell);
                            });
                            tableBody.appendChild(row);
                        });
                        table.appendChild(tableBody);

                        const opt = {
                            margin: 1,
                            filename: 'data.pdf',
                            image: {
                                type: 'jpeg',
                                quality: 0.98
                            },
                            html2canvas: {
                                scale: 3
                            },
                            jsPDF: {
                                unit: 'in',
                                format: 'letter',
                                orientation: 'landscape'
                            }
                        };

                        html2pdf().from(table).set(opt).save();
                    } else {
                        alert(response.data.message);
                    }
                })
                .catch(error => alert(error));
        }

        function toggleInputMahasiswaCardBody() {
            var inputMahasiswaCardBody = document.getElementById('inputMahasiswaCardBody');
            var iconToggle = document.getElementById('iconToggle');

            if (inputMahasiswaCardBody.style.display === 'none') {
                inputMahasiswaCardBody.style.display = 'block';
                iconToggle.classList.remove('fa-plus');
                iconToggle.classList.add('fa-minus');
            } else {
                inputMahasiswaCardBody.style.display = 'none';
                iconToggle.classList.remove('fa-minus');
                iconToggle.classList.add('fa-plus');
            }
        }
    </script>
</body>