<template>
  <h1>Заявки на возврат товара</h1>
  <v-list v-if="!fetchStatusText">
    <v-list-item
      v-for="item in items"
      :key="item.id"
      :value="item.url"
      :href="item.url"
      target="_blank"
    >
      <v-list-item-title>{{ item.url }}</v-list-item-title>
    </v-list-item>
  </v-list>
  <p v-else>{{ fetchStatusText }}</p>
</template>

<script setup>
import { onMounted, computed, ref } from "vue";
import { list } from "@/api/returnOfProduct";
import { status } from "@/enums/status";

const items = ref([]);

const fetchStatus = ref(status.PENDING);

const fetchStatusText = computed(() => {
  if (fetchStatus.value === status.OK && !items.value.length) {
    return "Заявок нет";
  }
  switch (fetchStatus.value) {
    case status.ERROR:
      return "Ошибка при получении данных";
    case status.PENDING:
      return "Данные загружаются";
    default:
      return "";
  }
});

onMounted(async () => {
  try {
    const { data } = await list();
    items.value = data;
    fetchStatus.value = status.OK;
  } catch (error) {
    fetchStatus.value = status.ERROR;
  }
});
</script>
