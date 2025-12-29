<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite para Equipe - CLIVUS</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            color: #666;
            font-size: 12px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CLIVUS</div>
            <h1>Convite para Equipe</h1>
        </div>
        
        <div class="content">
            <p>Olá,</p>
            
            <p>Você foi convidado(a) para fazer parte da equipe de <strong>{{ $invitation->owner->name }}</strong> no CLIVUS.</p>
            
            @if($invitation->teamMember)
            <p><strong>Informações do convite:</strong></p>
            <ul>
                <li><strong>Nome:</strong> {{ $invitation->teamMember->name }}</li>
                <li><strong>Cargo:</strong> {{ $invitation->teamMember->position ?? 'Não informado' }}</li>
                <li><strong>Tipo de Vínculo:</strong> {{ $invitation->teamMember->employment_type }}</li>
            </ul>
            @endif
            
            <div class="warning">
                <strong>⚠️ Importante:</strong> Este convite expira em {{ $invitation->expires_at->diffForHumans() }}.
            </div>
            
            <p>Para aceitar o convite e fazer parte da equipe, clique no botão abaixo:</p>
            
            <div style="text-align: center;">
                <a href="{{ route('team.invitation.accept', $invitation->token) }}" class="button">
                    Aceitar Convite
                </a>
            </div>
            
            <p>Ou copie e cole o link abaixo no seu navegador:</p>
            <p style="word-break: break-all; color: #667eea;">
                {{ route('team.invitation.accept', $invitation->token) }}
            </p>
            
            <p>Se você não solicitou este convite, pode ignorar este email.</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} CLIVUS. Todos os direitos reservados.</p>
            <p>Este é um email automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>

