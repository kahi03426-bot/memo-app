<script setup>
import Header from "@/components/Header.vue";
import MemoCreate from "@/pages/Memo-create.vue";
import memoSave from "@/pages/memo-save.vue";
import { ref, onMounted } from "vue";

const memos = ref([]);

const fetchMemos = async () => {
  const response = await fetch("http://localhost:48080/api/memos");
  memos.value = await response.json();
};

onMounted(fetchMemos);
</script>

<template>
  <Header />
  <div class="page-wrapper">
    <memo-create @saved="fetchMemos" />
    <memo-save :memos="memos" @refresh="fetchMemos" />
  </div>

  <!--  <router-view /> -->
</template>

<style scoped>
.page-wrapper {
  background: #ffefe8;
  min-height: 100vh;
  width: 100%;
  padding: 40px;
}
</style>
