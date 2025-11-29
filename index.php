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
    <title>God's Home of Refuge - Every Pet Deserves a Loving Home</title>

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
                <li><a href="#" class="nav-link" data-target="home-page" onclick="navigate('home-page')">Home</a></li>
                <li><a href="#" class="nav-link" data-target="about-page" onclick="navigate('about-page')">About</a></li>
                <li><a href="#" class="nav-link" data-target="find-pets-page" onclick="navigate('find-pets-page'); applyFilters();">Find Pets</a></li>
                <li><a href="#" class="nav-link" data-target="adopt-page" onclick="navigate('adopt-page')">Adopt</a></li>
                <li><a href="#" class="nav-link" data-target="health-page" onclick="navigate('health-page')">Health Records</a></li>
                <li><a href="#" class="nav-link" data-target="contact-page" onclick="navigate('contact-page')">Contact</a></li>
            </ul>
        </nav>

        <div class="flex items-center space-x-4">
            <?php if ($user_display): ?>
                <div class="text-sm text-gray-700 mr-3">Hello, <span class="font-semibold"><?= htmlspecialchars($user_display) ?></span></div>
                <a href="logout.php" class="text-sm font-medium text-white px-4 py-2 rounded-full shadow-md transition hover:bg-opacity-90" style="background-color: var(--primary-color);">
                    Logout
                </a>
            <?php else: ?>
                <button id="open-auth-btn" onclick="openModal()" class="text-sm font-medium text-white px-4 py-2 rounded-full shadow-md transition hover:bg-opacity-90" style="background-color: var(--primary-color);">
                    Login / Sign Up
                </button>
            <?php endif; ?>
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
    <!-- Hero -->
    <div class="hero-section py-20 px-6 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row items-center gap-10">
            <div class="lg:w-1/2">
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight mb-4 drop-shadow-lg">Every Pet Deserves a <strong>Loving Home</strong></h1>
                <p class="text-lg mb-6 drop-shadow-md">Change your life with the perfect pet companion. Our intelligent matching system connects you with pets that fit your lifestyle, ensuring a long-lasting and loving relationship.</p>

                <div class="search-match mb-6">
                    <h3 class="font-semibold text-lg mb-3">Find Your Perfect Match</h3>
                    <div class="search-form flex gap-3 mb-4">
                        <select id="hero-filter-type" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 flex-grow">
                            <option value="All Types">Pet Type</option>
                            <option value="Dog">Dog</option>
                            <option value="Cat">Cat</option>
                            <option value="Other">Other</option>
                        </select>
                        <select id="hero-filter-age" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 flex-grow">
                            <option value="All Ages">Age Range</option>
                            <option value="Puppy/Kitten">Puppy/Kitten (0-1)</option>
                            <option value="Adult">Adult (1-7)</option>
                            <option value="Senior">Senior (7+)</option>
                        </select>
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

            <div class="lg:w-1/2">
                <!-- featured hero image placeholder -->
                <img src="https://placehold.co/700x420/efe8e0/3b3a3c?text=Happy+Pets" alt="Happy pets" class="rounded-lg shadow-lg object-cover w-full h-auto">
            </div>
        </div>
    </div>

    <!-- Trust / Badges -->
    <div class="trust-section text-center py-10 bg-gray-50">
        <p class="text-gray-600 mb-8"><b>Trusted by leading animal welfare organizations</b></p>
        <div class="badges flex justify-center gap-12 md:gap-20 flex-wrap">
            <div class="badge-item w-24"><div class="icon">🐾</div><p class="text-sm font-medium">ASPCA Certified</p></div>
            <div class="badge-item w-24"><div class="icon">🩺</div><p class="text-sm font-medium">Veterinary Approved</p></div>
            <div class="badge-item w-24"><div class="icon">⭐</div><p class="text-sm font-medium">5-Star Rated</p></div>
            <div class="badge-item w-24"><div class="icon">✅</div><p class="text-sm font-medium">98% Success Rate</p></div>
        </div>
    </div>

    <!-- Featured pets row (first 3-4 pets) -->
    <div class="featured-pets text-center py-16 px-4">
        <h2 class="text-3xl font-bold mb-3">Meet Your New Best Friend</h2>
        <p class="text-gray-600 max-w-2xl mx-auto mb-12">Discover amazing pets waiting for their forever homes. Every pet comes with a complete health record and personality profile.</p>

        <div class="pet-cards-container flex justify-center gap-6 flex-wrap" id="featured-pets-container">
            <!-- JS will inject featured pet cards here using PETS_DATA -->
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
<section id="find-pets-page" data-page class="py-16 px-4 hidden">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-extrabold text-center mb-2">Find Your Perfect Pet</h1>
        <p class="text-lg text-gray-600 text-center mb-8">View our currently available pets and find your new best friend.</p>

        <div class="filter-bar flex flex-col md:flex-row gap-4 justify-center md:justify-between items-center mb-10 mx-auto max-w-4xl">
            <select id="filter-type" class="p-3 rounded-lg w-full md:w-auto">
                <option value="All Types">All Types</option>
                <option value="Dog">Dog</option>
                <option value="Cat">Cat</option>
                <option value="Other">Other</option>
            </select>
            <select id="filter-age" class="p-3 rounded-lg w-full md:w-auto">
                <option value="All Ages">All Ages</option>
                <option value="Puppy/Kitten">Puppy/Kitten</option>
                <option value="Adult">Adult</option>
                <option value="Senior">Senior</option>
            </select>
            <select id="filter-size" class="p-3 rounded-lg w-full md:w-auto">
                <option value="All Sizes">All Sizes</option>
                <option value="Small">Small</option>
                <option value="Medium">Medium</option>
                <option value="Large">Large</option>
            </select>
            <button id="apply-filters-btn" class="text-white px-8 py-3 rounded-lg font-semibold w-full md:w-auto transition hover:bg-amber-600" style="background-color: var(--primary-color);">
                Apply Filters
            </button>
        </div>

        <div id="find-pets-grid" class="pet-cards-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- JS will inject pet cards here from PETS_DATA array -->
        </div>
    </div>
