<?php
require_once __DIR__ . '/bootstrap.php';

// Require authentication
if (!$loggedIn) {
    $_SESSION['auth_redirect'] = 'my-account.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
    header('Location: login.php');
    exit;
}

$token    = $_SESSION['customer_token'];
$customer = $_SESSION['customer_data'] ?? [];

// Handle profile update form submission
$profileSuccess = '';
$profileError   = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['_action']) && $_POST['_action'] === 'update_profile') {
    $payload = [];
    foreach (['first_name', 'last_name', 'phone', 'billing_address', 'shipping_address', 'city', 'state', 'postal_code', 'country'] as $field) {
        if (isset($_POST[$field])) {
            $payload[$field] = trim($_POST[$field]);
        }
    }
    $res = $apiClient->putWithAuth('auth/profile', $payload, $token);
    if ($res && !empty($res['success'])) {
        $customer = $res['data'];
        $_SESSION['customer_data'] = $customer;
        $profileSuccess = 'Profile updated successfully.';
    } else {
        $profileError = $res['message'] ?? 'Failed to update profile. Please try again.';
    }
}

// Fetch orders
$ordersData = $apiClient->getWithAuth('orders', [], $token);
$orders     = $ordersData['data'] ?? ($ordersData ?? []);
if (isset($orders['data'])) {
    $orders = $orders['data']; // unwrap paginated
}

$activeTab  = $_GET['tab'] ?? 'dashboard';
$pageTitle  = 'My Account || TT Devassy Jewellery';
require_once __DIR__ . '/templates/header-inner.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <h2>My Account</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li class="active">My Account</li>
            </ul>
        </div>
    </div>
</div>

