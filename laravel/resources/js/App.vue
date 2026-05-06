<script setup>
import Header from "@/components/Header.vue";
import MemoCreate from "@/pages/Memo-create.vue";
import memoSave from "@/pages/memo-save.vue";
import { onMounted } from "vue";
import { useMemoStore } from "@/memoStore.ts";

const memoStore = useMemoStore();

onMounted(() => {
  memoStore.fetchMemos();
});
</script>

<template>
  <Header />
  <div class="page-wrapper">
    <memo-create />
    <memo-save />
  </div>
</template>

<style scoped>
.page-wrapper {
  background: #ffefe8;
  min-height: 100vh;
  width: 100%;
  padding: 150px 40px 40px 40px;
}
</style>
