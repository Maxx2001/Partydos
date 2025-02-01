<script setup>
import {router} from "@inertiajs/vue3";
import DeleteAccountModal from "@/Layouts/Partials/Modals/DeleteAccountModal.vue";
import {ref} from "vue";

const logout = () => router.post(route('logout'));
const registerDoNotSellMyData = () => router.post(route('user-do-not-sell-my-data'));

const deleteAccountModal = ref('deleteAccountModal');

</script>


<template>
    <footer class="bg-gradient-to-br from-blue-500 to-purple-600 text-white py-12 md:py-16 px-6">
        <div class="max-w-6xl mx-auto grid grid-cols-3 md:grid-cols-5 space-y-8 md:space-y-0 gap-4">

            <!-- Logo and Description -->
            <div class="flex flex-col items-center md:items-start space-y-4 col-span-3 md:col-span-2 text-left">
                <h2 class="text-4xl font-bold w-full max-w-xs">Partydos</h2>
                <p class="text-gray-100 text-sm md:text-base max-w-xs">
                    Plan your events effortlessly with our suite of powerful tools designed for easy and engaging event management.
                </p>
            </div>

            <!-- Navigation Links -->
            <div class="flex flex-col items-center md:items-start space-y-4 col-span-1 text-left px-2 md:px-0">
                <span class="text-xl font-semibold w-full">Pages</span>
                <ul class="space-y-2 flex flex-col w-full">
                    <li><a href="#" class="hover:underline hover:text-blue-200 transition duration-300">Home</a></li>
                    <li><a href="#" class="hover:underline hover:text-blue-200 transition duration-300">Features</a></li>
                    <li><a href="#" class="hover:underline hover:text-blue-200 transition duration-300">Roadmap</a></li>
                    <li><a href="#" class="hover:underline hover:text-blue-200 transition duration-300">Contact</a></li>
                </ul>
            </div>

            <div class="flex flex-col items-center md:items-start space-y-4 col-span-1 text-left px-2 md:px-0">
                <span class="text-xl font-semibold flex w-full">
                    <span>Contact </span>
                    <span class="hidden md:block pl-1"> Us</span>
                </span>
                <ul class="space-y-2 flex flex-col w-full">
                    <li><a href="#" class="hover:underline hover:text-blue-200 transition duration-300">To be</a></li>
                </ul>
<!--                <p class="text-sm text-gray-100">Email: info@partydos.app</p>-->
            </div>

            <div class="flex flex-col items-center md:items-start space-y-4 col-span-1 text-left">
                <span class="text-xl font-semibold w-full">Profile</span>
                <div class="flex space-x-4 flex-col w-full">
                    <ul class="space-y-2 flex flex-col">
                        <li v-if="$page.props.auth && $page.props.auth.user">
                            <button @click="logout" class="hover:underline hover:text-blue-200 transition duration-300">
                                Logout
                            </button>
                        </li>
                        <li v-else>
                            <a :href="route('login')" class="hover:underline hover:text-blue-200 transition duration-300">
                                Login
                            </a>
                        </li>
                        <li v-if="$page.props.auth && $page.props.auth.user">
                            <button @click="deleteAccountModal.openModal()" class="hover:underline hover:text-blue-200 transition duration-300 text-left">
                                Delete account
                            </button>
                        </li>
                        <li>
                            <a :href="route('privacy-policy')" class="hover:underline hover:text-blue-200 transition duration-300">
                                Privacy Policy
                            </a>
                        </li>
                        <li v-if="$page.props.auth && $page.props.auth.user">
                            <button @click="registerDoNotSellMyData" class="hover:underline hover:text-blue-200 transition duration-300 text-left">
                                Do not sell my data
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-12 border-t border-blue-500 pt-6">
            <p class="text-center text-sm text-gray-200">&copy; 2024 Partydos. All rights reserved.</p>
        </div>
    </footer>

    <DeleteAccountModal
        v-if="$page.props.auth && $page.props.auth.user"
        ref="deleteAccountModal"
    />
</template>