<!-- Account Page Area -->
<div class="account-page-area">
    <div class="container">
        <div class="row">
            <!-- Sidebar Nav -->
            <div class="col-lg-3">
                <ul class="nav myaccount-tab-trigger" id="account-page-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'dashboard' ? 'active' : '' ?>"
                           data-bs-toggle="tab" href="#account-dashboard" role="tab">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'orders' ? 'active' : '' ?>"
                           data-bs-toggle="tab" href="#account-orders" role="tab">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'details' ? 'active' : '' ?>"
                           data-bs-toggle="tab" href="#account-details" role="tab">Account Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="col-lg-9">
                <div class="tab-content myaccount-tab-content" id="account-page-tab-content">

                    <!-- Dashboard -->
                    <div class="tab-pane fade <?= $activeTab === 'dashboard' ? 'show active' : '' ?>"
                         id="account-dashboard" role="tabpanel">
                        <div class="myaccount-dashboard">
                            <p>Hello <b><?= htmlspecialchars(trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''))) ?></b>
                                (not you? <a href="logout.php">Sign out</a>)</p>
                            <p>From your account dashboard you can view your recent orders and
                                <a href="javascript:void(0)" data-bs-toggle="tab" data-bs-target="#account-details">edit your account details</a>.</p>
                        </div>
                    </div>

                    <!-- Orders -->
                    <div class="tab-pane fade <?= $activeTab === 'orders' ? 'show active' : '' ?>"
                         id="account-orders" role="tabpanel">
                        <div class="myaccount-orders">
                            <h4 class="small-title">MY ORDERS</h4>
                            <?php if (empty($orders)): ?>
                            <p>You have no orders yet. <a href="shop.php">Start shopping!</a></p>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th>ORDER #</th>
                                            <th>DATE</th>
                                            <th>STATUS</th>
                                            <th>TOTAL</th>
                                        </tr>
                                        <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><strong>#<?= (int) ($order['id'] ?? 0) ?></strong></td>
                                            <td><?= htmlspecialchars(substr($order['created_at'] ?? '', 0, 10)) ?></td>
                                            <td><?= htmlspecialchars(ucfirst($order['status'] ?? 'pending')) ?></td>
                                            <td><?= $currencySymbol ?><?= number_format((float) ($order['total'] ?? 0), 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Account Details -->
                    <div class="tab-pane fade <?= $activeTab === 'details' ? 'show active' : '' ?>"
                         id="account-details" role="tabpanel">
                        <div class="myaccount-details">
                            <?php if ($profileSuccess !== ''): ?>
                            <div class="alert alert-success" style="padding:10px 15px;margin-bottom:15px;background:#d4edda;border:1px solid #c3e6cb;border-radius:4px;color:#155724;">
                                <?= htmlspecialchars($profileSuccess) ?>
                            </div>
                            <?php endif; ?>
                            <?php if ($profileError !== ''): ?>
                            <div class="alert alert-danger" style="padding:10px 15px;margin-bottom:15px;background:#f8d7da;border:1px solid #f5c6cb;border-radius:4px;color:#721c24;">
                                <?= htmlspecialchars($profileError) ?>
                            </div>
                            <?php endif; ?>
                            <form action="my-account.php?tab=details" method="POST" class="hiraola-form">
                                <input type="hidden" name="_action" value="update_profile">
                                <div class="hiraola-form-inner">
                                    <div class="single-input single-input-half">
                                        <label for="acc-first-name">First Name</label>
                                        <input type="text" id="acc-first-name" name="first_name"
                                               value="<?= htmlspecialchars($customer['first_name'] ?? '', ENT_QUOTES) ?>">
                                    </div>
                                    <div class="single-input single-input-half">
                                        <label for="acc-last-name">Last Name</label>
                                        <input type="text" id="acc-last-name" name="last_name"
                                               value="<?= htmlspecialchars($customer['last_name'] ?? '', ENT_QUOTES) ?>">
                                    </div>
                                    <div class="single-input">
                                        <label for="acc-email">Email</label>
                                        <input type="email" id="acc-email"
                                               value="<?= htmlspecialchars($customer['email'] ?? '', ENT_QUOTES) ?>"
                                               disabled style="background:#f5f5f5;cursor:not-allowed;">
                                        <small style="color:#888;">Email cannot be changed here.</small>
                                    </div>
                                    <div class="single-input">
                                        <label for="acc-phone">Phone</label>
                                        <input type="text" id="acc-phone" name="phone"
                                               value="<?= htmlspecialchars($customer['phone'] ?? '', ENT_QUOTES) ?>">
                                    </div>
                                    <div class="single-input">
                                        <label for="acc-billing">Billing Address</label>
                                        <input type="text" id="acc-billing" name="billing_address"
                                               value="<?= htmlspecialchars($customer['billing_address'] ?? '', ENT_QUOTES) ?>">
                                    </div>
                                    <div class="single-input">
                                        <label for="acc-shipping">Shipping Address</label>
                                        <input type="text" id="acc-shipping" name="shipping_address"
                                               value="<?= htmlspecialchars($customer['shipping_address'] ?? '', ENT_QUOTES) ?>">
                                    </div>
                                    <div class="single-input single-input-half">
                                        <label for="acc-city">City</label>
                                        <input type="text" id="acc-city" name="city"
                                               value="<?= htmlspecialchars($customer['city'] ?? '', ENT_QUOTES) ?>">
                                    </div>
                                    <div class="single-input single-input-half">
                                        <label for="acc-state">State</label>
                                        <input type="text" id="acc-state" name="state"
                                               value="<?= htmlspecialchars($customer['state'] ?? '', ENT_QUOTES) ?>">
                                    </div>
                                    <div class="single-input single-input-half">
                                        <label for="acc-postal">Postal Code</label>
                                        <input type="text" id="acc-postal" name="postal_code"
                                               value="<?= htmlspecialchars($customer['postal_code'] ?? '', ENT_QUOTES) ?>">
                                    </div>
                                    <div class="single-input single-input-half">
                                        <label for="acc-country">Country</label>
                                        <input type="text" id="acc-country" name="country"
                                               value="<?= htmlspecialchars($customer['country'] ?? '', ENT_QUOTES) ?>">
                                    </div>
                                    <div class="single-input">
                                        <button class="hiraola-btn hiraola-btn_dark" type="submit">
                                            <span>SAVE CHANGES</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Account Page Area End -->

<?php require_once __DIR__ . '/templates/footer.php'; ?>
