@extends('layouts.app')

@section('content')
<script src="https://code.iconify.design/1/1.0.0/iconify.min.js"></script>
<!-- Flash Message -->
@if(session('success'))
    <div class="bg-green-600 text-white p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('dashboard.index') }}"
       class="inline-flex items-center gap-2 bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 px-4 rounded shadow">
        <i class="iconify" data-icon="ion:arrow-back-outline"></i>
        Kembali ke Dashboard
    </a>
</div>

<div class="mb-2">
    <h1 class="text-2xl font-bold text-white flex items-center gap-2">
        <i class="iconify" data-icon="mdi:layout-template"></i>
        Voucher Template Manager
    </h1>
    <p class="text-sm text-gray-400 flex items-center gap-1">
        <i class="iconify" data-icon="mdi:check-circle"></i> DHS
    </p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
    <!-- Konfigurasi Template -->
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow">
        <h2 class="text-xl font-semibold text-white mb-4 flex items-center gap-2">
            <i class="iconify" data-icon="mdi:settings"></i>
            Template Configuration
        </h2>

        <!-- Tombol Tambah Template -->
        <div class="mb-4">
            <button type="button" onclick="openModal()"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                <i class="iconify" data-icon="mdi:plus-circle"></i>
                Tambah Voucher Baru
            </button>
        </div>

        <!-- Form Save Template -->
        <form method="POST" action="{{ route('voucher.template.save') }}">
            @csrf

            <!-- Pilih Template -->
            <div class="mb-4">
                <label for="voucherSelect" class="block text-sm font-medium text-gray-300 mb-1">Select Template</label>
                <select id="templateSelect" name="template_id" class="form-control">
                    <option value="">-- Pilih Template --</option>
    @foreach($voucherTemplates as $t)
                        <option value="{{ $t->id }}">
            {{ $t->name }}
        </option>
    @endforeach
                </select>
            </div>

            <!-- Kode HTML Template -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-1">HTML Template Code</label>
                <textarea name="code" rows="14"
                    class="w-full text-xs font-mono bg-gray-900 text-green-300 border border-gray-700 rounded p-2"
                    required>{{ old('code', $selectedTemplate->code ?? '') }}</textarea>
            </div>
<!-- Tombol Save & Reset -->
<div class="flex flex-col sm:flex-row gap-2 mt-4">
    <!-- Save -->
    <button type="submit"
            class="flex items-center gap-1 bg-blue-600 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">
        <i class="iconify" data-icon="mdi:content-save"></i> Save Template
    </button>

    <!-- Reset -->
    <a href="{{ route('voucher.template.reset') }}"
            class="flex items-center gap-1 bg-yellow-600 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">
        <i class="iconify" data-icon="mdi:restore"></i> Reset to Default
    </a>
</div>
</form> <!-- penutup form Save -->

<!-- Form Hapus (terpisah) -->
<!-- Form Hapus (terpisah) -->
<form method="POST" action="{{ route('voucher.template.delete') }}"
      onsubmit="return confirm('Yakin ingin menghapus template ini?');"
      class="mt-2">
    @csrf
    <input type="hidden" id="deleteTemplateId" name="template_id" value="{{ $selectedTemplate->id ?? '' }}">
    <button type="submit"
            class="flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">
        <i class="iconify" data-icon="mdi:delete"></i> Hapus Template
    </button>
</form>


    </div>



    <!-- Live Preview -->
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow">
        <h2 class="text-xl font-semibold text-white mb-4 flex items-center gap-2">
            <i class="iconify" data-icon="mdi:eye"></i>
            Live Preview
        </h2>

        <div id="previewBox"
             class="bg-white text-black p-2 rounded shadow overflow-auto min-h-[300px] flex justify-center items-center">
            Pilih template dulu untuk melihat preview.
        </div>
    </div>
</div>

<!-- Modal Popup Tambah Template -->
<div id="voucherModal" 
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
    
    <div id="voucherModalContent"
         class="bg-white w-full max-w-lg p-6 rounded-2xl shadow-xl relative transform scale-95 opacity-0 transition-all duration-200">
        
        <!-- Tombol Close -->
        <button onclick="closeModal()" 
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 transition">
            ✖
        </button>

        <!-- Header -->
        <h2 class="text-xl font-bold mb-5 flex items-center gap-2 text-gray-800">
            <i class="iconify text-blue-600" data-icon="mdi:plus-circle-outline"></i>
            Tambah Voucher Baru
        </h2>

<form id="addTemplateForm" method="POST" action="{{ route('voucher.template.store') }}">
    @csrf


    <!-- Nama Template -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Template</label>
        <input type="text" name="name" 
               class="w-full rounded-lg px-3 py-2 border border-gray-300 
                      bg-white text-gray-900 placeholder-gray-400
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
               placeholder="Masukkan nama template..."
               required>
    </div>

    <!-- Kode HTML -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">HTML Template Code</label>
        <textarea name="code" rows="10"
                  class="w-full rounded-lg px-3 py-2 font-mono text-sm 
                         bg-gray-900 text-green-300 border border-gray-700 
                         focus:ring-2 focus:ring-blue-500 focus:outline-none"
                  placeholder="Tulis kode HTML template di sini..."
                  required></textarea>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex justify-end gap-3 pt-2">
        <button type="button" onclick="closeModal()" 
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition">
            Batal
        </button>
        <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            Simpan
        </button>
    </div>
</form>

    </div>
</div>



<script>
    
function openModal() {
    const modal = document.getElementById('voucherModal');
    const content = document.getElementById('voucherModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('voucherModal');
    const content = document.getElementById('voucherModalContent');
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

// Preview Template ketika dropdown berubah
document.getElementById('templateSelect').addEventListener('change', function () {
    let id = this.value;

    // Update value form Hapus
    document.getElementById('deleteTemplateId').value = id;

    // Preview template
    if (!id) {
        document.getElementById('previewBox').innerHTML = "Pilih template dulu untuk melihat preview.";
        return;
    }

    fetch("{{ route('voucher.template.render') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ template_id: id })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('previewBox').innerHTML = data.html;
    })
    .catch(err => {
        document.getElementById('previewBox').innerHTML = "Error: " + err;
    });
});


// Tambah Template Baru via Modal AJAX
document.getElementById('addTemplateForm').addEventListener('submit', function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch(this.action, {
        method: "POST",
        body: formData // Jangan set Content-Type manual saat pakai FormData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Tambah template baru ke dropdown
            let select = document.getElementById('templateSelect');
            let opt = document.createElement("option");
            opt.value = data.template.id;
            opt.text = data.template.name;
            opt.selected = true;
            select.appendChild(opt);

            // Trigger render untuk update preview
            select.dispatchEvent(new Event('change'));

            // Tutup modal
            closeModal();

            // Reset form modal
            this.reset();
        } else {
            alert("Gagal menyimpan template!");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi error saat menyimpan template!");
    });
});
</script>

@endsection
