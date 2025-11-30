<?php
// index.php - Part 1
session_start();
// include config.php only if you want to access DB here (not required for header)
// include 'config.php';

// Helper for display name (if logged in)
$user_display = null;
if (isset($_SESSION['user_id'])) {
    // Prefer to use name if you store it in session; fallback to email
    $user_display = isset($_SESSION['first_name']) ? ($_SESSION['first_name'] . (isset($_SESSION['last_name']) ? ' ' . $_SESSION['last_name'] : '')) : $_SESSION['email'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God's Home of Refuge</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Optional: custom CSS file -->
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
<body class="min-h-screen bg-white">

    <!-- Header / Navigation -->
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
        <?php if ($user_display): ?>
            <div class="text-sm text-gray-700 mr-3">Hello, <span class="font-semibold"><?= htmlspecialchars($user_display) ?></span></div>
            <a href="logout.php" class="text-sm font-medium text-white px-4 py-2 rounded-full shadow-md transition hover:bg-opacity-90" 
            style="background-color: var(--primary-color);">
                Logout
            </a>
        <?php else: ?>
            <button id="open-auth-btn" onclick="openModal()" class="text-sm font-medium text-white px-4 py-2 rounded-full shadow-md transition hover:bg-opacity-90" 
            style="background-color: var(--primary-color); margin-top: 10px;">
                Login / Sign Up
            </button>
        <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Authentication Modal (Login / Signup) -->
    <!-- Modal container is hidden by default; shown via JS openModal() -->
    <div id="modal-container" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center min-h-screen p-0 sm:p-4 z-20 items-end sm:items-center">
        <div id="auth-card" class="bg-white w-full sm:max-w-md rounded-t-xl sm:rounded-xl shadow-2xl p-6 sm:p-8 transform transition-all duration-500 ease-in-out max-h-[95vh] overflow-y-auto">
            <div class="flex mb-6 border-b border-gray-200 -mt-2 -mx-4 sm:-mx-6">
                <button id="login-tab" onclick="showView('login')" class="tab-button active-tab px-6 py-3 text-sm font-medium">Login</button>
                <button id="signup-tab" onclick="showView('signup')" class="tab-button px-6 py-3 text-sm font-medium">Sign Up</button>
            </div>

            <!-- LOGIN VIEW -->
            <div id="login-view">
                <header class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-700">Enter your credentials</h3>
                    <button onclick="closeModal()" aria-label="Close" class="text-gray-400 hover:text-gray-600 transition duration-150">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </header>

                <!-- Login form submits to JS handler (AJAX) -->
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

            <!-- SIGNUP VIEW -->
            <div id="signup-view" class="hidden">
                <header class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-700">Tell us about yourself</h3>
                    <button onclick="closeModal()" aria-label="Close" class="text-gray-400 hover:text-gray-600 transition duration-150">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" 
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="signup-confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="signup-confirm-password" placeholder="Confirm your password" required class="auth-input w-full pr-10 pl-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                <button type="button" onclick="togglePasswordVisibility('signup-confirm-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-brown focus:outline-none" aria-label="Toggle password visibility">
                                    <svg id="signup-confirm-password-toggle-show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </button>
                            </div>
                        </div>

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

    <!-- small reusable message box -->
    <div id="message-box" class="fixed top-4 left-1/2 transform -translate-x-1/2 p-4 rounded-lg shadow-xl text-white bg-gray-800 transition-opacity duration-300 opacity-0 pointer-events-none z-50"></div>

<!-- CONTENT SECTIONS START BELOW (Part 2 will contain the rest of the pages and HTML) -->

<script>
/* Basic modal/tab JS used across parts.
   Full navigation and AJAX handlers will be provided in Part 4. */

function openModal() {
    document.getElementById('modal-container').classList.remove('hidden');
    showView('login');
}

function closeModal() {
    document.getElementById('modal-container').classList.add('hidden');
}

function showView(which) {
    document.getElementById('login-view').classList.toggle('hidden', which !== 'login');
    document.getElementById('signup-view').classList.toggle('hidden', which !== 'signup');

    // Toggle active tab classes (simple)
    document.getElementById('login-tab').classList.toggle('active-tab', which === 'login');
    document.getElementById('signup-tab').classList.toggle('active-tab', which === 'signup');
}

function showMessage(msg, duration = 3000) {
    const box = document.getElementById('message-box');
    box.textContent = msg;
    box.classList.remove('opacity-0', 'pointer-events-none');
    setTimeout(() => {
        box.classList.add('opacity-0');
        box.classList.add('pointer-events-none');
    }, duration);
}

function togglePasswordVisibility(id) {
    const el = document.getElementById(id);
    if (!el) return;
    if (el.type === 'password') el.type = 'text';
    else el.type = 'password';
}

// Placeholder navigate function referenced by nav links — real navigation logic in Part 2/4
function navigate(target) {
    // Implement showing/hiding sections in Part 2
    console.log('navigate to', target);
}
</script>

<!-- END OF PART 1 -->

<!-- PART 2 START: Main content sections (home, about, find-pets, pet-detail) -->
<section id="home-page" data-page class="min-h-screen">
        <!-- 2. Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight mb-4 drop-shadow-lg">Every Pet Deserves a <strong>Loving Home</strong></h1>
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
                        <!-- LINKED TO FIND PETS PAGE (View All Pets Button) -->
                        <button class="browse-all bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition" onclick="navigate('find-pets-page'); applyFilters();">View All Pets</button>
                        <!-- UPDATED: Navigate to adopt-page instead of generic button -->
                        <button class="start-app bg-gray-800 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition" onclick="navigate('adopt-page')">Start Application</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Trust/Validation Section (Skipped for brevity, assume content is stable) -->
        <div class="trust-section text-center py-10 bg-gray-50">
            <p class="text-gray-600 mb-8"><b>Trusted by leading animal welfare organizations</b></p>
            <div class="badges flex justify-center gap-12 md:gap-20 flex-wrap">
                <div class="badge-item w-24">
                    <div class="icon">🐾</div>
                    <p class="text-sm font-medium">ASPCA Certified</p>
                </div>
                <div class="badge-item w-24">
                    <div class="icon">🩺</div>
                    <p class="text-sm font-medium">Veterinary Approved</p>
                </div>
                <div class="badge-item w-24">
                    <div class="icon">⭐</div>
                    <p class="text-sm font-medium">5-Star Rated</p>
                </div>
                <div class="badge-item w-24">
                    <div class="icon">✅</div>
                    <p class="text-sm font-medium">98% Success Rate</p>
                </div>
            </div>
        </div>

        <!-- 4. Featured Pets Section (Uses dynamic rendering like the Find Pets page now) -->
        <div class="featured-pets text-center py-16 px-4">
            <h2 class="text-3xl font-bold mb-3">Meet Your New Best Friend</h2>
            <p class="text-gray-600 max-w-2xl mx-auto mb-12">Discover amazing pets waiting for their forever homes. Every pet comes with a complete health record and personality profile.</p>

            <div class="pet-cards-container flex justify-center gap-6 flex-wrap">
                <!-- Using only the first three pets for the featured section -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const featuredContainer = document.querySelector('.featured-pets .pet-cards-container');
                        if (featuredContainer) {
                            // Render only the first 3 pets for featured section
                            featuredContainer.innerHTML = PETS_DATA.slice(0, 4).map(createPetCardHTML).join('');
                        }
                    });
                </script>
            </div>
        </div>
    </section>
