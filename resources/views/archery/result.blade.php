<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Turnamen Panahan</title>

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

    <div class="max-w-7xl mx-auto px-4 py-8 md:py-12">

        <!-- Card Utama -->
        <div class="bg-white rounded-2xl shadow-lg border border-primary-100 overflow-hidden">

            <!-- Header -->
            <header class="bg-primary-600 text-white px-6 py-5 md:px-8 md:py-6 text-center">
                <h1 class="text-2xl md:text-3xl font-bold flex items-center justify-center gap-3">
                    <i class="fa-solid fa-trophy"></i>
                    Hasil Turnamen Panahan
                </h1>
                <p class="mt-2 text-primary-100 text-sm md:text-base">
                    {{ $distance }} • {{ $setup['num_ends'] }} End • {{ $setup['arrows_per_end'] }} Panah per End
                </p>
            </header>

            <!-- Konten -->
            <div class="p-4 md:p-6 lg:p-8 space-y-8">

                <!-- Peringkat -->
                <section>
                    <h2 class="text-xl md:text-2xl font-bold text-primary-800 mb-6 text-center">🏆 Peringkat Akhir</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($results as $index => $player)
                            <div
                                class="border-2 rounded-xl p-5 text-center transition hover:shadow-md
                                @if ($index == 0) border-yellow-400 bg-yellow-50
                                @elseif($index == 1) border-gray-400 bg-gray-50
                                @elseif($index == 2) border-orange-300 bg-orange-50
                                @else border-primary-200 bg-primary-50 @endif">
                                <div class="text-4xl mb-2">
                                    @if ($index == 0)
                                        🥇
                                    @elseif($index == 1)
                                        🥈
                                    @elseif($index == 2)
                                        🥉
                                    @else
                                        #{{ $index + 1 }}
                                    @endif
                                </div>
                                <h3
                                    class="text-lg md:text-xl font-bold @if ($index == 0) text-yellow-700 @endif">
                                    {{ $player['name'] }}
                                </h3>
                                <p class="text-xl md:text-2xl font-bold text-primary-600 mt-1">{{ $player['total'] }}
                                    Poin</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Tabel Detail -->
                <section>
                    <h2 class="text-xl md:text-2xl font-bold text-primary-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-table"></i>
                        📊 Detail Skor
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm md:text-base border-collapse border border-primary-200 rounded-lg">
                            <thead>
                                <tr class="bg-primary-100 text-primary-800">
                                    <th class="border border-primary-200 px-4 py-3 font-semibold text-center">#</th>
                                    <th class="border border-primary-200 px-4 py-3 font-semibold text-left">Nama Pemain
                                    </th>
                                    @foreach ($ends as $end)
                                        <th class="border border-primary-200 px-4 py-3 font-semibold text-center">End
                                            {{ $end }}</th>
                                    @endforeach
                                    <th class="border border-primary-200 px-4 py-3 font-semibold text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $index => $player)
                                    <tr
                                        class="hover:bg-primary-50 transition
                                        @if ($index == 0) bg-yellow-50
                                        @elseif($index == 1) bg-gray-50
                                        @elseif($index == 2) bg-orange-50
                                        @else bg-white @endif">
                                        <td class="border border-primary-200 px-4 py-3 text-center font-bold">
                                            {{ $index + 1 }}</td>
                                        <td class="border border-primary-200 px-4 py-3 font-semibold">
                                            {{ $player['name'] }}</td>
                                        @foreach ($ends as $end)
                                            <td class="border border-primary-200 px-4 py-3 text-center">
                                                {{ $player['scores'][$end]['total'] ?? 0 }}</td>
                                        @endforeach
                                        <td
                                            class="border border-primary-200 px-4 py-3 text-center font-bold text-primary-700 text-lg">
                                            {{ $player['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Aksi -->
                <section class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('archery.download', ['filename' => $filename]) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-primary-600 text-white font-semibold rounded-xl shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition">
                        <i class="fa-solid fa-download"></i>
                        Download Excel Report
                    </a>
                    <a href="{{ route('archery.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        <i class="fa-solid fa-rotate-right"></i>
                        Buat Turnamen Baru
                    </a>
                </section>

            </div>
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.js"></script>
</body>

</html>
