<template>

  <UseHead title="Client - Auth" description="Login authentication for client users." />

  <div class="flex justify-center items-center h-screen w-auto">

    <ToggleDarkMode class="fixed sm:top-20 z-50 top-3 left-3 sm:left-20 duration-200" />

    <UForm 
      :state="state" 
      @submit="onSubmit" 
      :validate="validate" 
      @error="onError"
      class="h-auto w-[500px] flex flex-col gap-5 rounded p-8 bg-custom-50 dark:bg-custom-900 shadow-lg border dark:border-custom-700 border-custom-300">

      <header class="flex items-center justify-center gap-1">
        <UIcon 
          name="i-lucide-shield-check" 
          class="text-3xl" />
        <h1 class="text-2xl font-bold cursor-default">Authentication</h1>
      </header>

      <hr class="border-custom-300 dark:border-custom-500">

      <!-- username -->
      <UFormGroup 
        class="grid gap-1" 
        name="username" 
        :ui="{ error: 'mt-1' }">

        <template #label>
          <div class="flex items-center justify-start gap-1">
            <UIcon 
              name="i-lucide-user-round" 
              class="text-lg" />
            <p class="text-base">Username</p>
          </div>
        </template>

        <template #default="{ error }">
          <UInput 
            v-model="state.user.username" 
            placeholder="Username"
            type="text" 
            color="gray" 
            size="md"
            :trailing-icon="error ? 'i-heroicons-exclamation-triangle-20-solid' : undefined" 
            :ui="{
              rounded: 'rounded',
              color: error ?
                { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : 
                { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900' } }
            }" />
        </template>

        <template #error="{ error }">
          <span
            :class="[state.errors ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
            {{ state.errors && state.errors._data && state.errors._data.errors && state.errors._data.errors.username && state.errors._data.errors.username[0] }}
          </span>
        </template>
      </UFormGroup>

      <!-- password -->
      <UFormGroup 
        class="grid gap-1" 
        name="password" 
        :ui="{ error: 'mt-1' }">

        <template #label>
          <div class="flex items-center justify-start gap-1">
            <UIcon 
              name="i-lucide-key-round" 
              class="text-lg" />
            <p class="text-base">Password</p>
          </div>
        </template>

        <template #default="{ error }">
          <UInput 
          v-model="state.user.password" 
          placeholder="Password"
          type="password" 
          color="gray" 
          size="md"
            :trailing-icon="error ? 'i-heroicons-exclamation-triangle-20-solid' : undefined" 
            :ui="{
              rounded: 'rounded',
              color: error ?
                { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : 
                { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900' } }
            }" />
        </template>

        <template #error="{ error }">
          <span
            :class="[state.errors ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
            {{ state.errors && state.errors._data && state.errors._data.errors && state.errors._data.errors.password && state.errors._data.errors.password[0] }}
          </span>
        </template>

      </UFormGroup>

      <UButton 
        type="submit" 
        :label="label" 
        :loading-icon="loadIcon" 
        :loading="loading"
        class="flex justify-center items-center gap-1 py-2 rounded dark:text-custom-50 dark:bg-custom-500 hover:dark:bg-custom-500/75" />

      <Divider />

      <UButton 
        to="/auth/admin" 
        label="Continue as Admin" 
        icon="i-lucide-circle-user-round"
        class="flex justify-center items-center gap-1 py-3 rounded bg-custom-700 hover:bg-custom-800 dark:bg-custom-700 hover:dark:bg-custom-700/75 dark:text-custom-50" />

    </UForm>
  </div>
</template>
<script setup>
import { reactive, ref } from 'vue';

const state = reactive({
  errors: [],
  user: {
    username: null,
    password: null
  }
});

const loading = ref(false);
const loadIcon = ref('');
const label = ref('Sign In');

async function onSubmit() {
  const params = {
    username: state.user.username,
    password: state.user.password
  }
  try {
    const response = await $fetch(`http://127.0.0.1:8000/api/auth/login`, {
      method: 'POST',
      body: params
    })
    if (response) {
      console.log(response)
      localStorage.setItem('_token', response.data.token)
      navigateTo('/auth/otp')
    }
  } catch (error) {
    state.errors = error.response
    console.log(error.response);
    console.log('error', error)
  }
}

</script>