<!-- ABOUT PAGE -->
<section id="about-page" data-page class="py-16 px-4 hidden">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl font-extrabold mb-3 rounded-lg">About God's Home of Refuge</h1>
        <p class="text-lg text-gray-600 mb-12">We are dedicated to creating lasting bonds between pets and families through compassionate care and innovative technology.</p>

        <div class="about-mission items-center mb-16 flex flex-col lg:flex-row bg-white p-6 shadow-xl rounded-xl">
            <div class="text-content text-left w-full lg:w-1/2 mb-6 lg:mb-0">
                <h2 class="text-3xl font-bold mb-4">Our Mission</h2>
                <p class="mb-4 text-gray-700">Founded in 2020, God's Home of Refuge revolutionized pet adoption by integrating advanced matching algorithms with comprehensive veterinary care. We believe every pet deserves a loving home, and every family deserves the perfect companion.</p>
                <p class="mb-4 text-gray-700">Our platform connects animal shelters and loving families to ensure the best outcomes for all pets in our care.</p>

                <div class="flex items-center text-left mt-6 p-3 rounded-lg border-l-4 border-l-orange-400 bg-orange-50/50">
                    <span class="text-xl mr-3">✨</span>
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
                <span class="text-4xl block mb-4">🩺</span>
                <h3 class="text-xl font-bold mb-2">Comprehensive Health Screening</h3>
                <p class="text-gray-600 text-sm">Every pet receives thorough health examinations and vaccinations before adoption.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-t-orange-400 transform transition duration-300 hover:scale-[1.03] hover:shadow-xl cursor-pointer">
                <span class="text-4xl block mb-4">💖</span>
                <h3 class="text-xl font-bold mb-2">Perfect Matching</h3>
                <p class="text-gray-600 text-sm">Our AI-powered system matches pets with families based on lifestyle and preferences.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-t-orange-400 transform transition duration-300 hover:scale-[1.03] hover:shadow-xl cursor-pointer">
                <span class="text-4xl block mb-4">🐾</span>
                <h3 class="text-xl font-bold mb-2">Lifetime Support</h3>
                <p class="text-gray-600 text-sm">We provide ongoing support and resources throughout your pet's life.</p>
            </div>
        </div>
    </div>
</section>

 <!-- FIND PETS PAGE -->
    <!-- ============================================== -->
    <section id="find-pets-page" data-page class="py-16 px-4 hidden">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl font-extrabold text-center mb-2" style="color: var(--secondary-color);">Find Your Perfect Pet</h1>
            <p class="text-lg text-gray-600 text-center mb-8">View our currently available pets and find your new best friend.</p>

            <!-- Filter Bar -->
            <div class="filter-bar flex flex-col md:flex-row gap-4 justify-center md:justify-between items-center mb-10 mx-auto max-w-4xl">
                <select id="filter-type" class="p-3 rounded-lg w-full md:w-auto" style="color: var(--secondary-color);">
                    <option value="All Types">All Types</option>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Other">Other</option>
                </select>
                <select id="filter-age" class="p-3 rounded-lg w-full md:w-auto" style="color: var(--secondary-color);">
                    <option value="All Ages">All Ages</option>
                    <option value="Puppy/Kitten">Puppy/Kitten</option>
                    <option value="Adult">Adult</option>
                    <option value="Senior">Senior</option>
                </select>
                <select id="filter-size" class="p-3 rounded-lg w-full md:w-auto" style="color: var(--secondary-color);">
                    <option value="All Sizes">All Sizes</option>
                    <option value="Small">Small</option>
                    <option value="Medium">Medium</option>
                    <option value="Large">Large</option>
                </select>
                <button id="apply-filters-btn" class="text-white px-8 py-3 rounded-lg font-semibold w-full md:w-auto transition hover:bg-amber-600" style="background-color: var(--primary-color);">
                    Apply Filters
                </button>
            </div>

            <!-- Pet Cards Grid - Content is generated by JS (applyFilters on load) -->
            <div id="find-pets-grid" class="pet-cards-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Pet Cards will be injected here by JavaScript -->
            </div>
        </div>
    </section>

    <!-- ============================================== -->
    <!-- PET DETAIL PAGE (New Page) -->
    <!-- ============================================== -->
    <section id="pet-detail-page" data-page class="py-16 px-4 hidden">
        <div class="max-w-5xl mx-auto">
            <a href="#" class="text-gray-500 hover:text-gray-700 transition mb-6 inline-flex items-center" onclick="navigate('find-pets-page'); applyFilters(); return false;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Pet Search
            </a>

            <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
                <div class="flex flex-col lg:flex-row gap-10">
                    <!-- Image Column -->
                    <div class="lg:w-1/2">
                        <!-- Default placeholder for the image which will be replaced by renderPetDetails -->
                        <img id="detail-img" src="https://placehold.co/500x350/cccccc/3b3a3c?text=Pet+Image" alt="Pet Profile" class="w-full h-auto object-cover rounded-lg shadow-md">
                    </div>
                    
                    <!-- Details Column -->
                    <div class="lg:w-1/2">
                        <span class="inline-block bg-green-100 text-green-700 text-sm font-medium px-3 py-1 rounded-full mb-3">Available for Adoption</span>
                        <h1 id="detail-name" class="text-4xl font-extrabold mb-1" style="color: var(--secondary-color);">Buddy</h1>
                        <p id="detail-meta" class="text-lg text-gray-500 mb-6">Labrador Retriever • 4 years old • Male</p>

                        <p id="detail-description" class="text-gray-700 mb-8 leading-relaxed">
                            Buddy is a loyal and gentle companion who loves outdoor activities and is great with kids. He is well-trained and socialized, making him a perfect family pet. Buddy enjoys long walks, playing fetch, and spending quality time with his companions.
                        </p>

                        <!-- Key Stats Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-10">
                            <div class="detail-info-box">
                                <h4 class="text-xs font-semibold uppercase text-gray-500">Size</h4>
                                <p id="detail-size-value" class="text-base font-medium text-gray-800">Large (65 lbs)</p>
                            </div>
                            <div class="detail-info-box">
                                <h4 class="text-xs font-semibold uppercase text-gray-500">Energy Level</h4>
                                <p id="detail-energy-value" class="text-base font-medium text-gray-800">High</p>
                            </div>
                            <div class="detail-info-box">
                                <h4 class="text-xs font-semibold uppercase text-gray-500">Good With Kids</h4>
                                <p id="detail-kids-value" class="text-base font-medium text-gray-800">Excellent</p>
                            </div>
                            <div class="detail-info-box">
                                <h4 class="text-xs font-semibold uppercase text-gray-500">Training</h4>
                                <p id="detail-training-value" class="text-base font-medium text-gray-800">House trained</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="detail-actions flex gap-4">
                            <!-- UPDATED: Use startAdoptionProcess to navigate and pre-fill the form -->
                            <button class="text-white shadow-lg flex-grow hover:bg-opacity-90" style="background-color: var(--primary-color);" onclick="startAdoptionProcess(document.getElementById('detail-name').textContent)">
                                Adopt <span id="adopt-pet-name">Buddy</span>
                            </button>
                            <button class="border border-gray-400 text-gray-700 hover:bg-gray-100 flex-grow" onclick="alert('Contacting shelter for more information.')">
                                Contact Shelter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- PART 2 END -->


