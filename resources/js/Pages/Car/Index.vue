<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import InputText from 'primevue/inputtext';
import { FilterMatchMode, FilterOperator } from "primevue/api";
import Column from 'primevue/column';
import Button from 'primevue/button';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps(['cars']);
const create = ref(false);
const edit = ref(false);
const carId = ref(null);
const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const imagePath = ref(`${window.location.origin}/storage/`)

const form = useForm({
    brand : "",
    model : "",
    image : "",
    police_number : "",
    rent_fee : "",
});

const url = ref(null)
const photo = ref(null)

const previewImage = (e) => {
    const file = e.target.files[0];
    url.value = URL.createObjectURL(file);
}

const closeModal = () => {
    create.value = false
    edit.value = false
    form.reset()
    photo.value = null
    url.value = null
    carId.value = null
}

const submitCar = () => {

    if (photo.value != null) {
        form.image = photo.value.files[0];
    }

    if(edit.value) {
        console.log(form)
        form.post(route('car.update', carId.value), {onSuccess: () => closeModal(), onError: (e) => console.log(e)})
    } else {
        form.post(route('car.store'), {onSuccess: () => closeModal(), onError: (e) => console.log(e)})
    }
}

const editForm = (data) => {
    carId.value = data.id
    form.brand = data.brand
    form.model = data.model
    form.police_number = data.police_number
    form.rent_fee = data.rent_fee
    create.value = true
    edit.value = true
    if(data.image){
        url.value = window.location.origin + '/storage/' +data.image
    }
}
console.log(props.cars)

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
                    <DataTable v-model:filters="filters" filterDisplay="row" :value="cars" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" showGridlines tableStyle="width:100%" >
                        <template #header>
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="text-xl font-bold text-900">List Of Your Car</span>
                                <div class="flex gap-2">
                                    <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                                    <Button label="Add" icon="pi pi-plus" class="p-2 text-white bg-green-400" @click="create = true" />
                                </div>
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
                        <Column header="Action">
                            <template #body="slotProps">
                                <div v-if="slotProps.data.is_rent">
                                    Rented by : {{ slotProps.data.rented_by.user.name }} ({{ slotProps.data.rented_by.user.phone }})
                                </div>
                                <div v-else>
                                    <div class="hidden gap-2 md:flex">
                                        <Button label=" Edit" @click="editForm(slotProps.data)" icon="pi pi-pencil" class="p-2 text-white bg-blue-500"/>
                                        <Link :href="route('car.destroy', slotProps.data.id)" method="delete" as="button" >
                                            <Button label=" Delete" icon="pi pi-trash" class="p-2 text-white bg-red-500"/>
                                        </Link>
                                    </div>
                                    <div class="flex gap-2 md:hidden">
                                        <Button icon="pi pi-pencil" @click="editForm(slotProps.data)" class="p-2 text-white bg-blue-500"/>
                                        <Link :href="route('car.destroy', slotProps.data.id)" method="delete" as="button" >
                                            <Button icon="pi pi-trash" class="p-2 text-white bg-red-500"/>
                                        </Link>
                                    </div>
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
        <Modal :show="create" @close="closeModal">
            <div class="p-6 md:p-12"> 
                <form @submit.prevent="submitCar">
                <div class="mt-2">
                    <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 ">Brand</label>
                    <input v-model="form.brand" type="text" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Car Brand">
                </div>
                <InputError :message="form.errors.brand" class="mb-2" />

                <div class="mt-2">
                    <label for="model" class="block mb-2 text-sm font-medium text-gray-900 ">Model</label>
                    <input v-model="form.model" type="text" id="model" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Car Model">
                </div>
                <InputError :message="form.errors.model" class="mb-2" />

                <div class="mt-2">
                    <label for="police_number" class="block mb-2 text-sm font-medium text-gray-900 ">Police Number</label>
                    <input v-model="form.police_number" type="text" id="police_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Police Number">
                </div>
                <InputError :message="form.errors.police_number" class="mb-2" />

                <div class="mt-2">
                    <label for="rent_fee" class="block mb-2 text-sm font-medium text-gray-900 ">Rent Fee / Day</label>
                    <input v-model="form.rent_fee" type="text" id="rent_fee" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="ex : 450000">
                </div>
                <InputError :message="form.errors.rent_fee" class="mb-2" />

                <div class="flex gap-2 mt-4">
                    <div>
                        <label for="file" class="block mb-2 text-sm font-medium text-gray-900 ">Image Upload</label>
                        <input
                        type="file"
                        @change="previewImage"
                        ref="photo"
                        class="w-full px-4 py-2 my-4 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                    />
                    <div
                        v-if="form.errors.image"
                        class="font-bold text-red-600"
                        >
                        {{ form.errors.image }}
                    </div>
                </div>
                <div>                    
                    <img
                        v-show="url"
                            :src="url"
                            class="h-32 my-4"
                        />
                    </div>
                </div>
                
                <button class="px-3 py-2 text-white rounded bg-cyan-500 hover:bg-cyan-600">Save</button>
            </form>
            </div>
         
        </Modal>
    </AuthenticatedLayout>
</template>