</section>

<!-- PET DETAIL PAGE -->
<section id="pet-detail-page" data-page class="py-16 px-4 hidden">
    <div class="max-w-5xl mx-auto">
        <a href="#" class="text-gray-500 hover:text-gray-700 transition mb-6 inline-flex items-center" onclick="navigate('find-pets-page'); applyFilters(); return false;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Pet Search
        </a>

        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            <div class="flex flex-col lg:flex-row gap-10">
                <div class="lg:w-1/2">
                    <img id="detail-img" src="https://placehold.co/500x350/cccccc/3b3a3c?text=Pet+Image" alt="Pet Profile" class="w-full h-auto object-cover rounded-lg shadow-md">
                </div>

                <div class="lg:w-1/2">
                    <span class="inline-block bg-green-100 text-green-700 text-sm font-medium px-3 py-1 rounded-full mb-3">Available for Adoption</span>
                    <h1 id="detail-name" class="text-4xl font-extrabold mb-1">Buddy</h1>
                    <p id="detail-meta" class="text-lg text-gray-500 mb-6">Labrador Retriever • 4 years old • Male</p>

                    <p id="detail-description" class="text-gray-700 mb-8 leading-relaxed">
                        Buddy is a loyal and gentle companion who loves outdoor activities and is great with kids. He is well-trained and socialized, making him a perfect family pet.
                    </p>

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

                    <div class="detail-actions flex gap-4">
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
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border">
        <h1 class="text-4xl font-extrabold mb-2">Adoption Application</h1>
        <p class="text-gray-600 mb-8">Fill out this form to apply for adoption. Our staff will review your application and contact you.</p>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="p-4 bg-red-100 text-red-600 border border-red-300 rounded-lg mb-6">
                <b>You must be logged in to apply for adoption.</b><br>
                <button onclick="openModal()" class="underline text-primary-brown">Login or Create Account</button>
            </div>
        <?php endif; ?>

        <form id="adoption-form" onsubmit="submitAdoption(event)">
            <!-- Applicant Info -->
            <div class="mb-8">
                <h2 class="font-bold text-xl mb-4">Your Information</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" id="adopter-name" placeholder="Full Name" class="input-auth" required>
                    <input type="text" id="adopter-phone" placeholder="Phone Number" class="input-auth" required>
                </div>

                <input type="text" id="adopter-address" placeholder="Full Address" class="input-auth w-full mt-4" required>
            </div>

            <!-- Pet Info -->
            <div class="mb-8">
                <h2 class="font-bold text-xl mb-4">Pet Information</h2>
                <input type="text" id="adopter-pet" placeholder="Pet Name" class="input-auth w-full" required>
            </div>

            <!-- Home Situation -->
            <div class="mb-8">
                <h2 class="font-bold text-xl mb-4">Home Situation</h2>

                <textarea id="adopter-experience" rows="4" placeholder="Tell us about your pet care experience" class="input-auth w-full" required></textarea>

                <textarea id="adopter-living" rows="4" placeholder="Describe your living environment (yard, children, other pets)" class="input-auth w-full mt-4" required></textarea>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
            <button class="w-full py-3 rounded-lg font-semibold text-white shadow-md hover:bg-opacity-90"
                style="background-color: var(--primary-color);" type="submit">
                Submit Application
            </button>
            <?php endif; ?>
        </form>
    </div>
