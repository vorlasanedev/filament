<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employees Report</title>
    <style>
        @font-face {
            font-family: 'Phetsarath OT';
            src: url("{{ public_path('fonts/Phetsarath OT.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Phetsarath OT';
            src: url("{{ public_path('fonts/Phetsarath OT_Bold.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'Phetsarath OT';
            src: url("{{ public_path('fonts/Phetsarath OT Italic.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: italic;
        }
        @font-face {
            font-family: 'Phetsarath OT';
            src: url("{{ public_path('fonts/Phetsarath OT_Bold_Italic.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: italic;
        }
        body { font-family: '{{ $fontFamily }}', sans-serif; font-size: 14px; }
        .header { width: 100%; border-bottom: 2px solid #ddd; margin-bottom: 20px; padding-bottom: 10px; }
        .logo { float: left; }
        .invoice-details { float: right; text-align: right; }
        .clearfix::after { content: ""; clear: both; display: table; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #eee; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .total-section { margin-top: 20px; text-align: right; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 12px; text-align: center; color: #888; }
    </style>
</head>
<body>
    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 40px; vertical-align: middle;">
            <span style="font-size: 24px; font-weight: bold; vertical-align: middle; margin-left: 10px;">iFundev</span>
        </div>
        <div class="invoice-details">
            <h2>{{ __('navigation.employee_list') }}</h2>
            <p><strong>{{ __('fields.date') }}:</strong> {{ now()->format('Y-m-d') }}</p>
        </div>
    </div>

    <div>
        <strong>{{ __('fields.report_summary') }}</strong><br>
        {{ __('fields.total_employees') }}: {{ $employees->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('fields.first_name') }}</th>
                <th>{{ __('fields.last_name') }}</th>
                <th>{{ __('fields.email') }}</th>
                <th>{{ __('fields.phone') }}</th>
                <th>{{ __('fields.position') }}</th>
                <th class="text-right">{{ __('fields.salary') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->first_name }}</td>
                    <td>{{ $employee->last_name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td>{{ $employee->position?->name }}</td>
                    <td class="text-right">${{ number_format($employee->salary, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>{{ __('fields.total_salaries') }}:</strong></td>
                <td class="text-right"><strong>${{ number_format($employees->sum('salary'), 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        {{ __('fields.generated_by') }}
    </div>
</body>
</html>
