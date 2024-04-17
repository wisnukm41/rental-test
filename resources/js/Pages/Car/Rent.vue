<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { FilterMatchMode } from "primevue/api";
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Modal from '@/Components/Modal.vue';
import { computed, ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import Calendar from 'primevue/calendar';

const props = defineProps(['cars']);
const openForm = ref(false);

const imagePath = ref(`${window.location.origin}/storage/`)
const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const form = useForm({
    date_start : "",
    date_end : "",
    selected : ""
});

const selectedCar = ref(null);

const closeModal = () => {
    openForm.value = false
    form.reset()
    selectedCar.value = null
}

const submitCar = () => {
    form.post(route('rent.store'), {onSuccess: () => closeModal(), onError: (e) => console.log(e)})
}

const rentForm = (data) => {
    form.selected = data.id
    selectedCar.value = data
    openForm.value = true
}

const estimate = computed(() => {
    const date_start = new Date(form.date_start)
    const date_end = new Date(form.date_end)
    const days = Math.floor((date_end - date_start) / (1000 * 60 * 60 * 24))
    if(form.date_start && form.date_end ){
        return (days + 1) * selectedCar.value.rent_fee 
    } else {
        return 0
    }
})


</script>

<template>
    <Head title="Rent Car" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Rent Car</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-10 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <DataTable v-model:filters="filters" filterDisplay="row" :value="cars" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" showGridlines tableStyle="width:100%">
                        <template #header>
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="text-xl font-bold text-900">List of All Car</span>
                                <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                            </div>
                        </template>
                        <Column field="brand" header="Brand"></Column>
                        <Column field="model" header="Model"></Column>
                        <Column header="Image">
                            <template #body="slotProps">
                                <div v-if="slotProps.data.image">
                                    <img :src="`${imagePath}${slotProps.data.image}`" :alt="`image of ${slotProps.data.brand}`" class="mx-auto w-[8rem] border-round" />
                                </div>
                                <div v-else>No Image</div>
                            </template>
                        </Column>
                        <Column field="police_number" header="Police Number"></Column>
                        <Column header="Rent Fee / Day">
                            <template #body="slotProps">
                            {{ slotProps.data.rent_fee.toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'IDR',
                                }) }}
                            </template>
                        </Column>
                        <Column header="Action">
                            <template #body="slotProps">
                                <div class="hidden gap-2 md:flex">
                                    <Button label=" Rent" @click="rentForm(slotProps.data)" icon="pi pi-clock" class="p-2 text-white bg-blue-500"/>
                                </div>
                                <div class="flex gap-2 md:hidden">
                                    <Button label="" @click="rentForm(slotProps.data)" icon="pi pi-clock" class="p-2 text-white bg-blue-500"/>
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
        <Modal :show="openForm" @close="closeModal">
            <div class="p-6 md:p-12"> 
                <h2 class="mb-2 text-xl font-bold">Car Info</h2>

                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Brand : </label>
                    <span>{{ selectedCar.brand }}</span>
                </div>
                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Model : </label>
                    <span>{{ selectedCar.model }}</span>
                </div>
                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Police Number : </label>
                    <span>{{ selectedCar.police_number }}</span>
                </div>
                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Available Now : </label>
                    <span>{{ selectedCar.is_rent ? 'No' : 'Yes' }}</span>
                </div>
                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Rented : </label>
                    <span>{{ selectedCar.rented }} times</span>
                </div>
                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Rent Fee / Day : </label>
                    <span>{{ selectedCar.rent_fee.toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'IDR',
                                }) }}</span>
                </div>
                <div v-if="selectedCar.image">
                        <img
                            :src="`${imagePath}${selectedCar.image}`"
                            class="w-full my-4"
                        />
                </div>

                <h2 class="mb-2 text-xl font-bold">Owner Info</h2>

                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Name : </label>
                    <span>{{ selectedCar.user.name }}</span>
                </div>
                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Phone : </label>
                    <span>{{ selectedCar.user.phone }}</span>
                </div>
                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Address : </label>
                    <span>{{ selectedCar.user.address }}</span>
                </div>

                <h2 class="mt-2 mb-2 text-xl font-bold">Rent Info</h2>
                <form @submit.prevent="submitCar">
                <div class="mt-2">
                    <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 ">Start Date</label>
                    <Calendar v-model="form.date_start" :min-date="new Date()"/>
                </div>
                <InputError :message="form.errors.date_start" class="mb-2" />
                <div class="mt-2">
                    <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 ">End Date</label>
                    <Calendar v-model="form.date_end" :min-date="new Date()" />
                </div>
                <InputError :message="form.errors.date_end" class="mb-2" />
                <div class="mt-4">
                    Estimated Price : {{ estimate > 0 ? estimate.toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'IDR',
                                }) : 0 }}
                </div>

                <button class="px-3 py-2 mt-4 text-white rounded bg-cyan-500 hover:bg-cyan-600">Rent</button>
                
                </form>
            </div>
         
        </Modal>
    </AuthenticatedLayout>
</template>
