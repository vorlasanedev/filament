<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Employees</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 20px;
            }
        }
    </style>
</head>
<body class="bg-gray-50 p-8" onload="window.print()">
    <div class="max-w-5xl mx-auto bg-white p-8 shadow-sm print:shadow-none print:p-0">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div class="h-16">
                 @include('filament.brand')
            </div>
            <div class="text-right">
                <h1 class="text-2xl font-bold text-gray-800">Employee List</h1>
                <p class="text-sm text-gray-500">Date: {{ now()->format('Y-m-d H:i') }}</p>
                <p class="text-sm text-gray-500">Total: {{ $employees->count() }}</p>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-gray-800">
                    <th class="py-2 font-bold">First Name</th>
                    <th class="py-2 font-bold">Last Name</th>
                    <th class="py-2 font-bold">Email</th>
                    <th class="py-2 font-bold">Phone</th>
                    <th class="py-2 font-bold">Position</th>
                    <th class="py-2 font-bold text-right">Salary</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                    <tr class="border-b border-gray-200">
                        <td class="py-3">{{ $employee->first_name }}</td>
                        <td class="py-3">{{ $employee->last_name }}</td>
                        <td class="py-3 text-sm text-gray-600">{{ $employee->email }}</td>
                        <td class="py-3 text-sm">{{ $employee->phone }}</td>
                        <td class="py-3 text-sm">{{ $employee->position }}</td>
                        <td class="py-3 text-right font-mono">${{ number_format($employee->salary, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="mt-8 pt-4 border-t text-center text-sm text-gray-400">
            <p>Printed from Employee Management System</p>
        </div>
    </div>
</body>
</html>
