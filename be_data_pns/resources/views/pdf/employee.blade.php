<!DOCTYPE html>
<html>
<head>
    <title>Employee Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Employee Report</h1>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Unit Kerja</th>
                <th>Pangkat</th>
                <th>Eselon</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Jenis Kelamin</th>
                <th>Tempat, Tanggal Lahir</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $employee->nip }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->work_unit->unit_name ?? '-' }}</td>
                    <td>{{ $employee->work_unit->grade ?? '-' }}</td>
                    <td>{{ $employee->work_unit->echelon ?? '-' }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone_number }}</td>
                    <td>{{ $employee->profile->gender ?? '-' }}</td>
                    <td>
                        {{ $employee->profile->birth_place ?? '-' }},
                        {{ $employee->profile->birth_date ? \Carbon\Carbon::parse($employee->profile->birth_date)->format('d-m-Y') : '-' }}
                    </td>
                    <td>{{ $employee->profile->address ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
