<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Modal from '@/Components/Modal.vue';
import { computed, ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import Calendar from 'primevue/calendar';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from "primevue/api";

const props = defineProps(['cars']);
const openForm = ref(false);
const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const imagePath = ref(`${window.location.origin}/storage/`)

const form = useForm({
    date_return : "",
    selected : "",
    price: ""
});

const selectedCar = ref(null);
const selectedData = ref(null);

const closeModal = () => {
    openForm.value = false
    form.reset()
    selectedCar.value = null
}

const submitCar = () => {
    form.price = paid.value
    form.post(route('borrow.store'), {onSuccess: () => closeModal(), onError: (e) => console.log(e)})
}

const returnForm = (data) => {
    form.selected = data.id
    selectedCar.value = data.car
    selectedData.value = data
    openForm.value = true
}

const paid = computed(() => {
    const date_start = new Date(selectedData.value.start_date)
    const date_end = new Date(form.date_return)
    const days = Math.floor((date_end - date_start) / (1000 * 60 * 60 * 24))
    if( form.date_return ){
        return (days + 1) * selectedCar.value.rent_fee 
    } else {
        return 0
    }
})


</script>

<template>
    <Head title="Borrow Car" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Borrow Car</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-10 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <DataTable v-model:filters="filters" filterDisplay="row" :value="cars" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" showGridlines tableStyle="width:100%">
                        <template #header>
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="text-xl font-bold text-900">List of your Rented Car</span>
                                <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                            </div>
                        </template>
                        <Column header="Car">
                            <template #body="slotProps">
                                {{ `${slotProps.data.car.brand} - ${slotProps.data.car.model}` }}
                            </template>
                        </Column>
                        <Column header="Rent Date">
                            <template #body="slotProps">
                                {{ `${new Date(slotProps.data.start_date).toDateString('Y')} - ${new Date(slotProps.data.end_date).toDateString('Y')}` }}
                            </template>
                        </Column>
                        <Column header="Estimated Cost">
                            <template #body="slotProps">
                                {{ `${ ((Math.floor((new Date(slotProps.data.end_date) - new Date(slotProps.data.start_date)) / (1000 * 60 * 60 * 24) + 1) * slotProps.data.car.rent_fee).toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'IDR',
                                }) )}` }}
                            </template>
                        </Column>
                        <Column header="Action">
                            <template #body="slotProps">
                                <div class="hidden gap-2 md:flex">
                                    <Button label=" Return" @click="returnForm(slotProps.data)" icon="pi pi-arrow-left" class="p-2 text-white bg-red-500"/>
                                </div>
                                <div class="flex gap-2 md:hidden">
                                    <Button label="" @click="returnForm(slotProps.data)" icon="pi pi-arrow-left" class="p-2 text-white bg-red-500"/>
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

                <h2 class="mb-2 text-xl font-bold">Rent Info</h2>
                <form @submit.prevent="submitCar">
                <div>
                    <label class="mb-2 text-sm text-gray-900 ">Start Date : </label>
                    <span>{{ new Date(selectedData.start_date).toDateString() }}</span>
                </div>
                <InputError :message="form.errors.date_start" class="mb-2" />
                <div class="mt-2">
                    <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 ">Return Date</label>
                    <Calendar v-model="form.date_return" :min-date="new Date(selectedData.start_date)" />
                </div>
                <InputError :message="form.errors.date_return" class="mb-2" />
                <div class="mt-4">
                    Paid Price : {{ paid > 0 ? paid.toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'IDR',
                                }) : 0 }}
                </div>

                <button class="px-3 py-2 mt-4 text-white rounded bg-cyan-500 hover:bg-cyan-600">Confirm</button>
                </form>
            </div>
         
        </Modal>
    </AuthenticatedLayout>
</template>
