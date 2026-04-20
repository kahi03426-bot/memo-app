<script setup lang="ts">
import { ref, onMounted } from "vue";
import PlusSvg from "@/components/svgs/PlusSvg.vue";
import DocumentSvg from "@/components/svgs/DocumentSvg.vue";
import TrashSvg from "@/components/svgs/TrashSvg.vue";

interface Memo {
  id: number;
  title: string;
  content: string;
  created_at: string;
}

const title = ref("");
const content = ref("");
const memos = ref<Memo[]>([]);

// --- 編集用の状態 ---
const editingId = ref<number | null>(null);
const editContent = ref(""); // 編集中のテキストを一時保存

// 編集モード開始
const startEdit = (memo: Memo) => {
  editingId.value = memo.id;
  editContent.value = memo.content;
};

// 編集キャンセル
const cancelEdit = () => {
  editingId.value = null;
  editContent.value = "";
};

// --- API操作 ---

const fetchMemos = async () => {
  try {
    const response = await fetch("http://localhost:48080/api/memos");
    if (!response.ok) throw new Error("取得失敗");
    memos.value = await response.json();
  } catch (error) {
    console.error("一覧取得エラー:", error);
  }
};

const handleSave = async () => {
  if (!content.value) return;
  try {
    const response = await fetch("http://localhost:48080/api/memos", {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ title: "無題のメモ", content: content.value }),
    });
    if (!response.ok) throw new Error("保存失敗");
    content.value = "";
    await fetchMemos();
  } catch (error) {
    alert("保存に失敗しました");
  }
};

// インライン更新用の関数
const handleUpdate = async (id: number) => {
  if (!editContent.value) return;
  try {
    const response = await fetch(`http://localhost:48080/api/memos/${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ content: editContent.value }),
    });
    if (!response.ok) throw new Error("更新失敗");
    editingId.value = null;
    await fetchMemos();
  } catch (error) {
    alert("更新に失敗しました");
  }
};

const handleDelete = async (id: number) => {
  if (!confirm("削除してもよろしいですか？")) return;
  try {
    const response = await fetch(`http://localhost:48080/api/memos/${id}`, { method: "DELETE" });
    if (!response.ok) throw new Error("削除失敗");
    await fetchMemos();
  } catch (error) {
    alert("削除できませんでした");
  }
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleString("ja-JP", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  });
};

onMounted(() => fetchMemos());

const handleKeyDown = (e: KeyboardEvent) => {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    handleSave();
  }
};

const placeholderText = `ここにメモを入力...
(Enterで保存 / Shift+Enterで改行)`;
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

    <div class="saved-memo-header">
      <div class="saved-memo-box">
        <DocumentSvg class="icon" />
        <h1 class="saved-memo">保存されたメモ</h1>
      </div>
      <div class="memo-number">{{ memos.length }}件</div>
    </div>

    <div v-for="memo in memos" :key="memo.id" class="memo-item">
      <div v-if="editingId === memo.id" class="edit-layout">
        <textarea v-model="editContent" class="inline-textarea"></textarea>
        <div class="edit-actions">
          <button @click="handleUpdate(memo.id)" class="update-btn">更新</button>
          <button @click="cancelEdit" class="cancel-btn">キャンセル</button>
        </div>
      </div>

      <div v-else @click="startEdit(memo)" class="display-layout">
        <button class="delete-button" @click.stop="handleDelete(memo.id)">
          <TrashSvg class="icon" />
        </button>
        <p class="memo-text">{{ memo.content }}</p>
        <small class="memo-date">{{ formatDate(memo.created_at) }}</small>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* 既存のスタイルを維持しつつ追加・修正 */

.page-wrapper {
  background: #ffefe8;
  min-height: 100vh;
  width: 100%;
  padding: 40px;
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
  font-weight: bold;
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

.memo-textarea.has-content {
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

.icon {
  width: 24px;
  height: 24px;
}
.saved-memo {
  font-size: 1.25rem;
  margin: 0;
  color: #333;
  font-weight: bold;
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

.saved-memo-box {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 10px;
}

.saved-memo-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 40px 0 10px 0;
  width: 90%;
  max-width: 600px;
  margin: 0 auto;
}

.memo-item {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  padding: 20px;
  margin: 20px auto;
  position: relative;
  transition: all 0.3s ease;
  cursor: pointer;
  max-width: 600px;
  width: 90%;
}

.memo-item:hover {
  border-color: #ff884d;
  background-color: #fffcfb;
}

/* 編集用スタイル */
.inline-textarea {
  width: 100%;

  min-height: 100px;
  border: 2px solid #ff884d;
  border-radius: 8px;
  padding: 10px;
  font-family: inherit;
  font-size: 1rem;
  resize: vertical;
  outline: none;
}

.edit-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

.update-btn {
  background: #ff884d;
  color: white;
  border: none;
  padding: 6px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-weight: bold;
}

.cancel-btn {
  background: #eee;
  color: #666;
  border: none;
  padding: 6px 16px;
  border-radius: 20px;
  cursor: pointer;
}

/* 削除ボタン */
.delete-button {
  position: absolute;
  top: 15px;
  right: 15px;
  background: #ff4d4d;
  color: white;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.2s;
  z-index: 10;
}

.memo-item:hover .delete-button {
  opacity: 1;
}

/* 他、既存のボタンスタイルなどはそのまま適用 */
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
.memo-number {
  background: #ffd9c2;
  padding: 4px 12px;
  border-radius: 20px;
  color: #ff6a20;
  font-weight: bold;
}
</style>
