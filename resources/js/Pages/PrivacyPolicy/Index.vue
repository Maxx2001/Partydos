<template>
    <DefaultLayout>
        <div class="bg-gray-50 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto bg-white shadow-md rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Privacy Statement and Terms of Service</h1>
                <p class="text-sm text-gray-600 mb-4">Effective Date: 2 januari 2025</p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-3">Privacy Statement</h2>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">1. Information We Collect</h3>
                    <p class="text-gray-600 mb-2">
                        When you use Partydos, we may collect the following types of information:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-4">
                        <li><strong>Personal Information:</strong> Name, email address, username, and password (required for account creation). Event details, such as titles, descriptions, dates, and participant information.</li>
                        <li><strong>Usage Data:</strong> IP address, browser type, operating system, and device information. Website activity, including pages visited, time spent, and actions performed.</li>
                        <li><strong>Cookies and Tracking Technologies:</strong> Cookies and similar technologies are used to track your activity on our site and improve your experience. You can manage cookie settings in your browser.</li>
                    </ul>

                    <h3 class="text-xl font-medium text-gray-700 mb-2">2. How We Use Your Information</h3>
                    <p class="text-gray-600 mb-4">
                        We use the information we collect to:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 mb-4">
                        <li>Provide and maintain our services.</li>
                        <li>Facilitate account creation and event management.</li>
                        <li>Communicate with you regarding updates, notifications, or event details.</li>
                        <li>Analyze usage trends to improve our platform.</li>
                    </ul>

                    <h3 class="text-xl font-medium text-gray-700 mb-2">3. Sharing Your Information</h3>
                    <p class="text-gray-600 mb-4">
                        We do not sell your personal information unless explicitly stated and agreed to by you. However, we may share your data with third-party service providers for email notifications, data storage, and analytics. Law enforcement or regulatory authorities may receive data if required by law.
                    </p>
                    <p class="text-gray-600 mb-4">
                        In certain cases, we may sell aggregated or anonymized personal information to third parties for marketing, research, or other purposes. This data will not directly identify you but may be used in combination with other information by the recipient. If you do not want your data to be sold, you can opt out at any time by contacting us at <a href="mailto:privacy@partydos.com" class="text-blue-500">privacy@partydos.com</a> or using the “Do Not Sell My Data” feature on our website.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-3">Terms of Service</h2>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">1. Acceptance of Terms</h3>
                    <p class="text-gray-600 mb-4">
                        By accessing or using Partydos, you agree to these Terms of Service. If you do not agree, please refrain from using our platform.
                    </p>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">2. Eligibility</h3>
                    <p class="text-gray-600 mb-4">
                        You must be at least 13 years old to use this website. If you are under the age of majority in your jurisdiction, you must have parental or guardian consent.
                    </p>
                    <!-- Continue with other terms following the same structure -->
                </section>
            </div>
        </div>
    </DefaultLayout>
</template>

<script setup>
import {useTitle} from "@/Composables/useTitle.js";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

useTitle('Partydos | PrivacyStatement');
</script>
