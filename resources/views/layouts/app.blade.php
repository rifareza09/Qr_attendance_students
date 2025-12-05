<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Attendance System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Custom Animations */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast-notification {
            animation: slideInRight 0.4s ease-out;
        }
        
        .toast-notification.hiding {
            animation: slideOutRight 0.4s ease-in;
        }
        
        /* Custom SweetAlert2 styling */
        .swal2-popup {
            border-radius: 15px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .swal2-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .swal2-confirm {
            border-radius: 8px;
            padding: 10px 30px;
            font-weight: 600;
        }
        
        .swal2-cancel {
            border-radius: 8px;
            padding: 10px 30px;
        }
    </style>
</head>
<body class="bg-gray-100">
    @yield('content')

    <script>
        // Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            customClass: {
                popup: 'colored-toast'
            }
        });

        // Show notifications from session
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                background: '#10b981',
                color: '#fff',
                iconColor: '#fff'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                background: '#ef4444',
                color: '#fff',
                iconColor: '#fff'
            });
        @endif

        @if(session('info'))
            Toast.fire({
                icon: 'info',
                title: '{{ session('info') }}',
                background: '#3b82f6',
                color: '#fff',
                iconColor: '#fff'
            });
        @endif

        @if(session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}',
                background: '#f59e0b',
                color: '#fff',
                iconColor: '#fff'
            });
        @endif

        // Global confirmation dialog function
        function confirmDelete(message = 'Data yang dihapus tidak dapat dikembalikan!') {
            return Swal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });
        }

        // Success notification
        function showSuccess(title, message = '') {
            Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK'
            });
        }

        // Error notification
        function showError(title, message = '') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK'
            });
        }

        // Loading notification
        function showLoading(message = 'Memproses...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    </script>
</body>
</html>
