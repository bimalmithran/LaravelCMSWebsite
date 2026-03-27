<?php
require_once __DIR__ . '/bootstrap.php';

// Already logged in → redirect home
if ($loggedIn) {
    header('Location: index.php');
    exit;
}

$error   = '';
$success = '';

// ── Handle Google token posted via hidden form ──────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['google_token'])) {
    $res = $authService->googleLogin(trim($_POST['google_token']));
    if ($res && !empty($res['success'])) {
        $_SESSION['customer_token'] = $res['data']['token'];
        $_SESSION['customer_data']  = $res['data']['customer'];
        header('Location: ' . ($_SESSION['auth_redirect'] ?? 'index.php'));
        unset($_SESSION['auth_redirect']);
        exit;
    }
    $error = $res['message'] ?? 'Google sign-in failed. Please try again.';
}

// ── Handle email / phone + password form ────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['google_token'])) {
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if ($identifier === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        $res = $authService->login($identifier, $password);
        if ($res && !empty($res['success'])) {
            $_SESSION['customer_token'] = $res['data']['token'];
            $_SESSION['customer_data']  = $res['data']['customer'];
            header('Location: ' . ($_SESSION['auth_redirect'] ?? 'index.php'));
            unset($_SESSION['auth_redirect']);
            exit;
        }
        $error = $res['message'] ?? 'Login failed. Please check your credentials.';
    }
}

// ── Fetch header settings for logo ──────────────────────────────────────────
$headerData = $headerService->getHeaderData();
$logoUrl    = $headerData['logo_url'] ?? '';
$siteName   = htmlspecialchars($headerData['site_name'] ?? 'TT Devassy Jewellery', ENT_QUOTES);

