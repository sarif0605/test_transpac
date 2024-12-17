<template>
  <div>
    <div class="modal" :class="{ 'modal-open': visible }">
      <div class="modal-box max-w-lg p-8 shadow-lg rounded-lg bg-white">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">
          {{ data ? "Update" : "Tambah" }} Work Unit
        </h2>
        
        <!-- Loading Spinner -->
        <div
          class="absolute top-0 left-0 w-full h-full flex justify-center items-center z-30"
          v-if="authStore.isLoading"
          style="background-color: rgba(0, 0, 0, 0.5)"
        >
          <Loading />
        </div>

        <!-- Error Alert -->
        <div role="alert" class="alert alert-error mb-4" v-if="authStore.isErr">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 shrink-0 stroke-current"
            fill="none"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <ul>
            <li v-for="(msg, index) in authStore.errMsg.general" :key="'general-' + index">
              {{ msg }}
            </li>
            <li
              v-for="(errors, field) in authStore.errMsg"
              v-if="field !== 'general'"
              :key="field"
            >
              <ul>
                <li v-for="(msg, index) in errors" :key="index">{{ msg }}</li>
              </ul>
            </li>
          </ul>
        </div>

        <!-- Form Inputs -->
        <form @submit.prevent="handleSubmit">
          <div class="space-y-4">
            <!-- Unit Name -->
            <label class="block text-sm font-medium text-gray-700">
              Nama Unit
              <input
                type="text"
                class="input input-bordered w-full mt-2"
                placeholder="Nama Unit"
                v-model="work_unit.unit_name"
                required
              />
            </label>
            <!-- Grade -->
            <label class="block text-sm font-medium text-gray-700">
              Grade
              <input
                type="text"
                class="input input-bordered w-full mt-2"
                placeholder="Grade"
                v-model="work_unit.grade"
                required
              />
            </label>
            <!-- Echelon -->
            <label class="block text-sm font-medium text-gray-700">
              Echelon
              <input
                type="text"
                class="input input-bordered w-full mt-2"
                placeholder="Echelon"
                v-model="work_unit.echelon"
                required
              />
            </label>
          </div>

          <!-- Modal Actions -->
          <div class="modal-action mt-6 flex justify-between">
            <button class="btn btn-primary w-full md:w-auto" type="submit">Kirim</button>
            <button class="btn btn-base-300 w-full md:w-auto mt-2 md:mt-0" type="button" @click="closeDialog">
              Close
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch, onMounted } from "vue";
import { customeApi } from "@/api";
import { useAuthStore } from "@/stores/AuthStore";
import { useToast } from "vue-toast-notification";
import Loading from "@/components/Loading.vue";

const $toast = useToast();
const authStore = useAuthStore();
const props = defineProps({
  data: {
    type: Object,
    default: null,
  },
  visible: Boolean,
});
const emit = defineEmits(["closeModal", "save", "update:visible"]);

const closeDialog = () => {
  emit("update:visible", false);
};

const work_unit = reactive({
  id: null,
  unit_name: "",
  echelon: "",
  grade: "",
});

const clearInput = () => {
  work_unit.id = null;
  work_unit.unit_name = "";
  work_unit.echelon = "";
  work_unit.grade = "";
};

const fetchData = () => {
  if (props.data) {
    work_unit.id = props.data.id;
    work_unit.unit_name = props.data.unit_name;
    work_unit.echelon = props.data.echelon;
    work_unit.grade = props.data.grade;
  } else {
    clearInput();
  }
};

watch(
  () => props.visible,
  (newValue) => {
    if (newValue) {
      fetchData();
    } else {
      clearInput();
    }
  }
);

const handleSubmit = async () => {
  authStore.isErr = false;
  authStore.errMsg = {};
  authStore.isLoading = true;
  try {
    const url = work_unit.id ? `/work-units/${work_unit.id}` : "/work-units";
    const method = work_unit.id ? "put" : "post";
    const response = await customeApi[method](
      url,
      { unit_name: work_unit.unit_name, grade: work_unit.grade, echelon: work_unit.echelon },
      {
        headers: {
          Authorization: `Bearer ${authStore.userToken}`,
        },
      }
    );
    authStore.isLoading = false;
    $toast.success(
      work_unit.id ? "Berhasil Ubah Data Work Unit" : "Berhasil Tambah Data Work Unit",
      {
        position: "top-right",
      }
    );
    if (response.data) {
      clearInput();
      emit("save");
      emit("closeModal");
    }
  } catch (error) {
    authStore.isLoading = false;
    authStore.isErr = true;
    if (error.response && error.response.data.errors) {
      authStore.errMsg = error.response.data.errors;
    } else {
      authStore.errMsg = { general: [error.response.data.message] };
    }
    console.log(error);
  }
};

onMounted(() => {
  clearInput();
  authStore.isErr = false;
});
</script>

<style scoped>
/* Optional Custom Styling */
.modal-box {
  max-width: 500px;
}
</style>
