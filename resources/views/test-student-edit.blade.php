<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Student Edit Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Student Edit Form</h2>
        
        <div class="card">
            <div class="card-header">
                <h5>Student Data Debug</h5>
            </div>
            <div class="card-body">
                @if(isset($student))
                    <p><strong>ID:</strong> {{ $student->id }}</p>
                    <p><strong>Student Number:</strong> {{ $student->student_number }}</p>
                    <p><strong>Name:</strong> {{ $student->name }}</p>
                    <p><strong>Gender:</strong> {{ $student->gender }}</p>
                    <p><strong>Birth Date (Raw):</strong> {{ $student->birth_date }}</p>
                    <p><strong>Birth Date (Formatted):</strong> {{ $student->birth_date ? $student->birth_date->format('Y-m-d') : 'N/A' }}</p>
                @else
                    <p>No student data found.</p>
                @endif
            </div>
        </div>

        @if(isset($student))
        <div class="card mt-4">
            <div class="card-header">
                <h5>Edit Form Test</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_number" class="form-label">Student Number</label>
                            <input type="text" class="form-control" 
                                   id="student_number" name="student_number" 
                                   value="{{ old('student_number', $student->student_number) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" 
                                   id="name" name="name" 
                                   value="{{ old('name', $student->name) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('gender', $student->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender', $student->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="birth_date" class="form-label">Birth Date</label>
                            <input type="date" class="form-control" 
                                   id="birth_date" name="birth_date" 
                                   value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
