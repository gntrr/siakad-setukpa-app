<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SIAKAD SETUKPA') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-blue: #1e3a8a;
            --secondary-blue: #3b82f6;
            --gold: #f59e0b;
            --dark-blue: #1e40af;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            min-height: 100vh;
            margin: 0;
        }
        
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.02)" points="0,0 1000,300 1000,1000 0,700"/></svg>');
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            margin: 0 1rem;
        }
        
        .logo-container {
            margin-bottom: 2rem;
        }

        .logo-emblem {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, var(--gold), #fbbf24);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
        }

        .logo-emblem i {
            font-size: 3rem;
            color: var(--primary-blue);
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffffff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gold);
        }
        
        .hero-description {
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn-hero {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.5rem;
            display: inline-block;
            backdrop-filter: blur(10px);
        }
        
        .btn-hero:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .btn-hero.btn-primary {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--primary-blue);
        }
        
        .btn-hero.btn-primary:hover {
            background: #fbbf24;
            border-color: #fbbf24;
            color: var(--primary-blue);
        }
        
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(30, 58, 138, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0;
            z-index: 1000;
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .features-section {
            padding: 4rem 0;
            background: rgba(255, 255, 255, 0.02);
            margin-top: 3rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .feature-card {
            text-align: center;
            padding: 2rem 1rem;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, var(--secondary-blue), var(--primary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.8rem;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: white;
        }

        .feature-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }
        
        @media (max-width: 768px) {
            .hero-content {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .btn-hero {
                padding: 0.8rem 2rem;
                font-size: 0.9rem;
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .logo-emblem {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>

<body>
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <strong class="text-white fs-5">SETUKPA</strong>
                </div>
                
                @if (Route::has('login'))
                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">
                            <i class="fas fa-sign-in-alt me-1"></i>Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="nav-link">
                                <i class="fas fa-user-plus me-1"></i>Daftar
                            </a>
                        @endif
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="logo-container">
                <div class="logo-emblem">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            
            <h1 class="hero-title">SIAKAD SETUKPA</h1>
            <h2 class="hero-subtitle">Sistem Informasi Akademik - Sekolah Pembentukan Perwira</h2>
            <p class="hero-description">
                Lembaga Pendidikan Polri yang bertugas menyelenggarakan pembentukan perwira Polri dari anggota Polri. 
                Membentuk karakter kepemimpinan yang berintegritas, profesional, dan siap mengabdi kepada bangsa dan negara.
            </p>
            
            <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-hero btn-primary">
                        <i class="fas fa-tachometer-alt mr-2"></i>Masuk Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-hero btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk Sistem
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-hero">
                            <i class="fas fa-user-plus me-2"></i>Daftar Akun
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Smooth scrolling animation
        document.addEventListener('DOMContentLoaded', function() {
            // Add entrance animation
            const heroContent = document.querySelector('.hero-content');
            heroContent.style.opacity = '0';
            heroContent.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                heroContent.style.transition = 'all 0.8s ease-out';
                heroContent.style.opacity = '1';
                heroContent.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
        
