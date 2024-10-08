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
        body {
            font-family: 'Sarabun', sans-serif;
        }

        #videoElement {
            width: 100%;
            max-width: 640px;
            height: auto;
        }
    </style>
</head>

<body class="bg-green-50 text-gray-800 p-8">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-6 text-green-700">ค่าของเซนเซอร์ปัจจุบัน</h1>

        @if ($sensorData)
            @php
                $moisturePercentage = round(($sensorData->moisture_level / 1023) * 100);
            @endphp

            <div class="space-y-4">
                <p class="text-lg"><span class="font-semibold">ระดับความชื้น:</span> {{ $moisturePercentage }}RH%</p>
                <p class="text-lg flex items-center">
                    <span class="font-semibold">สถานะ:</span>
                    @if ($sensorData->moisture_level >= 800)
                        <span class="text-red-500 ml-2">แห้งมาก</span>
                        <img src="./img/PerseveringFaceEmoji.png" alt="very dry face" class="ml-4 w-16 h-16">
                    @elseif($sensorData->moisture_level >= 600)
                        <span class="text-yellow-500 ml-2">แห้ง</span>
                        <img src="./img/ConfusedFaceEmoji.png" alt="dry face" class="ml-4 w-16 h-16">
                    @elseif($sensorData->moisture_level >= 450)
                        <span class="text-green-500 ml-2">ชื้นปานกลาง</span>
                        <img src="./img/SmilingFaceEmoji.png" alt="moderate moisture face" class="ml-4 w-16 h-16">
                    @else
                        <span class="text-blue-500 ml-2">ชื้นมาก</span>
                        <img src="./img/SmilingFacewithTightlyClosedeyes.png" alt="wet face" class="ml-4 w-16 h-16">
                    @endif
                </p>
                <p class="text-lg">
                    <span class="font-semibold">เกณฑ์ความชื้นปัจจุบัน:</span>
                    @if ($currentThreshold == 350)
                        ชื้นมาก
                    @elseif($currentThreshold == 450)
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
                <button type="button" onclick="showConfirmation(350, 'ชื้นมาก')"
                    class="flex items-center justify-center bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                    <i class="fas fa-seedling mr-2"></i> ชื้นมาก
                </button>
                <button type="button" onclick="showConfirmation(450, 'ชื้นปานกลาง')"
                    class="flex items-center justify-center bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition">
                    <i class="fas fa-leaf mr-2"></i> ชื้นปานกลาง
                </button>
                <button type="button" onclick="showConfirmation(600, 'แห้ง')"
                    class="flex items-center justify-center bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 transition">
                    <i class="fas fa-tree mr-2"></i> แห้ง
                </button>
                <button type="button" onclick="showConfirmation(800, 'แห้งมาก')"
                    class="flex items-center justify-center bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition">
                    <i class="fas fa-sun mr-2"></i> แห้งมาก
                </button>
            </div>
            <input type="hidden" id="moistureThreshold" name="moistureThreshold" value="">
        </form>

        <h2 class="text-2xl font-bold mt-8 mb-4 text-green-600">กล้อง</h2>
        <div class="flex justify-between items-center mb-4">
            <a href="{{ url('/watering-history') }}"
                class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition">
                ประวัติการรดน้ำ
            </a>
        </div>
        <div class="aspect-w-16 aspect-h-9">
            <iframe src="https://vdo.ninja/?view=rbEvdavpY"
                allow="microphone;camera;fullscreen;display-capture;autoplay"
                style="width: 100%; height: 500px; border: none;"></iframe>
        </div>


        <!-- Confirmation Modal -->
        <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-xl">
                <h3 class="text-xl font-bold mb-4">ยืนยันการเปลี่ยนแปลง</h3>
                <p id="confirmationMessage" class="mb-6"></p>
                <div class="flex justify-end space-x-4">
                    <button onclick="closeConfirmation()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">ยกเลิก</button>
                    <button onclick="submitForm()"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">ยืนยัน</button>
                </div>
            </div>
        </div>

        <script>
            function showConfirmation(threshold, label) {
                document.getElementById('moistureThreshold').value = threshold;
                document.getElementById('confirmationMessage').textContent =
                    `คุณต้องการเปลี่ยนความชื้นเป็น "${label}" (${threshold}) ใช่หรือไม่?`;
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

            let snapshotCount = 0;

            function captureSnapshot() {
                const iframe = document.querySelector('iframe');
                const iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
                const videoElement = iframeDocument.querySelector('video');

                if (videoElement) {
                    const canvas = document.createElement('canvas');
                    canvas.width = videoElement.videoWidth;
                    canvas.height = videoElement.videoHeight;
                    const context = canvas.getContext('2d');
                    context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
                    const imageData = canvas.toDataURL('image/png');

                    // ส่งภาพไปที่เซิร์ฟเวอร์
                    fetch('{{ route('save.snapshot') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                image: imageData
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Snapshot saved:', data);
                            updateSnapshotInfo();
                        })
                        .catch(error => {
                            console.error('Error saving snapshot:', error);
                            // อาจจะแสดง alert หรือข้อความแจ้งเตือนให้ผู้ใช้ทราบ
                        });


                    function updateSnapshotInfo() {
                        snapshotCount++;
                        const now = new Date();
                        document.getElementById('lastSnapshotTime').textContent =
                            `เวลาที่ถ่ายภาพล่าสุด: ${now.toLocaleString()}`;
                        document.getElementById('snapshotCount').textContent = `จำนวนภาพที่ถ่าย: ${snapshotCount}`;
                    }

                    // กำหนดค่าความถี่ในการถ่ายภาพ (หน่วยเป็นมิลลิวินาที)
                    const CAPTURE_INTERVAL = 60000; // 1 นาที

                    // เริ่มการถ่ายภาพอัตโนมัติ
                    function startAutomaticCapture() {
                        console.log("เริ่มการถ่ายภาพอัตโนมัติทุก " + (CAPTURE_INTERVAL / 1000) + " วินาที");
                        setInterval(captureSnapshot, CAPTURE_INTERVAL);
                    }

                    // เรียกใช้ฟังก์ชันเมื่อหน้าเว็บโหลดเสร็จ
                    window.addEventListener('load', startAutomaticCapture);

                    function captureSnapshot() {
                        console.log("Attempting to capture snapshot");
                        const iframe = document.querySelector('iframe');
                        if (!iframe) {
                            console.error("iframe not found");
                            return;
                        }

                        let iframeDocument;
                        try {
                            iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
                        } catch (error) {
                            console.error("Cannot access iframe content:", error);
                            return;
                        }

                        const videoElement = iframeDocument.querySelector('video');
                        if (!videoElement) {
                            console.error("Video element not found in iframe");
                            return;
                        }
                    }
                }
            }
        </script>
</body>

</html>
