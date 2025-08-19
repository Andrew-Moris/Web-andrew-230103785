<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>حاسبة المعدل التراكمي - GPA Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            padding: 2rem 0;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        
        .card-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        
        .subject-row {
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 1rem;
            padding: 1rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .subject-row:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.2);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }
        
        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
        }
        
        .btn-danger {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
        }
        
        .result-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            text-align: center;
        }
        
        .gpa-display {
            font-size: 3rem;
            font-weight: bold;
            margin: 1rem 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .classification {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.2);
            border-radius: 25px;
            display: inline-block;
        }
        
        .subjects-table {
            margin-top: 2rem;
        }
        
        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        
        .loading {
            display: none;
        }
        
        .loading.show {
            display: inline-block;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .navbar {
            background: linear-gradient(45deg, #667eea, #764ba2) !important;
        }
        
        .navbar-brand, .nav-link {
            color: white !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-calculator me-2"></i>
                Laravel App
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">الرئيسية</a>
                <a class="nav-link" href="{{ route('dashboard') }}">لوحة التحكم</a>
                <a class="nav-link active" href="{{ route('gpa.index') }}">حاسبة المعدل</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header text-center">
                        <h2 class="mb-0">
                            <i class="fas fa-graduation-cap me-3"></i>
                            حاسبة المعدل التراكمي (GPA Calculator)
                        </h2>
                        <p class="mb-0 mt-2">احسب معدلك التراكمي بدقة وسهولة</p>
                    </div>
                    <div class="card-body">
                        <form id="gpaForm">
                            @csrf
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4><i class="fas fa-book me-2"></i>المواد الدراسية</h4>
                                <div>
                                    <button type="button" class="btn btn-success btn-sm me-2" onclick="addSubject()">
                                        <i class="fas fa-plus"></i> إضافة مادة
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" onclick="loadSampleData()">
                                        <i class="fas fa-lightbulb"></i> بيانات تجريبية
                                    </button>
                                </div>
                            </div>

                            <div id="subjects-container">
                                <!-- Subjects will be added here -->
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <span class="loading spinner-border spinner-border-sm me-2" role="status"></span>
                                    <i class="fas fa-calculator me-2"></i>
                                    احسب المعدل التراكمي
                                </button>
                            </div>
                        </form>

                        <!-- Results Section -->
                        <div id="results" style="display: none;">
                            <div class="result-card fade-in">
                                <h3><i class="fas fa-trophy me-2"></i>نتائج المعدل التراكمي</h3>
                                <div class="gpa-display" id="gpa-value">0.00</div>
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <h5>إجمالي الساعات</h5>
                                        <p class="fs-4" id="total-hours">0</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>النقاط الكلية</h5>
                                        <p class="fs-4" id="total-points">0</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>النسبة المئوية</h5>
                                        <p class="fs-4" id="percentage">0%</p>
                                    </div>
                                </div>
                                <div class="classification" id="classification">تصنيف المعدل</div>
                            </div>

                            <!-- Detailed Results Table -->
                            <div class="subjects-table">
                                <h4 class="mb-3"><i class="fas fa-table me-2"></i>تفاصيل المواد</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>اسم المادة</th>
                                                <th>الدرجة</th>
                                                <th>الساعات المعتمدة</th>
                                                <th>التقدير</th>
                                                <th>النقاط</th>
                                                <th>النقاط الكلية</th>
                                            </tr>
                                        </thead>
                                        <tbody id="results-table">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let subjectCounter = 0;

        // Add initial subject on page load
        document.addEventListener('DOMContentLoaded', function() {
            addSubject();
        });

        function addSubject() {
            subjectCounter++;
            const container = document.getElementById('subjects-container');
            const subjectHtml = `
                <div class="subject-row" id="subject-${subjectCounter}">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">اسم المادة</label>
                            <input type="text" class="form-control" name="subjects[${subjectCounter}][name]" placeholder="مثال: الرياضيات" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">الدرجة (من 100)</label>
                            <input type="number" class="form-control" name="subjects[${subjectCounter}][grade]" min="0" max="100" step="0.1" placeholder="85" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">الساعات المعتمدة</label>
                            <select class="form-control" name="subjects[${subjectCounter}][credit_hours]" required>
                                <option value="">اختر</option>
                                <option value="1">1 ساعة</option>
                                <option value="2">2 ساعة</option>
                                <option value="3">3 ساعات</option>
                                <option value="4">4 ساعات</option>
                                <option value="5">5 ساعات</option>
                                <option value="6">6 ساعات</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm d-block w-100" onclick="removeSubject(${subjectCounter})">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', subjectHtml);
        }

        function removeSubject(id) {
            const subject = document.getElementById(`subject-${id}`);
            if (subject) {
                subject.remove();
            }
            
            // Ensure at least one subject remains
            const container = document.getElementById('subjects-container');
            if (container.children.length === 0) {
                addSubject();
            }
        }

        function loadSampleData() {
            fetch('/gpa/sample-data')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear existing subjects
                        document.getElementById('subjects-container').innerHTML = '';
                        subjectCounter = 0;
                        
                        // Add sample subjects
                        data.sample_subjects.forEach(subject => {
                            addSubject();
                            const currentSubject = document.getElementById(`subject-${subjectCounter}`);
                            currentSubject.querySelector('input[name*="[name]"]').value = subject.name;
                            currentSubject.querySelector('input[name*="[grade]"]').value = subject.grade;
                            currentSubject.querySelector('select[name*="[credit_hours]"]').value = subject.credit_hours;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading sample data:', error);
                    alert('حدث خطأ في تحميل البيانات التجريبية');
                });
        }

        document.getElementById('gpaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const loading = document.querySelector('.loading');
            const submitBtn = document.querySelector('button[type="submit"]');
            
            loading.classList.add('show');
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            const subjects = {};
            
            // Parse form data
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('subjects[')) {
                    const matches = key.match(/subjects\[(\d+)\]\[(\w+)\]/);
                    if (matches) {
                        const index = matches[1];
                        const field = matches[2];
                        
                        if (!subjects[index]) {
                            subjects[index] = {};
                        }
                        subjects[index][field] = value;
                    }
                }
            }
            
            // Convert to array
            const subjectsArray = Object.values(subjects).filter(subject => 
                subject.name && subject.grade && subject.credit_hours
            );
            
            if (subjectsArray.length === 0) {
                alert('يرجى إضافة مادة واحدة على الأقل');
                loading.classList.remove('show');
                submitBtn.disabled = false;
                return;
            }
            
            fetch('/gpa/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ subjects: subjectsArray })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayResults(data);
                } else {
                    alert('حدث خطأ في حساب المعدل');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال بالخادم');
            })
            .finally(() => {
                loading.classList.remove('show');
                submitBtn.disabled = false;
            });
        });

        function displayResults(data) {
            document.getElementById('gpa-value').textContent = data.gpa;
            document.getElementById('total-hours').textContent = data.total_credit_hours;
            document.getElementById('total-points').textContent = data.total_quality_points;
            document.getElementById('percentage').textContent = data.percentage_equivalent + '%';
            document.getElementById('classification').textContent = data.gpa_classification;
            
            // Populate results table
            const tableBody = document.getElementById('results-table');
            tableBody.innerHTML = '';
            
            data.subjects.forEach(subject => {
                const row = `
                    <tr>
                        <td>${subject.name}</td>
                        <td>${subject.grade}%</td>
                        <td>${subject.credit_hours}</td>
                        <td><span class="badge bg-primary">${subject.letter_grade}</span></td>
                        <td>${subject.grade_points}</td>
                        <td>${subject.quality_points}</td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
            
            document.getElementById('results').style.display = 'block';
            document.getElementById('results').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>
