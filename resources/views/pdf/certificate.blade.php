<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Participation</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 297mm;
            height: 210mm;
            position: relative;
        }
        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 40px 60px;
            box-sizing: border-box;
        }
        .certificate-border {
            border: 15px solid #FFD700;
            border-radius: 20px;
            padding: 30px;
            background: white;
            height: 100%;
            box-sizing: border-box;
            position: relative;
            box-shadow: inset 0 0 30px rgba(0,0,0,0.1);
        }
        .inner-border {
            border: 3px solid #DAA520;
            padding: 40px;
            height: 100%;
            box-sizing: border-box;
            position: relative;
        }
        .decorative-corner {
            position: absolute;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0.1;
        }
        .corner-tl { top: 0; left: 0; border-radius: 0 0 100% 0; }
        .corner-tr { top: 0; right: 0; border-radius: 0 0 0 100%; }
        .corner-bl { bottom: 0; left: 0; border-radius: 0 100% 0 0; }
        .corner-br { bottom: 0; right: 0; border-radius: 100% 0 0 0; }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .subtitle {
            font-size: 24px;
            color: #764ba2;
            margin: 10px 0 0 0;
            letter-spacing: 3px;
        }
        
        .content {
            text-align: center;
            margin: 40px 0;
        }
        .awarded-to {
            font-size: 20px;
            color: #555;
            margin-bottom: 15px;
            font-style: italic;
        }
        .participant-name {
            font-size: 42px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            padding: 15px 40px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
            min-width: 400px;
        }
        .description {
            font-size: 18px;
            color: #666;
            line-height: 1.8;
            margin: 30px auto;
            max-width: 700px;
        }
        .event-name {
            font-weight: bold;
            color: #764ba2;
            font-size: 22px;
        }
        .category {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 25px;
            border-radius: 20px;
            font-size: 16px;
            margin-top: 10px;
            font-weight: bold;
        }
        
        .footer {
            position: absolute;
            bottom: 60px;
            left: 60px;
            right: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .signature-block {
            text-align: center;
            flex: 1;
        }
        .signature-line {
            border-top: 2px solid #333;
            width: 250px;
            margin: 50px auto 10px;
        }
        .signature-label {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }
        .signature-name {
            font-size: 16px;
            color: #333;
            font-weight: bold;
            margin-top: 5px;
        }
        .date-block {
            text-align: center;
            flex: 1;
        }
        .date-label {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }
        .date-value {
            font-size: 16px;
            color: #333;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .seal {
            position: absolute;
            bottom: 80px;
            right: 100px;
            width: 100px;
            height: 100px;
            border: 5px solid #DAA520;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }
        .seal-text {
            font-size: 12px;
            font-weight: bold;
            color: #667eea;
            text-align: center;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="inner-border">
                <div class="decorative-corner corner-tl"></div>
                <div class="decorative-corner corner-tr"></div>
                <div class="decorative-corner corner-bl"></div>
                <div class="decorative-corner corner-br"></div>
                
                <div class="header">
                    <h1 class="title">Certificate</h1>
                    <p class="subtitle">of Participation</p>
                </div>
                
                <div class="content">
                    <p class="awarded-to">This certificate is proudly presented to</p>
                    
                    <div class="participant-name">{{ $peserta->nama_penuh }}</div>
                    
                    <p class="description">
                        For outstanding participation and contribution in<br>
                        <span class="event-name">{{ $event->title }}</span>
                        <br>
                        @if($event->venue)
                            held at {{ $event->venue }}
                            @if($event->city), {{ $event->city }}@endif
                        @endif
                        @if($event->start_date)
                            <br>on {{ \Carbon\Carbon::parse($event->start_date)->format('F d, Y') }}
                        @endif
                    </p>
                    
                    <div class="category">{{ $category }}</div>
                </div>
                
                <div class="footer">
                    <div class="signature-block">
                        <div class="signature-line"></div>
                        <p class="signature-label">Authorized Signature</p>
                        <p class="signature-name">Event Organizer</p>
                    </div>
                    
                    <div class="date-block">
                        <p class="date-label">Date Issued</p>
                        <p class="date-value">{{ $date }}</p>
                    </div>
                </div>
                
                <div class="seal">
                    <div class="seal-text">
                        OFFICIAL<br>SEAL
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>