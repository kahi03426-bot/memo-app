<script setup lang="ts">
import { ref } from "vue";
import PlusSvg from "@/components/svgs/PlusSvg.vue";

// 1. 変数はすべて「関数の外」で定義する
const title = ref("");
const content = ref("");

// ここ！これを handleKeyDown の外に出すのが正解です
const placeholderText = `ここにメモを入力...
(Enterで保存 / Shift+Enterで改行)`;

// 2. 保存処理
const handleSave = () => {
  if (!content.value) return;

  const newMemo = {
    id: Date.now(),
    title: title.value || "無題のメモ",
    content: content.value,
    date: new Date().toLocaleString(),
  };

  console.log("保存されたメモ:", newMemo);
  alert("メモを保存しました！");

  title.value = "";
  content.value = "";
};

// 3. キー入力判定
const handleKeyDown = (e: KeyboardEvent) => {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    handleSave();
  }
  // この中には placeholderText を書かない
};
</script>

<template>
  <div class="page-wrapper">
    <div class="memo-container">
      <div class="new-memo-header">
        <PlusSvg class="plus-icon-header" />
        <h1 class="title">新しいメモ</h1>
      </div>

      <textarea
        v-model="content"
        :placeholder="placeholderText"
        class="memo-textarea"
        :class="{ 'has-content': content.length > 0 }"
        @keydown="handleKeyDown"
      ></textarea>

      <div class="memo-footer">
        <span class="char-count">{{ content.length }} 文字</span>

        <button @click="handleSave" :disabled="!content" class="save-button">
          <PlusSvg class="plus-icon-button" />
          <span>保存する</span>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* スタイル部分は前回から変更なし */
.page-wrapper {
  background: #ffddce;
  min-height: 100vh;
  width: 100%;
  padding-top: 40px;
  padding-bottom: 40px;
}

.memo-container {
  width: 90%;
  max-width: 600px;
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  padding: 20px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
}

.new-memo-header {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  padding-bottom: 15px;
  gap: 10px;
}

.plus-icon-header {
  width: 24px;
  height: 24px;
  color: #ff884d;
}

.title {
  font-size: 1.25rem;
  margin: 0;
  color: #333;
}

.memo-textarea {
  width: 100%;
  min-height: 250px;
  padding: 15px;
  border: 2px solid #eeeeee;
  border-radius: 8px;
  outline: none;
  font-family: inherit;
  font-size: 1rem;
  line-height: 1.6;
  transition: border-color 0.3s ease;
  resize: vertical;
}

.memo-textarea.has-content {
  border-color: #ffaa80;
}

.memo-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 15px;
}

.char-count {
  font-size: 0.85rem;
  color: #888;
}

.save-button {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background-color: #ff884d;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  border-radius: 25px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s;
}

.plus-icon-button {
  width: 18px;
  height: 18px;
  color: inherit;
}

.plus-icon-button :deep(path) {
  stroke: currentColor !important;
}

.save-button:hover:not(:disabled) {
  background-color: #ff6a20;
}

.save-button:disabled {
  background-color: #cccccc;
  color: #888888;
  cursor: not-allowed;
}
</style>
