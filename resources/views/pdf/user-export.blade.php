<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User PDF Export</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0; 
            padding: 0;
        }
        .header {
            background-color: #1a6fb0; /* Blue color */
            color: white;
            padding: 30px;
            width: 100%;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-title-left {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.3;
        }
        .header-title-right {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: right;
            margin-bottom: 10px;
        }
        .contact-info {
            text-align: right;
            font-size: 9px;
            color: #d1e3f8;
        }
        .content {
            padding: 30px 40px;
        }
        .section-title {
            color: #1a6fb0;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 5px;
        }
        .field-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        .field-label {
            font-size: 12px;
            margin-bottom: 4px;
            color: #333;
        }
        .input-box {
            background-color: #dfe6f2; /* Light blue background */
            border: 1px solid #aebfd6; /* Light blue border */
            height: 24px;
            padding: 0 8px;
            line-height: 24px;
            color: #111;
            width: 100%;
            box-sizing: border-box;
        }
        .input-box-large {
            background-color: #dfe6f2;
            border: 1px solid #aebfd6;
            height: 100px;
            padding: 8px;
            color: #111;
            width: 100%;
            box-sizing: border-box;
        }
        .checkbox-group {
            font-size: 12px;
            margin-top: 5px;
        }
        .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #aebfd6;
            background-color: #dfe6f2;
            margin-right: 5px;
            vertical-align: middle;
        }
        .checkbox-label {
            margin-right: 15px;
            vertical-align: middle;
        }
        
        /* Layout utility classes */
        .w-100 { width: 100%; }
        .w-60 { width: 60%; }
        .w-50 { width: 50%; }
        .w-40 { width: 40%; }
        .w-33 { width: 33.33%; }
        .pr-10 { padding-right: 10px; }
        .pl-10 { padding-left: 10px; }
        
    </style>
</head>
<body>

@foreach($users as $index => $user)
<div class="header">
    <table>
        <tr>
            <td width="50%" valign="top">
                <div class="header-title-left">INFORMATION GATHERING<br>FORM</div>
            </td>
            <td width="50%" valign="top">
                <div class="header-title-right">ABC COMPANY</div>
                <div class="contact-info">
                    <table width="100%">
                        <tr>
                            <td align="right" width="50%" style="padding-right: 20px;">
                                Fax: (123) 456-7890<br>
                                Phone: (123) 456-7890
                            </td>
                            <td align="right" width="50%">
                                100 South Ellsworth Avenue Suite<br>
                                504, San Mateo, California 94401
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="content">

    <!-- Personal Information -->
    <div class="section-title">Personal Information</div>
    <table class="w-100" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;">
        <tr>
            <td class="w-60 pr-10" valign="top">
                <div class="field-label">First Name:</div>
                <div class="input-box">{{ explode(' ', $user->name)[0] ?? '' }}</div>
            </td>
            <td class="w-40 pl-10" valign="top">
                <div class="field-label">Date of Birth:</div>
                <div class="input-box"></div>
            </td>
        </tr>
        <tr><td colspan="2" height="10"></td></tr>
        <tr>
            <td class="w-60 pr-10" valign="top">
                <div class="field-label">Middle Name:</div>
                <div class="input-box"></div>
            </td>
            <td class="w-40 pl-10" valign="top">
                <div class="field-label">Social Security Number:</div>
                <div class="input-box"></div>
            </td>
        </tr>
        <tr><td colspan="2" height="10"></td></tr>
        <tr>
            <td class="w-60 pr-10" valign="top">
                <div class="field-label">Last Name:</div>
                <div class="input-box">{{ count(explode(' ', $user->name)) > 1 ? explode(' ', $user->name, 2)[1] : '' }}</div>
            </td>
            <td class="w-40 pl-10" valign="top">
                <div class="field-label">Marital Status:</div>
                <div class="checkbox-group">
                    <span class="checkbox"></span> <span class="checkbox-label">Married</span>
                    <span class="checkbox"></span> <span class="checkbox-label">Single</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Contact Information -->
    <div class="section-title">Contact Information</div>
    <table class="w-100" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;">
        <tr>
            <td colspan="3" valign="top">
                <div class="field-label">Email Address:</div>
                <div class="input-box">{{ $user->email }}</div>
            </td>
        </tr>
        <tr><td colspan="3" height="10"></td></tr>
        <tr>
            <td class="w-33 pr-10" valign="top">
                <div class="field-label">Mobile Phone Number:</div>
                <div class="input-box"></div>
            </td>
            <td class="w-33 pr-10 pl-10" valign="top">
                <div class="field-label">Home Phone Number:</div>
                <div class="input-box"></div>
            </td>
            <td class="w-33 pl-10" valign="top">
                <div class="field-label">Work Phone Number:</div>
                <div class="input-box"></div>
            </td>
        </tr>
    </table>

    <!-- Employer Information -->
    <div class="section-title">Employer Information</div>
    <table class="w-100" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 15px;">
        <tr>
            <td class="w-50 pr-10" valign="top">
                <div class="field-label">Employer Name:</div>
                <div class="input-box"></div>
            </td>
            <td class="w-50 pl-10" valign="top">
                <div class="field-label">Industry:</div>
                <div class="input-box"></div>
            </td>
        </tr>
        <tr><td colspan="2" height="10"></td></tr>
        <tr>
            <td colspan="2" valign="top">
                <div class="field-label">Employer Address:</div>
                <div class="input-box"></div>
            </td>
        </tr>
        <tr><td colspan="2" height="10"></td></tr>
        <tr>
            <td class="w-50 pr-10" valign="top">
                <div class="field-label">Occupation:</div>
                <div class="input-box"></div>
            </td>
            <td class="w-50 pl-10" valign="top">
                <div class="field-label">Annual Income:</div>
                <div class="input-box"></div>
            </td>
        </tr>
        <tr><td colspan="2" height="10"></td></tr>
        <tr>
            <td colspan="2" valign="top">
                <div class="field-label" style="margin-bottom: 8px;">Investment History:</div>
                <div class="checkbox-group">
                    <span class="checkbox"></span> <span class="checkbox-label">1-4 Years</span>
                    <span class="checkbox"></span> <span class="checkbox-label">5-8 Years</span>
                    <span class="checkbox"></span> <span class="checkbox-label">9-12 Years</span>
                    <span class="checkbox"></span> <span class="checkbox-label">13-18 Years</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Notes -->
    <div class="section-title">Notes:</div>
    <div class="input-box-large"></div>

</div>

@if(!$loop->last)
<div style="page-break-after: always;"></div>
@endif
@endforeach

</body>
</html>
