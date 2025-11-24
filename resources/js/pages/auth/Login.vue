<script setup lang="ts">
import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
  <AuthBase>
    <Head title="Log in" />

    <div class="w-full max-w-md mx-auto bg-white shadow-md rounded-xl p-8 space-y-6 border">
      <h1 class="text-2xl font-bold text-center">Hi, Selamat Datang!</h1>
      <p class="text-center text-gray-500 -mt-3">
        Login dengan Email
      </p>

      <!-- Status message -->
      <div
        v-if="status"
        class="text-center text-sm font-medium text-green-600"
      >
        {{ status }}
      </div>

      <!-- FORM -->
      <Form
        v-bind="AuthenticatedSessionController.store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="space-y-5"
      >
        <!-- Email -->
        <div class="space-y-1">
          <Label for="email">Email</Label>
          <Input
            id="email"
            type="email"
            name="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
            :tabindex="1"
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
            autocomplete="current-password"
            placeholder=""
            :tabindex="2"
        />
        <InputError :message="errors.password" />

        <!-- Lupa password dipindah ke bawah -->
        <div class="text-right pt-1">
            <TextLink
            v-if="canResetPassword"
            :href="request()"
            class="text-sm font-medium text-blue-600 hover:underline"
            >
            Lupa password?
            </TextLink>
        </div>
        </div>


        <!-- Remember Me -->
        <label class="flex items-center space-x-2 cursor-pointer">
          <Checkbox id="remember" name="remember" :tabindex="3" />
          <span class="text-sm">Remember me</span>
        </label>

        <!-- Submit -->
        <Button
          type="submit"
          class="w-full py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg"
          :disabled="processing"
          :tabindex="4"
        >
          <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin mr-2" />
          Log in
        </Button>

        <!-- Register Link -->
        <p class="text-center text-sm text-gray-600">
          Belum punya akun?
          <TextLink :href="register()" class="ml-1 font-medium text-blue-600 hover:underline">
            Daftar
          </TextLink>
        </p>
      </Form>
    </div>
  </AuthBase>
</template>
