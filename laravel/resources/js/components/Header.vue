<script setup lang="ts">
import DocumentSvg from "@/components/svgs/DocumentSvg.vue";
</script>

<template>
  <header class="header">
    <div class="icon-leftside">
    </div>

    <div class="logo-group">
      <DocumentSvg class="icon" />
      <h1>かんたんメモ</h1>
    </div>
    <div class="icon-rightside"></div>
  </header>
  <h2>思いついたことをサッと記録しよう</h2>
</template>

<style scoped>
.header {
  display: flex;
  justify-content: space-between; /* 3つの箱を「左・中・右」に振る */
  align-items: center; /* 全体の高さを真ん中に */
  height: 2.5rem; /* ヘッダーの高さを決める */
  padding: 4rem 4rem 3rem 4rem;
}

.icon-leftside,
.logo-group,
.icon-rightside {
  flex: 1; /* 「幅を0から計算して均等に1ずつ分ける」という強い命令 */
  width: 0;
  height: 100%; /* 親(60px)と同じ高さにする */
  display: flex; /* 中身を並べるためにflexにする */
  align-items: center;
}

.logo-group {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2.5rem;
}

.icon-leftside {
  display: flex;
  align-items: center;
}

.icon-left {
  width: 2.5rem;
  height: 2.5rem;
  color: darkblue;
}

h1 {
  font-size: 2.5rem;
  color: darkblue;
  margin: 0;
  font-weight: 700;
  display: flex;
}

.icon {
  width: 2.5rem;
  height: 2.5rem;
  color: #4979cf;
  display: flex;
}

.icon-rightside {
}

h2 {
  font-size: 1.5rem;
  color: #000000;
  font-weight: 400;
  justify-content: center;
  display: flex;
}
</style>