<!-- PART 3 START -->
<!-- ADOPTION APPLICATION PAGE -->
<section id="adopt-page" data-page class="py-16 px-4 hidden">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        <h1 class="text-4xl font-extrabold text-center mb-2" style="color: var(--secondary-color);">Adoption Application</h1>
        
        <?php if (!$user_display): ?>
            <div class="text-center py-10">
                <p class="text-xl text-red-500 font-semibold mb-4">You must be logged in to fill out the adoption form.</p>
                <p class="text-gray-600 mb-6">Please log in or create an account to start your adoption application.</p>
                <button onclick="openModal()" class="text-sm font-medium text-white px-6 py-3 rounded-full shadow-md transition hover:bg-opacity-90" 
                    style="background-color: var(--primary-color);">
                    Login / Sign Up
                </button>
            </div>
        <?php else: ?>
            <p class="text-lg text-gray-600 text-center mb-10">Help us find the perfect match by telling us about yourself and your preferences.</p>
            
            <form id="adoption-form" class="space-y-6">
                <h2 class="text-2xl font-semibold border-b pb-2 mb-4" style="color: var(--primary-color);">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" id="app-first-name" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500" placeholder="First Name *" required>
                    <input type="text" id="app-last-name" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500" placeholder="Last Name *" required>
                    <input type="email" id="app-email" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500" placeholder="Email Address *" required>
                    <input type="tel" id="app-phone" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500" placeholder="Phone Number *" required>
                </div>
                <input type="text" id="app-address" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 w-full" placeholder="Full Address *" required>
                
                <h2 class="text-2xl font-semibold border-b pb-2 mb-4 mt-8" style="color: var(--primary-color);">Pet Preference</h2>
                <p id="pet-name-message" class="text-sm text-gray-500 hidden">You are applying for: <span class="font-bold" id="pre-filled-pet-name"></span></p>
                <input type="text" id="app-pet-name" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 w-full" placeholder="Name of Pet You're Interested In (Optional)">
                
                <div class="space-y-3">
                    <label class="block font-medium text-gray-700">Experience Level *</label>
                    <div class="flex flex-wrap gap-6">
                        <label class="inline-flex items-center"><input type="radio" name="experience" value="First-time owner" class="form-radio text-amber-500 h-4 w-4" required><span class="ml-2">First-time owner</span></label>
                        <label class="inline-flex items-center"><input type="radio" name="experience" value="Some experience" class="form-radio text-amber-500 h-4 w-4"><span class="ml-2">Some experience</span></label>
                        <label class="inline-flex items-center"><input type="radio" name="experience" value="Very experienced" class="form-radio text-amber-500 h-4 w-4"><span class="ml-2">Very experienced</span></label>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <label class="block font-medium text-gray-700">Living Situation *</label>
                    <div class="flex flex-wrap gap-6">
                        <label class="inline-flex items-center"><input type="radio" name="housing" value="House with yard" class="form-radio text-amber-500 h-4 w-4" required><span class="ml-2">House with yard</span></label>
                        <label class="inline-flex items-center"><input type="radio" name="housing" value="Apartment/Condo" class="form-radio text-amber-500 h-4 w-4"><span class="ml-2">Apartment/Condo</span></label>
                        <label class="inline-flex items-center"><input type="radio" name="housing" value="Other" class="form-radio text-amber-500 h-4 w-4"><span class="ml-2">Other</span></label>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block font-medium text-gray-700">Do you currently own any other pets? *</label>
                    <div class="flex flex-wrap gap-6">
                        <label class="inline-flex items-center"><input type="radio" name="other-pets" value="Yes" class="form-radio text-amber-500 h-4 w-4" required><span class="ml-2">Yes</span></label>
                        <label class="inline-flex items-center"><input type="radio" name="other-pets" value="No" class="form-radio text-amber-500 h-4 w-4"><span class="ml-2">No</span></label>
                    </div>
                </div>

                <div>
                    <label for="app-reason" class="block text-sm font-medium text-gray-700 mb-1">Why do you want to adopt a pet? *</label>
                    <textarea id="app-reason" rows="4" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 w-full" placeholder="Tell us about your home, family, and why this pet is right for you." required></textarea>
                </div>

                <button type="submit" class="text-white font-semibold w-full py-3 rounded-lg mt-6 shadow-md transition hover:bg-opacity-90" style="background-color: var(--primary-color);">
                    Submit Application
                </button>
            </form>
        <?php endif; ?>
    </div>
</section>



<!-- HEALTH RECORDS PAGE -->
<section id="health-page" data-page class="py-16 px-4 hidden">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl font-extrabold text-center mb-2" style="color: var(--secondary-color);">Health Records</h1>
            <p class="text-lg text-gray-600 text-center mb-10">Access the full health history for all pets available for adoption.</p>

            <!-- Health Filter Bar (New Feature) -->
            <div class="filter-bar flex flex-col md:flex-row gap-4 justify-center items-center mb-10 mx-auto max-w-4xl">
                <select id="health-filter-type" class="p-3 rounded-lg w-full md:w-auto" style="color: var(--secondary-color);">
                    <option value="All Types">All Types</option>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Other">Other</option>
                </select>
                <select id="health-filter-vaccine" class="p-3 rounded-lg w-full md:w-auto" style="color: var(--secondary-color);">
                    <option value="All Status">All Vaccine Statuses</option>
                    <option value="Up to Date">Up to Date</option>
                    <option value="Attention Needed">Attention Needed</option>
                    <option value="N/A">Not Applicable (N/A)</option>
                </select>
                <button id="apply-health-filters-btn" class="text-white px-8 py-3 rounded-lg font-semibold w-full md:w-auto transition hover:bg-amber-600" style="background-color: var(--primary-color);">
                    Apply Filters
                </button>
            </div>
            
            <!-- Health Record Cards Grid - Content is generated by JS (applyHealthFilters on load) -->
            <div id="health-records-grid" class="health-cards-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Health Cards will be injected here by JavaScript -->
            </div>
        </div>
    </section>


