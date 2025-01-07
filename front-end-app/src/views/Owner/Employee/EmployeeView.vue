<template>
  <div class="min-h-screen bg-base-300 py-20 px-2 sm:p-8 md:p-12 lg:p-20">
    <div class="max-w-6xl mx-auto">
      <h1
        class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-4 sm:mb-8"
      >
        Employee
      </h1>
      <div class="flex flex-col sm:flex-row items-center justify-between mb-6 space-y-4 sm:space-y-0">
        <div class="flex items-center">
          <button class="btn btn-primary" @click="openNewDialog">
            <i class="pi pi-file-plus"></i> Add
          </button>
        </div>

        <div class="flex items-center space-x-4">
          <button class="btn btn-secondary" @click="generatePdf">
            <i class="pi pi-file-pdf"></i> Generate PDF
          </button>

          <input
            v-model="searchQuery"
            @input="searchEmployees"
            type="text"
            placeholder="Search Employees..."
            class="input input-bordered w-full sm:max-w-xs"
          />
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
                <th class="px-2 sm:px-4">NIP</th>
                <th class="px-2 sm:px-4">Nama</th>
                <th class="px-2 sm:px-4">Jabatan</th>
                <th class="px-2 sm:px-4">Tempat Tugas</th>
                <th class="px-2 sm:px-4">Agama</th>
                <th class="px-2 sm:px-4">No. Telepon</th>
                <th class="px-2 sm:px-4">Foto</th>
                <th class="px-2 sm:px-4">Unit Kerja</th>
                <th class="px-2 sm:px-4">Grade</th>
                <th class="px-2 sm:px-4">Echelon</th>
                <th class="px-2 sm:px-4">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="bg-base-100 border-b border-base-200"
                v-for="(work_unit, i) in employees"
              >
                <td class="px-2 sm:px-4">{{ i + 1 }}</td><td>{{ index + 1 }}</td>
                <td class="px-2 sm:px-4">{{ employee.nip }}</td>
                <td class="px-2 sm:px-4">{{ employee.name }}</td>
                <td class="px-2 sm:px-4">{{ employee.position }}</td>
                <td class="px-2 sm:px-4">{{ employee.duty_place }}</td>
                <td class="px-2 sm:px-4">{{ employee.religion }}</td>
                <td class="px-2 sm:px-4">{{ employee.phone_number }}</td>
                <td>
                  <img class="w-12 h-12 rounded-full object-cover" :src="employee.photo" alt="photo" />
                </td>
                <!-- Menampilkan work_unit -->
                <td class="px-2 sm:px-4">{{ employee.work_unit.unit_name }}</td>
                <td class="px-2 sm:px-4">{{ employee.work_unit.grade }}</td>
                <td class="px-2 sm:px-4">{{ employee.work_unit.echelon }}</td>
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
  <EmployeeDialog
    @closeModal="closeModal"
    @update:visible="showModal = $event"
    :data="selected"
    @save="getAll"
    :visible="showModal"
  />

  <DeleteDialog
    :visible="deleteDialogVisible"
    :itemName="searchEmployees?.name"
    @update:visible="deleteDialogVisible = $event"
    @confirmDelete="deleteEmployee"
  />
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useAuthStore } from '@/stores/AuthStore';
import { customeApi } from '@/api';
import { useToast } from 'vue-toast-notification';
import EmployeeDialog from './EmployeeDialog.vue';
import DeleteDialog from '@/components/DeleteDialog.vue';
import Loading from '@/components/Loading.vue';

const authStore = useAuthStore();
const $toast = useToast();
const showModal = ref(false);
const deleteDialogVisible = ref(false);
const selectedEmployee = ref(null);
const employees = ref([]);
const searchQuery = ref('');
const pagination = ref({});
const currentPage = ref(1);

const openNewDialog = () => {
  selectedEmployee.value = null;
  showModal.value = true;
};

const openEditDialog = (employee) => {
  selectedEmployee.value = employee;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedEmployee.value = null;
};

const openDeleteDialog = (employee) => {
  selectedEmployee.value = employee;
  deleteDialogVisible.value = true;
};

const generatePdf = async () => {
  try {
    const { data } = await customeApi.post('/employees/pdf', null, {
      headers: { Authorization: `Bearer ${authStore.userToken}` },
      responseType: 'blob',
    });
    const blob = new Blob([data], { type: 'application/pdf' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'employee-report.pdf';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    $toast.success('PDF report generated successfully!');
  } catch (error) {
    console.error('Error generating PDF:', error);
  }
};

const getAll = async () => {
  authStore.isLoading = true;
  try {
    const searchParams = new URLSearchParams();
    searchParams.append('page', currentPage.value);
    if (searchQuery.value) searchParams.append('query', searchQuery.value);

    const endpoint = searchQuery.value ? '/employees-search' : '/employees';
    const { data } = await customeApi.get(`${endpoint}?${searchParams.toString()}`, {
      headers: { Authorization: `Bearer ${authStore.userToken}` },
    });

    employees.value = data.data;
    pagination.value = data.pagination;
  } catch (error) {
    console.error('Failed to fetch employees:', error);
  } finally {
    authStore.isLoading = false;
  }
};

const searchEmployees = () => {
  currentPage.value = 1;
  getAll();
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

const deleteEmployee = async () => {
  if (!selectedEmployee.value) return;

  try {
    await customeApi.delete(`/employee/${selectedEmployee.value.id}`, {
      headers: { Authorization: `Bearer ${authStore.userToken}` },
    });
    deleteDialogVisible.value = false;
    getAll();
  } catch (error) {
    console.log('Failed to delete employee:', error);
  }
};

watch(searchQuery, () => {
  searchEmployees();
});

onMounted(() => {
  getAll();
});
</script>
