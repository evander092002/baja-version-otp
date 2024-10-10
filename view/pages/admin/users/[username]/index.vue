<template>
  <UseHead :title="`${user.username} - Users - Admin`" />

  <div class="flex flex-col p-5 gap-10 w-full h-auto">
    <section>
      <UBreadcrumb :links="links">
        <template #divider>
          <UIcon name="i-lucide-chevron-right" class="text-lg" />
        </template>

        <template #default="{ link, isActive }">
          <div
            :class="{
              'dark:text-white text-custom-800 text-lg cursor-default': isActive,
              'text-custom-300 hover:text-custom-500 hover:dark:text-custom-300 dark:text-custom-500 text-lg': !isActive
            }"
            class="rounded-full"
          >
            {{ link.label }}
          </div>
        </template>
      </UBreadcrumb>
    </section>

    <section class="flex items-center justify-center h-4/5 w-full">
      <div class="flex flex-col gap-5 w-full sm:w-3/4">
        <div class="flex justify-between">
          <div class="flex items-center gap-1 font-semibold cursor-default w-1/2">
            <UIcon name="i-lucide-book-user" class="text-xl" />
            <h1 class="text-xl font-bold">User Details</h1>
          </div>

          <div class="flex justify-end w-1/2 gap-x-2">
            <UButton
              label="Back"
              icon="i-lucide-move-left"
              class="flex items-center justify-center w-full rounded dark:bg-red-600 dark:hover:bg-red-500 bg-red-700 hover:bg-red-600 dark:text-custom-100"
              to="/admin/users"
            />

            <UButton
              v-if="user.role === 'superadmin'"
              label="Edit"
              icon="i-lucide-edit"
              class="flex cursor-pointer justify-center w-full items-center rounded dark:text-white"
              :to="`/admin/users/${user.username}/update`"
            />
            <UTooltip text="Only superadmin can edit" v-else>
              <UButton
                disabled
                class="opacity-20 cursor-not-allowed rounded"
                label="Edit"
                icon="i-lucide-edit"
              />
            </UTooltip>
          </div>
        </div>

        <div class="grid gap-5 lg:flex w-full">
          <section class="bg-custom-100 dark:bg-custom-900 border border-custom-300 dark:border-custom-700 rounded p-7 lg:w-1/2 w-full">
            <h1 class="font-semibold">Personal Information</h1>
            <hr class="border-custom-200 dark:border-custom-700 my-2" />
            <div v-for="(p, index) in profile" :key="index" class="grid grid-cols-3 gap-5 my-2">
              <h1 class="capitalize col-span-1">{{ p.label }}:</h1>
              <div class="col-span-2 dark:text-custom-300 text-custom-500 capitalize">
                <UKbd v-if="p.label === 'status'" :class="statusClass(p.value)" :value="p.value" class="col-span-3 text-center mt-2 lowercase" />
                <p v-else-if="p.label === 'email'" class="lowercase">{{ p.value }}</p>
                <p v-else>{{ p.value }}</p>
              </div>
            </div>
          </section>

          <div class="flex flex-col gap-5 lg:w-1/2 w-full">
            <section class="bg-custom-100 dark:bg-custom-900 rounded p-7 border border-custom-300 dark:border-custom-700">
              <h1 class="font-semibold">Login Credentials</h1>
              <hr class="border-custom-200 dark:border-custom-700 my-2" />
              <div v-for="(l, index) in login" :key="index" class="grid grid-cols-3 gap-5 my-2">
                <h1 class="capitalize col-span-1">{{ l.label }}:</h1>
                <p class="col-span-2 dark:text-custom-300 text-custom-500">{{ l.value }}</p>
              </div>
            </section>

            <section class="bg-custom-100 dark:bg-custom-900 rounded p-7 border border-custom-300 dark:border-custom-700">
              <h1 class="font-semibold">Timestamps (date & time)</h1>
              <hr class="border-custom-200 dark:border-custom-700 my-2" />
              <div v-for="(t, index) in timestamp" :key="index" class="grid grid-cols-5 gap-5 my-2">
                <h1 class="capitalize col-span-2">{{ t.label }}:</h1>
                <p class="col-span-3 dark:text-custom-300 text-custom-500 capitalize">{{ t.value }}</p>
              </div>
            </section>
          </div>
        </div>
      </div>
    </section>

    <hr class="border-custom-300 dark:border-custom-800" />

    <section class="flex flex-col gap-3 w-full sm:w-3/4 mx-auto">
      <div class="flex items-center gap-1 font-semibold cursor-default w-1/2">
        <UIcon name="i-lucide-book-open-text" class="text-xl" />
        <h1 class="text-xl font-bold">Notifications Log</h1>
      </div>
      <p class="mb-0 lg:-mb-6">These are the list of motions detected by this user.</p>
      <TableNotifications />
    </section>
  </div>
</template>

<script setup>
definePageMeta({ layout: 'sidebar' });

import { user } from '~/assets/js/userLogged';
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const { username } = useRoute().params;

const links = [
  { label: 'Users', to: '/admin/users' },
  { label: 'Details' }
];

const profile = ref([
  { label: 'name', value: '' },
  { label: 'gender', value: '' },
  { label: 'phone no.', value: '' },
  { label: 'email', value: '' },
  { label: 'role', value: '' },
  { label: 'status', value: '' },
]);

const login = ref([
  { label: 'username', value: '' },
  { label: 'password', value: '' },
]);

const timestamp = ref([
  { label: 'account created', value: user.account_created },
  { label: 'last update', value: user.updated_at },
]);

async function getUser() {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/users/${username}`, {
      method: 'GET',
      headers: {
        Authorization: `Bearer ${localStorage.getItem('_token')}`
      }
    });

    if (response.ok) {
      const data = await response.json();
      Object.assign(profile.value[0], { value: `${data.user.first_name} ${data.user.middle_initial}. ${data.user.last_name}` });
      profile.value[1].value = data.user.gender;
      profile.value[2].value = data.user.phone_number;
      profile.value[3].value = data.user.email;
      profile.value[4].value = data.user.role;
      profile.value[5].value = data.user.status;
      login.value[0].value = data.user.username;
      login.value[1].value = data.user.password;
      timestamp.value[0].value = data.user.created_at;
      timestamp.value[1].value = data.user.updated_at;
    } else {
      console.error('Error fetching user:', await response.json());
    }
  } catch (error) {
    console.error('Error fetching user:', error);
  }
}

function statusClass(value) {
  return {
    'dark:border bg-green-600 dark:border-green-700 text-custom-100 dark:text-green-400 cursor-default px-2': value === 'active',
    'dark:border bg-red-600 dark:border-red-700 text-custom-100 dark:text-red-400 cursor-default px-2': value === 'inactive'
  };
}

onMounted(getUser);
</script>
