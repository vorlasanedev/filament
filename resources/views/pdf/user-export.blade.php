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
            background-color: #fca311; /* Orange color */
            color: white;
            padding: 30px;
            width: 100%;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .logo-circle {
            background-color: white;
            color: #111;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            text-align: center;
            font-weight: bold;
            font-size: 36px;
            line-height: 80px;
        }
        .header-title {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: right;
            margin-bottom: 10px;
        }
        .contact-info {
            text-align: right;
            font-size: 11px;
        }
        .content {
            padding: 30px;
        }
        .section-title {
            background-color: #fca311;
            color: white;
            font-weight: bold;
            padding: 8px 15px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .field-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .field-table td {
            padding: 8px 0;
            vertical-align: middle;
        }
        .label-col {
            width: 35%;
            font-size: 12px;
        }
        .colon-col {
            width: 5%;
        }
        .input-col {
            width: 60%;
        }
        .input-box {
            background-color: #e2e8f0;
            border: 1px solid #fca311;
            height: 22px;
            padding: 0 8px;
            line-height: 22px;
            color: #111;
        }
        .main-columns {
            width: 100%;
            border-collapse: collapse;
        }
        .main-columns > tbody > tr > td {
            vertical-align: top;
            width: 48%;
        }
        .col-spacer {
            width: 4%;
        }
        .checkbox-group {
            font-size: 12px;
        }
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #fca311;
            background-color: #e2e8f0;
            margin-right: 5px;
            vertical-align: middle;
        }
        .checkbox-label {
            margin-right: 15px;
            vertical-align: middle;
        }
        .lorem-text {
            font-size: 10px;
            line-height: 1.4;
            margin-bottom: 15px;
            text-align: justify;
        }
        .disposition-section {
            background-color: #fca311;
            color: white;
            font-weight: bold;
            padding: 8px 15px;
            font-size: 14px;
        }
        .disposition-label {
            float: left;
        }
        .disposition-note {
            float: right;
            font-weight: normal;
            font-size: 11px;
            padding-top: 2px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

@foreach($users as $index => $user)
<div class="header">
    <table>
        <tr>
            <td width="20%">
                <div class="logo-circle">1K</div>
            </td>
            <td width="80%" valign="top">
                <div class="header-title">Fillable PDF Form</div>
                <div class="contact-info">
                    <table width="100%">
                        <tr>
                            <td align="right" width="50%" style="padding-right: 20px;">
                                Fax: (123) 456-7890<br>
                                Phone: (123) 456-7890
                            </td>
                            <td align="right" width="50%">
                                123 Main Street Avenue<br>
                                456, New York, NY 10030
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="content">
    <table class="main-columns">
        <tr>
            <!-- LEFT COLUMN -->
            <td>
                <div class="section-title">Your Details</div>
                
                <table class="field-table">
                    <tr>
                        <td class="label-col">Given Name</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box">{{ explode(' ', $user->name)[0] ?? '' }}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Family Name</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box">{{ count(explode(' ', $user->name)) > 1 ? explode(' ', $user->name, 2)[1] : '' }}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Full Address</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box"></div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Relationship<br>to the deceased</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box"></div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Email</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box">{{ $user->email }}</div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Signature</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box"></div></td>
                    </tr>
                </table>

                <div class="section-title">Licensed Funeral Director</div>

                <table class="field-table">
                    <tr>
                        <td class="label-col">Full Name</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box"></div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Full Address</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box"></div></td>
                    </tr>
                    <tr>
                        <td class="label-col">License N</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box"></div></td>
                    </tr>
                </table>
            </td>

            <td class="col-spacer"></td>

            <!-- RIGHT COLUMN -->
            <td>
                <div class="section-title">Details of The Deceased</div>

                <table class="field-table">
                    <tr>
                        <td class="label-col">Given Name</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box"></div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Middle Name</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box"></div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Family Name</td>
                        <td class="colon-col">:</td>
                        <td class="input-col"><div class="input-box"></div></td>
                    </tr>
                    <tr>
                        <td class="label-col">Gender</td>
                        <td class="colon-col">:</td>
                        <td class="input-col">
                            <div class="checkbox-group">
                                <span class="checkbox"></span> <span class="checkbox-label">Male</span>
                                <span class="checkbox"></span> <span class="checkbox-label">Female</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col">Date of birth</td>
                        <td class="colon-col">:</td>
                        <td class="input-col">
                            <span class="checkbox" style="width: 15px; height: 15px;"></span>
                            <span class="checkbox" style="width: 15px; height: 15px;"></span>
                            &nbsp;&nbsp;
                            <span class="checkbox" style="width: 15px; height: 15px;"></span>
                            <span class="checkbox" style="width: 15px; height: 15px;"></span>
                            &nbsp;&nbsp;
                            <span class="checkbox" style="width: 15px; height: 15px;"></span>
                            <span class="checkbox" style="width: 15px; height: 15px;"></span>
                            <span class="checkbox" style="width: 15px; height: 15px;"></span>
                            <span class="checkbox" style="width: 15px; height: 15px;"></span>
                        </td>
                    </tr>
                </table>

                <div class="lorem-text">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea
                </div>

                <table class="field-table" style="margin-bottom: 0;">
                    <tr>
                        <td style="width: 50%;">Driver License?</td>
                        <td style="width: 50%;">
                            <span class="checkbox"></span> Yes &nbsp;&nbsp; <span class="checkbox"></span> No
                        </td>
                    </tr>
                    <tr>
                        <td>Learner Permit?</td>
                        <td>
                            <span class="checkbox"></span> Yes &nbsp;&nbsp; <span class="checkbox"></span> No
                        </td>
                    </tr>
                    <tr>
                        <td>Non-driver ID Card?</td>
                        <td>
                            <span class="checkbox"></span> Yes &nbsp;&nbsp; <span class="checkbox"></span> No
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="disposition-section">
        <div class="disposition-label">Authorized Disposition(S)</div>
        <div class="disposition-note">(Check any that apply)</div>
        <div class="clear"></div>
    </div>
</div>
@if(!$loop->last)
<div style="page-break-after: always;"></div>
@endif
@endforeach

</body>
</html>
