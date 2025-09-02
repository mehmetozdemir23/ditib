<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0d7c66 0%, #0a6b54 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #0d7c66 0%, #0a6b54 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background: #f1f5f9;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        .warning {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>DİTİB</h1>
                <p>Réinitialisation de votre mot de passe</p>
            </div>
            
            <div class="content">
                <p>Bonjour,</p>
                
                <p>Vous avez demandé la réinitialisation de votre mot de passe pour votre compte DİTİB.</p>
                
                <p>Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>
                
                <div style="text-align: center;">
                    <a href="{{ $resetUrl }}" class="button">
                        Réinitialiser mon mot de passe
                    </a>
                </div>
                
                <div class="warning">
                    <strong>Important :</strong> Ce lien expire dans 60 minutes pour des raisons de sécurité.
                </div>
                
                <p>Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email. Votre mot de passe actuel restera inchangé.</p>
                
                <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
                <p style="word-break: break-all; color: #0d7c66;">{{ $resetUrl }}</p>
            </div>
            
            <div class="footer">
                <p>Cet email a été envoyé par DİTİB</p>
                <p>Si vous avez des questions, contactez-nous.</p>
            </div>
        </div>
    </div>
</body>
</html>