<!-- HEALTH DETAIL PAGE -->
<section id="health-detail-page" data-page class="py-16 px-4 hidden bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto">
             <a href="#" class="text-gray-500 hover:text-gray-700 transition mb-6 inline-flex items-center" onclick="navigate('health-page'); applyHealthFilters(); return false;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Health Records
            </a>
            
            <!-- Header Card -->
            <div class="bg-white p-6 rounded-xl shadow-lg mb-8 flex flex-col md:flex-row items-center gap-6 border-t-4" style="border-top-color: var(--primary-color);">
                <img id="health-detail-pet-img" src="https://placehold.co/100x100/e09e50/ffffff?text=Pet" alt="Pet Profile" class="w-24 h-24 object-cover rounded-full shadow-md">
                <div>
                    <h1 id="health-detail-pet-name" class="text-3xl font-extrabold" style="color: var(--secondary-color);">Buddy</h1>
                    <p id="health-detail-pet-meta" class="text-md text-gray-500">Labrador Retriever • 4 years old • Male</p>
                </div>
            </div>

            <!-- Summary -->
            <h2 class="text-2xl font-bold mb-4 border-b pb-2" style="color: var(--primary-color);">Health Summary</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="p-3 bg-white rounded-lg shadow-sm text-center">
                    <p class="text-xs uppercase font-semibold text-gray-500">Vaccination</p>
                    <p id="summary-vaccines" class="font-bold text-lg text-green-600">Up to Date</p>
                </div>
                <div class="p-3 bg-white rounded-lg shadow-sm text-center">
                    <p class="text-xs uppercase font-semibold text-gray-500">Last Checkup</p>
                    <p id="summary-checkup" class="font-bold text-lg text-gray-700">March 15, 2024</p>
                </div>
                <div class="p-3 bg-white rounded-lg shadow-sm text-center">
                    <p class="text-xs uppercase font-semibold text-gray-500">Weight</p>
                    <p id="summary-weight" class="font-bold text-lg text-gray-700">65 lbs</p>
                </div>
                <div class="p-3 bg-white rounded-lg shadow-sm text-center">
                    <p class="text-xs uppercase font-semibold text-gray-500">Medication</p>
                    <p id="summary-meds" class="font-bold text-lg text-gray-700">None Active</p>
                </div>
            </div>

            <!-- Full Records List -->
            <h2 class="text-2xl font-bold mb-4 border-b pb-2" style="color: var(--primary-color);">Complete Veterinary History</h2>
            <div id="health-records-list" class="space-y-4">
                <!-- Detailed records will be injected here by renderHealthDetails() -->
            </div>
            
            <div class="text-center mt-10">
                <button class="text-white px-8 py-3 rounded-lg font-semibold shadow-md transition hover:bg-amber-600" style="background-color: var(--primary-color);" onclick="alert('Printing complete pet Health Record.')">
                    Print Full History
                </button>
            </div>

        </div>
    </section>

<!-- CONTACT PAGE -->
    <section id="contact-page" data-page class="py-16 px-4 hidden">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            <h1 class="text-4xl font-extrabold text-center mb-2" style="color: var(--secondary-color);">Get In Touch</h1>
            <p class="text-lg text-gray-600 text-center mb-10">We'd love to hear from you. Please fill out the form below or use our direct contact info.</p>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                <!-- Contact Info Panel -->
                <div class="p-6 rounded-lg bg-gray-50 border border-gray-200">
                    <h3 class="text-xl font-bold mb-4" style="color: var(--primary-color);">Our Details</h3>
                    <div class="space-y-4 text-gray-700">
                        <p class="flex items-center">
                            <!-- Email Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            godshomeofrefuge@yahoo.com
                        </p>
                        <p class="flex items-center">
                            <!-- Phone Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            09266642935
                        </p>
                        <p class="flex items-start">
                            <!-- Location Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-amber-500 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Diokno Highway, Lemery, Batangas
                        </p>
                    </div>
                </div>

                <!-- Simple Map Placeholder (since external maps/images are disallowed) -->
                <div class="h-48 md:h-full w-full bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 font-medium border-4 border-gray-300 border-dashed">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3060.2485093277055!2d120.88421997382885!3d13.961855892398132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bda1fb68ed29fd%3A0x2c45bab6feba7c9!2sHouse%20of%20Namjoon%20(GHOR%20Shelter)!5e1!3m2!1sen!2sph!4v1762866963287!5m2!1sen!2sph" 
                    width="600" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <h2 class="text-2xl font-semibold border-b pb-2 mb-6" style="color: var(--primary-color);">Send Us A Message</h2>
            <form id="contact-form" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" id="contact-name" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500" placeholder="Your Name *" required>
                    <input type="email" id="contact-email" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500" placeholder="Your Email *" required>
                </div>
                <input type="text" id="contact-subject" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 w-full" placeholder="Subject (e.g., Volunteer Inquiry, Pet Question) *" required>
                
                <textarea id="contact-message" rows="5" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 w-full" placeholder="Your Message *" required></textarea>

                <button type="submit" class="w-full py-3 rounded-lg font-semibold text-white shadow-lg transition hover:bg-amber-700" style="background-color: var(--primary-color);">
                    Send Message
                </button>
            </form>
        </div>
    </section>
<!-- END OF CONTACT PAGE -->

<!-- FOOTER -->
<footer class="mt-20 bg-gray-900 text-white py-10">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 px-6">

        <div>
            <h3 class="text-xl font-bold mb-3">God's Home of Refuge</h3>
            <p class="text-gray-400">Helping abandoned pets find their forever homes.</p>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-3">Quick Links</h3>
            <ul class="text-gray-400 space-y-2">
                <li><a href="#" onclick="navigate('home-page')">Home</a></li>
                <li><a href="#" onclick="navigate('find-pets-page')">Find Pets</a></li>
                <li><a href="#" onclick="navigate('adopt-page')">Adoption</a></li>
                <li><a href="#" onclick="navigate('contact-page')">Contact</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-3">Follow Us</h3>
            <p class="text-gray-400">Stay updated with our rescue missions.</p>
            <div class="flex gap-3 mt-3">
                <div class="p-3 bg-gray-700 rounded-full">📘</div>
                <div class="p-3 bg-gray-700 rounded-full">📷</div>
                <div class="p-3 bg-gray-700 rounded-full">🐦</div>
            </div>
        </div>

    </div>

    <p class="text-center text-gray-500 mt-10">© 2025 God's Home of Refuge. All rights reserved.</p>
</footer>

<!-- PART 3 END -->

