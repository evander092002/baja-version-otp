<template>

  <UseHead title="Edit - Settings - Admin"/>

  <div class="h-auto w-full flex flex-col p-5 gap-10">

    <section class="">

      <UBreadcrumb :links="links">
        <template #divider>
          <UIcon name="i-lucide-chevron-right" class="text-lg" />
        </template>
        <template #default="{ link, isActive }">
          <div :class="{
            'dark:text-white text-custom-800 text-lg cursor-default': isActive,
            'text-custom-300 hover:text-custom-500 hover:dark:text-custom-300 dark:text-custom-500 text-lg': !isActive
          }" class="rounded-full">
            {{ link.label }}
          </div>
        </template>
      </UBreadcrumb>

    </section>
    <section class="h-4/5 w-full flex justify-center items-center">
      <div
        class="sm:w-3/4 w-full h-auto border p-10 rounded border-custom-300 bg-custom-100 dark:bg-custom-900 dark:border-custom-700">

        <UForm class="h-auto w-full flex flex-col gap-3" :state="state" @submit="onSubmit" :validate="validate"
          @error="onError">

          <header class="flex justify-between items-center">
            <div class="font-semibold cursor-default flex items-center gap-1 w-1/2">
              <UIcon name="i-lucide-edit" class="text-xl" />
              <h1 class="font-bold text-xl">Edit</h1>
            </div>
          </header>

          <div class="flex justify-between">

            <h1 class="text-lg w-auto font-medium">Personal Information</h1>

            <div class="flex justify-end w-1/2 gap-x-2">

              <section class="w-auto">
                  <UButton 
                    label="Cancel" 
                    icon="i-lucide-x"
                    class="flex justify-center w-full items-center rounded dark:bg-red-600 dark:hover:bg-red-500 bg-red-700 hover:bg-red-600 dark:text-custom-100"
                    to="/admin/settings" />
              </section>

              <section class="w-auto">
                <UButton 
                  :label="label" 
                  :loading-icon="loadIcon" 
                  :loading="loading" 
                  icon="i-lucide-save"
                  class="flex justify-center w-full items-center rounded dark:text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 hover:dark:bg-blue-800" 
                  type="submit" />
              </section>
            </div>
          </div>

          <hr class="border-custom-300 dark:border-custom-500 w-full">

          <section class="flex w-full gap-x-2">

            <!-- first name -->
            <UFormGroup class="w-1/2" label="First name" name="first_name" :ui="{ error: 'mt-1' }">

              <template #default="{ error }">
                <UInput type="text" color="gray" v-model="state.user.first_name" size="md" :ui="{
                  rounded: 'rounded',
                  color: error ?
                    { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900' } }
                }" />
              </template>

              <template #error="{ error }">
                <span
                  :class="[error ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
                  {{ error ? error : undefined }}
                </span>
              </template>
            </UFormGroup>

            <!-- last name -->
            <UFormGroup class="w-1/2" label="Last name" name="last_name" :ui="{ error: 'mt-1' }">

              <template #default="{ error }">
                <UInput type="text" color="gray" v-model="state.user.last_name" size="md" :ui="{
                  rounded: 'rounded',
                  color: error ?
                    { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900' } }
                }" />
              </template>

              <template #error="{ error }">
                <span
                  :class="[error ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
                  {{ error ? error : undefined }}
                </span>
              </template>
            </UFormGroup>

            <!-- middle initial -->
            <UFormGroup class="w-1/4" label="M. I." name="middle_initial">
              <template #default="{ error }">
                <UInput type="text" color="gray" size="md" v-model="state.user.middle_initial" :ui="{
                  rounded: 'rounded',
                  color: error ?
                    { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900 py-2' } }
                }" />
              </template>
              <template #error="{ error }">
                <span
                  :class="[error ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
                  {{ error ? error : undefined }}
                </span>
              </template>
            </UFormGroup>
          </section>

          <section class="flex w-full gap-x-2">

            <!-- gender -->
            <UFormGroup class="w-2/3" label="Gender" name="gender">
              <URadioGroup v-model="state.user.gender" :options="genderOptions" class="ml-2" />
              <template #error="{ error }">
                <span
                  :class="[error ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
                  {{ error ? error : undefined }}
                </span>
              </template>
            </UFormGroup>

            <!-- phone -->
            <UFormGroup class="w-1/2" name="phone">
              <template #label>
                <div class="flex items-center justify-start gap-1">
                  <p class="text-sm">Phone no.</p>
                  <UIcon name="i-emojione-v1-flag-for-philippines" />
                </div>
              </template>
              <template #default="{ error }">
                <UInput type="text" color="gray" size="md" v-model="state.user.phone_number" :ui="{
                  rounded: 'rounded',
                  color: error ?
                    { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900' } }
                }" />
              </template>
              <template #error="{ error }">
                <span
                  :class="[error ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
                  {{ error ? error : undefined }}
                </span>
              </template>
            </UFormGroup>

            <!-- email -->
            <UFormGroup class="w-1/2" name="email">
              <template #label>
                <p class="text-sm">Email</p>
              </template>
              <template #default="{ error }">
                <UInput type="email" color="gray" size="md" v-model="state.user.email" :ui="{
                  rounded: 'rounded',
                  color: error ?
                    { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900' } }
                }" />
              </template>
              <template #error="{ error }">
                <span
                  :class="[error ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
                  {{ error ? error : undefined }}
                </span>
              </template>
            </UFormGroup>
          </section>

          <h1 class="text-lg w-auto text-start mt-3 -mb-2 font-medium">Login Credentials</h1>
          <hr class="border-custom-300 dark:border-custom-500 w-full">

          <section class="flex w-full gap-x-2">

            <!-- username -->
            <UFormGroup class="w-1/2" label="Username" name="username">
              <template #default="{ error }">
                <UInput type="text" color="gray" size="md" v-model="state.user.username" :ui="{
                  rounded: 'rounded',
                  color: error ?
                    { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900' } }
                }" />
              </template>
              <template #error="{ error }">
                <span
                  :class="[error ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
                  {{ error ? error : undefined }}
                </span>
              </template>
            </UFormGroup>

            <!-- password -->
            <UFormGroup class="w-1/2" label="Password" name="password">
              <template #default="{ error }">
                <UInput type="password" color="gray" size="md" v-model="state.user.password" :ui="{
                  rounded: 'rounded',
                  color: error ?
                    { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900' } }
                }" />
              </template>
              <template #error="{ error }">
                <span
                  :class="[error ? 'text-red-500 dark:text-red-400 text-xs font-bold' : 'text-primary-500 dark:text-primary-400']">
                  {{ error ? error : undefined }}
                </span>
              </template>
            </UFormGroup>

          </section>
        </UForm>
      </div>
    </section>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'sidebar'
})
const links = [{
  label: 'Settings',
  to: '/admin/settings'
}, {
  label: 'Update',
}
]