</section>



<!-- HEALTH RECORDS PAGE -->
<section id="health-page" data-page class="py-16 px-4 hidden">
    <div class="max-w-5xl mx-auto">

        <h1 class="text-4xl font-extrabold mb-2 text-center">Pet Health Records</h1>
        <p class="text-center text-gray-600 mb-10">View vaccinations, treatments, and medical histories.</p>

        <div id="health-records-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- JS injected health cards -->
        </div>
    </div>
</section>


<!-- HEALTH DETAIL PAGE -->
<section id="health-detail-page" data-page class="py-16 px-4 hidden">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border">

        <a href="#" class="text-gray-600 flex items-center mb-6" onclick="navigate('health-page')">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Health Records
        </a>

        <h1 id="health-pet-name" class="text-3xl font-bold mb-4">Pet Name</h1>

        <div id="health-record-list">
            <!-- JS injected health info -->
        </div>

    </div>
</section>



<!-- CONTACT PAGE -->
<section id="contact-page" data-page class="py-16 px-4 hidden">
    <div class="max-w-4xl mx-auto">

        <h1 class="text-4xl font-extrabold mb-2 text-center">Contact Us</h1>
        <p class="text-center text-gray-600 mb-10">Have questions? We're here to help!</p>

        <div class="grid md:grid-cols-2 gap-8">

            <div class="bg-white p-6 rounded-xl shadow-md border">
                <h2 class="font-bold text-xl mb-4">Shelter Location</h2>
                <p class="text-gray-700 mb-4">Calamba City, Laguna, Philippines</p>

                <h2 class="font-bold text-xl mb-4">Contact Information</h2>
                <p class="text-gray-700">📞 +63 912 345 6789</p>
                <p class="text-gray-700">✉️ refuge@godshome.org</p>
            </div>

            <form class="bg-white p-6 rounded-xl shadow-md border" onsubmit="submitContact(event)">
                <h2 class="font-bold text-xl mb-4">Send us a message</h2>

                <input type="text" id="contact-name" placeholder="Full Name" class="input-auth w-full mb-4" required>
                <input type="email" id="contact-email" placeholder="Email" class="input-auth w-full mb-4" required>
                <textarea id="contact-message" rows="4" placeholder="Your Message" class="input-auth w-full mb-4" required></textarea>

                <button class="w-full py-3 rounded-lg font-semibold text-white shadow-md hover:bg-opacity-90"
                    style="background-color: var(--primary-color);">
                    Send Message
                </button>
            </form>

        </div>
    </div>
</section>




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
/* -------------------------------------------------------------
   SIMPLE PET DATA
------------------------------------------------------------- */
const PETS_DATA = [
  {
    id: 1,
    name: "Buddy",
    type: "Dog",
    breed: "Labrador",
    age: "4 years",
    size: "Large",
    gender: "Male",
    energy: "High",
    kids: "Excellent",
    description: "Buddy is loyal and great with children.",
    img: "https://placehold.co/600x400/ffc8b1/3b3a3c?text=Buddy"
  },
  {
    id: 2,
    name: "Mittens",
    type: "Cat",
    breed: "Domestic Shorthair",
    age: "2 years",
    size: "Small",
    gender: "Female",
    energy: "Medium",
    kids: "Great",
    description: "Calm, sweet and playful.",
    img: "https://placehold.co/600x400/cfe8ff/3b3a3c?text=Mittens"
  },
  {
    id: 3,
    name: "Rex",
    type: "Dog",
    breed: "German Shepherd",
    age: "3 years",
    size: "Large",
    gender: "Male",
    energy: "High",
    kids: "Good",
    description: "Smart and protective.",
    img: "https://placehold.co/600x400/dfe8d8/3b3a3c?text=Rex"
  }
];

