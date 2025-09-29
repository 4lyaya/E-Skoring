<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Skor Panahan</title>

    <!-- Tailwind CSS v3 -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcgl9M+6tnLtTlEm4Wtk2L5L8R9VEK6SjtX1fNW8MX2fU2gZs+Jm5vW7K/3gPLcHCA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Flowbite -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Konfigurasi warna hijau solid -->
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
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>

    <!-- Google Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-primary-50 font-sans text-gray-800">

    <!-- Container -->
    <div class="max-w-5xl mx-auto px-4 py-6 md:py-10">

        <!-- Card Utama -->
        <div class="bg-white rounded-2xl shadow-lg border border-primary-100 overflow-hidden">

            <!-- Header -->
            <header class="bg-primary-600 text-white px-6 py-5 md:px-8 md:py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-3">
                            <i class="fa-solid fa-bullseye"></i>
                            Input Skor Turnamen Panahan
                        </h1>
                        <p class="mt-1 text-primary-100 text-sm md:text-base">
                            {{ $distance }} • {{ $numEnds }} End • {{ $arrowsPerEnd }} Panah/End •
                            {{ $numPlayers }} Pemain
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <i class="fa-solid fa-trophy text-3xl text-primary-200"></i>
                    </div>
                </div>
            </header>

            <!-- Form -->
            <form action="{{ route('archery.calculate') }}" method="POST" x-data="scoreForm()"
                @submit.prevent="submitForm">
                @csrf

                <!-- Daftar Pemain -->
                <div class="p-4 md:p-6 lg:p-8 space-y-6">

                    <template x-for="(player, pIndex) in players" :key="pIndex">
                        <section class="border border-primary-200 rounded-xl bg-primary-50 p-4 md:p-5">

                            <!-- Baris Nama -->
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                                <h2 class="text-lg md:text-xl font-semibold text-primary-800 flex items-center gap-2">
                                    <i class="fa-solid fa-user-circle"></i>
                                    <span x-text="`Pemain ${pIndex + 1}`"></span>
                                </h2>
                                <div class="w-full md:w-1/2">
                                    <label class="sr-only">Nama Pemain</label>
                                    <input type="text" x-model="player.name" required
                                        placeholder="Masukkan nama lengkap"
                                        class="w-full rounded-lg border border-primary-300 bg-white px-4 py-2 text-sm md:text-base placeholder-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-500" />
                                </div>
                            </div>

                            <!-- End -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <template x-for="(end, eIndex) in ends" :key="eIndex">
                                    <div class="bg-white border border-primary-200 rounded-xl p-3 md:p-4">
                                        <h3
                                            class="text-sm md:text-base font-medium text-primary-700 mb-2 flex items-center gap-2">
                                            <i class="fa-solid fa-target"></i>
                                            End <span x-text="eIndex + 1"></span>
                                        </h3>
                                        <div class="grid grid-cols-3 gap-2">
                                            <template x-for="(arrow, aIndex) in arrowsPerEnd" :key="aIndex">
                                                <div>
                                                    <label class="text-xs text-gray-600">Panah <span
                                                            x-text="aIndex + 1"></span></label>
                                                    <input type="number" min="0" max="10" required
                                                        x-model.number="player.scores[eIndex][aIndex]"
                                                        class="w-full rounded-md border border-primary-300 px-3 py-2 text-center text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" />
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>

                        </section>
                    </template>

                </div>

                <!-- Submit -->
                <div class="px-4 pb-6 md:px-6 md:pb-8 lg:px-8 lg:pb-10 flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center gap-3 px-6 py-3 bg-primary-600 text-white font-semibold rounded-xl shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition">
                        <i class="fa-solid fa-trophy"></i>
                        Hitung Hasil Turnamen
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.3.0/dist/flowbite.min.js"></script>

    <!-- Alpine.js Logic -->
    <script>
        function scoreForm() {
            return {
                players: [],
                ends: [],
                arrowsPerEnd: {{ $arrowsPerEnd }},
                init() {
                    const numPlayers = {{ $numPlayers }};
                    const numEnds = {{ $numEnds }};
                    this.ends = Array.from({
                        length: numEnds
                    }, (_, i) => i);
                    this.players = Array.from({
                        length: numPlayers
                    }, (_, i) => ({
                        name: '',
                        scores: Array.from({
                                length: numEnds
                            }, () =>
                            Array.from({
                                length: this.arrowsPerEnd
                            }, () => 0)
                        )
                    }));
                },
                submitForm() {
                    // Buat form data untuk dikirim
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('archery.calculate') }}';
                    form.style.display = 'none';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    // Nama pemain
                    this.players.forEach((p, i) => {
                        const inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = `player_names[${i}]`;
                        inp.value = p.name;
                        form.appendChild(inp);
                    });

                    // Skor
                    this.players.forEach((p, pIdx) => {
                        p.scores.forEach((end, eIdx) => {
                            end.forEach((arrow, aIdx) => {
                                const inp = document.createElement('input');
                                inp.type = 'hidden';
                                inp.name = `scores[${pIdx}][${eIdx + 1}][${aIdx}]`;
                                inp.value = arrow;
                                form.appendChild(inp);
                            });
                        });
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
</body>

</html>
