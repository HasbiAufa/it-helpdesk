<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { background-color: #f4f6f9; }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 text-center">
                        
                        <div class="mb-3">
                            <i class="bi bi-shield-lock-fill text-primary" style="font-size: 4rem;"></i>
                        </div>
                        
                        <h4 class="fw-bold mb-4">Login Admin IT</h4>

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show text-start" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('cekLogin') }}" method="POST">
                            @csrf 
                            
                            <div class="mb-4 text-start">
                                <label for="pin" class="form-label text-muted fw-semibold small">PIN Akses Rahasia</label>
                                
                                <div class="position-relative">
                                    <input type="password" name="pin" id="pin" class="form-control form-control-lg bg-light pe-5" placeholder="Masukkan PIN..." required autofocus>
                                    
                                    <span id="btnTogglePin" class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d; z-index: 10;">
                                        <i class="bi bi-eye-slash fs-5" id="iconMata"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                                Masuk Sistem <i class="bi bi-box-arrow-in-right ms-2"></i>
                            </button>
                        </form>

                    </div>
                </div>

                <div class="text-center mt-4 text-muted small">
                    &copy; 2026 Sistem IT Helpdesk<br>
                    RS Syarif Hidayatullah
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // 1. SCRIPT FITUR TOMBOL MATA (SHOW/HIDE PIN)
            const pinInput = document.getElementById('pin');
            const btnTogglePin = document.getElementById('btnTogglePin');
            const iconMata = document.getElementById('iconMata');

            btnTogglePin.addEventListener('click', function() {
                // Ngecek tipe inputannya, kalo password ganti text, dan sebaliknya
                if (pinInput.type === 'password') {
                    pinInput.type = 'text';
                    iconMata.classList.remove('bi-eye-slash');
                    iconMata.classList.add('bi-eye');
                } else {
                    pinInput.type = 'password';
                    iconMata.classList.remove('bi-eye');
                    iconMata.classList.add('bi-eye-slash');
                }
            });

            // 2. SCRIPT POP-UP SWEETALERT KALAU LOGIN BERHASIL
            @if(session('login_success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Login Berhasil!',
                    text: '{{ session('login_success') }}',
                    showConfirmButton: false,
                    timer: 2000, // Durasi pop-up muncul (2 detik)
                    timerProgressBar: true,
                    allowOutsideClick: false // Biar ga bisa diklik sembarangan pas loading
                }).then(() => {
                    // Setelah 2 detik, baru arahin otomatis ke dashboard
                    window.location.href = "{{ route('dashboard') }}";
                });
            @endif

        });
    </script>
</body>
</html>