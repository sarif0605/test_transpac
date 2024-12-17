<template>
  <div>
    <div class="modal" :class="{ 'modal-open': visible }">
      <div class="modal-box relative z-20">
        <p class="py-4">{{ data ? "Update" : "Tambah" }} Employee</p>
        <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
          <div
            v-if="authStore.isLoading"
            class="absolute inset-0 top-0 bottom-0 left-0 right-0 flex items-center justify-center bg-white bg-opacity-75 z-10"
          >
            <Loading />
          </div>

          <div role="alert" class="alert alert-error" v-if="authStore.isErr">
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
              <li
                v-for="(msg, index) in authStore.errMsg.general"
                :key="'general-' + index"
              >
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

          <label class="input input-bordered flex items-center gap-2">
            <input
              type="text"
              class="grow"
              placeholder="NIP"
              v-model="employee.nip"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              type="text"
              class="grow"
              placeholder="Nama"
              v-model="employee.name"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              type="text"
              class="grow"
              placeholder="Jabatan"
              v-model="employee.position"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              type="text"
              class="grow"
              placeholder="Tempat Tugas"
              v-model="employee.duty_place"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              type="text"
              class="grow"
              placeholder="Agama"
              v-model="employee.religion"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              type="text"
              class="grow"
              placeholder="No. Telepon"
              v-model="employee.phone_number"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              type="text"
              class="grow"
              placeholder="NPWP"
              v-model="employee.npwp"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              type="email"
              class="grow"
              placeholder="Email"
              v-model="employee.email"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              type="text"
              class="grow"
              placeholder="Tempat Lahir"
              v-model="employee.birth_place"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              type="date"
              class="grow"
              placeholder="Tanggal Lahir"
              v-model="employee.birth_date"
            />
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <select class="select select-primary w-full" v-model="employee.gender">
              <option disabled value="">Pilih Jenis Kelamin</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <textarea
              class="textarea textarea-bordered w-full"
              placeholder="Alamat"
              v-model="employee.address"
            ></textarea>
          </label>

          <label class="input input-bordered flex items-center gap-2 mt-2">
            <input
              class="grow"
              id="photo"
              type="file"
              accept="image/*"
              @change="selectImage"
            />
          </label>

          <div class="avatar mt-2" v-if="employee.previewImage">
            <div class="w-24 rounded">
              <img :src="employee.previewImage" />
            </div>
          </div>

          <label class="flex items-center gap-2 mt-4">
            <button class="btn btn-primary" type="submit">Kirim</button>
            <button class="btn btn-base-300" type="button" @click="closeDialog">
              Close
            </button>
          </label>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from "vue";
import { customeApi } from "@/api";
import { useAuthStore } from "@/stores/AuthStore";
import Loading from "@/components/Loading.vue";
import { useToast } from "vue-toast-notification";
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
const employee = reactive({
  work_unit_id : "",
  nip : "",
  name : "",
  position : "",
  duty_place : "",
  religion : "",
  phone_number : "",
  npwp : "",
  email : "",
  birth_place : "",
  birth_date : "",
  gender : "",
  address : "",
  currentImage: null,
  previewImage: null,
});

const clearInput = () => {
  employee.work_unit_id = "",
  employee.nip = "",
  employee.name = "",
  employee.position = "",
  employee.duty_place = "",
  employee.religion = "",
  employee.phone_number = "",
  employee.npwp = "",
  employee.email = "",
  employee.birth_place = "",
  employee.birth_date = "",
  employee.gender = "",
  employee.address = "",
  employee.currentImage = null;
  employee.previewImage = null;
};

watch(
  () => props.data,
  (newValue) => {
    if (newValue) {
      employee.work_unit_id = newValue.work_unit_id,
      employee.nip = newValue.nip,
      employee.name = newValue.name,
      employee.position = newValue.position,
      employee.duty_place = newValue.duty_place,
      employee.religion = newValue.religion,
      employee.phone_number = newValue.phone_number,
      employee.npwp = newValue.npwp,
      employee.email = newValue.email,
      employee.birth_place = newValue.birth_place,
      employee.birth_date = newValue.birth_date,
      employee.gender = newValue.gender,
      employee.address = newValue.address,
      employee.previewImage = newValue.photo;
    } else {
      clearInput();
    }
  },
  { immediate: true }
);

const handleSubmit = async () => {
  authStore.isLoading = true;
  const isFileUpload = book.currentImage;
  let formData;
  let headers;
  if (isFileUpload) {
    formData = new FormData();
    formData.append("work_unit_id", employee.work_unit_id);
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
    if (book.currentImage) {
      formData.append("photo", employee.currentImage);
    } else if (book.previewImage) {
      formData.append("photo", employee.previewImage);
    }
  } else {
    formData = JSON.stringify({
      work_unit_id : employee.work_unit_id,
      nip : employee.nip,
      name : employee.name,
      position : employee.position,
      duty_place : employee.duty_place,
      religion : employee.religion,
      phone_number : employee.phone_number,
      npwp : employee.npwp,
      email : employee.email,
      birth_place : employee.birth_place,
      birth_date : employee.birth_date,
      gender : employee.gender,
      address : employee.address 
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
  }
};

const selectImage = (event) => {
  const file = event.target.files[0];
  if (file) {
    employee.currentImage = file;
    employee.previewImage = URL.createObjectURL(file);
  }
};
</script>
