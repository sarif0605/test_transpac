<template>
  <div class="modal" :class="{ 'modal-open': visible }">
    <div class="modal-box">
      <h4 class="text-lg font-bold">Konfirmasi Hapus Data</h4>
      <p class="py-4">
        Apakah Anda yakin Ingin Menghapus Data {{ itemName }} ?
      </p>
      <div class="modal-action">
        <button class="btn btn-danger" @click="confirmDelete">Hapus</button>
        <button class="btn" @click="closeDialog">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineEmits, defineProps } from "vue";
import { useToast } from "vue-toast-notification";
const $toast = useToast();
const props = defineProps({
  visible: Boolean,
  itemName: String,
});
const emit = defineEmits(["update:visible", "confirmDelete"]);

const closeDialog = () => {
  emit("update:visible", false);
};

const confirmDelete = () => {
  emit("confirmDelete");
  $toast.success(`Berhasil Menghapus Data ${props.itemName}`, {
    position: "top-right",
  });
};
</script>
