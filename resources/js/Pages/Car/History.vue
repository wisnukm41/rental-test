<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';


const props = defineProps(['pastRent', 'pastRented']);

</script>

<template>
    <Head title="My Car" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">My Car</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-10 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <DataTable :value="pastRent" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" showGridlines tableStyle="width:100%">
                        <template #header>
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="text-xl font-bold text-900">List of your Rented Car</span>
                            </div>
                        </template>
                        <Column header="Car">
                            <template #body="slotProps">
                                {{ `${slotProps.data.car.brand} - ${slotProps.data.car.model}` }}
                            </template>
                        </Column>
                        <Column header="Rent Date">
                            <template #body="slotProps">
                                {{ `${new Date(slotProps.data.start_date).toDateString('Y')} - ${new Date(slotProps.data.return_date).toDateString('Y')}` }}
                            </template>
                        </Column>
                        <Column header="You Paid">
                            <template #body="slotProps">
                                {{ `${ ( slotProps.data.total_fee.toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'IDR',
                                }) )}` }}
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-10 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <DataTable :value="pastRented" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" showGridlines tableStyle="width:100%">
                        <template #header>
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="text-xl font-bold text-900">List of your Car Rented</span>
                            </div>
                        </template>
                        <Column header="Car">
                            <template #body="slotProps">
                                {{ `${slotProps.data.car.brand} - ${slotProps.data.car.model}` }}
                            </template>
                        </Column>
                        <Column header="Rent Date">
                            <template #body="slotProps">
                                {{ `${new Date(slotProps.data.start_date).toDateString('Y')} - ${new Date(slotProps.data.return_date).toDateString('Y')}` }}
                            </template>
                        </Column>
                        <Column header="You Get">
                            <template #body="slotProps">
                                {{ `${ ( slotProps.data.total_fee.toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'IDR',
                                }) )}` }}
                            </template>
                        </Column>
                        <Column header="User Info">
                            <template #body="slotProps">
                                {{ slotProps.data.user.name }} ({{ slotProps.data.user.phone }})
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
