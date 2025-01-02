<script setup>
import { onMounted, ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout2.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    departamentos: Array,
});

const departamentos = ref(props.departamentos);

// Estado para los campos dinámicos
const newAviso = ref("");
const newHighlight = ref("");
const newLowLight = ref("");

const avisos = ref([]);
const highlights = ref([]);
const lowlights = ref([]);
const errors = ref([]);

async function getDepartamentos() {
    await axios
        .get("/api/getFlujo")
        .then((response) => {
            departamentos.value = response.data;
            // console.log({ departamentos: departamentos.value });

        })
        .catch((error) => {
            console.log(error);
        });
}

const form = useForm({
    nombre: "",
    departamento_id: "",
    avisos: [],
    highlights: [],
    lowlights: [],
});

const submit = () => {
    // Asignar los valores de highlights y lowlights al formulario antes de enviarlo
    form.avisos = avisos.value.map(a => a.value);
    form.highlights = highlights.value.map(h => h.value);
    form.lowlights = lowlights.value.map(l => l.value);

    form.post(route("reporte.store"), {
        onError: (errors) => {
            console.log(errors);
            showToast(errors.message, "error");
        },
        // onFinish: () => {
        //     if (!errors) {
        //         showToast("El registro ha sido creado", "success");

        //     }
        // }
        onFinish: () => form.reset(),
    });
};

// Funciones para añadir y eliminar campos dinámicos

const addAviso = () => {
    if (newAviso.value && newAviso.value.trim().length > 0) {
        avisos.value.push({ value: newAviso.value.trim() });
        newAviso.value = ""
    }
};

const addHighlight = () => {
    if (newHighlight.value && newHighlight.value.trim().length > 0) {
        highlights.value.push({ value: newHighlight.value.trim() });
        newHighlight.value = "";
    }
};

const addLowlight = () => {
    if (newLowLight.value && newLowLight.value.trim().length > 0) {
        lowlights.value.push({ value: newLowLight.value.trim() });
        newLowLight.value = "";
    }
};

const removeHighlight = (index) => highlights.value.splice(index, 1);
const removeAviso = (index) => avisos.value.splice(index, 1);
const removeLowlight = (index) => lowlights.value.splice(index, 1);

onMounted(() => {
    getDepartamentos();
});
</script>

<template>
    <Layout>

        <Head title="Usuarios" />
        <div class="pl-5 overflow-hidden">
            <div class="breadcrumbsTitulo">
                <h3 class="font-semibold text-xl">Reportes</h3>
            </div>

            <div class="flex items-center gap-2 breadcrumbs">
                <Link :href="route('reporte.index')" class="px-1">
                <h3>Lista de reportes</h3>
                </Link>
                <i class="pi-angle-right pi" style="font-size: 1rem"></i>
                <Link class="active">
                <b>Nuevo</b>
                </Link>
            </div>
        </div>

        <div class="p-5">
            <div class="overflow-hidden">
                <div class="mt-5">
                    <InputLabel for="departamento_id" value="Flujo de valor: " />
                    <select class="border-gray-300 focus:border-black rounded-md focus:ring-black w-80 cursor-pointer" required >
                        <option value="" disabled selected>
                            Seleccione un flujo de valor
                        </option>
                        <option v-for="departamento in departamentos" :key="departamento.id"
                        :value="departamento.departamento.id" >
                        {{ departamento.departamento.nombre }}
                        </option>
                    </select>
                </div>
                <div>
                    <form @submit.prevent="submit">
                        <div class="gap-8 grid grid-cols-6 mt-8">
                            <div class="col-span-6 md:col-span-3">
                                <div class="border-gray-300 p-6 border border-solid rounded-md">
                                    <b>Highlights:</b>

                                    <div class="flex items-center gap-8 my-5">
                                        <TextInput v-model="newHighlight" placeholder="Añadir nombre" type="text"
                                            class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                        <PrimaryButton type="button" @click="addHighlight" class="bg-black hover:bg-gray-800">
                                            <i class="pi pi-plus" style="font-size: 1rem"></i>
                                        </PrimaryButton>
                                    </div>

                                    <ul>
                                        <li class="h-48 min-h-48 overflow-scroll">
                                            <div v-for="(highlight, index) in highlights" :key="index"
                                                class="flex justify-between items-center my-6">
                                                <p class="text-lg">{{ highlight.value }}</p>
                                                <i @click="removeHighlight(index)"
                                                    class="hover:bg-gray-200 p-3 hover:rounded-lg cursor-pointer pi pi-trash"
                                                    style="color: red; font-size: 1.3rem"></i>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-span-6 md:col-span-3">
                                <div class="border-gray-300 p-6 border border-solid rounded-md">
                                    <b>Lowlights:</b>

                                    <div class="flex items-center gap-8 my-5">
                                        <TextInput v-model="newLowLight" placeholder="Añadir nombre" type="text"
                                            class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                        <PrimaryButton type="button" @click="addLowlight" class="bg-black hover:bg-gray-800">
                                            <i class="pi pi-plus" style="font-size: 1rem"></i>
                                        </PrimaryButton>
                                    </div>

                                    <ul>
                                        <li class="h-48 min-h-48 overflow-scroll">
                                            <div v-for="(lowlight, index) in lowlights" :key="index"
                                                class="flex justify-between items-center my-6">
                                                <p class="text-lg">{{ lowlight.value }}</p>
                                                <i @click="removeLowlight(index)"
                                                    class="hover:bg-gray-200 p-3 hover:rounded-lg cursor-pointer pi pi-trash"
                                                    style="color: red; font-size: 1.3rem"></i>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-span-6 md:col-span-3">
                                <div class="border-gray-300 p-6 border border-solid rounded-md">
                                    <b>Avisos:</b>

                                    <div class="flex items-center gap-8 my-5">
                                        <TextInput v-model="newAviso" placeholder="Añadir nombre" type="text"
                                            class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                        <PrimaryButton type="button" @click="addAviso" class="bg-black hover:bg-gray-800">
                                            <i class="pi pi-plus" style="font-size: 1rem"></i>
                                        </PrimaryButton>
                                    </div>

                                    <ul>
                                        <li class="h-48 min-h-48 overflow-scroll">
                                            <div v-for="(aviso, index) in avisos" :key="index"
                                                class="flex justify-between items-center my-6">
                                                <p class="text-lg">{{ aviso.value }}</p>
                                                <i @click="removeAviso(index)"
                                                    class="hover:bg-gray-200 p-3 hover:rounded-lg cursor-pointer pi pi-trash"
                                                    style="color: red; font-size: 1.3rem"></i>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <PrimaryButton type="submit" @click="addLowlight" class="float-end bg-black hover:bg-gray-800 mt-5">
                            Guardar
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </Layout>
</template>
