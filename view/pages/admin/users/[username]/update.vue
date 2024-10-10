<template>

    <UseHead :title="`Edit - ${user.username} - Users - Admin`"/>

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
        class="sm:w-3/4 w-full h-auto border p-5 rounded border-custom-300 bg-custom-100 dark:bg-custom-900 dark:border-custom-700">

        <UForm class="h-auto w-full flex flex-col gap-3" :state="state" @submit="onSubmit" :validate="validate"
        @error="onError">

        <header class="flex justify-between items-center">
            <div class="font-semibold cursor-default flex items-center gap-1 w-1/2">
            <UIcon name="i-lucide-edit" class="text-xl" />
            <h1 class="font-bold text-xl">Edit User</h1>
            </div>
        </header>

        <div class="flex justify-between">

            <h1 class="text-lg w-auto font-medium">Personal Information</h1>

            <div class="flex justify-end w-1/2 gap-x-2">

            <section class="w-auto">
                <UButton label="Cancel" icon="i-lucide-x"
                class="flex justify-center w-full items-center rounded dark:bg-red-600 dark:hover:bg-red-500 bg-red-700 hover:bg-red-600 dark:text-custom-100"
                :to="`/admin/users/${user.username}`"/>
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
            <UFormGroup class="w-1/4" label="M. I." name="m_i">
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

        <section class="flex w-full gap-x-2">

            <!-- status -->
            <UFormGroup class="w-2/3" label="Status" name="status">
            <URadioGroup v-model="state.user.status" :options="statusOptions" class="ml-2"
                :uiRadio="{ color: state.user.status === statusOptions[0].value ? 'text-green-500' : 'text-red-500' }" />
            </UFormGroup>

            <!-- role -->
            <UFormGroup class="w-full" label="Role" name="role">
            <template #default="{ error }">
                <USelectMenu disabled color="gray" size="md" :ui="{
                rounded: 'rounded',
                color: error ?
                    { red: { outline: 'bg-red-100 dark:bg-red-50 text-custom-900 dark:text-custom-900 focus:ring-1 focus:ring-red-400 border-2 border-red-400 focus:border-red-400 active:ring-red-400 active:border-red-400' } } : { gray: { outline: 'dark:bg-custom-100 dark:text-custom-900' } }
                }"
                :uiMenu="{ background: 'dark:bg-custom-400', option: { color: 'dark:text-white', active: 'dark:bg-custom-600', empty: 'dark:text-white' }, empty: 'dark:text-white' }"
                v-model="state.user.role" placeholder="Select a role" />
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

import { name, playSound } from '~/assets/js/sound'
import { user } from '~/assets/js/userLogged';

const statusOptions = [
{
    value: 'active',
    label: 'Active'
},
{
    value: 'inactive',
    label: 'Inactive'
}
];

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
    status: user.status,
    phone_number: user.phone_number,
    role: user.role,
    username: user.username,
    password: user.password,
    email: user.email
  }
});

const { username } = useRoute().params;
onMounted(() => {
  getUser(username)
});

async function getUser(username) {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/users/${username}`, {
      method: 'PUT',
      headers: {
        Authorization: `Bearer ${localStorage.getItem('_token')}`
      }
    });

    if (response.ok) {
      const data = await response.json();

      state.user.first_name = data.user.first_name
      state.user.last_name = data.user.last_name
      state.user.middle_initial = data.user.middle_initial
      state.user.gender = data.user.gender
      state.user.status = data.user.status
      state.user.phone_number = data.user.phone_number
      state.user.role = data.user.role
      state.user.username = data.user.username
      state.user.password = data.user.password
      state.user.email = data.user.email

      
    } else {
      console.error('Error fetching userrrr:', await response.json());
    }
  } catch (error) {
    console.error('Error fetchinggg user:', error);
  }
}

const loading = ref(false);
const loadIcon = ref('');
const label = ref('Update');

const toast = useToast()


async function onSubmit() {
  const { user } = state; // Destructure to make code cleaner

  const params = {
    first_name: user.first_name,
    last_name: user.last_name,
    middle_initial: user.middle_initial,
    gender: user.gender,
    phone_number: user.phone_number,
    status: user.status,
    role: user.role,
    password: user.password,
  };

  try {
    const response = await $fetch(`http://127.0.0.1:8000/api/users/${user.username}`, {
      method: 'PUT',
      body: params,
      headers: {
        Authorization: `Bearer ${localStorage.getItem('_token')}` // Use template literals for better readability
      }
    });

    if (response) {
      console.log('Updated successfully:', response);

      loading.value = true;
      loadIcon.value = 'i-lucide-loader-circle';
      label.value = '';
      name.value = 'success_2';

      setTimeout(() => {
        playSound();
        toast.add({
          title: 'Updated Successfully!',
          icon: 'i-lucide-circle-check-big',
          timeout: 2500,
          ui: {
            background: 'dark:bg-blue-700 bg-blue-300',
            progress: {
              background: 'dark:bg-white bg-blue-700 rounded-full'
            },
            ring: 'ring-1 ring-blue-700 dark:ring-custom-900',
            default: {
              closeButton: {
                color: 'white',
              }
            },
            icon: 'text-custom-900'
          },
        });
        label.value = 'Update';
        loading.value = false;
        navigateTo(`/admin/users/${user.username}`);
      }, 800);
    }
  } catch (error) {
    // Check if the error response exists
    state.errors = error.response || { message: 'An error occurred' };
    console.error('Error response:', error.response);
    console.error('Error:', error);
  }
}





const links = [{
label: 'Users',
to: '/admin/users'
}, {
label: 'Details',
to: `/admin/users/${user.username}`
}, {
label: 'Update',
}
]
</script>
