<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Turnamen Panahan</title>

    <!-- Tailwind CSS v3 -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Flowbite -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Konfigurasi warna hijau -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-primary-50 font-sans text-gray-800">

    <div class="max-w-2xl mx-auto px-4 py-8 md:py-12">

        <!-- Card Utama -->
        <div class="bg-white rounded-2xl shadow-lg border border-primary-100 overflow-hidden">

            <!-- Header -->
            <header class="bg-primary-600 text-white px-6 py-5 md:px-8 md:py-6">
                <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-3">
                    <i class="fa-solid fa-bullseye"></i>
                    Setup Turnamen Panahan
                </h1>
                <p class="mt-1 text-primary-100 text-sm md:text-base">
                    Atur jumlah pemain, end, panah, dan jarak untuk memulai turnamen.
                </p>
            </header>

            <!-- Form -->
            <form action="{{ route('archery.setup') }}" method="POST" class="p-4 md:p-6 lg:p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jumlah Pemain -->
                    <div>
                        <label for="num_players" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Pemain *
                        </label>
                        <input type="number" id="num_players" name="num_players" min="1" max="10"
                            value="2" required
                            class="w-full px-4 py-2 border border-primary-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="Jumlah pemain">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 10 pemain</p>
                    </div>

                    <!-- Jumlah End -->
                    <div>
                        <label for="num_ends" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah End *
                        </label>
                        <input type="number" id="num_ends" name="num_ends" min="1" max="12"
                            value="6" required
                            class="w-full px-4 py-2 border border-primary-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="Jumlah end">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 12 end</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Panah per End -->
                    <div>
                        <label for="arrows_per_end" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Panah per End *
                        </label>
                        <input type="number" id="arrows_per_end" name="arrows_per_end" min="1" max="6"
                            value="3" required
                            class="w-full px-4 py-2 border border-primary-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="Panah per end">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 6 panah per end</p>
                    </div>

                    <!-- Jarak -->
                    <div>
                        <label for="distance" class="block text-sm font-medium text-gray-700 mb-2">
                            Jarak *
                        </label>
                        <select id="distance" name="distance" required
                            class="w-full px-4 py-2 border border-primary-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                            @foreach ($distances as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-semibold rounded-xl shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition">
                        <i class="fa-solid fa-arrow-right"></i>
                        Lanjutkan ke Input Skor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.js"></script>
</body>

</html>
