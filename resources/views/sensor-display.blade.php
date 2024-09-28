<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลเซนเซอร์</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://vjs.zencdn.net/7.10.2/video-js.min.css" rel="stylesheet">
    <script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Sarabun', 'sans-serif'],
                    }
                }
            }
        }
    </script>
     <style>
        #videoElement {
            width: 100%;
            max-width: 640px;
            height: auto;
        }
    </style>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }
    </style>
</head>
<body class="bg-green-50 text-gray-800 p-8">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-6 text-green-700">ค่าของเซนเซอร์ปัจจุบัน</h1>



        @if($sensorData)
            @php
                $moisturePercentage = round(($sensorData->moisture_level / 1023) * 100);
            @endphp

            <div class="space-y-4">
                <p class="text-lg"><span class="font-semibold">ระดับความชื้น:</span> {{ $moisturePercentage }}RH%</p>
                <p class="text-lg">
                    <span class="font-semibold">สถานะ:</span>
                    @if($sensorData->moisture_level > $currentThreshold)
                        <span class="text-red-500">ดินแห้ง</span>
                    @else
                        <span class="text-green-500">ดินชื้น</span>
                    @endif
                </p>
                <p class="text-lg">
                    <span class="font-semibold">เกณฑ์ความชื้นปัจจุบัน:</span>
                    @if($currentThreshold == 200)
                        ชื้นมาก
                    @elseif($currentThreshold == 400)
                        ชื้นปานกลาง
                    @elseif($currentThreshold == 600)
                        แห้ง
                    @elseif($currentThreshold == 800)
                        แห้งมาก
                    @else
                        ไม่ทราบค่า
                    @endif
                </p>



                <p class="text-lg">
                    <span class="font-semibold">สถานะปั้มน้ำ:</span>
                    {{ $sensorData->pump_state ? 'เปิด' : 'ปิด' }}
                </p>
                <p class="text-lg">
                    <span class="font-semibold">สถานะของน้ำ:</span>
                    {{ $sensorData->water_level ? 'น้ำใกล้หมด' : 'น้ำอยู่ระดับปกติ' }}
                </p>
            </div>
        @else
            <p class="text-red-500">ไม่มีข้อมูล</p>
        @endif

        <h2 class="text-2xl font-bold mt-8 mb-4 text-green-600">ปรับแต่ง พืชที่ต้องการความชื้นมากน้อยต่างกัน</h2>

        <form id="moistureForm" action="{{ route('update.threshold') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="showConfirmation(200, 'ชื้นมาก')" class="flex items-center justify-center bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                    <i class="fas fa-seedling mr-2"></i> ชื้นมาก
                </button>
                <button type="button" onclick="showConfirmation(400, 'ชื้นปานกลาง')" class="flex items-center justify-center bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition">
                    <i class="fas fa-leaf mr-2"></i> ชื้นปานกลาง
                </button>
                <button type="button" onclick="showConfirmation(600, 'แห้ง')" class="flex items-center justify-center bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 transition">
                    <i class="fas fa-tree mr-2"></i> แห้ง
                </button>
                <button type="button" onclick="showConfirmation(800, 'แห้งมาก')" class="flex items-center justify-center bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition">
                    <i class="fas fa-sun mr-2"></i> แห้งมาก
                </button>
            </div>
            <input type="hidden" id="moistureThreshold" name="moistureThreshold" value="">
        </form>

        <h2 class="text-2xl font-bold mt-8 mb-4 text-green-600">กล้อง</h2>
        <div class="aspect-w-16 aspect-h-9">
                <iframe src="https://vdo.ninja/?view=4FDigv3"
                        allow="microphone;camera;fullscreen;display-capture;autoplay"
                        style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
        </div>

        @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-xl">
            <h3 class="text-xl font-bold mb-4">ยืนยันการเปลี่ยนแปลง</h3>
            <p id="confirmationMessage" class="mb-6"></p>
            <div class="flex justify-end space-x-4">
                <button onclick="closeConfirmation()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">ยกเลิก</button>
                <button onclick="submitForm()" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">ยืนยัน</button>
            </div>
        </div>
    </div>


    <script>
        function showConfirmation(threshold, label) {
            document.getElementById('moistureThreshold').value = threshold;
            document.getElementById('confirmationMessage').textContent = `คุณต้องการเปลี่ยนความชื้นเป็น "${label}" (${threshold}) ใช่หรือไม่?`;
            document.getElementById('confirmationModal').classList.remove('hidden');
            document.getElementById('confirmationModal').classList.add('flex');
        }

        function closeConfirmation() {
            document.getElementById('confirmationModal').classList.add('hidden');
            document.getElementById('confirmationModal').classList.remove('flex');
        }

        function submitForm() {
            document.getElementById('moistureForm').submit();
        }
    </script>


</body>
</html>
