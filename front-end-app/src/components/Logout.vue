<template>
  <div class="modal" :class="{ 'modal-open': visible }">
    <div class="modal-box">
      <p class="py-4">Apakah anda yakin ingin keluar?</p>
      <div class="modal-action">
        <button class="btn btn-danger" @click="confirmLogout">Logout</button>
        <button class="btn" @click="closeDialog">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  visible: Boolean,
});

const emit = defineEmits(["update:visible", "confirmLogout"]);

const closeDialog = () => {
  emit("update:visible", false);
};

const confirmLogout = () => {
  emit("confirmLogout");
};
</script>