<!-- PART 4 — FIXED, CLEAN, FULLY WORKING -->
<script>
      // --- PET DATA STRUCTURE ---
      const PETS_DATA = [
            { 
                id: 'buddy', name: 'Buddy', type: 'Dog', breed: 'Labrador Retriever', age: 4, gender: 'Male', size: 'Large', energy: 'High', goodWithKids: 'Excellent', training: 'House trained', 
                description: 'Buddy is a loyal and gentle companion who loves outdoor activities and is great with kids. He is well-trained and socialized, making him a perfect family pet. Buddy enjoys long walks, playing fetch, and spending quality time with his companions.', 
                tags: ['Friendly', 'Active'], imgSrc: 'Labrador Retriever.jpg', 
                health: { 
                    vaccines: 'Up to Date', lastCheckup: 'March 15, 2024', weight: '65 lbs', medications: 'None Active',
                    fullHistory: [
                        { date: '2024-03-15', record: 'Annual Checkup. Weight stable. Heartworm prevention administered.', category: 'Checkup' },
                        { date: '2023-11-01', record: 'Rabies booster administered.', category: 'Vaccination' },
                        { date: '2023-09-20', record: 'Dental cleaning performed. Minor tartar removed.', category: 'Procedure' }
                    ]
                }
            },
            { 
                id: 'whiskers', name: 'Whiskers', type: 'Cat', breed: 'Persian Cat', age: 7, gender: 'Female', size: 'Small', energy: 'Low', goodWithKids: 'Good', training: 'Litter trained', 
                description: 'Elegant and calm cat who enjoys quiet environments and gentle affection. Prefers lounging in sunbeams over rowdy play.', 
                tags: ['Calm', 'Independent'], imgSrc: 'Persian Cat.jpg',
                health: { 
                    vaccines: 'Needs Booster', lastCheckup: 'January 10, 2024', weight: '10 lbs', medications: 'Flea Prevention',
                    fullHistory: [
                        { date: '2024-01-10', record: 'Routine Checkup. Needs Feline Calicivirus booster. Started on monthly flea control.', category: 'Checkup' },
                        { date: '2023-07-25', record: 'FVRCP vaccination administered.', category: 'Vaccination' },
                        { date: '2022-12-05', record: 'Minor eye infection treated with topical ointment. Resolved.', category: 'Medication' }
                    ]
                }
            },
            { 
                id: 'scout', name: 'Scout', type: 'Dog', breed: 'Border Collie', age: 3, gender: 'Female', size: 'Medium', energy: 'High', goodWithKids: 'Good', training: 'Advanced commands', 
                description: 'Super smart and energetic dog who loves learning new tricks and agility training. Needs a large yard and an active owner.', 
                tags: ['Smart', 'Energetic'], imgSrc: 'Border Collie.jpg',
                health: { 
                    vaccines: 'Up to Date', lastCheckup: 'April 20, 2024', weight: '45 lbs', medications: 'None Active',
                    fullHistory: [
                        { date: '2024-04-20', record: 'Pre-adoption physical exam. Excellent health. Fecal exam negative.', category: 'Checkup' },
                        { date: '2023-03-05', record: 'Distemper/Parvo vaccination completed.', category: 'Vaccination' }
                    ]
                }
            },
            { 
                id: 'milo', name: 'Milo', type: 'Cat', breed: 'Orange Tabby', age: 0.5, gender: 'Male', size: 'Small', energy: 'Medium', goodWithKids: 'Excellent', training: 'Litter trained', 
                description: 'Playful kitten who loves toys and exploring. Very social and enjoys attention and cuddles.', 
                tags: ['Playful', 'Social'], imgSrc: 'Orange Tabby.jpg',
                health: { 
                    vaccines: 'Kitten Shots Pending', lastCheckup: 'May 5, 2024', weight: '5 lbs', medications: 'Dewormer',
                    fullHistory: [
                        { date: '2024-05-05', record: 'First kitten exam. Dewormer administered. Scheduled for first FVRCP in 2 weeks.', category: 'Checkup' },
                        { date: '2024-05-05', record: 'Initial dose of Pyrantel (Dewormer).', category: 'Medication' }
                    ]
                }
            },
            { 
                id: 'rex', name: 'Rex', type: 'Dog', breed: 'German Shepherd', age: 5, gender: 'Male', size: 'Large', energy: 'Medium', goodWithKids: 'Fair', training: 'Guard training', 
                description: 'Protective and loyal companion with excellent training. Makes a great family guard, but requires firm leadership.', 
                tags: ['Loyal', 'Protective'], imgSrc: 'German Shepherd.jpg',
                health: { 
                    vaccines: 'Up to Date', lastCheckup: 'February 1, 2024', weight: '80 lbs', medications: 'Joint Supplement',
                    fullHistory: [
                        { date: '2024-02-01', record: 'Senior Wellness exam. Started on Glucosamine/Chondroitin for joint support.', category: 'Checkup' },
                        { date: '2023-10-15', record: 'Annual Bordetella vaccine.', category: 'Vaccination' }
                    ]
                }
            },
            { 
                id: 'cocoa', name: 'Cocoa', type: 'Other', breed: 'Holland Lop Rabbit', age: 1, gender: 'Female', size: 'Small', energy: 'Low', goodWithKids: 'Fair', training: 'N/A', 
                description: 'Gentle and quiet companion who enjoys peaceful surroundings and gentle handling. Perfect for quiet homes.', 
                tags: ['Gentle', 'Quiet'], imgSrc: 'Holland Lop Rabbit.jpg',
                health: { 
                    vaccines: 'N/A', lastCheckup: 'March 1, 2024', weight: '4 lbs', medications: 'None Active',
                    fullHistory: [
                        { date: '2024-03-01', record: 'Routine wellness check for exotic pet. Teeth and digestion good.', category: 'Checkup' }
                    ]
                }
            },
            { 
                id: 'Bully', name: 'Bully', type: 'Dog', breed: 'Bulldog', age: 10, gender: 'Male', size: 'Large', energy: 'High', goodWithKids: 'Excellent', training: 'House trained', 
                description: 'Bully is a american breed of companion dog or toy dog. It appeared in Paris in the mid-nineteenth century, apparently the result of cross-breeding of Toy Bulldogs imported from England and local Parisian ratters.', 
                tags: ['Friendly', 'Active','Moody'], imgSrc: 'Bulldog.jpg', 
                health: { 
                    vaccines: 'Up to Date', lastCheckup: 'March 10, 2024', weight: '70 lbs', medications: ' Active',
                    fullHistory: [
                        { date: '2024-03-15', record: 'Annual Checkup. Weight stable. Heartworm prevention administered.', category: 'Checkup' },
                        { date: '2023-11-01', record: 'Rabies booster administered.', category: 'Vaccination' },
                        { date: '2023-09-20', record: 'Dental cleaning performed. Minor tartar removed.', category: 'Procedure' }
                    ]
                }
            },
            { 
                id: 'Muning', name: 'Muning', type: 'Cat', breed: 'Japanese Bobtail', age: 6, gender: 'Female', size: 'Small', energy: 'High', goodWithKids: 'Good', training: 'Litter trained', 
                description: 'Japanese Bobtail is a breed of domestic cat with an unusual bobtail more closely resembling the tail of a rabbit than that of other cats.', 
                tags: ['Calm', 'Independent', 'Playful'], imgSrc: 'Japanese bobtail.jpg',
                health: { 
                    vaccines: 'Needs Booster', lastCheckup: 'May 10, 2024', weight: '15 lbs', medications: 'Flea Prevention',
                    fullHistory: [
                        { date: '2024-01-10', record: 'Routine Checkup. Needs Feline Calicivirus booster. Started on monthly flea control.', category: 'Checkup' },
                        { date: '2023-07-25', record: 'FVRCP vaccination administered.', category: 'Vaccination' },
                        { date: '2022-12-05', record: 'Minor eye infection treated with topical ointment. Resolved.', category: 'Medication' }
                    ]
                }
            },
            { 
                id: 'Whitey', name: 'Whitey', type: 'Dog', breed: 'Askal', age: 1, gender: 'Female', size: 'Medium', energy: 'High', goodWithKids: 'Good', training: 'Advanced commands', 
                description: 'Super smart and energetic dog who loves learning new tricks and agility training.', 
                tags: ['Smart', 'Energetic' , 'Friendly'], imgSrc: 'whitey.jpg',
                health: { 
                    vaccines: 'Up to Date', lastCheckup: 'April 20, 2024', weight: '45 lbs', medications: 'None Active',
                    fullHistory: [
                        { date: '2024-04-20', record: 'Pre-adoption physical exam. Excellent health. Fecal exam negative.', category: 'Checkup' },
                        { date: '2023-03-05', record: 'Distemper/Parvo vaccination completed.', category: 'Vaccination' }
                    ]
                }
            },
            { 
                id: 'Cleo', name: 'Cleo', type: 'Cat', breed: 'British Shorthair', age: 0.5, gender: 'Male', size: 'Small', energy: 'Medium', goodWithKids: 'Excellent', training: 'Litter trained', 
                description: 'New born kitten who loves toys and exploring. Very social and enjoys attention and cuddles.', 
                tags: ['Playful', 'Social' , 'Sleepy'], imgSrc: 'British Shorthair.jpeg',
                health: { 
                    vaccines: 'Kitten Shots Pending', lastCheckup: 'May 5, 2024', weight: '5 lbs', medications: 'Dewormer',
                    fullHistory: [
                        { date: '2024-05-05', record: 'First kitten exam. Dewormer administered. Scheduled for first FVRCP in 2 weeks.', category: 'Checkup' },
                        { date: '2024-05-05', record: 'Initial dose of Pyrantel (Dewormer).', category: 'Medication' }
                    ]
                }
            },
            { 
                id: 'Rusty', name: 'Rusty', type: 'Dog', breed: 'Pomeranian', age: 5, gender: 'Male', size: 'Large', energy: 'Medium', goodWithKids: 'Fair', training: 'Guard training', 
                description: 'Pomeranian is a breed of dog of the Spitz type that is named for the Pomerania region in north-west Poland and north-east Germany in Central Europe.', 
                tags: ['Loyal', 'Protective'], imgSrc: 'Pomeranian.jpg',
                health: { 
                    vaccines: 'Up to Date', lastCheckup: 'February 1, 2024', weight: '80 lbs', medications: 'Joint Supplement',
                    fullHistory: [
                        { date: '2024-02-01', record: 'Senior Wellness exam. Started on Glucosamine/Chondroitin for joint support.', category: 'Checkup' },
                        { date: '2023-10-15', record: 'Annual Bordetella vaccine.', category: 'Vaccination' }
                    ]
                }
            },
            { 
                id: 'Theo', name: 'Theo', type: 'Other', breed: 'Warbler Bird', age: 1, gender: 'Female', size: 'Small', energy: 'Low', goodWithKids: 'Fair', training: 'N/A', 
                description: 'Gentle and quiet companion who enjoys peaceful surroundings and gentle handling.', 
                tags: ['Gentle', 'Quiet'], imgSrc: 'Warbler.jpg',
                health: { 
                    vaccines: 'N/A', lastCheckup: 'March 1, 2024', weight: '4 lbs', medications: 'None Active',
                    fullHistory: [
                        { date: '2024-03-01', record: 'Routine wellness check for exotic pet. Teeth and digestion good.', category: 'Checkup' }
                    ]
                }
            }
        ];

        // --- Navigation Logic (Simple Router) ---
        function navigate(pageId, petId = null, petName = null) { // Added petName
            // Hide all main content sections
            document.querySelectorAll('section[data-page]').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Show the requested section
            const targetSection = document.getElementById(pageId);
            if (targetSection) {
                targetSection.classList.remove('hidden');
            }

            // Update active state in nav bar
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            const activeLink = document.querySelector(`[data-target="${pageId}"]`);
            
            // Logic to set active link
            if (activeLink && ['home-page', 'about-page', 'adopt-page', 'find-pets-page', 'health-page', 'contact-page'].includes(pageId)) {
                 activeLink.classList.add('active');
            } else if (['pet-detail-page', 'find-pets-page'].includes(pageId)) {
                // Keep 'Find Pets' highlighted when viewing details or results
                document.querySelector(`[data-target="find-pets-page"]`).classList.add('active');
            } else if (['health-detail-page', 'health-page'].includes(pageId)) {
                // Keep 'Health Records' highlighted when viewing details or results
                document.querySelector(`[data-target="health-page"]`).classList.add('active');
            }

            // If navigating to detail page, load pet data
            if (pageId === 'pet-detail-page' && petId) {
                renderPetDetails(petId);
            }
            
            // If navigating to health detail page, load pet data
            if (pageId === 'health-detail-page' && petId) {
                renderHealthDetails(petId);
            }
            
            // If navigating to adoption page, pre-fill pet name
            if (pageId === 'adopt-page') {
                prefillAdoptionForm(petName);
            }
        }

        // --- ADOPTION FORM LOGIC ---
        function prefillAdoptionForm(petName) {
            const petNameInput = document.getElementById('app-pet-name');
            const petNameMessage = document.getElementById('pet-name-message');
            const prefilledPetNameSpan = document.getElementById('pre-filled-pet-name');

            if (petName && petNameInput && petNameMessage && prefilledPetNameSpan) {
                petNameInput.value = petName;
                prefilledPetNameSpan.textContent = petName;
                petNameMessage.classList.remove('hidden');
                petNameInput.disabled = true; // Disable input if pre-filled
            } else if (petNameInput && petNameMessage) {
                 // Clear previous data if not pre-filling
                petNameInput.value = '';
                petNameMessage.classList.add('hidden');
                petNameInput.disabled = false;
            }
        }

        function startAdoptionProcess(petName) {
            navigate('adopt-page', null, petName);
        }

        // --- PET DETAIL RENDERING ---
        function renderPetDetails(petId) {
            const pet = PETS_DATA.find(p => p.id === petId);
            if (!pet) {
                // Using console.error instead of alert as per instructions
                console.error('Pet details not found for ID:', petId);
                navigate('find-pets-page');
                return;
            }

            // Image
            document.getElementById('detail-img').src = pet.imgSrc;
            
            // Text Content
            document.getElementById('detail-name').textContent = pet.name;
            document.getElementById('detail-meta').textContent = `${pet.breed} • ${pet.age} years old • ${pet.gender}`;
            document.getElementById('detail-description').textContent = pet.description;
            
            // Detail Boxes
            document.getElementById('detail-size-value').textContent = `${pet.size} (${pet.size === 'Large' ? '65 lbs' : pet.size === 'Medium' ? '30 lbs' : '10 lbs'})`;
            document.getElementById('detail-energy-value').textContent = pet.energy;
            document.getElementById('detail-kids-value').textContent = pet.goodWithKids;
            document.getElementById('detail-training-value').textContent = pet.training;
            
            // Update button text for adoption
            document.getElementById('adopt-pet-name').textContent = pet.name;
        }

        // --- FILTERING LOGIC (The core filtering function for FIND PETS) ---
        function applyFilters(type = null, age = null, size = null) {
            
            const typeSelect = document.getElementById('filter-type');
            const ageSelect = document.getElementById('filter-age');
            const sizeSelect = document.getElementById('filter-size');

            // Set filter criteria based on arguments (if provided from Hero filter) or current UI state (if applying from Find Pets page)
            const typeFilter = type !== null ? type : typeSelect ? typeSelect.value : 'All Types';
            const ageFilter = age !== null ? age : ageSelect ? ageSelect.value : 'All Ages';
            const sizeFilter = size !== null ? size : sizeSelect ? sizeSelect.value : 'All Sizes';
            
            // Update the UI controls on the Find Pets page to reflect the chosen filters
            if (typeSelect) typeSelect.value = typeFilter;
            if (ageSelect) ageSelect.value = ageFilter;
            if (sizeSelect) sizeSelect.value = sizeFilter;

            const petCardsContainer = document.getElementById('find-pets-grid');
            if (!petCardsContainer) return; // Exit if container doesn't exist on the current page

            petCardsContainer.innerHTML = ''; // Clear current pets

            const filteredPets = PETS_DATA.filter(pet => {
                // Type Filter
                const typeMatch = (typeFilter === 'All Types' || pet.type === typeFilter);

                // Age Filter (Matches filter value options: 'All Ages', 'Puppy/Kitten', 'Adult', 'Senior')
                let ageMatch = true;
                if (ageFilter === 'Puppy/Kitten') {
                    ageMatch = pet.age <= 1;
                } else if (ageFilter === 'Adult') {
                    ageMatch = pet.age > 1 && pet.age <= 7;
                } else if (ageFilter === 'Senior') {
                    ageMatch = pet.age > 7;
                } else if (ageFilter.includes('All')) { // Catches 'All Ages' and 'Age Range' (from hero)
                    ageMatch = true;
                }

                // Size Filter
                const sizeMatch = (sizeFilter === 'All Sizes' || pet.size === sizeFilter);

                return typeMatch && ageMatch && sizeMatch;
            });

            if (filteredPets.length === 0) {
                 petCardsContainer.innerHTML = '<p class="text-center text-lg text-gray-500 col-span-full py-10">No pets match your current filters. Try adjusting your selections.</p>';
            } else {
                 filteredPets.forEach(pet => {
                     petCardsContainer.innerHTML += createPetCardHTML(pet);
                 });
            }
        }
        
        // --- HOME PAGE FILTER LOGIC ---
        function applyHeroFilters() {
             const typeFilter = document.getElementById('hero-filter-type').value;
             const ageFilter = document.getElementById('hero-filter-age').value;
             
             // Navigate to the find pets page
             navigate('find-pets-page');
             
             // Apply the selected filters from the Home page hero section
             applyFilters(typeFilter, ageFilter, 'All Sizes'); 
        }


        // Function to generate a single pet card HTML (Used for Find Pets)
        function createPetCardHTML(pet) {
             const tagsHTML = pet.tags.map(tag => 
                `<span class="tag bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">${tag}</span>`
             ).join('');

             return `
                 <div class="pet-card border border-gray-200 rounded-xl shadow-lg overflow-hidden transition hover:shadow-xl">
                    <div class="relative">
                        <img src="${pet.imgSrc}" alt="${pet.name}" class="object-cover">
                        <div class="availability absolute top-3 right-3 text-white px-3 py-1 rounded-full text-xs font-semibold">Available</div>
                    </div>
                    <div class="p-4 text-left">
                        <h3 class="text-xl font-bold">${pet.name}</h3>
                        <p class="details text-sm text-gray-500 mb-3">${pet.breed} • ${pet.age} years old • ${pet.gender}</p>
                        <p class="description text-gray-700 text-sm mb-4">${pet.description.substring(0, 80)}...</p>
                        <div class="tags flex gap-2 mb-4">
                            ${tagsHTML}
                        </div>
                        <button class="view-details w-full py-2 rounded-lg font-medium" style="background-color: var(--primary-color); color: white;" onclick="navigate('pet-detail-page', '${pet.id}')">View Details</button>
                    </div>
                </div>
            `;
        }
        
        // --- HEALTH RECORDS LOGIC (New Functions) ---
        
        // Filter function for Health Records
        function applyHealthFilters() {
            const typeSelect = document.getElementById('health-filter-type');
            const vaccineSelect = document.getElementById('health-filter-vaccine');
            
            const typeFilter = typeSelect ? typeSelect.value : 'All Types';
            const vaccineFilter = vaccineSelect ? vaccineSelect.value : 'All Status';

            const healthCardsContainer = document.getElementById('health-records-grid');
            if (!healthCardsContainer) return; 

            healthCardsContainer.innerHTML = ''; // Clear current pets

            const filteredPets = PETS_DATA.filter(pet => {
                // Type Filter
                const typeMatch = (typeFilter === 'All Types' || pet.type === typeFilter);

                // Vaccine Status Filter
                let vaccineMatch = true;
                if (vaccineFilter === 'Up to Date') {
                    vaccineMatch = pet.health.vaccines === 'Up to Date';
                } else if (vaccineFilter === 'Attention Needed') {
                    vaccineMatch = pet.health.vaccines !== 'Up to Date' && pet.health.vaccines !== 'N/A';
                } else if (vaccineFilter === 'N/A') {
                    vaccineMatch = pet.health.vaccines === 'N/A';
                }

                return typeMatch && vaccineMatch;
            });

            if (filteredPets.length === 0) {
                 healthCardsContainer.innerHTML = '<p class="text-center text-lg text-gray-500 col-span-full py-10">No Health Records found matching the filter.</p>';
            } else {
                 filteredPets.forEach(pet => {
                     healthCardsContainer.innerHTML += createHealthCardHTML(pet);
                 });
            }
        }
        
        // Function to generate a single health card HTML
        function createHealthCardHTML(pet) {
            const statusColor = pet.health.vaccines === 'Up to Date' ? 'text-green-600' : 
                                pet.health.vaccines === 'N/A' ? 'text-gray-500' : 'text-red-500';
            const statusText = pet.health.vaccines === 'Up to Date' ? 'Up to Date' : 
                               pet.health.vaccines === 'N/A' ? 'Not Applicable' : 'Attention Needed';

            return `
                 <div class="health-card border border-gray-200 rounded-xl shadow-lg overflow-hidden transition hover:shadow-xl">
                    <div class="relative">
                        <img src="${pet.imgSrc}" alt="${pet.name}" class="object-cover">
                        <div class="availability absolute top-3 right-3 bg-gray-800 text-white px-3 py-1 rounded-full text-xs font-semibold">${pet.type}</div>
                    </div>
                    <div class="p-4 text-left">
                        <h3 class="text-xl font-bold">${pet.name}</h3>
                        <p class="details text-sm text-gray-500 mb-3">${pet.breed} • ${pet.age} years old</p>
                        
                        <div class="space-y-3 pt-2 border-t mt-4">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    Vaccination
                                </span>
                                <span class="health-status ${statusColor}">${statusText}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-9 0V3h4v2M9 5h6" /></svg>
                                    Last Checkup
                                </span>
                                <span class="text-sm text-gray-500">${pet.health.lastCheckup}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6a2 2 0 002-2v-3.382A6.974 6.974 0 0020 15V8a2 2 0 10-4 0v3h-4v2h4m-4 0v-2h4v2" /></svg>
                                    Weight
                                </span>
                                <span class="text-sm text-gray-500">${pet.health.weight}</span>
                            </div>
                        </div>
                        
                        <!-- UPDATED: Added navigation to health-detail-page -->
                        <button class="w-full mt-4 py-2 rounded-lg font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 transition" onclick="navigate('health-detail-page', '${pet.id}')">View Full Records</button>
                    </div>
                </div>
            `;
        }
        
        // Function to render the Health Details Page
        function renderHealthDetails(petId) {
            const pet = PETS_DATA.find(p => p.id === petId);
            if (!pet) {
                console.error('Health details not found for ID:', petId);
                navigate('health-page');
                return;
            }
            
            document.getElementById('health-detail-pet-name').textContent = pet.name;
            document.getElementById('health-detail-pet-meta').textContent = `${pet.breed} • ${pet.age} years old • ${pet.gender}`;
            document.getElementById('health-detail-pet-img').src = pet.imgSrc;
            
            const recordsContainer = document.getElementById('health-records-list');
            recordsContainer.innerHTML = '';
            
            // Generate list of records
            pet.health.fullHistory.forEach(record => {
                let categoryColor = 'bg-gray-100 text-gray-800';
                if (record.category === 'Vaccination') categoryColor = 'bg-green-100 text-green-800';
                if (record.category === 'Checkup') categoryColor = 'bg-blue-100 text-blue-800';
                if (record.category === 'Medication') categoryColor = 'bg-purple-100 text-purple-800';
                if (record.category === 'Procedure') categoryColor = 'bg-red-100 text-red-800';

                recordsContainer.innerHTML += `
                    <div class="health-detail-card bg-white p-4 shadow-md rounded-lg mb-4 hover:shadow-lg transition">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider px-2 py-1 rounded-full ${categoryColor}">${record.category}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-500">${record.date}</span>
                        </div>
                        <p class="text-gray-700 leading-relaxed">${record.record}</p>
                    </div>
                `;
            });
            
             // Summary Section
            document.getElementById('summary-vaccines').textContent = pet.health.vaccines;
            document.getElementById('summary-checkup').textContent = pet.health.lastCheckup;
            document.getElementById('summary-weight').textContent = pet.health.weight;
            document.getElementById('summary-meds').textContent = pet.health.medications;
        }


        // Set initial page load
        window.onload = () => {
            // Initial render of all pets on the Find Pets page and update filter UI defaults
            applyFilters('All Types', 'All Ages', 'All Sizes'); 
            
            // Initial render of all pets on the Health Records page
            applyHealthFilters(); 
            
            // Add event listeners to filters (Find Pets page)
            const applyFiltersBtn = document.getElementById('apply-filters-btn');
            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', () => applyFilters());
            }
            
            // Add event listeners to filters (Health Records page)
            const applyHealthFiltersBtn = document.getElementById('apply-health-filters-btn');
            if (applyHealthFiltersBtn) {
                applyHealthFiltersBtn.addEventListener('click', () => applyHealthFilters());
            }


            // Navigate to Home by default
            navigate('home-page');
            
            // Handle adoption form submission
            const adoptionForm = document.getElementById('adoption-form');
            if (adoptionForm) {
                 adoptionForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    // Check if the user is logged in (assuming a global variable or function for this)
                    if (typeof firebase === 'undefined' || !firebase.auth().currentUser) {
                        alert('Please register or log in to submit an adoption application.');
                        return; // Stop the form submission
                    }
                    // Log to console instead of alert
                    console.log('Adoption Application Submitted!');
                    alert('Thank you for your application! We will review it shortly.'); 
                    // Reset form fields
                    e.target.reset();
                    // Navigate back to home or show success message on page
                    navigate('home-page'); 
                });
            }

            // Handle contact form submission (NEW LOGIC)
            const contactForm = document.getElementById('contact-form');

