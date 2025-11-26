@extends('layouts.admin')

@section('title', 'Edit Supplier')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Supplier</h1>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi supplier.
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('suppliers.update', $supplier) }}" 
              method="POST" 
              class="p-6 space-y-6"
              x-data="{
                  code: '{{ old('code', $supplier->code) }}',
                  name: '{{ old('name', $supplier->name) }}',
                  address: '{{ old('address', $supplier->address) }}',
                  phone: '{{ old('phone', $supplier->phone) }}',
                  email: '{{ old('email', $supplier->email) }}',
                  contact_person: '{{ old('contact_person', $supplier->contact_person) }}',
                  description: '{{ old('description', $supplier->description) }}',
                  submitting: false,
                  errors: {
                      code: '',
                      name: '',
                      phone: '',
                      email: '',
                      address: '',
                      contact_person: '',
                      description: ''
                  },
                  validateCode() {
                      this.code = this.code.toUpperCase().replace(/[^A-Z0-9-]/g, '');
                      if (!this.code) {
                          this.errors.code = 'Kode supplier wajib diisi';
                      } else if (this.code.length < 3) {
                          this.errors.code = 'Kode minimal 3 karakter';
                      } else if (this.code.length > 50) {
                          this.errors.code = 'Kode maksimal 50 karakter';
                      } else {
                          this.errors.code = '';
                      }
                  },
                  validateName() {
                      if (!this.name) {
                          this.errors.name = 'Nama supplier wajib diisi';
                      } else if (this.name.length < 3) {
                          this.errors.name = 'Nama minimal 3 karakter';
                      } else if (this.name.length > 255) {
                          this.errors.name = 'Nama maksimal 255 karakter';
                      } else {
                          this.errors.name = '';
                      }
                  },
                  validatePhone() {
                      if (this.phone && !/^[0-9+\-\s()]+$/.test(this.phone)) {
                          this.errors.phone = 'Format nomor telepon tidak valid';
                      } else if (this.phone && this.phone.length > 20) {
                          this.errors.phone = 'Nomor telepon maksimal 20 karakter';
                      } else {
                          this.errors.phone = '';
                      }
                  },
                  validateEmail() {
                      if (this.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                          this.errors.email = 'Format email tidak valid';
                      } else if (this.email && this.email.length > 255) {
                          this.errors.email = 'Email maksimal 255 karakter';
                      } else {
                          this.errors.email = '';
                      }
                  },
                  validateAddress() {
                      if (this.address && this.address.length > 500) {
                          this.errors.address = 'Alamat maksimal 500 karakter';
                      } else {
                          this.errors.address = '';
                      }
                  },
                  validateContactPerson() {
                      if (this.contact_person && this.contact_person.length > 255) {
                          this.errors.contact_person = 'Nama kontak maksimal 255 karakter';
                      } else {
                          this.errors.contact_person = '';
                      }
                  },
                  validateDescription() {
                      if (this.description && this.description.length > 1000) {
                          this.errors.description = 'Deskripsi maksimal 1000 karakter';
                      } else {
                          this.errors.description = '';
                      }
                  },
                  async submitForm(e) {
                      this.validateCode();
                      this.validateName();
                      this.validatePhone();
                      this.validateEmail();
                      this.validateAddress();
                      this.validateContactPerson();
                      this.validateDescription();
                      
                      if (Object.values(this.errors).some(error => error !== '')) {
                          e.preventDefault();
                          showToast('error', 'Mohon perbaiki error pada form!');
                          return;
                      }
                      
                      this.submitting = true;
                      showLoading();
                  }
              }"
              @submit="submitForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Kode Supplier -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Supplier <span class="text-red-500">*</span>
                    </label>

                    <input type="text" 
                           name="code" 
                           id="code" 
                           x-model="code"
                           @input="validateCode"
                           @blur="validateCode"
                           :class="{
                               'border-red-300 bg-red-50': errors.code || {{ $errors->has('code') ? 'true' : 'false' }},
                               'border-green-300 bg-green-50': code && !errors.code && !{{ $errors->has('code') ? 'true' : 'false' }},
                               'border-gray-300': !code || (!errors.code && !{{ $errors->has('code') ? 'true' : 'false' }})
                           }"
                           class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors uppercase"
                           placeholder="Contoh: SUP001"
                           maxlength="50">

                    <p x-show="errors.code" 
                       x-text="errors.code" 
                       class="mt-2 text-sm text-red-600"
                       x-transition></p>

                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Supplier -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Supplier <span class="text-red-500">*</span>
                    </label>

                    <input type="text" 
                           name="name" 
                           id="name" 
                           x-model="name"
                           @input="validateName"
                           @blur="validateName"
                           :class="{
                               'border-red-300 bg-red-50': errors.name || {{ $errors->has('name') ? 'true' : 'false' }},
                               'border-green-300 bg-green-50': name && !errors.name && !{{ $errors->has('name') ? 'true' : 'false' }},
                               'border-gray-300': !name || (!errors.name && !{{ $errors->has('name') ? 'true' : 'false' }})
                           }"
                           class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                           placeholder="Contoh: PT Sumber Makmur"
                           maxlength="255">

                    <p x-show="errors.name" 
                       x-text="errors.name" 
                       class="mt-2 text-sm text-red-600"
                       x-transition></p>

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-2 text-xs text-gray-500">
                        <span x-text="name.length"></span>/255 karakter
                    </p>
                </div>

            </div>

            <!-- Alamat -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat
                </label>

                <textarea name="address" 
                          id="address" 
                          rows="3"
                          x-model="address"
                          @input="validateAddress"
                          :class="{
                              'border-red-300 bg-red-50': errors.address,
                              'border-green-300 bg-green-50': address && !errors.address && address.length > 0,
                              'border-gray-300': !address || !errors.address
                          }"
                          class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none"
                          placeholder="Alamat lengkap supplier..."
                          maxlength="500"></textarea>

                <p x-show="errors.address" 
                   x-text="errors.address" 
                   class="mt-2 text-sm text-red-600"
                   x-transition></p>

                <p class="mt-2 text-xs text-gray-500">
                    <span x-text="address.length"></span>/500 karakter
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Telepon -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Telepon
                    </label>

                    <input type="text" 
                           name="phone" 
                           id="phone" 
                           x-model="phone"
                           @input="validatePhone"
                           @blur="validatePhone"
                           :class="{
                               'border-red-300 bg-red-50': errors.phone || {{ $errors->has('phone') ? 'true' : 'false' }},
                               'border-green-300 bg-green-50': phone && !errors.phone && !{{ $errors->has('phone') ? 'true' : 'false' }},
                               'border-gray-300': !phone || (!errors.phone && !{{ $errors->has('phone') ? 'true' : 'false' }})
                           }"
                           class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                           placeholder="08123456789"
                           maxlength="20">

                    <p x-show="errors.phone" 
                       x-text="errors.phone" 
                       class="mt-2 text-sm text-red-600"
                       x-transition></p>

                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>

                    <input type="email" 
                           name="email" 
                           id="email" 
                           x-model="email"
                           @input="validateEmail"
                           @blur="validateEmail"
                           :class="{
                               'border-red-300 bg-red-50': errors.email || {{ $errors->has('email') ? 'true' : 'false' }},
                               'border-green-300 bg-green-50': email && !errors.email && !{{ $errors->has('email') ? 'true' : 'false' }},
                               'border-gray-300': !email || (!errors.email && !{{ $errors->has('email') ? 'true' : 'false' }})
                           }"
                           class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                           placeholder="supplier@example.com"
                           maxlength="255">

                    <p x-show="errors.email" 
                       x-text="errors.email" 
                       class="mt-2 text-sm text-red-600"
                       x-transition></p>

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Contact Person -->
            <div>
                <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kontak Person
                </label>

                <input type="text" 
                       name="contact_person" 
                       id="contact_person" 
                       x-model="contact_person"
                       @input="validateContactPerson"
                       :class="{
                           'border-red-300 bg-red-50': errors.contact_person,
                           'border-green-300 bg-green-50': contact_person && !errors.contact_person && contact_person.length > 0,
                           'border-gray-300': !contact_person || !errors.contact_person
                       }"
                       class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                       placeholder="Nama PIC atau perwakilan"
                       maxlength="255">

                <p x-show="errors.contact_person" 
                   x-text="errors.contact_person" 
                   class="mt-2 text-sm text-red-600"
                   x-transition></p>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>

                <textarea name="description" 
                          id="description" 
                          rows="4"
                          x-model="description"
                          @input="validateDescription"
                          :class="{
                              'border-red-300 bg-red-50': errors.description,
                              'border-green-300 bg-green-50': description && !errors.description && description.length > 0,
                              'border-gray-300': !description || !errors.description
                          }"
                          class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none"
                          placeholder="Catatan tambahan tentang supplier..."
                          maxlength="1000"></textarea>

                <p x-show="errors.description" 
                   x-text="errors.description" 
                   class="mt-2 text-sm text-red-600"
                   x-transition></p>

                <p class="mt-2 text-xs text-gray-500">
                    <span x-text="description.length"></span>/1000 karakter
                    <span x-show="description.length > 900" class="text-orange-600 font-semibold">
                        (<span x-text="1000 - description.length"></span> tersisa)
                    </span>
                </p>
            </div>

            <!-- Status Aktif -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active"
                           value="1"
                           {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="ml-3">
                    <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                        Aktifkan supplier ini
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Supplier yang aktif dapat dinilai dalam assessment</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                
                <a href="{{ route('suppliers.index') }}"
                   class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg 
                          text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                          shadow-sm">
                    Batal
                </a>

                <button type="submit"
                        :disabled="submitting"
                        :class="{ 'opacity-50 cursor-not-allowed': submitting }"
                        class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-medium 
                               text-white bg-indigo-600 hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               shadow-sm">
                    
                    <svg x-show="submitting" 
                         class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                         fill="none" 
                         viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <span x-text="submitting ? 'Menyimpan...' : 'Perbarui Supplier'"></span>
                </button>

            </div>
        </form>
    </div>

</div>
@endsection