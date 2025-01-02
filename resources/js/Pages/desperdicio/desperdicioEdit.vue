<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";
import PilaresSelect from "@/Components/v2/PilaresSelect.vue";

const props = defineProps({
    areas: Array,
    departamentos: Array || null,
    tipos: Array,
    desperdicio: Object,
});

const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const tipos = ref(props.tipos);
const filteredUsuarios = ref();
const desperdicio = ref(props.desperdicio);
const selectedPilar = ref(desperdicio.value.area_id || localStorage.getItem("PILAR_ID") || 1);

const title = "Desperdicios";

const form = useForm({
    area_id: desperdicio.value.area_id,
    departamento_id: desperdicio.value.departamento_id,
    tipoDesperdicio_id: desperdicio.value.tipoDesperdicio_id,
    descripcion: desperdicio.value.descripcion,
    monto: desperdicio.value.monto,
    rango: desperdicio.value.rango,
    recurrencia: desperdicio.value.recurrencia,
    detectabilidad: desperdicio.value.detectabilidad,
});

const onSelectedPilar = (pilarID) => {
    selectedPilar.value = pilarID;
    form.area_id = pilarID;
};

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

async function onChange(eventOrValue) {
    const target_id = eventOrValue && eventOrValue.target ? eventOrValue.target.value : eventOrValue;
    await axios
        .get(route("departamentos.byArea", target_id))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });
}

async function getTipos(eventOrValue) {
    const target_id = eventOrValue && eventOrValue.target ? eventOrValue.target.value : eventOrValue;
    await axios
        .get(route("desperdicio.byDepartamento", target_id))
        .then((response) => (tipos.value = response.data.tipos))
        .catch((error) => {
            console.log(error);
        });
}


// const submit = () => {
//     try {
//         form.post(route("desperdicio.store"), {
//             onFinish: () => {
//                 showToast("El registro ha sido creado", "success");
//                 form.reset();
//             },
//         });

//     } catch (error) {
//         showToast("Ocurrio un error", "error");
//         console.error(error);
//     }
// };
const submit = () => {
    try {

        form.patch(route("desperdicio.update", desperdicio.value.id), {
            onError: (errors) => {
                console.log(errors);
                showToast(errors.message, "error");
            },
            onFinish: () => {
                showToast("El registro ha sido creado", "success");
                form.reset();
            },
        });

    } catch (error) {
        showToast("Ocurrio un error", "error");
        console.error(error);
    }
};


onMounted(() => {
    getAreas();
    // onChange(desperdicio.value.area_id);
    // getTipos(desperdicio.value.departamento_id);
    getTipos(1);

})

</script>

<template>
    <Layout :titulo="title">
        <PilaresSelect :currentPilarID="selectedPilar" :onSelectedPilar="onSelectedPilar"></PilaresSelect>


        <Head title="Desperdicios" />
        <div class="my-5 sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3 class="mb-2 font-semibold text-xl">Editar desperdicio</h3>
            </div>
            <div class="flex items-center breadcrumbs">
                <Link :href="route('desperdicio.index')" class="px-1">
                <h3>Desperdicios</h3>
                </Link>
                <i class="pi-angle-right mx-2 pi" style="font-size: 1rem"></i>
                <Link class="active">
                <b>editar</b>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div>
                    <div class="flex justify-end bg-white"></div>
                    <div class="border-gray-200 bg-white">
                        <div class="mx-auto container">
                            <form @submit.prevent="submit">

                                <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                    <!-- <div class="mt-4">
                                        <InputLabel for="departamento_id" value="Flujo de valor: " />

                                        <select ref="departamento_select" @change="getTipos($event)"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.departamento_id" required>
                                            <option value="" disabled selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="departamento in departamentos" :key="departamento.id"
                                                :value="departamento.id">
                                                {{ departamento.nombre }}
                                            </option>
                                        </select>
                                    </div> -->

                                    <div class="mt-4">
                                        <InputLabel for="departamento_id" value="Tipo desperdicio: " />

                                        <select ref="departamento_select"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.tipoDesperdicio_id" required>
                                            <option value="" disabled selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">
                                                {{ tipo.tipo }} - {{ tipo.nombre }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="monto" value="Monto: " />
                                        <!-- <AutoComplete v-model="form.lider_id" optionLabel="name"
                                            :suggestions="filteredUsuarios" forceSelection @complete="search"
                                            placeholder="" /> -->
                                        <TextInput id="tarea" v-model="form.monto" type="number" step="any"
                                            class="block mt-1 w-full" required autocomplete="tarea" />
                                    </div>
                                    <div class="mt-4">
                                        <InputLabel for="Descripcion" value="Descripcion: " />
                                        <!-- <AutoComplete v-model="form.lider_id" optionLabel="name"
                                            :suggestions="filteredUsuarios" forceSelection @complete="search"
                                            placeholder="" /> -->
                                        <TextInput id="descripcion" v-model="form.descripcion" type="text"
                                            class="block mt-1 w-full" required autocomplete="descripcion"
                                            maxlength="250" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="Rango" value="Rango: " />
                                        <TextInput id="rango" v-model="form.rango" type="number"
                                            class="block mt-1 w-full" required autocomplete="rango" min="1" max="10" />
                                    </div>
                                    <div class="mt-4">
                                        <InputLabel for="Recurrencia" value="Recurrencia: " />
                                        <TextInput id="recurrencia" v-model="form.recurrencia" type="number"
                                            class="block mt-1 w-full" required autocomplete="recurrencia" min="1"
                                            max="10" />
                                    </div>
                                    <div class="mt-4">
                                        <InputLabel for="Detectabilidad" value="Detectabilidad: " />
                                        <TextInput id="detectabilidad" v-model="form.detectabilidad" type="number"
                                            class="block mt-1 w-full" required autocomplete="detectabilidad" min="1"
                                            max="10" />
                                    </div>
                                </div>

                                <div class="flex justify-end border-gray-200 bg-white my-4 px-4 pt-2">

                                
                                            <PrimaryButton
                                                class="bg-black hover:bg-gray-800 px-10" :class="{ 'opacity-25': form.processing, }"
                                                :disabled="form.processing">
                                               Guardar
                                            </PrimaryButton>
                        
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
