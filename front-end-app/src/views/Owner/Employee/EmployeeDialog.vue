<template>
    <div>
    <div class="modal" :class="{ 'modal-open': visible }">
        <!-- Form Inputs -->
        <div class="modal-box relative z-20 max-w-4xl w-full">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">
          {{ data ? "Update" : "Tambah" }} Employee
        </h2>
        
        <!-- Form Submission -->
        <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
          
          <!-- Loading indicator -->
          <div v-if="authStore.isLoading" class="absolute inset-0 top-0 bottom-0 left-0 right-0 flex items-center justify-center bg-white bg-opacity-75 z-10">
            <Loading />
          </div>

          <!-- Error Notification -->
          <div role="alert" class="alert alert-error" v-if="authStore.isErr">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <ul>
              <li v-for="(msg, index) in authStore.errMsg.general" :key="'general-' + index">{{ msg }}</li>
              <li v-for="(errors, field) in authStore.errMsg" v-if="field !== 'general'" :key="field">
                <ul>
                  <li v-for="(msg, index) in errors" :key="index">{{ msg }}</li>
                </ul>
              </li>
            </ul>
          </div>

          <!-- Form Fields in Grid Layout -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">NIP</label>
              <input type="text" class="input input-bordered w-full mt-2" v-model="employee.nip" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Nama</label>
              <input type="text" class="input input-bordered w-full mt-2" v-model="employee.name" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Jabatan</label>
              <input type="text" class="input input-bordered w-full mt-2" v-model="employee.position" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Tempat Tugas</label>
              <input type="text" class="input input-bordered w-full mt-2" v-model="employee.duty_place" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Agama</label>
              <input type="text" class="input input-bordered w-full mt-2" v-model="employee.religion" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
              <input type="text" class="input input-bordered w-full mt-2" v-model="employee.phone_number" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">NPWP</label>
              <input type="text" class="input input-bordered w-full mt-2" v-model="employee.npwp" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" class="input input-bordered w-full mt-2" v-model="employee.email" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
              <input type="text" class="input input-bordered w-full mt-2" v-model="employee.birth_place" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
              <input type="date" class="input input-bordered w-full mt-2" v-model="employee.birth_date" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
              <select class="select select-primary w-full mt-2" v-model="employee.gender">
                <option disabled value="">Pilih Jenis Kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>

            <!-- Work Unit Selection -->
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
              <select class="select select-primary w-full mt-2" v-model="employee.work_unit_id">
                <option disabled value="">Pilih Unit Kerja</option>
                <option v-for="unit in workUnits" :key="unit.id" :value="unit.id">
                  {{ unit.unit_name }} - Grade: {{ unit.grade }} - Echelon: {{ unit.echelon }}
                </option>
              </select>
            </div>

            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700">Alamat</label>
              <textarea class="textarea textarea-bordered w-full mt-2" placeholder="Alamat" v-model="employee.address"></textarea>
            </div>

            <!-- File Upload -->
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700">Foto</label>
              <input class="grow mt-2" id="photo" type="file" accept="image/*" @change="selectImage" />
            </div>

            <!-- Image Preview -->
            <div class="col-span-2 mt-2" v-if="employee.previewImage || (data && data.photo)">
              <div class="avatar w-24 rounded">
                <img :src="employee.previewImage || data?.photo" />
              </div>
            </div>
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
import { reactive, ref, watch, computed, onMounted } from "vue";
import { customeApi } from "@/api";
import { useAuthStore } from "@/stores/AuthStore";
import Loading from "@/components/Loading.vue";
import { useToast } from "vue-toast-notification";

