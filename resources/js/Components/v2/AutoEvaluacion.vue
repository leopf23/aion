<script setup>
import { ref, onMounted } from 'vue'
import Radar from "@/Pages/Evaluacion/Chart/Radar.vue";
  
onMounted(() => {
    getLastAssessment();
    getItem();
});

const props = defineProps({
    item: Object || null,
    objetivos: Object || null
});

const item = ref({});
const objetivos = ref({});
const banner_path = ref();
const proposito = ref();
const slogan = ref();
const actuacion = ref();
const lastAssessment = ref({});
const loading = ref(true);

const getItem = () => {
    axios
        .get("/api/config-dashboard")
        .then((response) => {
            item.value = response.data;
            objetivos.value = item.value[1];
            proposito.value = item.value[0].proposito;
            slogan.value = item.value[0].slogan;
            actuacion.value = item.value[0].actuacion;
            banner_path.value = item.value[0].banner_path;
            // Set other form fields here as needed
        })
        .catch((error) => {
            console.error('Error fetching item:', error);
        });
};

const getLastAssessment = async () => {
    try {
        const response = await axios.get(route('evaluaciones.getUltimaEvaluacion'));
        lastAssessment.value = response.data;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};
</script>

<template>
  <div class="grid grid-cols-4 bg-white gap-[100px]">
      <div class="xl:col-span-2 col-span-3 mt-3">
          <!-- Chart -->
          <div class="relative aspect-square max-w-xl md:max-w-3xl mx-auto">
            <div>
                <h2 class="text-2xl mb-3 font-semibold text-gray-800">Autoevaluación</h2>
                <div v-if="!loading && lastAssessment">
                    <Radar :evaluacion="lastAssessment" />
                </div>
                <div v-else>
                    Loading...
                </div>
            </div>
          </div>
      </div>

      <div class="xl:col-span-2 col-span-3 mt-3">
          <h2 class="text-2xl mb-3 font-semibold text-gray-800">Objetivos</h2>
          <div class="text-sm leading-10">
              <ul>
                <li v-for="objetivo in objetivos">- {{ objetivo.objetivo }}</li>
              </ul>
          </div>
      </div>
  </div>
</template>
