<script setup>
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import {useTitle} from "@/Composables/useTitle.js";
import heroImage from "@/Assets/heroImage.webp";
import { Link as InertiaLink } from '@inertiajs/vue3';

useTitle('Features | Partydos');
</script>

<template>
    <DefaultLayout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-4xl font-bold text-center mb-12">Discover Partydos Features</h1>

            <section class="hero-section text-center mb-16">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">Bring people together with Partydos!</h2>
                <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                    Partydos makes planning and attending events effortless and enjoyable. Discover how our features help you create memorable experiences.
                </p>
                <img :src="heroImage" alt="Partydos Hero Image" class="mx-auto rounded-lg shadow-lg max-h-96" />
            </section>

            <section class="core-features mb-16">
                <h3 class="text-3xl font-semibold text-center mb-10">Core Event Management</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Calendar+</span> <!-- Icon: Calendar Plus -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Easy Event Creation</h4>
                        <p class="text-gray-600 text-center">Quickly set up events with titles, descriptions, dates, times, and locations.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Users</span> <!-- Icon: Users Group -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Flexible Attendance</h4>
                        <p class="text-gray-600 text-center">Manage RSVPs from both registered users and guest participants seamlessly.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Calendar</span> <!-- Icon: Calendar Days -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Calendar Integration</h4>
                        <p class="text-gray-600 text-center">Allow attendees to easily add events to their personal calendars (ICS, Google Calendar).</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Map Pin</span> <!-- Icon: Map Pin -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Location Support</h4>
                        <p class="text-gray-600 text-center">Integrate with mapping services and provide easy access to event locations.</p>
                    </div>
                </div>
            </section>

            <section class="invitation-system mb-16">
                <h3 class="text-3xl font-semibold text-center mb-10">Powerful Invitation System</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Link</span> <!-- Icon: Link -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Unique Event Links</h4>
                        <p class="text-gray-600 text-center">Every event gets a unique, shareable link for easy distribution.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Share</span> <!-- Icon: Envelope / Share -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Versatile Invitations</h4>
                        <p class="text-gray-600 text-center">Invite attendees via direct link sharing, email invitations, or built-in social sharing.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">RSVP</span> <!-- Icon: Check Circle / X Circle -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">RSVP Tracking</h4>
                        <p class="text-gray-600 text-center">Easily track responses as attendees accept or decline invitations.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Guests</span> <!-- Icon: User Group -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Guest List Overview</h4>
                        <p class="text-gray-600 text-center">Manage and view your guest list, including both registered users and guest attendees.</p>
                    </div>
                </div>
            </section>

            <section class="user-experience mb-16">
                <h3 class="text-3xl font-semibold text-center mb-10">Seamless User Experience</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Devices</span> <!-- Icon: Device Phone Mobile / Device Tablet / Computer Desktop -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Modern & Responsive</h4>
                        <p class="text-gray-600 text-center">Enjoy a sleek interface that works flawlessly on desktops, tablets, and mobile devices.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Nav</span> <!-- Icon: Adjustments Horizontal / Bars 3 -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Intuitive Navigation</h4>
                        <p class="text-gray-600 text-center">Easily find what you need with a user-friendly layout and clear navigation paths.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Alerts</span> <!-- Icon: Bell Alert / Wifi -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Real-time Updates</h4>
                        <p class="text-gray-600 text-center">Get immediate feedback on RSVPs and see event changes as they happen.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">Profile</span> <!-- Icon: User Circle -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Profile Management</h4>
                        <p class="text-gray-600 text-center">Manage your user profile, track your event history, and oversee your created events.</p>
                    </div>
                </div>
            </section>

            <section class="upcoming-features mb-16">
                <h3 class="text-3xl font-semibold text-center mb-10">Exciting Features Coming Soon!</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-gray-400 text-xs">Cart</span> <!-- Icon: Shopping Cart -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Event Shopping Lists</h4>
                        <p class="text-gray-500 text-center text-sm">Collaborate on and manage lists of items needed for your events.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-gray-400 text-xs">Date Poll</span> <!-- Icon: Calendar Check -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Smart Date Picker</h4>
                        <p class="text-gray-500 text-center text-sm">Find the perfect event date that works for most attendees with an intelligent polling tool.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-gray-400 text-xs">AI Ideas</span> <!-- Icon: Sparkles / Wand -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">AI Activity Suggestions</h4>
                        <p class="text-gray-500 text-center text-sm">Get AI-powered suggestions for fun and engaging event activities.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-gray-400 text-xs">Design</span> <!-- Icon: Paint Brush / Palette -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Custom Invite Designs</h4>
                        <p class="text-gray-500 text-center text-sm">Personalize your event invitations with beautiful, customizable templates.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-gray-400 text-xs">Embed</span> <!-- Icon: Code Bracket -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Embeddable Event Widget</h4>
                        <p class="text-gray-500 text-center text-sm">Showcase your public events directly on your own website or blog.</p>
                    </div>
                    <div class="feature-card bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                        <div class="text-center h-12 w-12 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-gray-400 text-xs">Polls</span> <!-- Icon: Chart Bar -->
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-center">Interactive Event Polls</h4>
                        <p class="text-gray-500 text-center text-sm">Engage your attendees by creating polls for event activities, food choices, and more.</p>
                    </div>
                </div>
            </section>

            <section class="selling-points mb-16 bg-gray-50 py-12">
                <h3 class="text-3xl font-semibold text-center mb-10">Why Choose Partydos?</h3>
                <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4 max-w-4xl mx-auto">
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Effortless Event Planning</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Flexible Participation</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Modern Technology</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Comprehensive Tools</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Calendar Integration</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Social Features</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Future-Ready</span>
                    </li>
                </ul>
            </section>

            <section class="call-to-action text-center py-16">
                <h3 class="text-3xl font-semibold mb-6">Ready to Plan Your Next Event?</h3>
                <p class="text-lg text-gray-600 mb-8 max-w-xl mx-auto">
                    Join Partydos today and experience the easiest way to bring people together. Sign up for free or create your first event now!
                </p>
                <InertiaLink href="/register" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition duration-300 text-lg">
                    Get Started Now
                </InertiaLink>
            </section>
        </div>
    </DefaultLayout>
</template>
