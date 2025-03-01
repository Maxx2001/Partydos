<template>
    <section class="relative bg-gradient-to-br from-blue-500 to-purple-600 text-white py-10 md:py-16 px-6">
        <div class="max-w-6xl mx-auto flex flex-col items-center justify-center text-center space-y-6">
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight drop-shadow-lg">
                <slot/>
            </h1>
        </div>

        <div class="absolute -top-10 left-1/4 w-32 h-4 bg-white/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-48 h-4 bg-white/10 rounded-full blur-3xl"></div>
    </section>
</template>