if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Check if the user is logged in
        if (typeof firebase === 'undefined' || !firebase.auth().currentUser) {
            alert('Please register or log in to send a message.');
            return;
        }

        const name = document.getElementById('contact-name').value;
        const email = document.getElementById('contact-email').value;
        const subject = document.getElementById('contact-subject').value;
        const message = document.getElementById('contact-message').value;

        // Log submission data
        console.log('Contact Form Submitted:');
        console.log(`Name: ${name}`);
        console.log(`Email: ${email}`);
        console.log(`Subject: ${subject}`);
        console.log(`Message: ${message}`);

        alert('Thank you for your message! We will get back to you shortly.');

        // 💡 Correct way to reset the form
        e.target.reset();

    });
}

        };
/* -------------------------------------------------------------
   LOGIN + SIGNUP AJAX
------------------------------------------------------------- */
function handleFormSubmit(e, type) {
  e.preventDefault();

  if (type === "Login") {
    const fd = new FormData();
    fd.append("email", document.getElementById("login-email").value);
    fd.append("password", document.getElementById("login-password").value);

    fetch("login.php", { method: "POST", body: fd })
      .then(r => r.text())
      .then(res => {
        if (res === "user") {
          // redirect to user dashboard / user account
          window.location.href = "user.html";
        }
        else if (res === "admin") {
          // redirect to admin dashboard
          window.location.href = "admin.html";
        }
        else {
          showMessage("Invalid login.");
        }
      });
  }

  if (type === "Create Account") {
    const pw = document.getElementById("signup-password").value;
    const cpw = document.getElementById("signup-confirm-password").value;

    if (pw !== cpw) return showMessage("Passwords do not match.");

    const fd = new FormData();
    fd.append("first_name", document.getElementById("signup-first-name").value);
    fd.append("last_name", document.getElementById("signup-last-name").value);
    fd.append("birthday", document.getElementById("signup-birthday").value);
    fd.append("mobile", document.getElementById("signup-mobile").value);
    fd.append("address", document.getElementById("signup-address").value);
    fd.append("email", document.getElementById("signup-email").value);
    fd.append("password", pw);

    fetch("signup.php", { method: "POST", body: fd })
      .then(r => r.text())
      .then(res => {
        if (res === "success") {
          showMessage("Account created!");
          showView("login");
        } else {
          showMessage("Signup failed.");
        }
      });
  }
}


/* -------------------------------------------------------------
   ADOPTION + CONTACT (LOCAL ONLY)
------------------------------------------------------------- */
function submitAdoption(e) {
  e.preventDefault();
  showMessage("Application submitted!");
  e.target.reset();
}

function submitContact(e) {
  e.preventDefault();
  showMessage("Message sent!");
  e.target.reset();
}

/* -------------------------------------------------------------
   SHOW MESSAGE
------------------------------------------------------------- */
function showMessage(msg) {
  const box = document.getElementById("message-box");
  box.textContent = msg;
  box.classList.remove("opacity-0");

  setTimeout(() => {
    box.classList.add("opacity-0");
  }, 2500);
}

/* -------------------------------------------------------------
   INIT
------------------------------------------------------------- */
document.addEventListener("DOMContentLoaded", () => {
  renderCards(PETS_DATA, "featured-pets-container");
  renderCards(PETS_DATA, "find-pets-grid");
  renderHealth();
  navigate("home-page");
});
</script>
<!-- PART 4 END -->

</body>
</html>