// Google client ID (optional – set in config.php to enable Google sign-in)
$googleClientId = $config['google_client_id'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | <?= $siteName ?></title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="shortcut icon" type="image/x-icon"
          href="<?= $logoUrl !== '' ? htmlspecialchars($logoUrl, ENT_QUOTES) : 'assets/images/favicon.ico' ?>">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <?php if ($googleClientId !== ''): ?>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <?php endif; ?>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f5f5f0;
            display: flex;
            align-items: stretch;
        }
        /* ── Brand Panel ── */
        .auth-brand {
            flex: 0 0 42%;
            background: linear-gradient(160deg, #1a1a1a 0%, #2d2316 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
        }
        .auth-brand::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('assets/images/bg/1.jpg') center/cover no-repeat;
            opacity: 0.15;
        }
        .brand-inner { position: relative; text-align: center; }
        .brand-logo { width: 120px; margin-bottom: 2rem; }
        .brand-tagline {
            color: #cda557;
            font-size: 1.1rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .brand-headline {
            color: #fff;
            font-size: 2rem;
            font-weight: 300;
            line-height: 1.3;
            margin-bottom: 1.5rem;
        }
        .brand-divider {
            width: 48px;
            height: 2px;
            background: #cda557;
            margin: 0 auto 1.5rem;
        }
        .brand-sub {
            color: rgba(255,255,255,0.55);
            font-size: 0.88rem;
            line-height: 1.7;
        }
        /* ── Form Panel ── */
        .auth-form-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }
        .auth-card {
            width: 100%;
            max-width: 440px;
        }
        .auth-logo-mobile {
            display: none;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .auth-logo-mobile img { height: 48px; }
        .auth-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.35rem;
        }
        .auth-subtitle {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        /* ── Identifier toggle ── */
        .id-toggle {
            display: flex;
            background: #f0ede6;
            border-radius: 8px;
            padding: 3px;
            margin-bottom: 1.25rem;
            gap: 3px;
        }
        .id-toggle button {
            flex: 1;
            border: none;
            background: transparent;
            border-radius: 6px;
            padding: 0.45rem 0;
            font-size: 0.85rem;
            font-weight: 500;
            color: #888;
            cursor: pointer;
            transition: all 0.2s;
        }
        .id-toggle button.active {
            background: #fff;
            color: #1a1a1a;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }
        /* ── Form fields ── */
        .form-group { margin-bottom: 1rem; }
        .form-label {
            display: block;
            font-size: 0.83rem;
            font-weight: 500;
            color: #444;
            margin-bottom: 0.35rem;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 0.7rem 0.9rem;
            font-size: 0.92rem;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            background: #fff;
            transition: border-color 0.2s;
            outline: none;
            color: #1a1a1a;
        }
        .form-control:focus { border-color: #cda557; box-shadow: 0 0 0 3px rgba(205,165,87,0.15); }
        /* ── Error / Success ── */
        .alert-error {
            background: #fff2f2;
            border: 1px solid #f5c2c2;
            color: #c0392b;
            border-radius: 8px;
            padding: 0.7rem 1rem;
            font-size: 0.87rem;
            margin-bottom: 1.25rem;
        }
        /* ── Submit button ── */
        .btn-primary-gold {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background: #cda557;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }
        .btn-primary-gold:hover { background: #b8903e; }
        /* ── Divider ── */
        .or-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #bbb;
            font-size: 0.82rem;
            margin: 1.5rem 0;
        }
        .or-divider::before, .or-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e5e5;
        }
        /* ── Google button ── */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            width: 100%;
            padding: 0.72rem;
            background: #fff;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #444;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-google:hover { border-color: #cda557; background: #fdfaf3; }
        .btn-google svg { width: 20px; height: 20px; flex-shrink: 0; }
        /* ── Footer link ── */
        .auth-footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.88rem;
            color: #888;
        }
        .auth-footer a { color: #cda557; text-decoration: none; font-weight: 600; }
        .auth-footer a:hover { text-decoration: underline; }
        /* ── Responsive ── */
        @media (max-width: 767px) {
            body { display: block; background: #fff; }
            .auth-brand { display: none; }
            .auth-form-wrap { padding: 2.5rem 1.25rem 3rem; }
            .auth-logo-mobile { display: block; }
        }
    </style>
</head>
<body>

<!-- Brand panel (left, desktop only) -->
<div class="auth-brand">
    <div class="brand-inner">
        <?php if ($logoUrl !== ''): ?>
        <img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES) ?>" alt="<?= $siteName ?>" class="brand-logo">
        <?php endif; ?>
        <p class="brand-tagline">Fine Jewellery</p>
        <h2 class="brand-headline">Crafted for<br>Every Occasion</h2>
        <div class="brand-divider"></div>
        <p class="brand-sub">Discover our curated collections of<br>diamonds, gold, and precious gems.</p>
    </div>
</div>

<!-- Form panel (right) -->
<div class="auth-form-wrap">
    <div class="auth-card">

        <!-- Mobile logo -->
        <div class="auth-logo-mobile">
            <a href="index.php">
                <img src="<?= $logoUrl !== '' ? htmlspecialchars($logoUrl, ENT_QUOTES) : 'assets/images/logob.svg' ?>"
                     alt="<?= $siteName ?>">
            </a>
        </div>

        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-subtitle">Sign in to your account to continue</p>

        <?php if ($error !== ''): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Email / Phone toggle -->
        <div class="id-toggle" id="idToggle">
            <button type="button" class="active" id="tabEmail" onclick="switchTab('email')">Email</button>
            <button type="button" id="tabPhone" onclick="switchTab('phone')">Phone</button>
        </div>

        <!-- Login form -->
        <form method="POST" action="login.php" id="loginForm">
            <div class="form-group">
                <label class="form-label" id="idLabel" for="identifier">Email Address</label>
                <input
                    type="email"
                    id="identifier"
                    name="identifier"
                    class="form-control"
                    placeholder="you@example.com"
                    value="<?= htmlspecialchars($_POST['identifier'] ?? '', ENT_QUOTES) ?>"
                    autocomplete="username"
                    required
                >
            </div>
            <div class="form-group">
                <label class="form-label" for="password">
                    Password
                    <a href="forgot-password.php" style="float:right; font-size:0.8rem; color:#cda557; text-decoration:none;">Forgot password?</a>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
            </div>
            <button type="submit" class="btn-primary-gold">Sign In</button>
        </form>

        <?php if ($googleClientId !== ''): ?>
        <div class="or-divider">or continue with</div>

        <!-- Hidden form for Google token submission -->
        <form method="POST" action="login.php" id="googleForm">
            <input type="hidden" name="google_token" id="googleTokenField">
        </form>

        <button type="button" class="btn-google" id="googleSignInBtn">
            <!-- Google icon -->
            <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            Continue with Google
        </button>
        <?php endif; ?>

        <p class="auth-footer">
            Don't have an account? <a href="register.php">Create one</a>
        </p>
        <p class="auth-footer" style="margin-top:0.5rem;">
            <a href="index.php" style="color:#888; font-weight:400;">← Back to store</a>
        </p>
    </div>
</div>

<script>
function switchTab(mode) {
    const emailBtn = document.getElementById('tabEmail');
    const phoneBtn = document.getElementById('tabPhone');
    const input    = document.getElementById('identifier');
    const label    = document.getElementById('idLabel');

    if (mode === 'email') {
        emailBtn.classList.add('active');
        phoneBtn.classList.remove('active');
        input.type        = 'email';
        input.placeholder = 'you@example.com';
        input.autocomplete = 'email';
        label.textContent = 'Email Address';
    } else {
        phoneBtn.classList.add('active');
        emailBtn.classList.remove('active');
        input.type        = 'tel';
        input.placeholder = '+91 98765 43210';
        input.autocomplete = 'tel';
        label.textContent = 'Phone Number';
        // Remove browser email validation when in phone mode
        input.removeAttribute('required');
        input.setAttribute('required', '');
    }
}

<?php if ($googleClientId !== ''): ?>
// Google Identity Services callback
function handleGoogleCredential(response) {
    document.getElementById('googleTokenField').value = response.credential;
    document.getElementById('googleForm').submit();
}

document.getElementById('googleSignInBtn').addEventListener('click', function () {
    if (typeof google === 'undefined') {
        alert('Google Sign-In is not available. Please try again.');
        return;
    }
    google.accounts.id.initialize({
        client_id: '<?= htmlspecialchars($googleClientId, ENT_QUOTES) ?>',
        callback: handleGoogleCredential,
        auto_select: false,
    });
    google.accounts.id.prompt();
});
<?php endif; ?>
</script>

</body>
</html>
