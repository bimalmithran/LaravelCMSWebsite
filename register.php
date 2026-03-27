<?php
require_once __DIR__ . '/bootstrap.php';

// Already logged in → redirect home
if ($loggedIn) {
    header('Location: index.php');
    exit;
}

$error   = '';
$success = '';
$old     = [];

// ── Handle Google token ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['google_token'])) {
    $res = $authService->googleLogin(trim($_POST['google_token']));
    if ($res && !empty($res['success'])) {
        $_SESSION['customer_token'] = $res['data']['token'];
        $_SESSION['customer_data']  = $res['data']['customer'];
        header('Location: index.php');
        exit;
    }
    $error = $res['message'] ?? 'Google sign-in failed. Please try again.';
}

// ── Handle registration form ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['google_token'])) {
    $old = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name'  => trim($_POST['last_name']  ?? ''),
        'email'      => trim($_POST['email']       ?? ''),
        'phone'      => trim($_POST['phone']       ?? ''),
    ];
    $password             = $_POST['password']              ?? '';
    $passwordConfirmation = $_POST['password_confirmation'] ?? '';

    if ($old['first_name'] === '' || $old['last_name'] === '' || $old['email'] === '') {
        $error = 'First name, last name, and email are required.';
    } elseif ($password === '') {
        $error = 'Please enter a password.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $passwordConfirmation) {
        $error = 'Passwords do not match.';
    } else {
        $res = $authService->register(
            $old['first_name'],
            $old['last_name'],
            $old['email'],
            $password,
            $passwordConfirmation,
            $old['phone'] !== '' ? $old['phone'] : null
        );

        if ($res && !empty($res['success'])) {
            $_SESSION['customer_token'] = $res['data']['token'];
            $_SESSION['customer_data']  = $res['data']['customer'];
            header('Location: index.php');
            exit;
        }

        // Laravel returns field-level validation errors
        if (!empty($res['errors'])) {
            $msgs  = array_values(array_merge(...array_values($res['errors'])));
            $error = implode(' ', $msgs);
        } else {
            $error = $res['message'] ?? 'Registration failed. Please try again.';
        }
    }
}

// ── Fetch header settings for logo ─────────────────────────────────────────
$headerData = $headerService->getHeaderData();
$logoUrl    = $headerData['logo_url'] ?? '';
$siteName   = htmlspecialchars($headerData['site_name'] ?? 'TT Devassy Jewellery', ENT_QUOTES);

