<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import { ref, onMounted, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import Layout from "@/Layouts/Layout2.vue";
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import ContenidoPanel from "./Partials/TabPanel/ContenidoPanel.vue";
import PilaresSelect from "@/Components/PilaresSelect.vue";


const openFilter = () => {
    customFilter.value = !customFilter.value

};
const props = defineProps({
    area_id: Number,
    departamento_id: Number,
});
const title = "minutero";
const areas = ref([]);
const selectedPilar = ref(1);
const selectedArea = ref({});
const area_id = props.area_id ? ref(props.area_id - 1) : ref(0);
const departamento_id = ref(props.departamento_id);

onMounted(() => {
    getAreas();
});

const getArea = () => {
    const selectedArea = areas.value.find(area => area.id == selectedPilar.value);
    return { ...selectedArea };
}

const getAreas = async () => {
    try {
        const response = await axios.get("/api/areas");
        areas.value = response.data;
        area_id.value = 1
        selectedArea.value = getArea();
    } catch (error) {
        console.error(error);
    }
};

const onSelectedPilar = (pilarID) => {
    selectedPilar.value = pilarID;
    selectedArea.value = getArea();
};

</script>

<style scoped>
.breadcrumbs {
    display: flex;
    gap: 8px;
    align-items: center;
}

.breadcrumbsTitulo h3 {
    font-size: 24px;
    font-weight: bold;
}

.breadcrumbs h3 {
    font-size: 20px;
}

.breadcrumbs a {
    color: #000;
    text-decoration: none;
}

.breadcrumbs .active h3 {
    font-weight: bold;
}
</style>

<template>
    <Layout :titulo="title">

        <Head title="Minutas" />
        <PilaresSelect :currentPilarID="selectedPilar" :onSelectedPilar="onSelectedPilar"></PilaresSelect>

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div class="border-gray-200 bg-white">
                    <h3 class="px-4 font-semibold text-xl">Minutas</h3>
                    <div class="mx-auto overflow-x-auto">
                        <div class="card">
                            <!-- Filtre input -->
                            <div class="block md:flex justify-between items-end content-center">
                                <Link  :href="route('minutas.create')">
                                    <PrimaryButton class="bg-black m-4 p-[100px]" label="Nueva Minuta">
                                        <i class="mr-2 pi pi-plus"></i>
                                    </PrimaryButton>
                                </Link>
                            </div>
                            <!-- content table -->
                            <ContenidoPanel :area="selectedArea" :departamento_id="departamento_id" />
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </Layout>
</template>
