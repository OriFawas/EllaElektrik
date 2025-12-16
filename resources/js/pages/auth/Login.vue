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
import { Form, Head, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

// Ambil recaptcha data dari page props
const page = usePage();
const recaptchaConfig = computed(() => page.props.recaptcha);
const siteKey = computed(() => recaptchaConfig.value?.site_key || '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');
const recaptchaEnabled = computed(() => recaptchaConfig.value?.enabled ?? true);

// reCAPTCHA state
const recaptchaLoaded = ref(false);
const recaptchaRendered = ref(false);
const recaptchaError = ref(false);
const recaptchaWidgetId = ref<number | null>(null);
const recaptchaToken = ref<string>('');
const recaptchaContainerRef = ref<HTMLElement | null>(null);

// Global callback functions
declare global {
    interface Window {
        grecaptcha: any;
        recaptchaReady: boolean;
        onRecaptchaLoadCallback?: () => void;
    }
}

// Callbacks untuk reCAPTCHA
const onRecaptchaSuccess = (token: string) => {
    console.log('✅ reCAPTCHA verified, token:', token.substring(0, 20) + '...');
    recaptchaToken.value = token;
    
    // Tambahkan token ke form sebelum submit
    const form = document.querySelector('form');
    if (form) {
        let input = form.querySelector('input[name="g-recaptcha-response"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'g-recaptcha-response';
            form.appendChild(input);
        }
        input.value = token;
    }
};

const onRecaptchaExpired = () => {
    console.log('⚠️ reCAPTCHA expired');
    recaptchaToken.value = '';
    updateRecaptchaInput('');
};

const onRecaptchaError = () => {
    console.log('❌ reCAPTCHA error');
    recaptchaError.value = true;
    recaptchaToken.value = '';
    updateRecaptchaInput('');
};

// Update hidden input untuk recaptcha token
const updateRecaptchaInput = (token: string) => {
    const form = document.querySelector('form');
    if (form) {
        let input = form.querySelector('input[name="g-recaptcha-response"]');
        if (input) {
            input.value = token;
        }
    }
};

// Inisialisasi global callback untuk dynamic load
const initializeGlobalCallbacks = () => {
    window.onRecaptchaLoadCallback = () => {
        console.log('✅ reCAPTCHA script loaded via dynamic load');
        recaptchaLoaded.value = true;
        renderRecaptcha();
    };
};

// Load reCAPTCHA script
const loadRecaptcha = () => {
    console.log('Loading reCAPTCHA script with site key:', siteKey.value);
    
    // Jika recaptcha disabled, skip
    if (!recaptchaEnabled.value) {
        console.log('reCAPTCHA is disabled');
        recaptchaLoaded.value = true;
        return;
    }
    
    // Inisialisasi callbacks
    initializeGlobalCallbacks();
    
    // Check if script already exists
    if (document.querySelector('script[src*="recaptcha"]')) {
        console.log('reCAPTCHA script already exists, waiting for load...');
        
        // Check if already loaded
        const checkInterval = setInterval(() => {
            if (window.grecaptcha && typeof window.grecaptcha.render === 'function') {
                clearInterval(checkInterval);
                recaptchaLoaded.value = true;
                renderRecaptcha();
            }
        }, 100);
        
        // Timeout after 5 seconds
        setTimeout(() => {
            clearInterval(checkInterval);
            if (!recaptchaLoaded.value) {
                recaptchaError.value = true;
            }
        }, 5000);
        
        return;
    }

    const script = document.createElement('script');
    script.src = `https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoadCallback&render=explicit`;
    script.async = true;
    script.defer = true;
    script.onerror = () => {
        console.error('Failed to load reCAPTCHA script');
        recaptchaError.value = true;
    };
    
    document.head.appendChild(script);
};

// Render reCAPTCHA widget
const renderRecaptcha = () => {
    console.log('Attempting to render reCAPTCHA with site key:', siteKey.value);
    
    // Jika recaptcha disabled, skip
    if (!recaptchaEnabled.value) {
        console.log('reCAPTCHA rendering skipped (disabled)');
        return;
    }
    
    if (!window.grecaptcha) {
        console.error('grecaptcha not available');
        recaptchaError.value = true;
        return;
    }
    
    if (typeof window.grecaptcha.render !== 'function') {
        console.error('grecaptcha.render is not a function');
        recaptchaError.value = true;
        return;
    }

    if (recaptchaWidgetId.value !== null) {
        console.log('reCAPTCHA already rendered');
        return;
    }

    // Tunggu DOM update
    setTimeout(() => {
        const container = recaptchaContainerRef.value;
        
        if (!container) {
            console.error('reCAPTCHA container ref not found');
            recaptchaError.value = true;
            return;
        }
        
        // Kosongkan container
        container.innerHTML = '';
        
        // Buat div baru untuk widget
        const widgetContainer = document.createElement('div');
        container.appendChild(widgetContainer);
        
        // Beri ID unik untuk widget
        const widgetId = 'recaptcha-widget-' + Date.now();
        widgetContainer.id = widgetId;
        
        try {
            console.log('Rendering reCAPTCHA widget with ID:', widgetId);
            
            // Render ke widget container
            recaptchaWidgetId.value = window.grecaptcha.render(widgetId, {
                sitekey: siteKey.value,
                theme: 'light',
                size: 'normal',
                callback: onRecaptchaSuccess,
                'expired-callback': onRecaptchaExpired,
                'error-callback': onRecaptchaError
            });
            
            console.log('✅ reCAPTCHA widget rendered with widget ID:', recaptchaWidgetId.value);
            recaptchaRendered.value = true;
            
        } catch (error) {
            console.error('Error rendering reCAPTCHA:', error);
            recaptchaError.value = true;
            
            // Clean up jika gagal
            if (widgetContainer) {
                widgetContainer.remove();
            }
        }
    }, 100);
};

// Reset reCAPTCHA
const resetRecaptcha = () => {
    if (window.grecaptcha && recaptchaWidgetId.value !== null) {
        try {
            window.grecaptcha.reset(recaptchaWidgetId.value);
            recaptchaToken.value = '';
            updateRecaptchaInput('');
        } catch (error) {
            console.error('Error resetting reCAPTCHA:', error);
        }
    }
};

// Reload reCAPTCHA
const reloadRecaptcha = () => {
    console.log('Reloading reCAPTCHA...');
    
    // Reset state
    recaptchaError.value = false;
    recaptchaLoaded.value = false;
    recaptchaRendered.value = false;
    recaptchaWidgetId.value = null;
    recaptchaToken.value = '';
    
    // Clear container
    if (recaptchaContainerRef.value) {
        recaptchaContainerRef.value.innerHTML = '';
    }
    
    // Remove existing script
    const existingScript = document.querySelector('script[src*="recaptcha"]');
    if (existingScript) {
        existingScript.remove();
    }
    
    // Delete global callback
    if (window.onRecaptchaLoadCallback) {
        delete window.onRecaptchaLoadCallback;
    }
    
    // Reload after a short delay
    setTimeout(() => {
        loadRecaptcha();
    }, 300);
};

// Setup event listener untuk recaptchaLoaded dari window
const setupRecaptchaListener = () => {
    window.addEventListener('recaptchaLoaded', () => {
        console.log('Received recaptchaLoaded event from window');
        recaptchaLoaded.value = true;
        
        // Tunggu sedikit sebelum render
        setTimeout(() => {
            renderRecaptcha();
        }, 100);
    });
};

// Cek apakah reCAPTCHA sudah dimuat
const checkRecaptchaLoaded = () => {
    console.log('Checking if reCAPTCHA is loaded...');
    
    if (window.recaptchaReady) {
        console.log('reCAPTCHA is ready (from window.recaptchaReady)');
        recaptchaLoaded.value = true;
        setTimeout(() => {
            renderRecaptcha();
        }, 100);
        return true;
    }
    
    if (window.grecaptcha && typeof window.grecaptcha.render === 'function') {
        console.log('grecaptcha object is available');
        recaptchaLoaded.value = true;
        setTimeout(() => {
            renderRecaptcha();
        }, 100);
        return true;
    }
    
    return false;
};

// Load reCAPTCHA on mount
onMounted(() => {
    console.log('Login component mounted with site key:', siteKey.value, 'enabled:', recaptchaEnabled.value);
    
    // Jika recaptcha disabled, skip semua
    if (!recaptchaEnabled.value) {
        console.log('reCAPTCHA is disabled, skipping initialization');
        return;
    }
    
    // Setup event listener
    setupRecaptchaListener();
    
    // Cek apakah reCAPTCHA sudah dimuat
    const isLoaded = checkRecaptchaLoaded();
    
    if (!isLoaded) {
        // Tunggu 300ms untuk melihat apakah script dari app.blade.php sudah dimuat
        setTimeout(() => {
            const stillNotLoaded = checkRecaptchaLoaded();
            if (!stillNotLoaded) {
                console.log('reCAPTCHA not loaded, loading manually...');
                loadRecaptcha();
            }
        }, 300);
    }
    
    // Fallback: if not loaded after 5 seconds, show error
    setTimeout(() => {
        if (!recaptchaLoaded.value && !recaptchaError.value) {
            console.log('Fallback: reCAPTCHA not loaded after 5 seconds');
            recaptchaError.value = true;
        }
    }, 5000);
});

// Cleanup
onBeforeUnmount(() => {
    if (window.grecaptcha && recaptchaWidgetId.value !== null) {
        try {
            window.grecaptcha.reset(recaptchaWidgetId.value);
        } catch (e) {
            console.warn('Error cleaning up reCAPTCHA:', e);
        }
    }
    
    // Clean up global callback
    if (window.onRecaptchaLoadCallback) {
        delete window.onRecaptchaLoadCallback;
    }
});
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
      <div v-if="status" class="text-center text-sm font-medium text-green-600">
        {{ status }}
      </div>

      <!-- FORM -->
      <Form
        v-bind="AuthenticatedSessionController.store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="space-y-5"
        @submit="resetRecaptcha"
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
          
          <!-- Lupa password -->
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

        <!-- Google reCAPTCHA (hanya jika enabled) -->
        <div v-if="recaptchaEnabled" class="space-y-2">
          <Label>Verifikasi Keamanan</Label>
          
          <!-- Loading state -->
          <div 
            v-if="!recaptchaLoaded && !recaptchaError" 
            class="flex items-center justify-center min-h-[78px] p-4 border border-gray-200 rounded"
          >
            <div class="flex items-center space-x-2">
              <LoaderCircle class="h-4 w-4 animate-spin text-blue-600" />
              <span class="text-sm text-gray-600">Memuat verifikasi keamanan...</span>
            </div>
          </div>
          
          <!-- Error state -->
          <div 
            v-if="recaptchaError" 
            class="flex flex-col items-center justify-center min-h-[78px] p-4 border border-red-300 rounded bg-red-50"
          >
            <p class="text-sm text-red-600 mb-2 text-center">Gagal memuat verifikasi keamanan</p>
            <Button 
              type="button" 
              @click="reloadRecaptcha" 
              variant="outline" 
              size="sm"
              class="text-xs"
            >
              Coba Lagi
            </Button>
          </div>
          
          <!-- Container untuk reCAPTCHA widget -->
          <div 
            v-show="recaptchaLoaded && !recaptchaError"
            ref="recaptchaContainerRef"
            class="flex justify-center min-h-[78px]"
          >
            <!-- Container akan diisi oleh reCAPTCHA widget -->
          </div>
          
          <!-- Hidden input untuk recaptcha response -->
          <input type="hidden" name="g-recaptcha-response" :value="recaptchaToken" />
          
          <InputError :message="errors['g-recaptcha-response']" />
          <p class="text-xs text-gray-500 text-center">
            Centang kotak di atas untuk membuktikan Anda bukan robot
          </p>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center space-x-2">
          <Checkbox id="remember" name="remember" :tabindex="4" />
          <Label for="remember" class="text-sm cursor-pointer">Ingat saya</Label>
        </div>

        <!-- Tombol Submit -->
        <Button
          type="submit"
          class="w-full py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg"
          :disabled="processing || (recaptchaEnabled && !recaptchaToken)"
          :tabindex="5"
        >
          <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin mr-2" />
          Log in
        </Button>

        <!-- Link ke Register -->
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