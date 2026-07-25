<!DOCTYPE html>
<html>

<head>
    <title>Student Registration Confirmation - {{ $applicationNumber }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 9pt;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            border: 2px solid #000;
        }

        .header {
            border-bottom: 2px solid #000;
            position: relative;
            min-height: 80px;
        }

        .header-logo {
            float: left;
            width: 80px;
            height: 80px;
            margin-left: 15px;
        }

        .header-content {
            text-align: center;
            padding-top: 2px;
        }

        .school-name {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        .school-type {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
            color: #333;
        }

        .affiliation {
            font-size: 9pt;
            margin: 2px 0;
            color: #666;
        }

        .school-code {
            font-size: 9pt;
            font-weight: bold;
            margin: 2px 0;
            color: #333;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .contact-info {
            text-align: center;
            font-size: 7pt;
            padding: 5px;
            color: #555;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        .section {
            margin-bottom: 2px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #e8e8e8;
            padding: 5px;
            font-weight: bold;
            font-size: 10pt;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table tr {
            border-bottom: 1px solid #ddd;
        }

        .details-table th,
        .details-table td {
            padding: 4px 6px;
            text-align: left;
            vertical-align: top;
            font-size: 9pt;
        }

        .details-table th {
            width: 25%;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .details-table td {
            width: 25%;
        }

        .photo-section {
            border: 1px solid #ddd;
            padding: 2px;
        }

        .document-image {
            display: block;
            margin: 20px auto;
            max-width: 60%;
            max-height: 40vh;
            height: auto;
            width: auto;
            border: 1px solid #ddd;
            padding: 5px;
            object-fit: contain;
            page-break-inside: avoid;
        }

        .document-section {
            page-break-before: always;
            padding: 10px;
        }

        .document-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 5px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding: 5px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header with Logo -->
        <div class="header clearfix">
            <img src="{{ public_path('assets/admin/assets/img/logo.png') }}" class="header-logo" alt="School Logo">
            <div class="header-content">
                <h1 class="school-name">Bishop Westcott Boys' School</h1>
                <p class="school-type">Residential and Day School, Affiliated to CISCE - New Delhi, School Code: JH028
                </p>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="contact-info">
            Campus: Namkum - 824010, Ranchi, Jharkhand Campus | Ph: +91-8092065002 | Email: bwbs_namkum@yahoo.co.in |
            Web:
            www.bishopwestcottboysschool.com
        </div>

        <!-- Student Registration Title -->
        <div
            style="background-color: #1a73e8; color: white; text-align: center; padding: 4px; font-size: 10pt; font-weight: bold;">
            STUDENT REGISTRATION CONFIRMATION
        </div>

        <div class="section">
            <div class="section-title">Student Information</div>

            <table class="details-table">
                <tr>
                    <th>Application Number</th>
                    <td>{{ $applicationNumber }}</td>
                    <th>Session</th>
                    <td colspan="2" style="text-align: left;">{{ $student->session_year }}</td>
                    <td rowspan="4" style="vertical-align: top; text-align: right;">
                        @if ($student->stu_photo_path)
                            <img src="{{ storage_path('app/public/' . $student->stu_photo_path) }}" alt="Student Photo"
                                class="photo-section" width="120px" height="150px" style="display: inline-block;" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Applied for Class</th>
                    <td>{{ $student->apply_class }}</td>
                    <th style="width: 170px">Seeking for Admission</th>
                    <td>{{ $student->seeking_admission }}</td>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <td colspan="3">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->surname }}
                    </td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td>{{ \Carbon\Carbon::parse($student->dob)->format('d F Y') }}</td>
                    <th style="width: 170px">Place of Birth</th>
                    <td colspan="3">{{ $student->birth_place }}</td>
                </tr>
                <tr>
                    <th>Nationality</th>
                    <td>{{ $student->nationality }}</td>
                    <th>Religion</th>
                    <td colspan="3">
                        {{ $student->religion == 'Other' ? $student->other_religion : $student->religion }}
                    </td>
                </tr>
                <tr>
                    <th style="width: 120px">Mother Tongue</th>
                    <td>{{ $student->mother_tongue }}</td>
                    <th style="width: 170px">Language spoken at home</th>
                    <td colspan="3">{{ $student->home_language }}</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>{{ $student->category }}</td>
                    <th>Aadhaar Number</th>
                    <td colspan="3">{{ $student->aadhaar }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ $student->gender }}</td>
                    <th>Blood Group</th>
                    <td colspan="3">{{ $student->blood_group }}</td>
                </tr>
            </table>
        </div>

        <!-- Educational Details -->
        <div class="section">
            <div class="section-title">Educational Details</div>
            <table class="details-table">
                <tr>
                    <th>Last Class Studied</th>
                    <td>{{ $student->last_class }}</td>

                    <th>Medium of Instruction</th>
                    <td>{{ $student->instruction_medium }}</td>
                </tr>
                <tr>
                    <th> Last Institution Attended</th>
                    <td colspan="3">{{ $student->last_institution }}</td>
                </tr>
                <tr>
                    <th>PEN No.</th>
                    <td>{{ $student->pen_no ?? 'N/A' }}</td>

                    <th>PEN No. Issue Year</th>
                    <td>{{ $student->pen_issue_year ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <!-- Parents Details -->
        <div class="section">
            <div class="section-title">Parents Details</div>
            <table class="details-table">
                <tr>
                    <th colspan="2" style="background-color: #f4f4f4; text-align: center;">Father's Information</th>
                    <th colspan="2" style="background-color: #f4f4f4; text-align: center;">Mother's Information</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $student->father_prefix }} {{ $student->father_name }}</td>

                    <th>Name</th>
                    <td> {{ $student->mother_prefix }} {{ $student->mother_name }}</td>
                </tr>
                <tr>
                    <th>Qualification</th>
                    <td>{{ $student->father_qualification ?? 'N/A' }}</td>

                    <th>Qualification</th>
                    <td>{{ $student->mother_qualification ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Occupation</th>
                    <td>{{ $student->father_occupation }}</td>

                    <th>Occupation</th>
                    <td>{{ $student->mother_occupation }}</td>
                </tr>
                <tr>
                    <th>Aadhaar No.</th>
                    <td>{{ $student->father_aadhaar }}</td>

                    <th>Aadhaar No.</th>
                    <td>{{ $student->mother_aadhaar }}</td>
                </tr>
                <tr>
                    <th>Contact</th>
                    <td>
                        Mobile: {{ $student->father_mobile ?? 'N/A' }}<br>
                        Email: {{ $student->father_email ?? 'N/A' }}
                    </td>

                    <th>Contact</th>
                    <td>
                        Mobile: {{ $student->mother_mobile ?? 'N/A' }}<br>
                        Email: {{ $student->mother_email ?? 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <th>Alternate/WhatsApp</th>
                    <td>{{ $student->father_whatsapp ?? 'N/A' }}</td>

                    <th>Alternate/WhatsApp</th>
                    <td>{{ $student->mother_whatsapp ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <!-- Address -->
        <div class="section">
            <div class="section-title">Address</div>
            <table class="details-table" style="width: 100%; border-collapse: collapse">
                <tr>
                    <th colspan="2" style="background-color: #f4f4f4; text-align: center;">Correspondence Address
                    </th>
                    <th colspan="2" style="background-color: #f4f4f4; text-align: center;">Permanent Address</th>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 11px"> {{ $student->corr_postal_address }}, District -
                        {{ $student->corr_district }}, Pin Code -
                        {{ $student->corr_pin_code }}, {{ $student->corr_state_name }}
                    </td>
                    <td colspan="2" style="font-size: 11px"> {{ $student->perm_postal_address }}, District -
                        {{ $student->perm_district }}, Pin Code -
                        {{ $student->perm_pin_code }}, {{ $student->perm_state_name }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Payment Details -->
        <div class="section">
            <div class="section-title">Payment Details</div>
            <table class="details-table">
                <tr>
                    <th style="font-size: 10px">Transaction ID :
                        {{ $student->transaction_id }}
                    </th>
                    <th style="font-size: 10px">Payment Date :
                        {{ date('d-M-Y', strtotime($student->transaction_date)) }}
                    </th>
                    <th style="font-size: 10px">Amount :
                        {{ $student->transaction_total_amount }}
                    </th>
                </tr>
            </table>
        </div>

        <!-- Document Upload Status -->
        <div class="section">
            <div class="section-title">Document Uploaded Details</div>
            <table class="details-table">
                <tr>
                    <th style="font-size: 8px">Student Passport Photo :
                        {{ $student->stu_photo_path ? 'Yes' : 'No' }}
                    </th>
                    <th style="font-size: 8px">Student Aadhaar Photo :
                        {{ $student->aadhaar_document_path ? 'Yes' : 'No' }}
                    </th>
                    <th style="font-size: 8px">Student Birth Certificate Photo :
                        {{ $student->birth_certificate_path ? 'Yes' : 'No' }}
                    </th>
                    <th style="font-size: 8px">Student Payment Receipt Photo :
                        {{ $student->receipt_path ? 'Yes' : 'No' }}
                    </th>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>
                Registration Form Generated on:
                {{ \Carbon\Carbon::parse($student->created_at)->format('d F Y H:i A') }} |
                Application No: {{ $applicationNumber }}
            </p>
        </div>
    </div>

    <!-- Page 2: Aadhaar Card -->
    @if ($student->aadhaar_document_path)
        <div class="document-section">
            <div class="document-title">AADHAAR CARD - {{ $student->first_name }} {{ $student->surname }}</div>
            <img src="{{ storage_path('app/public/' . $student->aadhaar_document_path) }}" alt="Aadhaar Card"
                class="document-image">
            <div style="text-align: center; margin-top: 10px; font-size: 8pt; color: #666;">
                Application No: {{ $applicationNumber }} | Student: {{ $student->first_name }}
                {{ $student->surname }}
            </div>
        </div>
    @endif

    <!-- Page 3: Birth Certificate -->
    @if ($student->birth_certificate_path)
        <div class="document-section">
            <div class="document-title">BIRTH CERTIFICATE - {{ $student->first_name }} {{ $student->surname }}</div>
            <img src="{{ storage_path('app/public/' . $student->birth_certificate_path) }}" alt="Birth Certificate"
                class="document-image">
            <div style="text-align: center; margin-top: 10px; font-size: 8pt; color: #666;">
                Application No: {{ $applicationNumber }} | Student: {{ $student->first_name }}
                {{ $student->surname }}
            </div>
        </div>
    @endif

    <!-- Page 4: Payment Receipt -->
    @if ($student->receipt_path)
        <div class="document-section">
            <div class="document-title">PAYMENT RECEIPT - {{ $student->first_name }} {{ $student->surname }}</div>
            <img src="{{ storage_path('app/public/' . $student->receipt_path) }}" alt="Payment Receipt"
                class="document-image">
            <div style="text-align: center; margin-top: 10px; font-size: 10pt; color: #666;">
                Application No: {{ $applicationNumber }} | Student: {{ $student->first_name }}
                {{ $student->surname }} | Transaction ID : {{ $student->transaction_id }}
            </div>
        </div>
    @endif
</body>

</html>