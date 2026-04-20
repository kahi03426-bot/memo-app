<script setup lang="ts">
import DocumentSvg from "@/components/svgs/DocumentSvg.vue";
import TrashSvg from "@/components/svgs/TrashSvg.vue";
import { ref } from "vue"; // onMountedは不要になるので削除

interface Memo {
  id: number;
  title: string;
  content: string;
  created_at: string;
}

// --- 1. 親からデータをもらう設定 (重複していたので一つに) ---
defineProps<{
  memos: Memo[];
}>();

// --- 2. 親に「更新して！」と伝える設定 ---
const emit = defineEmits(["refresh"]);

// --- 編集用の状態 ---
const editingId = ref<number | null>(null);
const editContent = ref("");

const startEdit = (memo: Memo) => {
  editingId.value = memo.id;
  editContent.value = memo.content;
};

const cancelEdit = () => {
  editingId.value = null;
  editContent.value = "";
};

// --- API操作 (自分自身の fetchMemos は削除) ---

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
    // ★重要: 自分のfetchではなく、親に再取得をお願いする
    emit("refresh");
  } catch (error) {
    alert("更新に失敗しました");
  }
};

const handleDelete = async (id: number) => {
  if (!confirm("削除してもよろしいですか？")) return;
  try {
    const response = await fetch(`http://localhost:48080/api/memos/${id}`, {
      method: "DELETE",
    });
    if (!response.ok) throw new Error("削除失敗");

    // ★重要: 親に再取得をお願いする
    emit("refresh");
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
</script>

<template>
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
</template>

<style scoped>
/* スタイルは今のままでOKです！ */
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
.memo-item:hover .delete-button {
  opacity: 1;
}
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
  cursor: pointer;
}
.memo-number {
  background: #ffd9c2;
  padding: 4px 12px;
  border-radius: 20px;
  color: #ff6a20;
  font-weight: bold;
}
</style>
