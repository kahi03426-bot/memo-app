<script setup lang="ts">
import DocumentSvg from "@/components/svgs/DocumentSvg.vue";
</script>

<template>
  <header class="header">
    <div class="header-center">
      <DocumentSvg class="icon" />
      <h1 class="title">かんたんメモ</h1>
    </div>
  </header>
  <h2>思いついたことをサッと記録しよう</h2>
</template>

<style scoped>
.header {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 60px;
  padding: 0 20px;
  background-color: #fff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.header-center {
  display: flex;
  align-items: center;
  gap: 10px;
}

.icon{
  height: 32px;
  width: auto;
}

.title {
  font-size: 1.25rem;
  margin: 0;
  color: #ff4500;
}

h2 {
  font-size: 1rem;
  color: #000000;
  font-weight: 400;
  justify-content: center;
  display: flex;
  margin-top: 0.8rem;
}
</style>
