@section('title')
    Reset Password | MDSJEDPR
@stop

<x-guest-layout>
    @extends('layouts.master2')

    @section('css')
        <!-- Sidemenu-respoansive-tabs css -->
        <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
            rel="stylesheet">

        <!-- Custom Reset Password Styles -->
        <style>
            .reset-container {
                min-height: 100vh;
                background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
            }

            .reset-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="10" cy="80" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grain)"/></svg>');
            }

            .reset-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 20px;
                box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
                padding: 3rem 2.5rem;
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 500px;
            }

            .main-logo {
                font-size: 2.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-align: center;
                margin-bottom: 2rem;
                letter-spacing: 2px;
            }

            .reset-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 1.5rem;
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
            }

            .reset-icon i {
                font-size: 2.5rem;
                color: white;
            }

            .reset-text h2 {
                color: #2d3748;
                font-size: 1.8rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                text-align: center;
            }

            .reset-text p {
                color: #718096;
                font-weight: 400;
                text-align: center;
                margin-bottom: 2rem;
                font-size: 0.95rem;
                line-height: 1.6;
            }

            .form-group {
                margin-bottom: 1.5rem;
                position: relative;
            }

            .form-group label {
                color: #4a5568;
                font-weight: 600;
                margin-bottom: 0.5rem;
                display: block;
            }

            .form-group input {
                width: 100%;
                padding: 12px 16px;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                font-size: 1rem;
                transition: all 0.3s ease;
                background: #fff;
            }

            .form-group input:focus {
                outline: none;
                border-color: #007bff;
                box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
                transform: translateY(-1px);
            }

            .password-strength {
                margin-top: 0.5rem;
                font-size: 0.85rem;
            }

            .strength-bar {
                height: 4px;
                background: #e2e8f0;
                border-radius: 2px;
                margin-top: 0.5rem;
                overflow: hidden;
            }

            .strength-bar-fill {
                height: 100%;
                width: 0%;
                transition: all 0.3s ease;
                border-radius: 2px;
            }

            .btn-reset {
                width: 100%;
                padding: 14px;
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                border: none;
                border-radius: 12px;
                color: white;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-top: 1rem;
                position: relative;
                overflow: hidden;
            }

            .btn-reset::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s ease;
            }

            .btn-reset:hover::before {
                left: 100%;
            }

            .btn-reset:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
            }

            .error-message {
                color: #e53e3e;
                font-size: 0.85rem;
                margin-top: 0.5rem;
                display: block;
            }

            @media (max-width: 768px) {
                .reset-container {
                    padding: 1rem;
                }

                .reset-card {
                    padding: 2rem 1.5rem;
                    margin: 0;
                }

                .main-logo {
                    font-size: 2rem;
                }

                .reset-text h2 {
                    font-size: 1.5rem;
                }
            }

            /* Floating Animation for Background Elements */
            .floating-element {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                animation: floating 6s ease-in-out infinite;
            }

            .floating-element:nth-child(1) {
                width: 80px;
                height: 80px;
                top: 20%;
                left: 10%;
                animation-delay: 0s;
            }

            .floating-element:nth-child(2) {
                width: 60px;
                height: 60px;
                top: 60%;
                right: 15%;
                animation-delay: 2s;
            }

            .floating-element:nth-child(3) {
                width: 40px;
                height: 40px;
                bottom: 20%;
                left: 20%;
                animation-delay: 4s;
            }

            @keyframes floating {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
        </style>
    @endsection

    @section('content')
        <div class="reset-container">
            <!-- Floating Background Elements -->
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>

            <div class="reset-card">
                <!-- Brand Name -->
                <h1 class="main-logo">MDSJEDPR</h1>

                <!-- Icon -->
                <div class="reset-icon">
                    <i class="fas fa-lock"></i>
                </div>

                <!-- Reset Password Text -->
                <div class="reset-text">
                    <h2>Reset Password</h2>
                    <p>Enter your new password below. Make sure it's strong and secure!</p>
                </div>

                <!-- Reset Password Form -->
                <form action="{{ route('password.store') }}" method="POST">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Field -->
                    <div class="form-group">
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email"
                                      type="email"
                                      name="email"
                                      :value="old('email', $request->email)"
                                      required
                                      autofocus
                                      autocomplete="username"
                                      placeholder="Enter your email" />
                        <x-input-error :messages="$errors->get('email')" class="error-message" />
                    </div>

                    <!-- New Password Field -->
                    <div class="form-group">
                        <x-input-label for="password" :value="__('New Password')" />
                        <x-text-input id="password"
                                      type="password"
                                      name="password"
                                      required
                                      autocomplete="new-password"
                                      placeholder="Enter your new password" />
                        <div class="strength-bar">
                            <div class="strength-bar-fill" id="strengthBar"></div>
                        </div>
                        <div class="password-strength" id="strengthText"></div>
                        <x-input-error :messages="$errors->get('password')" class="error-message" />
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation"
                                      type="password"
                                      name="password_confirmation"
                                      required
                                      autocomplete="new-password"
                                      placeholder="Confirm your new password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
                    </div>

                    <!-- Reset Button -->
                    <button type="submit" class="btn-reset">
                        <span><i class="fas fa-check-circle"></i> Reset Password</span>
                    </button>
                </form>
            </div>
        </div>
    @endsection

    @section('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                const strengthBar = document.getElementById('strengthBar');
                const strengthText = document.getElementById('strengthText');

                // Password strength checker
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    let text = '';
                    let color = '';

                    if (password.length >= 8) strength++;
                    if (password.match(/[a-z]/)) strength++;
                    if (password.match(/[A-Z]/)) strength++;
                    if (password.match(/[0-9]/)) strength++;
                    if (password.match(/[^a-zA-Z0-9]/)) strength++;

                    switch(strength) {
                        case 0:
                        case 1:
                            text = 'Weak password';
                            color = '#e53e3e';
                            strengthBar.style.width = '20%';
                            break;
                        case 2:
                            text = 'Fair password';
                            color = '#f59e0b';
                            strengthBar.style.width = '40%';
                            break;
                        case 3:
                            text = 'Good password';
                            color = '#3b82f6';
                            strengthBar.style.width = '60%';
                            break;
                        case 4:
                            text = 'Strong password';
                            color = '#10b981';
                            strengthBar.style.width = '80%';
                            break;
                        case 5:
                            text = 'Very strong password';
                            color = '#059669';
                            strengthBar.style.width = '100%';
                            break;
                    }

                    strengthBar.style.backgroundColor = color;
                    strengthText.textContent = text;
                    strengthText.style.color = color;
                });

                // Add floating animation to form inputs
                const inputs = document.querySelectorAll('input[type="password"], input[type="email"]');
                inputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        this.parentElement.style.transform = 'scale(1.02)';
                    });

                    input.addEventListener('blur', function() {
                        this.parentElement.style.transform = 'scale(1)';
                    });
                });

                // Add ripple effect to button
                const resetBtn = document.querySelector('.btn-reset');
                resetBtn.addEventListener('click', function(e) {
                    let ripple = document.createElement('span');
                    let rect = this.getBoundingClientRect();
                    let size = Math.max(rect.width, rect.height);
                    let x = e.clientX - rect.left - size / 2;
                    let y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        </script>

        <style>
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }

            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        </style>
    @endsection
</x-guest-layout>
