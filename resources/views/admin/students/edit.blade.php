<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Edit Student</h1>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    ← Back to Dashboard
                </a>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <p class="font-semibold">Please fix the following errors:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Edit Form -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
                    <h2 class="text-2xl font-bold text-white">Edit Student Information</h2>
                    <p class="text-blue-100">Student ID: #{{ $student->id }}</p>
                </div>

                <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Student Name -->
                    <div>
                        <label for="student_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="student_name" 
                            name="student_name" 
                            value="{{ old('student_name', $student->student_name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            required
                        >
                        @error('student_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username', $student->username) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            required
                        >
                        <p class="mt-1 text-sm text-gray-500">Username must be unique</p>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- IP Address -->
                    <div>
                        <label for="student_ip" class="block text-sm font-medium text-gray-700 mb-2">
                            IP Address <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="student_ip" 
                            name="student_ip" 
                            value="{{ old('student_ip', $student->student_ip) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition font-mono bg-gray-50"
                            required
                            pattern="^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$"
                            placeholder="192.168.1.100"
                        >
                        <p class="mt-1 text-sm text-gray-500">Format: xxx.xxx.xxx.xxx (e.g., 192.168.1.100)</p>
                        @error('student_ip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password (Optional) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password <span class="text-gray-500">(Optional - Leave blank to keep current)</span>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            minlength="6"
                        >
                        <p class="mt-1 text-sm text-gray-500">Minimum 6 characters. Leave blank to keep current password.</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Account Status <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="is_active" 
                                    value="1" 
                                    {{ old('is_active', $student->is_active) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                >
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="is_active" 
                                    value="0" 
                                    {{ old('is_active', $student->is_active) == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                >
                                <span class="ml-2 text-gray-700">Inactive</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Divider -->
                    <hr class="my-6">

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <button 
                            type="submit" 
                            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition"
                        >
                            💾 Save Changes
                        </button>
                        
                        <a 
                            href="{{ route('admin.students.view', $student->id) }}" 
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold text-center transition"
                        >
                            ✖️ Cancel
                        </a>
                    </div>
                </form>

                <!-- Danger Zone -->
                <div class="bg-red-50 border-t border-red-200 p-6">
                    <h3 class="text-lg font-semibold text-red-700 mb-2">Danger Zone</h3>
                    <p class="text-sm text-red-600 mb-4">Deleting this student will permanently remove all their data including attendance records.</p>
                    
                    <button onclick="deleteStudentFromEdit({{ $student->id }}, '{{ $student->student_name }}')" 
                            class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Student
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteStudentFromEdit(studentId, studentName) {
            Swal.fire({
                title: '⚠️ Danger Zone!',
                html: `
                    <div class="text-left">
                        <p class="text-gray-700 mb-3">Anda akan menghapus student:</p>
                        <div class="bg-red-50 p-4 rounded-lg mb-4">
                            <p class="font-bold text-red-700 text-lg">${studentName}</p>
                        </div>
                        <p class="text-sm text-red-600 font-semibold">⚠️ Ini akan menghapus:</p>
                        <ul class="text-sm text-gray-600 list-disc list-inside mb-3">
                            <li>Data student</li>
                            <li>Semua riwayat attendance</li>
                            <li>QR codes yang pernah di-generate</li>
                        </ul>
                        <p class="text-red-700 font-bold">Data yang dihapus TIDAK dapat dikembalikan!</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '🗑️ Ya, Hapus Permanen!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/students/${studentId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }
    </script>
</body>
</html>
