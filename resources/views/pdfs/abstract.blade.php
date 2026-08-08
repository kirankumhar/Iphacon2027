<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Abstract Submission - {{ $abstract->acknowledgement_id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1E293B;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #01579B;
            color: #FFFFFF;
            padding: 15px 20px;
            text-align: center;
            border-bottom: 3px solid #0288D1;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 10px;
            margin: 0;
            color: #E0F2FE;
        }
        .ack-bar {
            background-color: #F1F5F9;
            border: 1px solid #CBD5E1;
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .ack-bar td {
            font-size: 11px;
        }
        .section-title {
            background-color: #0288D1;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 11px;
            padding: 5px 10px;
            margin-top: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #64748B;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        .info-value {
            font-weight: bold;
            color: #0F172A;
            font-size: 10.5px;
        }
        .authors-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        .authors-table th, .authors-table td {
            border: 1px solid #CBD5E1;
            padding: 5px 8px;
            text-align: left;
            font-size: 10px;
        }
        .authors-table th {
            background-color: #F8FAFC;
            color: #334155;
            font-weight: bold;
        }
        .content-box {
            border: 1px solid #E2E8F0;
            padding: 8px 12px;
            margin-bottom: 10px;
            background-color: #FAFAFA;
            border-radius: 4px;
        }
        .content-title {
            font-weight: bold;
            color: #01579B;
            font-size: 10.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .content-body {
            font-size: 10.5px;
            color: #1E293B;
            white-space: pre-line;
        }
        .footer {
            margin-top: 25px;
            border-top: 1px solid #CBD5E1;
            padding-top: 8px;
            text-align: center;
            font-size: 9px;
            color: #64748B;
        }
        .badge-mode {
            background-color: #FF6B00;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Header Banner -->
    <div class="header">
        <h1>IPHACON 2027 - Abstract Submission Receipt</h1>
        <p>71st Annual National Conference of Indian Public Health Association | RIMS, Ranchi</p>
    </div>

    <!-- Acknowledgement Bar -->
    <table class="ack-bar" width="100%">
        <tr>
            <td width="50%">
                <div class="info-label">Acknowledgement ID</div>
                <div class="info-value" style="color: #0288D1; font-size: 13px;">{{ $abstract->acknowledgement_id }}</div>
            </td>
            <td width="50%" text-align="right">
                <div class="info-label">Submission Date</div>
                <div class="info-value">{{ $abstract->submitted_at ? $abstract->submitted_at->format('d M Y, h:i A') : date('d M Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- Overview Details -->
    <table class="info-table" width="100%">
        <tr>
            <td width="33%">
                <div class="info-label">Presentation Mode</div>
                <div class="info-value"><span class="badge-mode">{{ $abstract->presentation_mode ?: 'N/A' }}</span></div>
            </td>
            <td width="33%">
                <div class="info-label">Presenter Category</div>
                <div class="info-value">{{ $abstract->presenter_category == 'Other' ? $abstract->other_category_text : $abstract->presenter_category }}</div>
            </td>
            <td width="34%">
                <div class="info-label">Word Count</div>
                <div class="info-value">{{ $abstract->total_word_count ?: 0 }} Words</div>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 6px;">
                <div class="info-label">Conference Sub-Theme</div>
                <div class="info-value" style="color: #01579B;">{{ $abstract->conference_theme }}</div>
            </td>
        </tr>
    </table>

    <!-- Presenting Author Information -->
    <div class="section-title">Presenting Author Information</div>
    <table class="info-table" width="100%">
        <tr>
            <td width="50%">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ $abstract->presenting_author_name }}</div>
            </td>
            <td width="50%">
                <div class="info-label">Designation</div>
                <div class="info-value">{{ $abstract->presenting_author_designation }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="info-label">Department</div>
                <div class="info-value">{{ $abstract->presenting_author_department }}</div>
            </td>
            <td>
                <div class="info-label">Institution / Organization</div>
                <div class="info-value">{{ $abstract->presenting_author_institution }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="info-label">City, State, Country</div>
                <div class="info-value">{{ $abstract->presenting_author_city }}, {{ $abstract->presenting_author_state }}, {{ $abstract->presenting_author_country }}</div>
            </td>
            <td>
                <div class="info-label">Email & Contact</div>
                <div class="info-value">{{ $abstract->presenting_author_email }} | {{ $abstract->presenting_author_mobile }}</div>
            </td>
        </tr>
    </table>

    <!-- Co-Authors List -->
    @if(!empty($abstract->co_authors) && is_array($abstract->co_authors) && count($abstract->co_authors) > 0)
        <div class="section-title">Co-Authors List ({{ count($abstract->co_authors) }})</div>
        <table class="authors-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">Co-Author Name</th>
                    <th width="20%">Designation</th>
                    <th width="25%">Department</th>
                    <th width="25%">Institution</th>
                </tr>
            </thead>
            <tbody>
                @foreach($abstract->co_authors as $index => $coAuthor)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $coAuthor['name'] ?? 'N/A' }}</strong></td>
                        <td>{{ $coAuthor['designation'] ?? 'N/A' }}</td>
                        <td>{{ $coAuthor['department'] ?? 'N/A' }}</td>
                        <td>{{ $coAuthor['institution'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Abstract Content -->
    <div class="section-title">Structured Abstract Content</div>

    <div class="content-box">
        <div class="content-title" style="font-size: 11.5px; color: #000;">Abstract Title:</div>
        <div class="content-body" style="font-weight: bold; font-size: 11px;">{{ $abstract->abstract_title }}</div>
    </div>

    @if($abstract->abstract_background)
    <div class="content-box">
        <div class="content-title">Background:</div>
        <div class="content-body">{{ $abstract->abstract_background }}</div>
    </div>
    @endif

    @if($abstract->abstract_objectives)
    <div class="content-box">
        <div class="content-title">Objectives:</div>
        <div class="content-body">{{ $abstract->abstract_objectives }}</div>
    </div>
    @endif

    @if($abstract->abstract_methodology)
    <div class="content-box">
        <div class="content-title">Methods / Methodology:</div>
        <div class="content-body">{{ $abstract->abstract_methodology }}</div>
    </div>
    @endif

    @if($abstract->abstract_results)
    <div class="content-box">
        <div class="content-title">Results:</div>
        <div class="content-body">{{ $abstract->abstract_results }}</div>
    </div>
    @endif

    @if($abstract->abstract_conclusion)
    <div class="content-box">
        <div class="content-title">Conclusion:</div>
        <div class="content-body">{{ $abstract->abstract_conclusion }}</div>
    </div>
    @endif

    @if($abstract->keywords)
    <div style="margin-top: 10px;">
        <span class="info-label">Keywords: </span>
        <span class="info-value">{{ $abstract->keywords }}</span>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        IPHACON 2027 Scientific Committee | Email: iphacon2027@gmail.com | Website: www.iphacon2027.com
    </div>

</body>
</html>