const $toast = useToast();
const authStore = useAuthStore();
const props = defineProps({
  category: {
    type: Array,
    required: true,
  },
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
const employee = reactive({
  nip: "",
  name: "",
  position: "",
  duty_place: "",
  religion: "",
  phone_number: "",
  npwp: "",
  email: "",
  birth_place: "",
  birth_date: "",
  gender: "",
  address: "",
  work_unit_id: "",
  currentImage: null,
  previewImage: null,
});

const clearInput = () => {
  employee.nip = "";
  employee.name = "";
  employee.position = "";
  employee.duty_place = "";
  employee.religion = "";
  employee.phone_number = "";
  employee.npwp = "";
  employee.email = "";
  employee.birth_place = "";
  employee.birth_date = "";
  employee.gender = "";
  employee.address = "";
  employee.work_unit_id = "";
  employee.currentImage = null;
  employee.previewImage = null;
};

watch(
  () => props.data,
  (newValue) => {
    if (newValue) {
      employee.nip = newValue.nip;
      employee.name = newValue.name;
      employee.position = newValue.position;
      employee.duty_place = newValue.duty_place;
      employee.religion = newValue.religion;
      employee.phone_number = newValue.phone_number;
      employee.npwp = newValue.npwp;
      employee.email = newValue.email;
      employee.birth_place = newValue.profile['birth_place'];
      employee.birth_date = newValue.birth_date;
      employee.gender = newValue.gender;
      employee.address = newValue.address;
      employee.work_unit_id = newValue.work_unit_id;
      employee.previewImage = newValue.photo;
    } else {
      clearInput();
    }
  },
  { immediate: true }
);

const handleSubmit = async () => {
  authStore.isLoading = true;
  const isFileUpload = employee.currentImage;
  let formData;
  let headers;
  if (isFileUpload) {
    formData = new FormData();
    formData.append("nip", employee.nip);
    formData.append("name", employee.name);
    formData.append("position", employee.position);
    formData.append("duty_place", employee.duty_place);
    formData.append("religion", employee.religion);
    formData.append("phone_number", employee.phone_number);
    formData.append("npwp", employee.npwp);
    formData.append("email", employee.email);
    formData.append("birth_place", employee.birth_place);
    formData.append("birth_date", employee.birth_date);
    formData.append("gender", employee.gender);
    formData.append("address", employee.address);
    formData.append("work_unit_id", employee.work_unit_id);
    if (employee.currentImage) {
      formData.append("photo", employee.currentImage);
    } else if (employee.previewImage) {
      formData.append("photo", employee.previewImage);
    }
  } else {
    formData = JSON.stringify({
      nip: employee.nip,
      name: employee.name,
      position: employee.position,
      duty_place: employee.duty_place,
      religion: employee.religion,
      phone_number: employee.phone_number,
      npwp: employee.npwp,
      email: employee.email,
      birth_place: employee.birth_place,
      birth_date: employee.birth_date,
      gender: employee.gender,
      address: employee.address,
      work_unit_id: employee.work_unit_id,
    });
  }
  headers = {
    Authorization: `Bearer ${authStore.userToken}`,
    "Content-Type": isFileUpload ? "multipart/form-data" : "application/json",
  };

  try {
    const url = employee.nip ? `/employees/${employee.nip}` : "/employees";
    const method = employee.nip ? "put" : "post";
    const response = await customeApi[method](url, formData, {
      headers: headers,
    });
    console.log(method, url, headers);
    if (response.data) {
      clearInput();
      emit("save");
      emit("closeModal");
    }
    authStore.isLoading = false;
    $toast.success(
      book.id ? "Berhasil Ubah Data Employee" : "Berhasil Tambah Data Employee",
      {
        position: "top-right",
      }
    );
  } catch (error) {
    console.log(error);
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

// Fetch work units
const fetchWorkUnits = async () => {
  try {
    const { data } = await customeApi.get('/all', {
      headers: { Authorization: `Bearer ${authStore.userToken}` }
    });
    workUnits.value = data.data;
    console.log(workUnits.value);
    
  } catch (error) {
    console.error('Failed to fetch work units:', error);
    $toast.error('Failed to load work units', { position: "top-right" });
  }
};

const selectImage = (event) => {
  const file = event.target.files[0];
  if (file) {
    employee.currentImage = file;
    employee.previewImage = URL.createObjectURL(file);
  }
};

onMounted(() => {
  fetchWorkUnits();
});
</script>