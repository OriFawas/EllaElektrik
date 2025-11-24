<script setup lang="ts">
import PasswordResetLinkController from '@/actions/App/Http/Controllers/Auth/PasswordResetLinkController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
  status?: string;
}>();
</script>

<template>
  <AuthLayout>
    <Head title="Forgot Password" />

    <div class="w-full max-w-md mx-auto bg-white shadow-md rounded-xl p-8 space-y-6 border">
      <h1 class="text-2xl font-bold text-center">Lupa Password?</h1>
      <p class="text-center text-gray-500 -mt-3">
        Masukkan email anda untuk menerima tautan reset password
      </p>

      <!-- STATUS SUCCESS -->
      <div v-if="status" class="text-center text-sm font-medium text-green-600">
        {{ status }}
      </div>

      <Form
        v-bind="PasswordResetLinkController.store.form()"
        v-slot="{ errors, processing }"
        class="space-y-5"
      >
        <!-- EMAIL -->
        <div class="space-y-1">
          <Label for="email">Email</Label>
          <Input
            id="email"
            type="email"
            name="email"
            autocomplete="off"
            autofocus
            placeholder="email@example.com"
          />
          <InputError :message="errors.email" />
        </div>

        <!-- SUBMIT -->
        <Button
          class="w-full py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg"
          :disabled="processing"
        >
          <LoaderCircle
            v-if="processing"
            class="h-4 w-4 animate-spin mr-2"
          />
          Kirim Link Reset Password
        </Button>

        <!-- BACK TO LOGIN -->
        <p class="text-center text-sm text-gray-600 pt-1">
          Ingat password?
          <TextLink :href="login()" class="ml-1 font-medium text-blue-600 hover:underline">
            Log in
          </TextLink>
        </p>
      </Form>
    </div>
  </AuthLayout>
</template>
