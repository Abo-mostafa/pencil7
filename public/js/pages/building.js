  // المسار الأساسي للمجلد الذي يحتوي على ملفات XML
        const basePath = '../assets/building/';
        
        // عنصر الحاوية لعرض المباني
        const buildingsContainer = document.getElementById('buildings-container');
        const floorDetails = document.getElementById('floor-details');
        const floorTitle = document.getElementById('floor-title');
        const roomsContainer = document.getElementById('rooms-container');
        
        // دالة لتحميل ملف XML الفهرس
        async function loadIndexFile() {
            try {
                const response = await fetch(basePath + 'buildings.xml');
                if (!response.ok) {
                    throw new Error('خطأ في تحميل ملف الفهرس');
                }
                const text = await response.text();
                const parser = new DOMParser();
                return parser.parseFromString(text, 'text/xml');
            } catch (error) {
                console.error('فشل في تحميل ملف الفهرس:', error);
                return null;
            }
        }
        
        // دالة لتحليل ملف الفهرس
        function parseIndexData(xmlDoc) {
            if (!xmlDoc) return [];
            
            const buildings = [];
            const buildingElements = xmlDoc.querySelectorAll('building');
            
            buildingElements.forEach(buildingElement => {
                const file = buildingElement.getAttribute('file');
                const name = buildingElement.getAttribute('name');
                
                if (file && name) {
                    buildings.push({
                        file: file,
                        name: name
                    });
                }
            });
            
            return buildings;
        }
        
        // دالة لتحميل ملف XML للمبنى
        async function loadBuildingXMLFile(filename) {
            try {
                const response = await fetch(basePath + filename);
                if (!response.ok) {
                    throw new Error(`خطأ في تحميل الملف: ${filename}`);
                }
                const text = await response.text();
                const parser = new DOMParser();
                return parser.parseFromString(text, 'text/xml');
            } catch (error) {
                console.error(`فشل في تحميل ${filename}:`, error);
                return null;
            }
        }
        
        // دالة لتحليل بيانات المبنى من ملف XML
        function parseBuildingData(xmlDoc) {
            if (!xmlDoc) return null;
            
            const buildingElement = xmlDoc.querySelector('building');
            if (!buildingElement) return null;
            
            const name = buildingElement.querySelector('name')?.textContent || 'غير معروف';
            const floors = parseInt(buildingElement.querySelector('floors')?.textContent) || 0;
            const color = buildingElement.querySelector('color')?.textContent || '#abcce2';
            
            // استخراج بيانات الغرف لكل طابق
            const floorsData = [];
            const floorElements = buildingElement.querySelectorAll('floor');
            
            floorElements.forEach(floorElement => {
                const floorNumber = parseInt(floorElement.getAttribute('number')) || 0;
                const rooms = [];
                
                const roomElements = floorElement.querySelectorAll('room');
                roomElements.forEach(roomElement => {
                    const type = roomElement.getAttribute('type') || 'غير معروف';
                    const capacity = parseInt(roomElement.getAttribute('capacity')) || 0;
                    const roomId = roomElement.textContent || '';
                    
                    rooms.push({
                        type: type,
                        capacity: capacity,
                        id: roomId
                    });
                });
                
                floorsData.push({
                    number: floorNumber,
                    rooms: rooms
                });
            });
            
            return {
                name: name,
                floors: floors,
                color: color,
                floorsData: floorsData
            };
        }
        
        // دالة لإنشاء عنصر HTML للمبنى
        function createBuildingElement(buildingData, buildingInfo) {
            const buildingContainer = document.createElement('div');
            buildingContainer.className = 'building-container';
            buildingContainer.setAttribute('data-building', buildingInfo.name);
            
            // اسم المبنى
            const buildingName = document.createElement('div');
            buildingName.className = 'building-name';
            buildingName.textContent = buildingInfo.name;
            
            // السقف
            const roof = document.createElement('div');
            roof.className = 'building-roof';
            roof.style.backgroundColor = buildingData.color;
            
            // جسم المبنى (الطوابق)
            const buildingBody = document.createElement('div');
            buildingBody.className = 'building-body';
            
            // إنشاء الطوابق من الأعلى إلى الأسفل
            for (let i = buildingData.floors; i >= 1; i--) {
                const floorWrapper = document.createElement('div');
                floorWrapper.className = 'floor-wrapper';
                
                const floor = document.createElement('div');
                floor.className = 'building-floor';
                floor.textContent = `طابق ${i}`;
                floor.setAttribute('data-floor', i);
                floor.setAttribute('data-building', buildingData.name);
                
                // إضافة معلومات الطابق المخفية
                const floorInfo = document.createElement('div');
                floorInfo.className = 'floor-info';
                floorInfo.id = `floor-info-${buildingData.name}-${i}`;
                
                const floorData = buildingData.floorsData.find(f => f.number === i);
                if (floorData && floorData.rooms.length > 0) {
                    floorData.rooms.forEach(room => {
                        const roomElement = document.createElement('div');
                        roomElement.className = 'room-item';
                        roomElement.textContent = `${room.id} (${room.type})`;
                        roomElement.setAttribute('data-room', room.id);
                        roomElement.setAttribute('data-type', room.type);
                        roomElement.setAttribute('data-capacity', room.capacity);
                        
                        roomElement.addEventListener('click', function(e) {
                            e.stopPropagation();
                            showRoomInfo(room, buildingData.name, i);
                        });
                        
                        floorInfo.appendChild(roomElement);
                    });
                } else {
                    const noRooms = document.createElement('div');
                    noRooms.textContent = 'لا توجد غرف في هذا الطابق';
                    noRooms.style.color = '#999';
                    noRooms.style.fontStyle = 'italic';
                    floorInfo.appendChild(noRooms);
                }
                
                // إضافة حدث النقر للطابق
                floor.addEventListener('click', function() {
                    // إزالة النشاط من جميع الطوابق
                    document.querySelectorAll('.building-floor').forEach(f => {
                        f.classList.remove('active');
                    });
                    
                    // إخفاء جميع معلومات الطوابق
                    document.querySelectorAll('.floor-info').forEach(f => {
                        f.classList.remove('active');
                    });
                    
                    // إظهار معلومات هذا الطابق
                    this.classList.add('active');
                    floorInfo.classList.add('active');
                    
                    // إظهار التفاصيل الكاملة للطابق
                    showFloorDetails(floorData, buildingData.name, i);
                });
                
                floorWrapper.appendChild(floor);
                floorWrapper.appendChild(floorInfo);
                buildingBody.appendChild(floorWrapper);
            }
            
            // القاعدة
            const base = document.createElement('div');
            base.className = 'building-base';
            
            // صورة البوابة
            const gateImage = document.createElement('img');
            gateImage.src = '../public/images/gate.avif';
            gateImage.alt = 'بوابة المبنى';
            base.appendChild(gateImage);
            
            // تجميع جميع العناصر
            buildingContainer.appendChild(buildingName);
            buildingContainer.appendChild(roof);
            buildingContainer.appendChild(buildingBody);
            buildingContainer.appendChild(base);
            
            return buildingContainer;
        }
        
        // دالة لعرض تفاصيل الطابق
        function showFloorDetails(floorData, buildingName, floorNumber) {
            floorTitle.textContent = `تفاصيل الطابق ${floorNumber} - مبنى ${buildingName}`;
            roomsContainer.innerHTML = '';
            
            if (floorData && floorData.rooms.length > 0) {
                floorData.rooms.forEach(room => {
                    const roomCard = document.createElement('div');
                    roomCard.className = 'room-card';
                    roomCard.innerHTML = `
                        <strong>${room.id}</strong><br>
                        النوع: ${room.type}<br>
                        السعة: ${room.capacity} شخص
                    `;
                    
                    roomCard.addEventListener('click', function() {
                        showRoomInfo(room, buildingName, floorNumber);
                    });
                    
                    roomsContainer.appendChild(roomCard);
                });
            } else {
                roomsContainer.innerHTML = '<div style="text-align: center; color: #999; font-style: italic;">لا توجد غرف في هذا الطابق</div>';
            }
            
            floorDetails.classList.add('active');
        }
        
        // دالة لعرض معلومات الغرفة في الكونسول
        function showRoomInfo(room, buildingName, floorNumber) {
            console.log('=== معلومات الغرفة ===');
            console.log(`المبنى: ${buildingName}`);
            console.log(`الطابق: ${floorNumber}`);
            console.log(`رقم الغرفة: ${room.id}`);
            console.log(`نوع الغرفة: ${room.type}`);
            console.log(`سعة الغرفة: ${room.capacity} شخص`);
            console.log('====================');
            
            // يمكنك إضافة أي إجراءات إضافية هنا عند النقر على الغرفة
            alert(`تم النقر على الغرفة: ${room.id}\nتفاصيل الغرفة في الكونسول`);
        }
        
        // الدالة الرئيسية لتحميل وعرض جميع المباني
        async function loadAndDisplayBuildings() {
            buildingsContainer.innerHTML = '<div class="loading">جاري تحميل بيانات المباني...</div>';
            
            // تحميل ملف الفهرس
            const indexDoc = await loadIndexFile();
            if (!indexDoc) {
                buildingsContainer.innerHTML = '<div class="error">خطأ في تحميل الفهرس</div>';
                return;
            }
            
            const buildingFiles = parseIndexData(indexDoc);
            if (buildingFiles.length === 0) {
                buildingsContainer.innerHTML = '<div class="no-buildings">لم يتم العثور على مباني</div>';
                return;
            }
            
            buildingsContainer.innerHTML = '';
            
            // تحميل وعرض كل مبنى
            for (const buildingInfo of buildingFiles) {
                const buildingXML = await loadBuildingXMLFile(buildingInfo.file);
                if (buildingXML) {
                    const buildingData = parseBuildingData(buildingXML);
                    if (buildingData) {
                        const buildingElement = createBuildingElement(buildingData, buildingInfo);
                        buildingsContainer.appendChild(buildingElement);
                    }
                }
            }
            
            if (buildingsContainer.children.length === 0) {
                buildingsContainer.innerHTML = '<div class="no-buildings">لم يتم العثور على بيانات للمباني</div>';
            }
        }
        
        // إغلاق تفاصيل الطابق عند النقر خارجها
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.building-floor') && !e.target.closest('.floor-details')) {
                document.querySelectorAll('.building-floor').forEach(f => {
                    f.classList.remove('active');
                });
                
                document.querySelectorAll('.floor-info').forEach(f => {
                    f.classList.remove('active');
                });
                
                floorDetails.classList.remove('active');
            }
        });
        
        // تحميل وعرض المباني عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', loadAndDisplayBuildings);