<template>
  <h1>Форма заявки на возврат товара</h1>
  <p v-if="submitStatusText">{{ submitStatusText }}</p>
  <form>
    <v-text-field
      v-model="state.firstName"
      :error-messages="
        apiErrors?.first_name || v$.firstName.$errors.map((e) => e.$message)
      "
      label="Имя"
      required
      @blur="v$.firstName.$touch"
      @input="input('firstName')"
    ></v-text-field>

    <v-text-field
      v-model="state.lastName"
      :error-messages="
        apiErrors?.last_name || v$.lastName.$errors.map((e) => e.$message)
      "
      label="Фамилия"
      required
      @blur="v$.lastName.$touch"
      @input="input('lastName')"
    ></v-text-field>

    <v-text-field
      v-model="state.phone"
      :error-messages="
        apiErrors?.phone || v$.phone.$errors.map((e) => e.$message)
      "
      label="Телефон"
      required
      @blur="v$.phone.$touch"
      @input="input('phone')"
    ></v-text-field>

    <v-textarea
      label="Текст заявки"
      v-model="state.applicationText"
      :error-messages="
        apiErrors?.application_text ||
        v$.applicationText.$errors.map((e) => e.$message)
      "
      required
      @blur="v$.applicationText.$touch"
      @input="input('applicationText')"
    ></v-textarea>

    <v-btn class="me-4" @click="submit" :disabled="isDisabledButtonOnForm">
      Отправить заявку
    </v-btn>
  </form>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, minLength } from "@vuelidate/validators";
import { store } from "@/api/returnOfProduct";
import { camelCaseToSnakeCase } from "../helpers";
import { status } from "@/enums/status";

const apiErrors = ref([]);
const submitStatus = ref(status.NULL);

const initialState = {
  firstName: "",
  lastName: "",
  phone: "",
  applicationText: "",
};

const state = reactive({
  ...initialState,
});

const isDisabledButtonOnForm = computed(
  () => submitStatus.value === status.PENDING
);

const submitStatusText = computed(() => {
  switch (submitStatus.value) {
    case status.ERROR:
      return "Ошибка на сервере";
    case status.OK:
      return "Форма успешно отправлена";
    default:
      return "";
  }
});

const rules = {
  firstName: {
    required,
    minLengthValue: minLength(2),
    maxLengthValue: maxLength(10),
  },
  lastName: {
    required,
    minLengthValue: minLength(2),
    maxLengthValue: maxLength(10),
  },
  phone: {
    required,
    minLengthValue: minLength(7),
    maxLengthValue: maxLength(15),
  },
  applicationText: { required, minLengthValue: minLength(10) },
};

const v$ = useVuelidate(rules, state);

function input(camelCase) {
  delete apiErrors.value[camelCaseToSnakeCase(camelCase)];
  v$.value[camelCase].$touch;
  console.log(v$.value);
}
async function submit() {
  v$.value.$validate();
  if (v$.value.$error) {
    return;
  }
  submitStatus.value = status.PENDING;
  try {
    await store(state);
    submitStatus.value = status.OK;
  } catch (error) {
    if (error.response.status == 422) {
      apiErrors.value = error.response.data.errors;
    } else {
      submitStatus.value = status.ERROR;
    }
  }
}
</script>