$googleClientId = $config['google_client_id'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account | <?= $siteName ?></title>
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
        .brand-divider { width: 48px; height: 2px; background: #cda557; margin: 0 auto 1.5rem; }
        .brand-sub { color: rgba(255,255,255,0.55); font-size: 0.88rem; line-height: 1.7; }

        .auth-form-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            overflow-y: auto;
        }
        .auth-card { width: 100%; max-width: 460px; }

        .auth-logo-mobile { display: none; text-align: center; margin-bottom: 1.5rem; }
        .auth-logo-mobile img { height: 48px; }

        .auth-title { font-size: 1.75rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.35rem; }
        .auth-subtitle { color: #888; font-size: 0.9rem; margin-bottom: 1.75rem; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }

        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 0.83rem; font-weight: 500; color: #444; margin-bottom: 0.35rem; }
        .form-label .optional { color: #bbb; font-weight: 400; font-size: 0.78rem; }
        .form-control {
            display: block; width: 100%;
            padding: 0.7rem 0.9rem;
            font-size: 0.92rem;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            background: #fff;
            transition: border-color 0.2s;
            outline: none; color: #1a1a1a;
        }
        .form-control:focus { border-color: #cda557; box-shadow: 0 0 0 3px rgba(205,165,87,0.15); }

        .alert-error {
            background: #fff2f2; border: 1px solid #f5c2c2; color: #c0392b;
            border-radius: 8px; padding: 0.7rem 1rem; font-size: 0.87rem; margin-bottom: 1.25rem;
        }

        .btn-primary-gold {
            display: block; width: 100%; padding: 0.8rem;
            background: #cda557; color: #fff;
            font-size: 0.95rem; font-weight: 600; letter-spacing: 0.04em;
            border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; margin-top: 0.25rem;
        }
        .btn-primary-gold:hover { background: #b8903e; }

        .terms-note { font-size: 0.78rem; color: #aaa; text-align: center; margin-top: 0.75rem; line-height: 1.5; }
        .terms-note a { color: #cda557; text-decoration: none; }

        .or-divider {
            display: flex; align-items: center; gap: 0.75rem;
            color: #bbb; font-size: 0.82rem; margin: 1.25rem 0;
        }
        .or-divider::before, .or-divider::after { content: ''; flex: 1; height: 1px; background: #e5e5e5; }

        .btn-google {
            display: flex; align-items: center; justify-content: center; gap: 0.65rem;
            width: 100%; padding: 0.72rem; background: #fff;
            border: 1.5px solid #ddd; border-radius: 8px;
            font-size: 0.9rem; font-weight: 500; color: #444; cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-google:hover { border-color: #cda557; background: #fdfaf3; }
        .btn-google svg { width: 20px; height: 20px; flex-shrink: 0; }

        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: #888; }
        .auth-footer a { color: #cda557; text-decoration: none; font-weight: 600; }
        .auth-footer a:hover { text-decoration: underline; }

        @media (max-width: 767px) {
            body { display: block; background: #fff; }
            .auth-brand { display: none; }
            .auth-form-wrap { padding: 2.5rem 1.25rem 3rem; }
            .auth-logo-mobile { display: block; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="auth-brand">
    <div class="brand-inner">
        <?php if ($logoUrl !== ''): ?>
        <img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES) ?>" alt="<?= $siteName ?>" class="brand-logo">
        <?php endif; ?>
        <p class="brand-tagline">Fine Jewellery</p>
        <h2 class="brand-headline">Join Our<br>Community</h2>
        <div class="brand-divider"></div>
        <p class="brand-sub">Create an account to track orders,<br>save favourites, and enjoy exclusive offers.</p>
    </div>
</div>

<div class="auth-form-wrap">
    <div class="auth-card">

        <div class="auth-logo-mobile">
            <a href="index.php">
                <img src="<?= $logoUrl !== '' ? htmlspecialchars($logoUrl, ENT_QUOTES) : 'assets/images/logob.svg' ?>"
                     alt="<?= $siteName ?>">
            </a>
        </div>

        <h1 class="auth-title">Create account</h1>
        <p class="auth-subtitle">It's free and takes less than a minute</p>

        <?php if ($error !== ''): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control"
                           placeholder="First" value="<?= htmlspecialchars($old['first_name'] ?? '', ENT_QUOTES) ?>"
                           autocomplete="given-name" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control"
                           placeholder="Last" value="<?= htmlspecialchars($old['last_name'] ?? '', ENT_QUOTES) ?>"
                           autocomplete="family-name" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="you@example.com" value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES) ?>"
                       autocomplete="email" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">
                    Phone Number <span class="optional">(optional)</span>
                </label>
                <input type="tel" id="phone" name="phone" class="form-control"
                       placeholder="+91 98765 43210" value="<?= htmlspecialchars($old['phone'] ?? '', ENT_QUOTES) ?>"
                       autocomplete="tel">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Min. 6 characters" autocomplete="new-password" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control" placeholder="Repeat password" autocomplete="new-password" required>
                </div>
            </div>

            <button type="submit" class="btn-primary-gold">Create Account</button>
            <p class="terms-note">
                By creating an account, you agree to our
                <a href="pages/terms-conditions">Terms &amp; Conditions</a> and
                <a href="pages/privacy-policy">Privacy Policy</a>.
            </p>
        </form>

        <?php if ($googleClientId !== ''): ?>
        <div class="or-divider">or sign up with</div>

        <form method="POST" action="register.php" id="googleForm">
            <input type="hidden" name="google_token" id="googleTokenField">
        </form>

        <button type="button" class="btn-google" id="googleSignInBtn">
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
            Already have an account? <a href="login.php">Sign in</a>
        </p>
        <p class="auth-footer" style="margin-top:0.5rem;">
            <a href="index.php" style="color:#888; font-weight:400;">← Back to store</a>
        </p>
    </div>
</div>

<?php if ($googleClientId !== ''): ?>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
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
</script>
<?php endif; ?>

</body>
</html>
