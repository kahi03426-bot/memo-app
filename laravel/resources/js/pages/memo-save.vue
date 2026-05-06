<script setup lang="ts">
import DocumentSvg from "@/components/svgs/DocumentSvg.vue";
import TrashSvg from "@/components/svgs/TrashSvg.vue";
import { useMemoStore, type Memo } from "@/memoStore.ts";
import { ref } from "vue";

const memoStore = useMemoStore();
const editingId = ref<number | null>(null);
const editTitle = ref("");
const editContent = ref("");
const editDue = ref("");

const startEdit = (memo: Memo) => {
  editingId.value = memo.id;
  editTitle.value = memo.title;
  editContent.value = memo.content;
  editDue.value = memo.due || "";
};

const cancelEdit = () => {
  editingId.value = null;
  editTitle.value = "";
  editContent.value = "";
  editDue.value = ""; // これを追加！
};

const handleUpdate = async (id: number) => {
  if (!editTitle.value && !editContent.value) return;
  try {
    await memoStore.updateMemo(id, editTitle.value, editContent.value, editDue.value);
    editingId.value = null;
  } catch (error) {
    alert("更新に失敗しました");
  }
};

const handleDelete = async (id: number) => {
  if (!confirm("削除してもよろしいですか？")) return;
  try {
    await memoStore.deleteMemo(id);
  } catch (error) {
    alert("削除できませんでした");
  }
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleString("ja-JP", {
    year: "2-digit",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  });
};
</script>

<template>
  <div class="saved-memo-header">
    <div class="saved-memo-box">
      <DocumentSvg class="icon" />
      <h1 class="saved-memo">保存されたメモ</h1>
    </div>
    <div class="memo-number">{{ memoStore.memos.length }}件</div>
  </div>

  <div v-for="memo in memoStore.memos" :key="memo.id" class="memo-item">
    <div v-if="editingId === memo.id" class="edit-layout">
      <input v-model="editTitle" class="inline-textarea" placeholder="タイトル" />
      <textarea v-model="editContent" class="inline-textarea" placeholder="内容"></textarea>
      <div class="edit-footer">
        <input type="datetime-local" v-model="editDue" class="edit-date-input" />
        <div class="edit-actions">
          <button @click="handleUpdate(memo.id)" class="update-btn">更新</button>
          <button @click="cancelEdit" class="cancel-btn">キャンセル</button>
        </div>
      </div>
    </div>

    <div v-else @click="startEdit(memo)" class="display-layout">
      <button class="delete-button" @click.stop="handleDelete(memo.id)">
        <TrashSvg class="icon" />
      </button>
      <p class="memo-title">{{ memo.title }}</p>
      <p class="memo-content">{{ memo.content }}</p>
      <p class="due-date">Due : {{ formatDate(memo.due) }}</p>
    </div>
  </div>
</template>

<style scoped>
.icon {
  width: 24px;
  height: 24px;
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

.saved-memo-box {
  display: flex;
  align-items: center;
  gap: 10px;
}

.saved-memo {
  font-size: 1.25rem;
  margin: 0;
  color: #333;
  font-weight: bold;
}

.memo-number {
  background: #ffd9c2;
  padding: 4px 12px;
  border-radius: 20px;
  color: #ff6a20;
  font-weight: bold;
  font-size: 0.9rem;
}

.memo-item {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  padding: 20px;
  margin: 20px auto;
  position: relative;
  max-width: 600px;
  width: 90%;
  box-sizing: border-box;
  transition: all 0.3s ease;
  cursor: pointer;
}

.memo-item:hover {
  border-color: #ff884d;
  background-color: #fffcfb;
}

.display-layout {
  display: flex;
  flex-direction: column;
}

.memo-title {
  font-size: 1.15rem;
  font-weight: bold;
  margin: 0 0 4px 0;
  color: #333;
}

.memo-content {
  font-size: 1rem;
  margin: 0 0 8px 0;
  color: #555;
  line-height: 1.3;
  white-space: pre-wrap;
}

.due-date {
  font-size: 0.85rem;
  color: #888;
  margin:0;
}

.edit-layout {
  display: flex;
  flex-direction: column;
  gap: 12px;
  width: 100%;
}

.inline-textarea {
  width: 100%;
  box-sizing: border-box;
  border: 2px solid #ff884d;
  border-radius: 8px;
  padding: 12px;
  font-family: inherit;
  font-size: 1rem;
  outline: none;
  background: #fff;
}


textarea.inline-textarea {
  min-height: 100px;
  resize: vertical;
}

.edit-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
}

.edit-date-input {
  border: 2px solid #ff884d;
  border-radius: 8px;
  padding: 8px 12px;
  outline: none;
  font-family: inherit;
  color: #555;
}

.edit-actions {
  display: flex;
  gap: 10px;
}

.update-btn {
  background: #ff884d;
  color: white;
  border: none;
  padding: 8px 20px;
  border-radius: 20px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.2s;
}

.update-btn:hover {
  background: #e6763d;
}

.cancel-btn {
  background: #eee;
  color: #666;
  border: none;
  padding: 8px 20px;
  border-radius: 20px;
  cursor: pointer;
  transition: background 0.2s;
}

.cancel-btn:hover {
  background: #ddd;
}


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
  transition:
    opacity 0.2s,
    transform 0.2s;
  z-index: 10;
  cursor: pointer;
}

.delete-button:hover {
  transform: scale(1.1);
  background: #ff3333;
}

.memo-item:hover .delete-button {
  opacity: 1;
}

@media (hover: none) {
  .delete-button {
    opacity: 1;
  }
}
</style>
