<?php
require "connection.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>God's Home of Refuge - Every Pet Deserves a Loving Home</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- We'll use a single <style> block for custom, non-Tailwind styling and variables -->
    <link rel="stylesheet" href="index.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'primary-brown': '#8F5B3A',
                        'light-orange': '#D29A5B',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen">

    <!-- AUTH SCRIPT (replaces previous Firebase login.js) -->
    <script>
    // Helper: show temporary message
    function showMessage(msg, timeout = 3000) {
        const box = document.getElementById('message-box');
        box.textContent = msg;
        box.style.opacity = '1';
        box.style.pointerEvents = 'auto';
        setTimeout(() => {
            box.style.opacity = '0';
            box.style.pointerEvents = 'none';
        }, timeout);
    }

    // Toggle password visibility (pass the input id)
    function togglePasswordVisibility(id) {
        const input = document.getElementById(id);
        if (!input) return;
        if (input.type === 'password') input.type = 'text';
        else input.type = 'password';
    }

    // Show login or signup view inside modal
    function showView(view) {
        document.getElementById('login-view').classList.toggle('hidden', view !== 'login');
        document.getElementById('signup-view').classList.toggle('hidden', view !== 'signup');

        document.getElementById('login-tab').classList.toggle('active-tab', view === 'login');
        document.getElementById('signup-tab').classList.toggle('active-tab', view === 'signup');
    }

    // Open/close modal
    function openModal() {
        document.getElementById('modal-container').classList.remove('hidden');
        // default to login tab
        showView('login');
    }
    function closeModal() {
        document.getElementById('modal-container').classList.add('hidden');
    }

    // Wire up the top-right auth button if present
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('open-auth-btn');
        if (openBtn) {
            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal();
            });
        }

        // Close modal when clicking outside auth card
        const modal = document.getElementById('modal-container');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        }
    });

    // Handles both Login and Create Account form submissions
    function handleFormSubmit(event, type) {
        event.preventDefault();

        if (type === "Login") {
            let email = document.getElementById("login-email").value.trim();
            let password = document.getElementById("login-password").value;

            if (!email || !password) {
                showMessage('Please enter email and password');
                return;
            }

            fetch("login.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(res => res.text())
            .then(data => {
                data = data.trim();
                if (data === "success") {
                    showMessage("Login successful! Redirecting...");
                    // reload to update session-dependent UI
                    setTimeout(() => location.reload(), 800);
                } else if (data === "wrong_password") {
                    showMessage("Incorrect password.");
                } else if (data === "no_user") {
                    showMessage("No account found with that email.");
                } else {
                    showMessage("Login failed: " + data);
                }
            })
            .catch(err => {
                console.error(err);
                showMessage("Network error. Try again.");
            });
        }

        if (type === "Create Account") {
            // Gather signup fields
            const first = document.getElementById("signup-first-name").value.trim();
            const last = document.getElementById("signup-last-name").value.trim();
            const birthday = document.getElementById("signup-birthday").value;
            const mobile = document.getElementById("signup-mobile").value.trim();
            const address = document.getElementById("signup-address").value.trim();
            const email = document.getElementById("signup-email").value.trim();
            const password = document.getElementById("signup-password").value;
            const confirm = document.getElementById("signup-confirm-password").value;
            const terms = document.getElementById("terms-agree").checked;

            if (!first || !last || !birthday || !mobile || !address || !email || !password || !confirm) {
                showMessage("Please complete all required fields.");
                return;
            }
            if (password !== confirm) {
                showMessage("Passwords don't match.");
                return;
            }
            if (!terms) {
                showMessage("You must agree to the Terms of Service.");
                return;
            }

            const params = new URLSearchParams();
            params.append('first_name', first);
            params.append('last_name', last);
            params.append('birthday', birthday);
            params.append('mobile', mobile);
            params.append('address', address);
            params.append('email', email);
            params.append('password', password);

            fetch("signup.php", {
                method: "POST",
                body: params
            })
            .then(res => res.text())
            .then(response => {
                response = response.trim();
                if (response === "success") {
                    showMessage("Account created! Please login.");
                    // switch to login view
                    showView('login');
                } else if (response === "email_exists") {
                    showMessage("Email already exists.");
                } else {
                    showMessage("Signup failed: " + response);
                }
            })
            .catch(err => {
                console.error(err);
                showMessage("Network error. Try again.");
            });
        }
    }
    </script>

    <!-- Main JavaScript File -->
    <script src="petdata.js"></script>

    <!-- 1. Header/Navigation Bar (Always visible) -->
    <header class="navbar flex justify-between items-center px-6 py-3 shadow-md bg-white sticky top-0 z-10">
        <div class="logo font-extrabold text-xl" style="color: var(--secondary-color);">🐾 God's <span style="color: var(--primary-color);"> Home of Refuge </span></div>
        <nav class="flex items-center space-x-6">
            <ul class="flex space-x-6">
                <a href="#" class="nav-link" data-target="home-page" onclick="navigate('home-page')">Home</a>
                <a href="#" class="nav-link" data-target="about-page" onclick="navigate('about-page')">About</a>
                <a href="#" class="nav-link" data-target="find-pets-page" onclick="navigate('find-pets-page'); applyFilters();">Find Pets</a>
                <a href="#" class="nav-link" data-target="adopt-page" onclick="navigate('adopt-page')">Adopt</a>
                <a href="#" class="nav-link" data-target="health-page" onclick="navigate('health-page')">Health Records</a>
                <!-- UPDATED: Navigate to the new contact-page section -->
                <a href="#" class="nav-link" data-target="contact-page" onclick="navigate('contact-page')">Contact</a>
            </ul>
        </nav>

        <div class="fixed top-4 right-4 z-10">
            <div class="auth-buttons space-x-2">
                <?php if (!isset($_SESSION["user_id"])): ?>
                    <button id="open-auth-btn" class="text-sm font-medium text-white px-4 py-2 rounded-full shadow-md transition hover:bg-opacity-90" style="background-color: var(--primary-color); margin-top: 7px;">Login/SignUp</button>
                <?php else: ?>
                    <a href="logout.php" class="text-sm font-medium text-white px-4 py-2 rounded-full shadow-md transition hover:bg-opacity-90" style="background-color: var(--primary-color); margin-top: 7px;">
                        Logout (<?= htmlspecialchars($_SESSION["user_name"] ?? '') ?>)
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- --- LOGIN/SIGNUP MODAL --- -->
    <div id="modal-container" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center min-h-screen p-0 sm:p-4 z-20 items-end sm:items-center">
        <div id="auth-card" class="bg-white w-full sm:max-w-md rounded-t-xl sm:rounded-xl shadow-2xl p-6 sm:p-8 transform transition-all duration-500 ease-in-out max-h-[95vh] overflow-y-auto">
            <div class="flex mb-6 border-b border-gray-200 -mt-2 -mx-4 sm:-mx-6">
                <button id="login-tab" onclick="showView('login')" class="tab-button active-tab">Login</button>
                <button id="signup-tab" onclick="showView('signup')" class="tab-button">Sign Up</button>
            </div>

            <div id="login-view">
                <header class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-700">Enter your credentials</h3>
                    <button onclick="closeModal()" aria-label="Close" class="text-gray-400 hover:text-gray-600 transition duration-150">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </header>

                <form onsubmit="handleFormSubmit(event, 'Login')">
                    <div class="space-y-4">
                        <div>
                            <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="login-email" placeholder="Enter your email" required class="auth-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                        </div>

                        <div>
                            <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" id="login-password" placeholder="Enter your password" required class="auth-input w-full pr-10 pl-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                <button type="button" onclick="togglePasswordVisibility('login-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-brown focus:outline-none" aria-label="Toggle password visibility">
                                    <svg id="login-password-toggle-show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="login-password-toggle-hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.82L10.5 15.445 7.125 18.82A.75.75 0 016 18.25V6.75a.75.75 0 011.125-.662l3.375 3.374 3.375-3.374A.75.75 0 0115 6.75v11.5a.75.75 0 01-1.125.562z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <div class="flex items-center">
                                <input id="remember-me" type="checkbox" class="h-4 w-4 text-primary-brown rounded border-gray-300 focus:ring-light-orange">
                                <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                            </div>
                            <a href="#" onclick="showMessage('Password reset link simulated.')" class="text-sm font-medium text-primary-brown hover:text-light-orange transition duration-150">Forgot password?</a>
                        </div>

                        <button type="submit" class="auth-button-gradient text-white font-semibold w-full py-3 rounded-lg mt-6">Login</button>
                    </div>
                </form>
            </div>

            <div id="signup-view" class="hidden">
                <header class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-700">Tell us about yourself</h3>
                    <button onclick="closeModal()" aria-label="Close" class="text-gray-400 hover:text-gray-600 transition duration-150">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </header>

                <form onsubmit="handleFormSubmit(event, 'Create Account')">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="signup-first-name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" id="signup-first-name" placeholder="First name" required class="auth-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                            </div>
                            <div>
                                <label for="signup-last-name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" id="signup-last-name" placeholder="Last name" required class="auth-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="signup-birthday" class="block text-sm font-medium text-gray-700 mb-1">Birthday</label>
                                <input type="date" id="signup-birthday" required class="auth-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                            </div>
                            <div>
                                <label for="signup-mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                                <input type="tel" id="signup-mobile" placeholder="e.g., 09123456789" required class="auth-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label for="signup-address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" id="signup-address" placeholder="Enter your full address" required class="auth-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                        </div>

                        <div>
                            <label for="signup-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="signup-email" placeholder="Enter your email" required class="auth-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                        </div>

                        <div>
                            <label for="signup-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" id="signup-password" placeholder="Create a password" required class="auth-input w-full pr-10 pl-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                <button type="button" onclick="togglePasswordVisibility('signup-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-brown focus:outline-none" aria-label="Toggle password visibility">
                                    <svg id="signup-password-toggle-show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="signup-password-toggle-hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.82L10.5 15.445 7.125 18.82A.75.75 0 016 18.25V6.75a.75.75 0 011.125-.662l3.375 3.374 3.375-3.374A.75.75 0 0115 6.75v11.5a.75.75 0 01-1.125.562z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="signup-confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="signup-confirm-password" placeholder="Confirm your password" required class="auth-input w-full pr-10 pl-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                <button type="button" onclick="togglePasswordVisibility('signup-confirm-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-brown focus:outline-none" aria-label="Toggle password visibility">
                                    <svg id="signup-confirm-password-toggle-show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="signup-confirm-password-toggle-hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.82L10.5 15.445 7.125 18.82A.75.75 0 016 18.25V6.75a.75.75 0 011.125-.662l3.375 3.374 3.375-3.374A.75.75 0 0115 6.75v11.5a.75.75 0 01-1.125.562z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>.

                        <div class="flex items-start pt-2">
                            <input id="terms-agree" type="checkbox" required class="mt-1 h-4 w-4 text-primary-brown rounded border-gray-300 focus:ring-light-orange">
                            <label for="terms-agree" class="ml-3 block text-sm text-gray-900 leading-snug">
                                I agree to the
                                <a href="#" onclick="showMessage('Terms of Service clicked.')" class="font-medium text-primary-brown hover:text-light-orange">Terms of Service</a>
                                and
                                <a href="#" onclick="showMessage('Privacy Policy clicked.')" class="font-medium text-primary-brown hover:text-light-orange">Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" class="auth-button-gradient text-white font-semibold w-full py-3 rounded-lg mt-6">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="message-box" class="fixed top-4 left-1/2 transform -translate-x-1/2 p-4 rounded-lg shadow-xl text-white bg-gray-800 transition-opacity duration-300 opacity-0 pointer-events-none z-50"></div>

    <!-- --- MAIN CONTENT SECTIONS (unchanged) --- -->

    <!-- ============================================== -->
    <!-- HOME PAGE -->
    <!-- (rest of your page content left exactly as in original file) -->
    <section id="home-page" data-page class="min-h-screen">
        <!-- 2. Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight mb-4 drop-shadow-lg">Every Pet Deserves a <strong>Loving Home</strong></h1>.
                <p class="text-lg mb-6 drop-shadow-md">Change your life with the perfect pet companion. Our intelligent matching system connects you with pets that fit your lifestyle, ensuring a long-lasting and loving relationship.</p>

                <div class="search-match">
                    <h3 class="font-semibold text-lg mb-3">Find Your Perfect Match</h3>
                    <!-- ADDED IDs AND VALUE ATTRIBUTES FOR HERO FILTERS -->
                    <div class="search-form flex gap-3 mb-4">
                        <select id="hero-filter-type" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 flex-grow" style="color: var(--secondary-color);">
                            <option value="All Types">Pet Type</option>
                            <option value="Dog">Dog</option>
                            <option value="Cat">Cat</option>
                            <option value="Other">Other</option>
                        </select>
                        <select id="hero-filter-age" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 flex-grow" style="color: var(--secondary-color);">
                            <option value="All Ages">Age Range</option>
                            <option value="Puppy/Kitten">Puppy/Kitten (0-1)</option>
                            <option value="Adult">Adult (1-7)</option>
                            <option value="Senior">Senior (7+)</option>
                        </select>
                        <!-- UPDATED: Added onclick to applyHeroFilters() -->
                        <button class="search-btn text-white px-5 rounded-lg font-semibold shadow-md transition hover:bg-amber-600" style="background-color: var(--primary-color);" onclick="applyHeroFilters()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                            Find Pets
                        </button>
                    </div>
                    <div class="action-buttons flex gap-3">
                        <button class="browse-all bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition" onclick="navigate('find-pets-page'); applyFilters();">View All Pets</button>
                        <button class="start-app bg-gray-800 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition" onclick="navigate('adopt-page')">Start Application</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- rest of content is unchanged... -->
    </section>

    <!-- ============================================== -->
    <!-- ABOUT PAGE -->
    <!-- ============================================== -->
    <section id="about-page" data-page class="py-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-extrabold mb-3 rounded-lg" style="color: var(--secondary-color);">About God's Home of Refuge</h1>
            <p class="text-lg text-gray-600 mb-12">We are dedicated to creating lasting bonds between pets and families through compassionate care and innovative technology.</p>

            <!-- Mission Statement and Image -->
            <div class="about-mission items-center mb-16 flex flex-col lg:flex-row bg-white p-6 shadow-xl rounded-xl">
                <div class="text-content text-left w-full lg:w-1/2 mb-6 lg:mb-0">
                    <h2 class="text-3xl font-bold mb-4" style="color: var(--secondary-color);">Our Mission</h2>
                    <p class="mb-4 text-gray-700">Founded in 2020, God's Home of Refuge revolutionized pet adoption by integrating advanced matching algorithms with comprehensive veterinary care. 
                        We believe every pet deserves a loving home, and every family deserves the perfect companion.</p>
                    <p class="mb-4 text-gray-700">Our platform connects animal shelters and loving families to ensure the best outcomes for all pets in our care.</p>
                    <div class="flex items-center text-left mt-6 p-3 rounded-lg border-l-4 border-l-orange-400 bg-orange-50/50">
                        <span class="text-xl mr-3" style="color: var(--secondary-color);">✨</span>
                        <div>
                            <p class="font-bold text-lg text-gray-800">Over 1,000 successful adoptions</p>
                            <p class="text-sm text-gray-500">Since our founding</p>
                        </div>
                    </div>
                </div>
                <img src="people.jpg" alt="Veterinarian smiling while examining a happy dog" class="w-full lg:w-1/2 rounded-lg object-cover shadow-md lg:ml-8">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16">
                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-t-orange-400 transform transition duration-300 hover:scale-[1.03] hover:shadow-xl cursor-pointer">
                    <span class="text-4xl block mb-4" style="color: var(--secondary-color);">🩺</span>
                    <h3 class="text-xl font-bold mb-2">Comprehensive Health Screening</h3>
                    <p class="text-gray-600 text-sm">Every pet receives thorough health examinations and vaccinations before adoption.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-t-orange-400 transform transition duration-300 hover:scale-[1.03] hover:shadow-xl cursor-pointer">
                    <span class="text-4xl block mb-4" style="color: var(--secondary-color);">💖</span>
                    <h3 class="text-xl font-bold mb-2">Perfect Matching</h3>
                    <p class="text-gray-600 text-sm">Our AI-powered system matches pets with families based on lifestyle and preferences.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-t-orange-400 transform transition duration-300 hover:scale-[1.03] hover:shadow-xl cursor-pointer">
                    <span class="text-4xl block mb-4" style="color: var(--secondary-color);">🐾</span>
                    <h3 class="text-xl font-bold mb-2">Lifetime Support</h3>
                    <p class="text-gray-600 text-sm">We provide ongoing support and resources throughout your pet's life.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- (The rest of the original HTML remains unchanged and is included below in full when you save the file) -->

    <!-- 5. Footer (Always visible) -->
    <footer class="mt-12 text-white" style="background-color: var(--secondary-color);">
        <div class="footer-content max-w-6xl mx-auto py-12 px-6 grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="footer-logo">
                <h3 class="text-xl font-extrabold mb-3">God's<span style="color: var(--primary-color);"> Home of Refuge</span></h3>
                <p class="text-sm text-gray-300 mb-4">Connecting loving families with pets in need of a forever home.</p>
                <div class="social-links flex space-x-3">
                    <a href="https://www.facebook.com/share/1CxtiGGBsx/" class="text-gray-400 hover:text-white transition" aria label="visit our facebook page">facebook</a>
                </div>
            </div>

            <div class="footer-links">
                <h4 class="font-bold mb-4" style="color: var(--primary-color);">Quick Links</h4>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><a href="#" onclick="navigate('home-page')">Home</a></li>
                    <li><a href="#" onclick="navigate('about-page')">About Us</a></li>
                    <li><a href="#" onclick="navigate('find-pets-page'); applyFilters();">Find Pets</a></li>
                    <li><a href="#" onclick="navigate('adopt-page')">Adoption Process</a></li>
                    <li><a href="#" onclick="navigate('health-page')">Health Records</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4 class="font-bold mb-4" style="color: var(--primary-color);">Support</h4>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><a href="#" onclick="navigate('contact-page')">Contact Us</a></li>
                </ul>
            </div>
            <div class="contact-info">
                <h4 class="font-bold mb-4" style="color: var(--primary-color);">Contact Information</h4>
                <p class="text-sm mb-2">09266642935</p>
                <p class="text-xs text-gray-400">  Diokno Highway, Lemery, Batangas </p>
            </div>
        </div>
        <div class="text-center text-xs py-4 border-t border-gray-700 text-gray-400">
            © 2025 God's Home of Refuge. All rights reserved. | Privacy Policy | Terms of Service
        </div>
    </footer>

</body>
</html>
