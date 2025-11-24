<script setup lang="ts">
import RegisteredUserController from '@/actions/App/Http/Controllers/Auth/RegisteredUserController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
</script>

<template>
  <AuthBase>
    <Head title="Register" />

    <div class="w-full max-w-md mx-auto bg-white shadow-md rounded-xl p-8 space-y-6 border">
      <h1 class="text-2xl font-bold text-center">Buat Akun</h1>
      <p class="text-center text-gray-500 -mt-3">
        Silahkan daftar terlebih dahulu
      </p>

      <!-- FORM -->
      <Form
        v-bind="RegisteredUserController.store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="space-y-5"
      >
        <!-- Full Name -->
        <div class="space-y-1">
          <Label for="name" class="font-medium">Nama</Label>
          <Input
            id="name"
            type="text"
            name="name"
            required
            autocomplete="name"
            placeholder="example"
            :tabindex="1"
          />
          <InputError :message="errors.name" />
        </div>

        <!-- Email -->
        <div class="space-y-1">
          <Label for="email">Alamat Email</Label>
          <Input
            id="email"
            type="email"
            name="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
            :tabindex="2"
          />
          <InputError :message="errors.email" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
          <Label for="password">Password</Label>
          <Input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            placeholder=""
            :tabindex="3"
          />
          <InputError :message="errors.password" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
          <Label for="password_confirmation">Konfirmasi Password</Label>
          <Input
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            placeholder=""
            :tabindex="4"
          />
          <InputError :message="errors.password_confirmation" />
        </div>

        <!-- Submit Button -->
        <Button
          type="submit"
          class="w-full py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg"
          :disabled="processing"
          :tabindex="5"
        >
          <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin mr-2" />
          Buat Akun
        </Button>

        <!-- Login Link -->
        <p class="text-center text-sm text-gray-600">
          Sudah punya akun?
          <TextLink :href="login()" class="ml-1 font-medium text-blue-600 hover:underline">
            Log in
          </TextLink>
        </p>
      </Form>
    </div>
  </AuthBase>
</template>
