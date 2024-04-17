<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Welcome !</div>
                    <div class="p-6">This is a test project for PT JASAMEDIKA TRANSMEDIC</div>
                    <div class="p-6">by <a href="https://www.linkedin.com/in/wisnukm41/" target="_blank">Wisnu Murfadilah Rokhsan</a></div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