import { name, playSound } from '~/assets/js/sound'
import { user, fetchUser } from '~/assets/js/userLogged'; // Adjust the path as needed
import { useRoute } from 'vue-router';


onMounted(async () => {
  await fetchUser(); // Ensure you wait for the user data to be fetched
  console.log('admin settings username', user);
});



const toast = useToast()

const genderOptions = [
  {
    value: 'male',
    label: 'Male'
  },
  {
    value: 'female',
    label: 'Female'
  }
];


const state = reactive({
  errors: [],
  user: {
    first_name: user.first_name,
    last_name: user.last_name,
    middle_initial: user.middle_initial,
    gender: user.gender,
    phone_number: user.phone_number,
    username: user.username,
    password: user.password,
    email: user.email
  }
});


const loading = ref(false);
const loadIcon = ref('');
const label = ref('Update');


async function onSubmit() {
  const params = {
    first_name: state.user.first_name,
    last_name: state.user.last_name,
    middle_initial: state.user.middle_initial,
    gender: state.user.gender,
    phone_number: state.user.phone_number,
    username: state.user.username,
    password: state.user.password,
    email: state.user.email
  }
  try {
    const response = await $fetch(`http://127.0.0.1:8000/api/user/update`, {
      method: 'PUT',
      body: params,
      headers: {
        Authorization: 'Bearer ' + localStorage.getItem('_token') // Use 'Bearer' prefix for token
      }
    })
    if (response) {
      console.log(response)
      navigateTo('/client/settings')
    }
  } catch (error) {
    state.errors = error.response
    console.log(error.response);
    console.log('error', error)
  }
}
</script>
