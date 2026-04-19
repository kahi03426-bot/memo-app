<script setup lang="ts">
import { ref } from "vue";
import PlusSvg from "@/components/svgs/PlusSvg.vue";

const title = ref("");
const content = ref("");

const placeholderText = `ここにメモを入力...
(Enterで保存 / Shift+Enterで改行)`;

const handleSave = async () => {
  if (!content.value) return;

  const newMemo = {
    title: title.value || "無題のメモ",
    content: content.value,
  };

  try {
    // 2. サーバー（Laravel）の API へデータを送信
    const response = await fetch("http://localhost:48080/api/memos", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(newMemo), // データをテキスト形式に変換
    });

    // 3. サーバーからの返事が OK かチェック
    if (!response.ok) {
      throw new Error("サーバーでの保存に失敗しました");
    }

    const result = await response.json();
    console.log("DBに保存されたデータ:", result);
    alert("データベースに保存しました！");

    title.value = "";
    content.value = "";
  }

  catch (error) {
    // 5. 通信エラーなどが起きた場合の処理
    console.error("保存中にエラー:", error);
    alert("保存できませんでした。サーバーが動いているか確認してください。");
  }
};

const handleKeyDown = (e: KeyboardEvent) => {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    handleSave();
  }
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
  border: 2px solid #eeeeee; /* 通常時の枠線 */
  border-radius: 8px;
  outline: none;
  font-family: inherit;
  font-size: 1rem;
  line-height: 1.6;
  transition: all 0.3s ease; /* 変化をふわっとさせる */
  resize: vertical;
}

/* ★ 1. クリックした時（フォーカス時）の枠色 */
.memo-textarea:focus {
  border-color: #ff884d; /* オレンジ色に変わる */
  background-color: #fffcfb; /* ほんの少しだけ背景を明るくする（お好みで） */
  box-shadow: 0 0 0 4px rgba(255, 136, 77, 0.1); /* ふわっとした光彩を追加 */
}

/* ★ 2. 文字が入っている時の枠色（フォーカスが外れてもオレンジを維持したい場合） */
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
