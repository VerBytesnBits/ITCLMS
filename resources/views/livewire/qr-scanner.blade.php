<div class="space-y-6">
    <livewire:dashboard-heading title="Issue Reported Table" subtitle="Details of reported issues" icon="qr-code"
        gradient-from-color="#3b82f6" gradient-to-color="#7c3aed" icon-color="text-blue-500" />

    {{-- <div x-data="qrScanner()" x-init="init()" class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        <div
            class="col-span-1 bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200 dark:border-zinc-700 flex flex-col overflow-hidden">


            <div
                class="border-b border-zinc-200 dark:border-zinc-700 p-4 flex items-center gap-2 bg-zinc-50 dark:bg-zinc-900/50">
                <flux:icon.scan-qr-code class="w-5 h-5 text-blue-500" />
                <h2 class="font-semibold text-lg text-zinc-800 dark:text-zinc-200">QR Scanner</h2>
            </div>


            <div class="flex-1 p-5 space-y-4">

                <div id="reader"
                    class="relative w-full aspect-square bg-gray-100 dark:bg-zinc-900 rounded-xl overflow-hidden border border-gray-300 dark:border-zinc-700 flex items-center justify-center">
                    <div id="placeholder"
                        class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                        <flux:icon.scan-qr-code class="w-20 h-20 md:w-24 md:h-24" />
                        <p class="text-sm mt-2">Camera is off</p>
                    </div>
                </div>


                <div>
                    <label for="cameraSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Select Camera
                    </label>
                    <select id="cameraSelect"
                        class="w-full border border-gray-300 dark:border-zinc-600 rounded-md px-2 py-1 mt-1 text-sm dark:bg-zinc-800 dark:text-white">
                        <option value="">Select...</option>
                    </select>
                </div>
            </div>


            <div x-data="{ isScanning: false }"
                class="border-t border-zinc-200 dark:border-zinc-700 p-4 flex justify-between gap-3 bg-zinc-50 dark:bg-zinc-900/50">

                <!-- Start Button -->
                <button x-show="!isScanning" @click="startScanner(); isScanning = true"
                    class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium px-3 py-2 rounded-lg text-sm transition">
                    Start
                </button>

                <!-- Stop Button -->
                <button x-show="isScanning" @click="stopScanner(); isScanning = false"
                    class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium px-3 py-2 rounded-lg text-sm transition">
                    Stop
                </button>
            </div>

        </div>



        <div
            class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-700 flex flex-col overflow-hidden">


            <div
                class="bg-gradient-to-r from-blue-600 to-indigo-500 text-white px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <flux:icon.package class="w-5 h-5 text-white/90" />
                    Inventory Item Details
                </h2>
                @if ($scannedCode)
                    <span class="text-xs bg-white/20 px-3 py-1 rounded-full">
                        Scanned
                    </span>
                @endif
            </div>


            <div class="p-6 flex-1">
                @if ($scannedCode)
                    <div class="mb-5">
                        <p
                            class="text-green-600 text-sm break-all bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-2">
                            <strong>Scanned Code:</strong> {{ $scannedCode }}
                        </p>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text"
                        class="border border-gray-300 dark:border-zinc-600 rounded-lg p-2 text-sm dark:bg-zinc-800 dark:text-white"
                        placeholder="Item Name">

                    <input type="text"
                        class="border border-gray-300 dark:border-zinc-600 rounded-lg p-2 text-sm dark:bg-zinc-800 dark:text-white"
                        placeholder="Item Code">

                    <input type="text"
                        class="border border-gray-300 dark:border-zinc-600 rounded-lg p-2 text-sm dark:bg-zinc-800 dark:text-white"
                        placeholder="Category">

                    <input type="text"
                        class="border border-gray-300 dark:border-zinc-600 rounded-lg p-2 text-sm dark:bg-zinc-800 dark:text-white"
                        placeholder="Brand">

                    <input type="text"
                        class="border border-gray-300 dark:border-zinc-600 rounded-lg p-2 text-sm dark:bg-zinc-800 dark:text-white"
                        placeholder="Location">
                </div>

                <div class="mt-5">
                    <input type="text"
                        class="border border-gray-300 dark:border-zinc-600 rounded-lg p-2 w-full text-sm dark:bg-zinc-800 dark:text-white"
                        placeholder="Report Title *">

                    <textarea
                        class="border border-gray-300 dark:border-zinc-600 rounded-lg p-2 w-full mt-3 text-sm dark:bg-zinc-800 dark:text-white"
                        rows="3" placeholder="Report Description *"></textarea>
                </div>
            </div>


            <div
                class="bg-gray-50 dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-700 px-6 py-4 flex justify-end">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                    <flux:icon.send class="w-4 h-4 text-white/80" />
                    Submit Report
                </button>
            </div>
        </div>

    </div> --}}


    {{-- Issue Reports Table --}}
    <livewire:issues.issue-table />

</div>
