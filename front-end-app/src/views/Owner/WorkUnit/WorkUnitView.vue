<template>
  <div class="min-h-screen bg-base-300 py-20 px-2 sm:p-8 md:p-12 lg:p-20">
    <div class="max-w-6xl mx-auto">
      <h1
        class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-4 sm:mb-8"
      >
        Work Unit
      </h1>
      <div
        class="flex flex-col sm:flex-row justify-start md:items-center mb-2 md:mb-5 md:space-y-4 sm:space-y-0"
      >
        <div>
          <button class="btn btn-active btn-primary" @click="openNewDialog()">
            <i class="pi pi-file-plus"></i>
          </button>
        </div>
        <div class="w-full sm:w-auto">
          <!-- Area untuk input pencarian jika diperlukan -->
        </div>
      </div>
      <div class="overflow-hidden mb-5">
        <div v-if="authStore.isLoading" class="flex justify-center py-4">
          <Loading />
        </div>
        <div class="overflow-x-auto" v-else>
          <table class="table w-full">
            <thead>
              <tr class="bg-base-content text-white border-b border-gray-300">
                <th class="px-2 sm:px-4">No</th>
                <th class="px-2 sm:px-4">Unit Name</th>
                <th class="px-2 sm:px-4">Grade</th>
                <th class="px-2 sm:px-4">Echelon</th>
                <th class="px-2 sm:px-4">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="bg-base-100 border-b border-base-200"
                v-for="(work_unit, i) in dataWorkUnit"
              >
                <td class="px-2 sm:px-4">{{ i + 1 }}</td>
                <td class="px-2 sm:px-4">{{ work_unit.unit_name }}</td>
                <td class="px-2 sm:px-4">{{ work_unit.grade }}</td>
                <td class="px-2 sm:px-4">{{ work_unit.echelon }}</td>
                <td class="px-2 sm:px-4">
                  <button
                    class="btn btn-secondary btn-sm sm:btn-md mx-1 sm:mx-2"
                    @click="openEditDialog(work_unit)"
                  >
                    <i class="pi pi-pencil"></i>
                  </button>
                  <button
                    class="btn btn-accent btn-sm sm:btn-md"
                    @click="openDeleteDialog(work_unit)"
                  >
                    <i class="pi pi-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex justify-center mb-5 space-x-2">
        <button
          class="btn btn-sm sm:btn-md"
          @click="previousPage"
          :disabled="!pagination.prev_page_url"
        >
          «
        </button>
        <button class="btn btn-sm sm:btn-md">
          {{ currentPage }}
        </button>
        <button
          class="btn btn-sm sm:btn-md"
          @click="nextPage"
          :disabled="!pagination.next_page_url"
        >
          »
        </button>
      </div>
    </div>
  </div>
  <WorkUnitDialog
    @closeModal="closeModal"
    @update:visible="showModal = $event"
    :data="selectedWorkUnit"
    @save="getAll"
    :visible="showModal"
  />

  <DeleteDialog
    :visible="deleteDialogVisible"
    :itemName="selectedWorkUnit?.unit_name"
    @update:visible="deleteDialogVisible = $event"
    @confirmDelete="deleteWorkUnit"
  />
</template>

<script setup>
import { ref, onMounted } from "vue";
import { customeApi } from "@/api";
import { useAuthStore } from "@/stores/AuthStore";
import DeleteDialog from "@/components/DeleteDialog.vue";
import WorkUnitDialog from "./WorkUnitDialog.vue";
import Loading from "@/components/Loading.vue";
const authStore = useAuthStore();
const showModal = ref(false);
const deleteDialogVisible = ref(false);
const dataWorkUnit = ref([]);
const selectedWorkUnit = ref(null);
const currentPage = ref(1);
const pagination = ref({});

const openModal = () => {
  showModal.value = true;
};

const openNewDialog = () => {
  selectedWorkUnit.value = null;
  openModal();
};

const openEditDialog = (work_unit) => {
  selectedWorkUnit.value = work_unit;
  openModal();
};

const closeModal = () => {
  showModal.value = false;
  selectedWorkUnit.value = null;
};

const getAll = async () => {
  authStore.isLoading = true;
  try {
    const { data } = await customeApi.get(
      `/work-units?page=${currentPage.value}`, 
      {
        headers: {
          Authorization: `Bearer ${authStore.userToken}`,
        },
      }
    );
    dataWorkUnit.value = data.data;
    pagination.value = data.pagination;
    console.log(dataWorkUnit.value);
  } catch (error) {
    console.error("Error fetching work units:", error);
  } finally {
    authStore.isLoading = false; // Set loading to false
  }
};

const openDeleteDialog = (category) => {
  selectedWorkUnit.value = category;
  deleteDialogVisible.value = true;
};

const deleteWorkUnit = async () => {
  if (!selectedWorkUnit.value) return;

  try {
    await customeApi.delete(`/work-units/${selectedWorkUnit.value.id}`, {
      headers: {
        Authorization: `Bearer ${authStore.userToken}`,
      },
    });
    getAll();
    deleteDialogVisible.value = false;
  } catch (error) {
    console.error("Failed to delete genre:", error);
  }
};

const previousPage = () => {
  if (pagination.value.prev_page_url) {
    currentPage.value -= 1;
    getAll();
  }
};

const nextPage = () => {
  if (pagination.value.next_page_url) {
    currentPage.value += 1;
    getAll();
  }
};

onMounted(() => {
  authStore.isErr = false;
  getAll();
});
</script>
