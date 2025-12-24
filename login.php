<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
    </head>
<body class="login-body">
    <div class="login-card card p-4 p-md-5 rounded-4 shadow-lg fade-in">
        <div class="text-center mb-4">
             <h3 class="fw-light letter-spacing-2 text-white">
                <span class="fw-bold text-premium">STUDIO</span>ONYX
            </h3>
            <p class="text-muted small">Connectez-vous à votre espace.</p>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger py-2 small border-0 bg-danger bg-opacity-10 text-danger text-center mb-3">
                Identifiants incorrects.
            </div>
        <?php endif; ?>

        <form action="auth.php" method="POST">
            <div class="mb-3">
                <label class="form-label text-white-50 small fw-bold">EMAIL</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary border-opacity-25 text-white"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control bg-dark border-secondary border-opacity-25 text-white" placeholder="admin@elite.com" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label text-white-50 small fw-bold">MOT DE PASSE</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary border-opacity-25 text-white"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control bg-dark border-secondary border-opacity-25 text-white" placeholder="admin" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold text-uppercase" style="background: #c5a059; border:none; color: #000;">
                Se connecter
            </button>
        </form>
        
        <div class="text-center mt-4">
            <a href="#" class="text-muted small text-decoration-none">Mot de passe oublié ?</a>
        </div>
    </div>
</body>
</html>