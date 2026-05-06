<script setup lang="ts">
import DocumentSvg from "@/components/svgs/DocumentSvg.vue";
</script>

<template>
  <header class="header">
    <div class="header-center">
      <DocumentSvg class="icon" />
      <h1 class="title">かんたんメモ</h1>
    </div>
    <h2 class="subtitle">思いついたことをサッと記録しよう</h2>
  </header>
</template>

<style scoped>
.header {
  display: flex;
  z-index:1000;
  position: fixed;
  justify-content: center;
  flex-direction: column;
  align-items: center;
  padding-top: 30px;
  background-color: #fff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  width: 100%;
}

.header-center {
  display: flex;
  align-items: center;
  gap: 10px;
}

.icon {
  height: 32px;
  width: auto;
}

.title {
  font-size: 1.25rem;
  margin: 0;
  color: #ff4500;
}

.subtitle {
  font-size: 1rem;
  color: #888888;
  justify-content: center;
  margin: 15px 0 10px 0;
}
</style>