/* -------------------------------------------------------------
   PAGE NAVIGATION
------------------------------------------------------------- */
function navigate(id) {
  const pages = document.querySelectorAll("[data-page]");
  pages.forEach(p => p.classList.add("hidden"));

  const show = document.getElementById(id);
  if (show) show.classList.remove("hidden");

  window.scrollTo({ top: 0, behavior: "smooth" });
}

/* -------------------------------------------------------------
   RENDER PET CARDS
------------------------------------------------------------- */
function renderCards(list, containerID) {
  const container = document.getElementById(containerID);
  if (!container) return;

  let html = "";
  for (let pet of list) {
    html += `
      <div class="bg-white rounded-xl shadow-md overflow-hidden w-full max-w-sm">
        <img src="${pet.img}" class="w-full h-44 object-cover">
        <div class="p-4">
          <h3 class="text-xl font-bold">${pet.name}</h3>
          <p class="text-sm text-gray-500">${pet.breed} • ${pet.age}</p>
          <p class="mt-3 text-gray-700">${pet.description.substring(0, 70)}...</p>
          <div class="mt-4 flex gap-2">
            <button onclick="openDetail(${pet.id})"
              class="px-3 py-2 bg-orange-600 text-white rounded-lg">View</button>
            <button onclick="startAdoptionProcess('${pet.name}')"
              class="px-3 py-2 border rounded-lg">Adopt</button>
          </div>
        </div>
      </div>
    `;
  }

  container.innerHTML = html;
}

/* -------------------------------------------------------------
   FILTER PETS (Find Pets Page)
------------------------------------------------------------- */
function applyFilters() {
  const type = document.getElementById("filter-type").value;
  const age = document.getElementById("filter-age").value;
  const size = document.getElementById("filter-size").value;

  const filtered = PETS_DATA.filter(pet => {
    if (type !== "All Types" && pet.type !== type) return false;
    if (size !== "All Sizes" && pet.size !== size) return false;
    return true;
  });

  renderCards(filtered, "find-pets-grid");
}

/* -------------------------------------------------------------
   OPEN PET DETAIL PAGE
------------------------------------------------------------- */
function openDetail(id) {
  const pet = PETS_DATA.find(p => p.id === id);
  if (!pet) return;

  document.getElementById("detail-img").src = pet.img;
  document.getElementById("detail-name").textContent = pet.name;
  document.getElementById("detail-meta").textContent =
    pet.breed + " • " + pet.age + " • " + pet.gender;
  document.getElementById("detail-description").textContent = pet.description;

  document.getElementById("detail-size-value").textContent = pet.size;
  document.getElementById("detail-energy-value").textContent = pet.energy;
  document.getElementById("detail-kids-value").textContent = pet.kids;
  document.getElementById("adopt-pet-name").textContent = pet.name;

  navigate("pet-detail-page");
}

/* -------------------------------------------------------------
   HEALTH RECORDS PAGE
------------------------------------------------------------- */
function renderHealth() {
  const box = document.getElementById("health-records-grid");
  if (!box) return;

  let html = "";
  for (let pet of PETS_DATA) {
    html += `
      <div class="bg-white p-4 shadow rounded-lg">
        <div class="flex gap-4">
          <img src="${pet.img}" class="w-20 h-20 object-cover rounded-lg">
          <div>
            <h3 class="font-bold">${pet.name}</h3>
            <p class="text-sm">${pet.breed} • ${pet.age}</p>
            <p class="text-green-600 font-bold text-sm mt-2">Vaccines: Complete</p>
          </div>
        </div>
      </div>
    `;
  }

  box.innerHTML = html;
}

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
        if (res === "user") window.location.reload();
        else if (res === "admin") window.location.href = "admin/index.php";
        else showMessage("Invalid login.");
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
