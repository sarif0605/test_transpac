<template>
  <div class="min-h-screen bg-base-200 py-20 px-4 sm:px-8 lg:px-12">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-6 text-center">
        Manage Employees
      </h1>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row items-center justify-between mb-6 space-y-4 sm:space-y-0">
        <div class="flex items-center">
          <button class="btn btn-primary" @click="openNewDialog">
            <i class="pi pi-file-plus"></i> Add Employee
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

      <!-- Employee List Table -->
      <div v-if="authStore.isLoading" class="flex justify-center py-6">
        <Loading />
      </div>
      <div v-else>
        <div class="overflow-x-auto">
          <table class="table w-full">
            <thead>
              <tr class="bg-base-content text-white border-b border-gray-300">
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Tempat Tugas</th>
                <th>Agama</th>
                <th>No. Telepon</th>
                <th>Foto</th>
                <th>Unit Kerja</th>
                <th>Grade</th>
                <th>Echelon</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="bg-base-100 border-b border-base-200" v-for="(employee, index) in employees" :key="employee.id">
                <td>{{ index + 1 }}</td>
                <td>{{ employee.nip }}</td>
                <td>{{ employee.name }}</td>
                <td>{{ employee.position }}</td>
                <td>{{ employee.duty_place }}</td>
                <td>{{ employee.religion }}</td>
                <td>{{ employee.phone_number }}</td>
                <td>
                  <img class="w-12 h-12 rounded-full object-cover" :src="employee.photo" alt="photo" />
                </td>
                <!-- Menampilkan work_unit -->
                <td>{{ employee.work_unit.unit_name }}</td>
                <td>{{ employee.work_unit.grade }}</td>
                <td>{{ employee.work_unit.echelon }}</td>
                <td class="space-x-2">
                  <button
                    class="btn btn-sm btn-secondary"
                    @click="openEditDialog(employee)"
                  >
                    <i class="pi pi-pencil"></i>
                  </button>
                  <button
                    class="btn btn-sm btn-error"
                    @click="openDeleteDialog(employee)"
                  >
                    <i class="pi pi-trash"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="employees.length === 0">
                <td colspan="12" class="text-center">Tidak ada data karyawan</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination Controls -->
      <div class="flex justify-center space-x-4 mt-6">
        <button
          class="btn btn-sm sm:btn-md"
          @click="previousPage"
          :disabled="!pagination.prev_page_url"
        >
          « Previous
        </button>
        <button class="btn btn-sm sm:btn-md">{{ currentPage }}</button>
        <button
          class="btn btn-sm sm:btn-md"
          @click="nextPage"
          :disabled="!pagination.next_page_url"
        >
          Next »
        </button>
      </div>
    </div>

    <!-- Add/Edit Employee Dialog -->
    <EmployeeDialog
      :visible="showModal"
      :data="selectedEmployee"
      @closeModal="closeModal"
      @save="getAll"
    />

    <!-- Delete Confirmation Dialog -->
    <DeleteDialog
      :visible="deleteDialogVisible"
      :itemName="selectedEmployee?.name"
      @update:visible="deleteDialogVisible = $event"
      @confirmDelete="deleteEmployee"
    />
  </div>
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
