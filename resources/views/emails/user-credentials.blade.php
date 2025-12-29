<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciais de Acesso - CLIVUS</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">CLIVUS</h1>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1f2937; margin-top: 0;">Bem-vindo ao CLIVUS!</h2>
        
        <p>Suas credenciais de acesso foram criadas com sucesso:</p>
        
        <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #8b5cf6;">
            <p style="margin: 10px 0;"><strong>Email:</strong> {{ $user->email }}</p>
            <p style="margin: 10px 0;"><strong>Senha:</strong> {{ $password }}</p>
        </div>
        
        <p style="color: #ef4444; font-weight: bold;">⚠️ Por segurança, altere sua senha após o primeiro acesso.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/login') }}" style="display: inline-block; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                Acessar Sistema
            </a>
        </div>
        
        <p style="color: #6b7280; font-size: 14px; margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
            Se você não solicitou esta conta, por favor ignore este email.
        </p>
    </div>
</body>
</html>

