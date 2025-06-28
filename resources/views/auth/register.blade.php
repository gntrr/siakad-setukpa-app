<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - {{ config('app.name', 'SIAKAD SETUKPA') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --danger-color: #dc2626;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            padding: 1rem;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            opacity: 0.9;
            margin-bottom: 0;
        }

        .register-body {
            padding: 2rem;
        }

        .form-floating {
            margin-bottom: 1.25rem;
        }

        .form-floating > .form-control, 
        .form-floating > .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 0.75rem;
            height: auto;
            transition: all 0.3s ease;
        }

        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .form-floating > label {
            padding: 1rem 0.75rem;
            color: var(--secondary-color);
        }

        .btn-register {
            background: linear-gradient(135deg, var(--success-color) 0%, #047857 100%);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: 100%;
            color: white;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
            color: white;
        }

        .btn-register:disabled {
            opacity: 0.7;
            transform: none;
            box-shadow: none;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .logo-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        .strength-weak { color: var(--danger-color); }
        .strength-medium { color: #f59e0b; }
        .strength-strong { color: var(--success-color); }

        @media (max-width: 576px) {
            .register-container {
                padding: 0.5rem;
            }
            
            .register-header {
                padding: 1.5rem;
            }
            
            .register-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Register Header -->
            <div class="register-header">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1>Create Account</h1>
                <p>Join SIAKAD SETUKPA System</p>
            </div>

            <!-- Register Body -->
            <div class="register-body">
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <!-- Name -->
                    <div class="form-floating">
                        <input id="name" class="form-control @error('name') is-invalid @enderror" 
                               type="text" name="name" value="{{ old('name') }}" 
                               placeholder="Full Name" required autofocus autocomplete="name">
                        <label for="name">Full Name</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="form-floating">
                        <input id="email" class="form-control @error('email') is-invalid @enderror" 
                               type="email" name="email" value="{{ old('email') }}" 
                               placeholder="name@example.com" required autocomplete="username">
                        <label for="email">Email Address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="form-floating">
                        <select id="role" class="form-select @error('role') is-invalid @enderror" 
                                name="role" required>
                            <option value="">Choose Role</option>
                            <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen (Teacher)</option>
                            <option value="manajemen" {{ old('role') == 'manajemen' ? 'selected' : '' }}>Manajemen (Staff)</option>
                        </select>
                        <label for="role">Role</label>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <small class="text-muted">Admin accounts can only be created by existing admins</small>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-floating">
                        <input id="password" class="form-control @error('password') is-invalid @enderror" 
                               type="password" name="password" placeholder="Password" 
                               required autocomplete="new-password">
                        <label for="password">Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-floating">
                        <input id="password_confirmation" class="form-control" 
                               type="password" name="password_confirmation" 
                               placeholder="Confirm Password" required autocomplete="new-password">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="form-text" id="passwordMatch"></div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-register">
                        <span class="btn-text">Create Account</span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Creating...
                        </span>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-link">
                    <p class="mb-0">Already have an account? 
                        <a href="{{ route('login') }}">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center mt-4">
            <small class="text-white-50">
                &copy; {{ date('Y') }} SIAKAD SETUKPA. All rights reserved.
            </small>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Acceptance of Terms</h6>
                    <p>By registering for SIAKAD SETUKPA, you agree to these terms and conditions.</p>
                    
                    <h6>2. User Responsibilities</h6>
                    <ul>
                        <li>Provide accurate and complete information</li>
                        <li>Maintain the security of your account credentials</li>
                        <li>Use the system responsibly and ethically</li>
                        <li>Report any security vulnerabilities or issues</li>
                    </ul>

                    <h6>3. Data Privacy</h6>
                    <p>Your personal information will be handled according to our privacy policy and applicable data protection laws.</p>

                    <h6>4. System Usage</h6>
                    <ul>
                        <li>The system is for academic purposes only</li>
                        <li>Unauthorized access attempts are prohibited</li>
                        <li>Data manipulation or corruption is strictly forbidden</li>
                    </ul>

                    <h6>5. Account Termination</h6>
                    <p>Accounts may be terminated for violation of these terms or suspicious activity.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="acceptTerms()">Accept Terms</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const submitBtn = form.querySelector('button[type="submit"]');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            // Form submission
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
            });

            // Password strength checker
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });

            // Password confirmation checker
            confirmPasswordInput.addEventListener('input', function() {
                checkPasswordMatch();
            });

            passwordInput.addEventListener('input', function() {
                if (confirmPasswordInput.value) {
                    checkPasswordMatch();
                }
            });

            // Re-enable button if form validation fails
            const errors = document.querySelectorAll('.is-invalid');
            if (errors.length > 0) {
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
            }
        });

        function checkPasswordStrength(password) {
            const strengthDiv = document.getElementById('passwordStrength');
            let strength = 0;
            let message = '';
            let className = '';

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            switch (strength) {
                case 0:
                case 1:
                case 2:
                    message = 'Weak password';
                    className = 'strength-weak';
                    break;
                case 3:
                case 4:
                    message = 'Medium password';
                    className = 'strength-medium';
                    break;
                case 5:
                    message = 'Strong password';
                    className = 'strength-strong';
                    break;
            }

            strengthDiv.textContent = password ? message : '';
            strengthDiv.className = `password-strength ${className}`;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('passwordMatch');

            if (confirmPassword) {
                if (password === confirmPassword) {
                    matchDiv.innerHTML = '<small class="text-success"><i class="fas fa-check me-1"></i>Passwords match</small>';
                } else {
                    matchDiv.innerHTML = '<small class="text-danger"><i class="fas fa-times me-1"></i>Passwords do not match</small>';
                }
            } else {
                matchDiv.innerHTML = '';
            }
        }

        function acceptTerms() {
            document.getElementById('terms').checked = true;
            bootstrap.Modal.getInstance(document.getElementById('termsModal')).hide();
        }

        // Handle Enter key in form fields
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const form = document.getElementById('registerForm');
                if (e.target.form === form && e.target.type !== 'checkbox') {
                    const inputs = Array.from(form.querySelectorAll('input, select'));
                    const currentIndex = inputs.indexOf(e.target);
                    const nextInput = inputs[currentIndex + 1];
                    
                    if (nextInput) {
                        nextInput.focus();
                    } else {
                        form.submit();
                    }
                }
            }
        });
    </script>
</body>
</html>
