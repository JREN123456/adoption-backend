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