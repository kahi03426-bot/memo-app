import { defineStore } from "pinia";
import { ref } from "vue";

export interface Memo {
  id: number;
  title: string;
  content: string;
  created_at: string;
  due: string;
}

export const useMemoStore = defineStore("memo", () => {
  const memos = ref<Memo[]>([]);

  // 一覧取得
  const fetchMemos = async () => {
    try {
      const response = await fetch("http://localhost:48080/api/memos");
      if (!response.ok) throw new Error("取得失敗");
      memos.value = await response.json();
    } catch (error) {
      console.error("一覧取得エラー:", error);
    }
  };

  // 保存 (POST)
  const saveMemo = async (title: string, content: string, due: string) => {
    const response = await fetch("http://localhost:48080/api/memos", {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ title, content, due }),
    });
    if (!response.ok) throw new Error("保存失敗");
    await fetchMemos(); // 保存できたらリストを最新にする
  };

  // 更新 (PUT)
  const updateMemo = async (id: number, title: string, content: string, due: string) => {
    const response = await fetch(`http://localhost:48080/api/memos/${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ title, content, due }),
    });
    if (!response.ok) throw new Error("更新失敗");
    await fetchMemos();
  };

  // 削除 (DELETE)
  const deleteMemo = async (id: number) => {
    const response = await fetch(`http://localhost:48080/api/memos/${id}`, { method: "DELETE" });
    if (!response.ok) throw new Error("削除失敗");
    await fetchMemos();
  };

  return { memos, fetchMemos, saveMemo, updateMemo, deleteMemo };
});
