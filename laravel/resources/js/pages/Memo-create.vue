<script setup lang="ts">
import { ref, onMounted } from "vue";
import PlusSvg from "@/components/svgs/PlusSvg.vue";

interface Memo {
  id: number;
  title: string;
  content: string;
  created_at: string;
}

const title = ref("");
const content = ref("");
const memos = ref<Memo[]>([]);
const due = ref("");

const fetchMemos = async () => {
  try {
    const response = await fetch("http://localhost:48080/api/memos");
    if (!response.ok) throw new Error("取得失敗");
    memos.value = await response.json();
  } catch (error) {
    console.error("一覧取得エラー:", error);
  }
};

const emit = defineEmits(["saved"]);
const handleSave = async () => {
  if (!title.value && !content.value) return;
  try {
    const response = await fetch("http://localhost:48080/api/memos", {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ title: title.value, content: content.value, due: due.value }),
    });
    if (!response.ok) throw new Error("保存失敗");

    title.value = "";
    content.value = "";
    due.value = "";

    emit("saved");
  } catch (error) {
    alert("保存に失敗しました");
  }
};

onMounted(() => fetchMemos());

const handleKeyDown = (e: KeyboardEvent) => {
  if (e.key === "Enter" && !e.shiftKey && !e.isComposing) {
    e.preventDefault();
    handleSave();
  }
};

const placeholderText = `ここに詳細を入力...
(Enterで保存 / Shift+Enterで改行)`;
</script>

<template>
  <div class="memo-container">
    <div class="new-memo-header">
      <PlusSvg class="plus-icon-header" />
      <h1 class="title">新しいメモ</h1>
    </div>

    <textarea
      v-model="title"
      placeholder="TODO"
      class="memo-title-textarea"
      @keydown="handleKeyDown"
      :class="{ has_title: title }"
    ></textarea>

    <textarea
      v-model="content"
      :placeholder="placeholderText"
      class="memo-textarea"
      :class="{ has_content: content }"
      @keydown="handleKeyDown"
    ></textarea>
    <input
      type="datetime-local"
      v-model="due"
      class="date-input"
      :class="{ is_empty: !due, due: due }"
    />
    <div class="memo-footer">
      <span class="char-count">{{ content.length }} 文字</span>
      <button @click="handleSave" :disabled="!title" class="save-button">
        <PlusSvg class="plus-icon-button" />
        <span>保存する</span>
      </button>
    </div>
  </div>
</template>

<style scoped>
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
  font-weight: bold;
}
.memo-title-textarea {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 10px;
  border: 2px solid #eeeeee;
  border-radius: 8px;
  outline: none;
  font-size: 1rem;
  line-height: 1.6;
  transition: all 0.3s ease;
  resize: vertical;
  height: 50px;
}

.memo-title-textarea:focus {
  border-color: #ff884d;
  background-color: #fffcfb;
  box-shadow: 0 0 0 4px rgba(255, 136, 77, 0.1);
}

.memo-title-textarea.has_title {
  border-color: #ffaa80;
}
.memo-textarea {
  width: 100%;
  min-height: 100px;
  padding: 15px;
  border: 2px solid #eeeeee;
  border-radius: 8px;
  outline: none;
  font-family: inherit;
  font-size: 1rem;
  line-height: 1.6;
  transition: all 0.3s ease;
  resize: vertical;
}

.memo-textarea:focus {
  border-color: #ff884d;
  background-color: #fffcfb;
  box-shadow: 0 0 0 4px rgba(255, 136, 77, 0.1);
}

.memo-textarea.has_content {
  border-color: #ffaa80;
}

.memo-textarea::placeholder {
  white-space: pre-line;
  color: #bbb;
}

.memo-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 15px;
}

.date-input {
  border: 2px solid #eeeeee;
  border-radius: 8px;
  margin-top: 15px;
  padding: 10px;
  outline: none;
  height: 50px;
}

.date-input:focus {
  border-color: #ff884d;
  background-color: #fffcfb;
  box-shadow: 0 0 0 4px rgba(255, 136, 77, 0.1);
}

.date-input.is_empty {
  color: #999;
}
.date-input.due {
  border-color: #ffaa80;
}

.char-count {
  font-size: 0.85rem;
  margin-left: 15px;
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
  background-color: #c6c6c6;
  color: #ededed;
  cursor: not-allowed;
}

.save-button {
  background-color: #ff884d;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 25px;
  font-weight: bold;
  cursor: pointer;
}
.save-button:disabled {
  background-color: #ccc;
}
</style>
