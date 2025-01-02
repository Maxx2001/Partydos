<template>
    <div class="flex flex-col items-center justify-center py-16 px-6 bg-gradient-to-b from-blue-50 to-white border">
        <h1 class="text-2xl md:text-4xl font-bold pb-12 text-gray-900 text-center">
            Easily add events to your calendar!
        </h1>
        <div
            class="w-full max-w-2xl bg-white rounded-lg shadow-lg overflow-hidden"
        >
            <div class="flex divide-x divide-gray-200">
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium bg-blue-500 text-white cursor-pointer transition-colors duration-300 ease-in-out hover:animate-pulse">
                    Apple
                </div>
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium text-gray-600 cursor-pointer transition-colors duration-200 ease-out hover:bg-blue-500 hover:text-white hover:shadow-lg hover:animate-pulse">
                    Google
                </div>
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium text-gray-600 cursor-pointer transition-colors duration-200 ease-out hover:bg-blue-500 hover:text-white hover:shadow-lg hover:animate-pulse">
                    Hotmail
                </div>
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium text-gray-600 cursor-pointer transition-colors duration-200 ease-out hover:bg-blue-500 hover:text-white hover:shadow-lg hover:animate-pulse">
                    Other
                </div>
            </div>
        </div>
    </div>
</template>
