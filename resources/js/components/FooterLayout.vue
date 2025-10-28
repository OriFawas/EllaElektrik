<template>
  <footer class="bg-[#f3f1ed] text-[#222] py-12 px-6 md:px-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-15">
      
      <!-- MAP -->
      <div class="ml-4">
        <h2 class="text-xl font-bold mb-4">Lokasi Kami</h2>
        <div class="w-full h-64 md:h-72 rounded-lg overflow-hidden shadow-md">
          <iframe
            class="w-full h-full"
            style="border:0;"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.1539490356845!2d110.67341777319237!3d-7.6665927758709635!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a40faa38699a7%3A0xe275646f6d5ace7b!2sElla%20Elektrik!5e0!3m2!1sid!2sid!4v1760797451603!5m2!1sid!2sid">
          </iframe>
        </div>
      </div>

      <!-- KANAN -->
      <div class="flex flex-col justify-start h-64 md:h-72">
        <!-- Atas: 3 kolom horizontal -->
        <div class="grid grid-cols-3 gap-25">
          <div>
            <h2 class="text-xl font-semibold mb-2 text-gray-700">Kategori</h2>
            <ul class="space-y-1 text-gray-600">
              <li>Elektronik Rumah Tangga</li>
              <li>Elektronik Dapur</li>
              <li>Kelistrikan</li>
            </ul>
          </div>

          <div>
            <h2 class="text-xl font-semibold mb-2 text-gray-700">Help</h2>
            <ul class="space-y-1 text-gray-600">
              <li>Help Center</li>
              <li>Garansi</li>
              <li>Contact Us</li>
            </ul>
          </div>

          <div>
            <h2 class="text-xl font-semibold mb-2 text-gray-700">About Us</h2>
            <ul class="space-y-1 text-gray-600">
              <li>Visi Misi</li>
            </ul>
          </div>
        </div>

        <!-- Bawah: Temukan Kami sejajar dengan map -->
        <div class="mt-25">
          <h2 class="text-xl font-semibold mb-2 text-gray-700">Temukan Kami</h2>
          <p class="text-gray-600 leading-relaxed">
            Jalan Stasiun Ceper No.940, Ngaglik, Klepu, Kec. Ceper, Kabupaten Klaten,<br>
            Jawa Tengah 57465
          </p>
        </div>
      </div>

    </div>
  </footer>
</template>
