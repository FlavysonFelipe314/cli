<!DOCTYPE html>
<html>
<head>
    <title>Pagamento Confirmado - CLIVUS</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
        .header { background-color: #10b981; color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 20px; background-color: white; }
        .success-box { background-color: #d1fae5; border: 1px solid #10b981; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .info-box { background-color: #f0f9ff; border: 1px solid #3b82f6; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #777; text-align: center; }
        .button { display: inline-block; padding: 12px 24px; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>✅ Pagamento Confirmado!</h2>
        </div>
        <div class="content">
            <p>Olá <strong>{{ $user->name }}</strong>,</p>
            
            <div class="success-box">
                <p><strong>Seu pagamento foi confirmado com sucesso!</strong></p>
                <p>Sua assinatura do plano <strong>{{ $subscription->plan->name }}</strong> está agora ativa.</p>
            </div>
            
            <div class="info-box">
                <h3>Detalhes da Assinatura:</h3>
                <ul>
                    <li><strong>Plano:</strong> {{ $subscription->plan->name }}</li>
                    <li><strong>Valor:</strong> R$ {{ number_format($subscription->plan->price, 2, ',', '.') }}</li>
                    <li><strong>Status:</strong> Ativa</li>
                    @if($subscription->next_billing_date)
                    <li><strong>Próxima Cobrança:</strong> {{ $subscription->next_billing_date->format('d/m/Y') }}</li>
                    @endif
                </ul>
            </div>
            
            <p>Agora você tem acesso completo a todos os recursos do CLIVUS!</p>
            
            <p style="text-align: center;">
                <a href="{{ route('finance.accounts.index') }}" class="button">Acessar Sistema</a>
            </p>
            
            <p>Se tiver qualquer dúvida, entre em contato com o suporte.</p>
            
            <p>Atenciosamente,<br>A Equipe CLIVUS</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} CLIVUS. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>

