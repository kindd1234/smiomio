<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>smiomio — Premium Facebook Scheduling</title>

    <!-- Google Font: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ["Plus Jakarta Sans", "sans-serif"] },
                extend: {
                    colors: {
                        brand: "#4F46E5",
                        brandLight: "#818CF8",
                        brandDark: "#312E81",
                        premiumBg: "#0D0D15",
                        premiumCard: "rgba(255,255,255,0.05)"
                    }
                }
            }
        };
    </script>

    <style>
        body {
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        /* Animated gradient for hero background */
        .animated-bg {
            background: linear-gradient(135deg, #4F46E5, #818CF8, #312E81);
            background-size: 300% 300%;
            animation: gradientShift 12s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating blob animation */
        .blob {
            animation: blobMove 10s infinite ease-in-out;
        }

        @keyframes blobMove {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -20px) scale(1.25); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.25s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>

<body class="bg-premiumBg text-gray-200 font-sans">

    <!-- NAVBAR -->
    <nav class="w-full px-6 md:px-10 py-4 flex justify-between items-center bg-white/5 backdrop-blur-md border-b border-white/10">
        <div class="text-xl font-extrabold text-white">smiomio</div>

        <div class="flex gap-4">
            <button onclick="openLogin()" 
                class="px-4 py-2 text-sm bg-black/20 border border-white/20 rounded-lg text-white hover:bg-black/10 transition">
                Login
            </button>
            <button onclick="openRegister()" 
                class="px-4 py-2 text-sm bg-brand text-white rounded-lg hover:bg-brandLight transition">
                Register
            </button>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="relative text-center px-6 py-24 md:py-32 overflow-hidden">

        <div class="absolute top-10 left-10 w-52 h-52 bg-white/10 rounded-full blur-3xl blob"></div>
        <div class="absolute bottom-10 right-10 w-72 h-72 bg-white/5 rounded-full blur-3xl blob"></div>

        <div class="relative z-10">
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-white">
                Premium Facebook Scheduling<br class="hidden md:block"> Built for Professionals
            </h1>

            <p class="text-base md:text-lg max-w-xl mx-auto mt-4 text-gray-200 opacity-90">
                smiomio helps businesses schedule posts, automate publishing, 
                and grow their presence — all with a premium, modern experience.
            </p>

            <a href="#get-started"
            class="mt-8 inline-block px-7 py-3 text-lg bg-black/20 backdrop-blur-lg border border-white/20 rounded-xl text-white font-semibold shadow-lg hover:bg-black/10 transition">
                Start Scheduling
            </a>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="py-20 px-6 max-w-5xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-14">How It Works</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">

            <div>
                <div class="w-14 h-14 mx-auto bg-brand/20 rounded-xl border border-brand/30 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8m-8 4h6m-6 4h4m-4 4h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold">1. Create Your Post</h3>
                <p class="text-gray-400 mt-2">Write and design your Facebook content directly in smiomio.</p>
            </div>

            <div>
                <div class="w-14 h-14 mx-auto bg-brand/20 rounded-xl border border-brand/30 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m4-4a8 8 0 11-16 0 8 8 0 0116 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold">2. Pick a Schedule</h3>
                <p class="text-gray-400 mt-2">Choose when your post should go live – today or months later.</p>
            </div>

            <div>
                <div class="w-14 h-14 mx-auto bg-brand/20 rounded-xl border border-brand/30 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2 2 4-4m2 5l2 2 6-6" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold">3. Automate Everything</h3>
                <p class="text-gray-400 mt-2">Relax while smiomio posts your content automatically.</p>
            </div>

        </div>
    </section>

    <!-- TESTIMONIALS WITH SWIPER -->
<section class="py-20 px-6 max-w-5xl mx-auto relative">
    <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">What Users Are Saying</h2>

    <div class="swiper mySwiper">
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <div class="bg-premiumCard p-8 rounded-2xl border border-white/10 backdrop-blur-md text-center max-w-xl mx-auto">
                    <p class="text-gray-300 text-lg">
                        “smiomio completely transformed our posting workflow.”
                    </p>
                    <div class="mt-4 text-sm opacity-70">— Alex M.</div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="bg-premiumCard p-8 rounded-2xl border border-white/10 backdrop-blur-md text-center max-w-xl mx-auto">
                    <p class="text-gray-300 text-lg">
                        “Incredible automation features and beautiful design.”
                    </p>
                    <div class="mt-4 text-sm opacity-70">— Sarah K.</div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="bg-premiumCard p-8 rounded-2xl border border-white/10 backdrop-blur-md text-center max-w-xl mx-auto">
                    <p class="text-gray-300 text-lg">
                        “A must-have tool for social media managers.”
                    </p>
                    <div class="mt-4 text-sm opacity-70">— Daniel R.</div>
                </div>
            </div>

        </div>

        <div class="swiper-pagination mt-6"></div>
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-button-prev text-white"></div>
    </div>
</section>

    <!-- CTA -->
    <section id="get-started" class="py-24 text-center px-6">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Start Growing Today</h2>
        <p class="text-gray-400 max-w-md mx-auto mb-8">Create your account and automate your Facebook presence.</p>

        <a href="#" 
           class="px-10 py-4 bg-brand text-white rounded-xl text-lg font-semibold hover:bg-brandLight transition shadow-xl">
            Get Started Now
        </a>
    </section>

    <!-- FOOTER -->
    <footer class="py-8 text-center text-gray-500 text-sm border-t border-white/10">
        © 2025 smiomio — All rights reserved.
    </footer>




<!-- LOGIN MODAL -->
<div id="loginModal" class="flex fixed inset-0 bg-black/90 backdrop-blur-md hidden items-center justify-center z-50 transition-opacity">
    <div class="bg-premiumCard border border-white/10 p-8 rounded-2xl w-full max-w-md shadow-2xl animate-fadeIn relative">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Login</h2>

        <form method="POST" action="/admin/login">
            @csrf
            <div class="space-y-4">

                <div>
                    <label class="text-gray-300 text-sm">Email</label>
                    <input type="email" name="email" required
                        class="w-full mt-1 px-4 py-3 bg-black/20 border border-white/20 
                        rounded-lg text-white focus:outline-none focus:border-brand"/>
                </div>

                <div>
                    <label class="text-gray-300 text-sm">Password</label>
                    <input type="password" name="password" required
                        class="w-full mt-1 px-4 py-3 bg-black/20 border border-white/20 
                        rounded-lg text-white focus:outline-none focus:border-brand"/>
                </div>

                <button type="submit"
                    class="w-full mt-5 py-3 bg-brand text-white font-semibold rounded-lg hover:bg-brandLight transition">
                    Login
                </button>
            </div>
        </form>

        <p class="text-gray-400 text-sm text-center mt-4">
            Don't have an account? 
            <button onclick="switchToRegister()" class="text-brandLight hover:underline">Register here</button>
        </p>

        <button onclick="closeLogin()" class="absolute top-4 right-4 text-gray-400 hover:text-white">
            ✕
        </button>
    </div>
</div>

<!-- REGISTER MODAL -->
<div id="registerModal" class="flex fixed inset-0 bg-black/90 backdrop-blur-md hidden items-center justify-center z-50">
    <div class="bg-premiumCard border border-white/10 p-8 rounded-2xl w-full max-w-md shadow-2xl animate-fadeIn relative">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Create Account</h2>

        <form method="POST" action="/register">
            @csrf
            <div class="space-y-4">

                <div>
                    <label class="text-gray-300 text-sm">Name</label>
                    <input type="text" name="name" required
                        class="w-full mt-1 px-4 py-3 bg-black/20 border border-white/20 
                        rounded-lg text-white focus:outline-none focus:border-brand"/>
                </div>

                <div>
                    <label class="text-gray-300 text-sm">Email</label>
                    <input type="email" name="email" required
                        class="w-full mt-1 px-4 py-3 bg-black/20 border border-white/20 
                        rounded-lg text-white focus:outline-none focus:border-brand"/>
                </div>

                <div>
                    <label class="text-gray-300 text-sm">Password</label>
                    <input type="password" name="password" required
                        class="w-full mt-1 px-4 py-3 bg-black/20 border border-white/20 
                        rounded-lg text-white focus:outline-none focus:border-brand"/>
                </div>

                <div>
                    <label class="text-gray-300 text-sm">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full mt-1 px-4 py-3 bg-black/20 border border-white/20 
                        rounded-lg text-white focus:outline-none focus:border-brand"/>
                </div>

                <button type="submit"
                    class="w-full mt-5 py-3 bg-brand text-white font-semibold rounded-lg hover:bg-brandLight transition">
                    Create Account
                </button>
            </div>
        </form>

        <p class="text-gray-400 text-sm text-center mt-4">
            Already have an account? 
            <button onclick="switchToLogin()" class="text-brandLight hover:underline">Login here</button>
        </p>

        <button onclick="closeRegister()" class="absolute top-4 right-4 text-gray-400 hover:text-white">
            ✕
        </button>
    </div>
</div>





    <!-- SCRIPTS -->
<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 3,
        spaceBetween: 20,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        effect: "slide",
        speed: 650,
        breakpoints: {
            768: { slidesPerView: 2 }
        }
    });

    /* MODAL HANDLERS */
    function openLogin() {
        document.getElementById("loginModal").classList.remove("hidden");
    }
    function closeLogin() {
        document.getElementById("loginModal").classList.add("hidden");
    }
    function openRegister() {
        document.getElementById("registerModal").classList.remove("hidden");
    }
    function closeRegister() {
        document.getElementById("registerModal").classList.add("hidden");
    }

    function switchToRegister() {
        closeLogin();
        setTimeout(openRegister, 150);
    }
    function switchToLogin() {
        closeRegister();
        setTimeout(openLogin, 150);
    }
</script>

</body>
</html>
