<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout & Store Launch — Mature Nature SaaS</title>
  
  <!-- Google Fonts & FontAwesome -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --primary: #ff5a2c;
      --primary-hover: #e0451a;
      --primary-soft: rgba(255, 90, 44, 0.08);
      --dark: #0e1b3d;
      --dark-card: #152347;
      --text: #1e293b;
      --muted: #64748b;
      --border: #e2e8f0;
      --bg: #f8fafc;
      --white: #ffffff;
      --success: #10b981;
      --radius: 16px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
    }

    /* Top Banner / Navbar */
    .mn-header {
      background: var(--dark);
      padding: 18px 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .mn-header .container {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .mn-logo {
      font-size: 22px;
      font-weight: 900;
      color: #fff;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .mn-logo span {
      color: var(--primary);
    }
    .mn-secure-badge {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #94a3b8;
      font-size: 13px;
      font-weight: 600;
    }
    .mn-secure-badge i {
      color: var(--success);
    }

    /* Main Container */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .co-wrapper {
      padding: 50px 0 80px;
    }

    /* Page Header */
    .co-header {
      text-align: center;
      margin-bottom: 40px;
    }
    .co-chip {
      display: inline-block;
      font-size: 11px;
      font-weight: 800;
      color: var(--primary);
      background: var(--primary-soft);
      border: 1px solid rgba(255, 90, 44, 0.16);
      padding: 6px 18px;
      border-radius: 50px;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 14px;
    }
    .co-header h1 {
      font-size: 36px;
      font-weight: 900;
      color: var(--dark);
      letter-spacing: -0.5px;
      margin-bottom: 10px;
    }
    .co-header p {
      font-size: 16px;
      color: var(--muted);
      max-width: 520px;
      margin: 0 auto;
    }

    /* Step Indicators */
    .co-steps {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 44px;
    }
    .co-step {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .co-step-num {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: var(--primary);
      color: #fff;
      font-size: 15px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 14px rgba(255, 90, 44, 0.3);
    }
    .co-step.inactive .co-step-num {
      background: #e2e8f0;
      color: #94a3b8;
      box-shadow: none;
    }
    .co-step-label {
      font-size: 12px;
      font-weight: 700;
      color: var(--dark);
      margin-top: 6px;
    }
    .co-step.inactive .co-step-label {
      color: #94a3b8;
    }
    .co-step-connector {
      width: 80px;
      height: 3px;
      background: #e2e8f0;
      margin: 0 15px 22px;
      border-radius: 4px;
    }
    .co-step-connector.active {
      background: var(--primary);
    }

    /* Grid Layout */
    .co-grid {
      display: grid;
      grid-template-columns: 1fr 420px;
      gap: 32px;
      align-items: start;
    }

    /* Card Base */
    .co-card {
      background: var(--white);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
      padding: 36px;
      margin-bottom: 24px;
    }
    .co-card-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 28px;
      padding-bottom: 18px;
      border-bottom: 1px solid #f1f5f9;
    }
    .co-card-icon {
      width: 44px;
      height: 44px;
      background: var(--primary-soft);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: var(--primary);
    }
    .co-card-header h2 {
      font-size: 19px;
      font-weight: 800;
      color: var(--dark);
    }

    /* Form Fields */
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group.full {
      grid-column: span 2;
    }
    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: #374151;
      margin-bottom: 8px;
    }
    .input-wrap {
      position: relative;
    }
    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 15px;
      pointer-events: none;
      transition: color 0.2s;
    }
    .form-input {
      width: 100%;
      height: 52px;
      padding: 0 16px 0 48px;
      border: 1.5px solid var(--border);
      border-radius: 12px;
      font-size: 14.5px;
      color: var(--text);
      background: #f8fafc;
      transition: all 0.2s;
      outline: none;
      font-family: inherit;
    }
    .form-input:focus {
      border-color: var(--primary);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(255, 90, 44, 0.08);
    }
    .input-wrap:focus-within .input-icon {
      color: var(--primary);
    }

    /* Payment Methods Cards */
    .payment-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 14px;
    }
    .payment-option {
      position: relative;
    }
    .payment-option input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
    }
    .payment-box {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px;
      border: 1.5px solid var(--border);
      border-radius: 12px;
      cursor: pointer;
      background: #f8fafc;
      transition: all 0.2s;
    }
    .payment-box i {
      font-size: 20px;
      color: var(--muted);
    }
    .payment-box span {
      font-size: 13.5px;
      font-weight: 700;
      color: var(--dark);
    }
    .payment-option input:checked + .payment-box {
      border-color: var(--primary);
      background: var(--primary-soft);
      box-shadow: 0 0 0 3px rgba(255, 90, 44, 0.1);
    }
    .payment-option input:checked + .payment-box i {
      color: var(--primary);
    }

    /* Summary Card */
    .co-summary {
      background: var(--white);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
      padding: 32px;
      position: sticky;
      top: 24px;
    }
    .summary-title {
      font-size: 18px;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 24px;
      padding-bottom: 16px;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .summary-title i {
      color: var(--primary);
    }

    /* Plan Badge */
    .plan-badge {
      background: linear-gradient(135deg, var(--dark) 0%, var(--dark-card) 100%);
      border-radius: 14px;
      padding: 22px;
      color: #fff;
      margin-bottom: 24px;
      text-align: center;
    }
    .plan-badge .label {
      font-size: 10px;
      font-weight: 800;
      color: rgba(255, 255, 255, 0.6);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 6px;
      display: block;
    }
    .plan-badge .name {
      font-size: 24px;
      font-weight: 900;
      color: #fff;
      display: block;
      margin-bottom: 4px;
    }
    .plan-badge .price {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.85);
      font-weight: 600;
    }

    /* Summary Row */
    .summary-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 0;
      font-size: 14px;
      border-bottom: 1px dashed #f1f5f9;
    }
    .summary-row .key {
      color: var(--muted);
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .summary-row .key i {
      color: var(--primary);
    }
    .summary-row .val {
      color: var(--dark);
      font-weight: 800;
    }

    .total-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--primary-soft);
      border: 1px solid rgba(255, 90, 44, 0.16);
      border-radius: 12px;
      padding: 18px 20px;
      margin-top: 20px;
    }
    .total-row .label {
      font-size: 15px;
      font-weight: 800;
      color: var(--dark);
    }
    .total-row .amount {
      font-size: 26px;
      font-weight: 900;
      color: var(--primary);
    }

    /* Submit Button */
    .btn-submit {
      width: 100%;
      height: 58px;
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: 14px;
      font-size: 16.5px;
      font-weight: 800;
      font-family: inherit;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-top: 24px;
      box-shadow: 0 8px 24px rgba(255, 90, 44, 0.32);
      transition: all 0.25s;
    }
    .btn-submit:hover {
      background: var(--primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(255, 90, 44, 0.4);
    }

    /* Trust Items */
    .trust-list {
      margin-top: 24px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .trust-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
      font-weight: 600;
      color: var(--muted);
    }
    .trust-item i {
      color: var(--success);
    }

    @media (max-width: 991px) {
      .co-grid {
        grid-template-columns: 1fr;
      }
      .co-summary {
        position: static;
      }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header class="mn-header">
    <div class="container">
      <a href="/" class="mn-logo">
        <i class="fa-solid fa-rocket"></i> Mature Nature <span>SaaS</span>
      </a>
      <div class="mn-secure-badge">
        <i class="fa-solid fa-lock"></i> 256-Bit SSL Encrypted & Provisioned Safely
      </div>
    </div>
  </header>

  <!-- Checkout Section -->
  <section class="co-wrapper">
    <div class="container">

      <!-- Title & Steps -->
      <div class="co-header">
        <span class="co-chip"><i class="fa-solid fa-bolt"></i> Instant SaaS Store Setup</span>
        <h1>Complete Your Checkout</h1>
        <p>You're one step away from launching your online storefront. Verify your details below to activate your website.</p>
      </div>

      <div class="co-steps">
        <div class="co-step">
          <div class="co-step-num">1</div>
          <div class="co-step-label">Select Plan</div>
        </div>
        <div class="co-step-connector active"></div>
        <div class="co-step">
          <div class="co-step-num">2</div>
          <div class="co-step-label">Account Details</div>
        </div>
        <div class="co-step-connector active"></div>
        <div class="co-step">
          <div class="co-step-num">3</div>
          <div class="co-step-label">Launch Store</div>
        </div>
      </div>

      <!-- Main Form -->
      <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf
        <input type="hidden" name="plan_id" value="{{ $data['package']->id }}">
        <input type="hidden" name="selected_template" value="{{ $data['selected_template'] }}">

        <div class="co-grid">
          
          <!-- Left Side: Billing & Payment -->
          <div class="co-left">

            <!-- Card 1: Customer & Shop Details -->
            <div class="co-card">
              <div class="co-card-header">
                <div class="co-card-icon">
                  <i class="fa-solid fa-store"></i>
                </div>
                <h2>Store & Billing Details</h2>
              </div>

              <div class="form-row">
                <div class="form-group full">
                  <label class="form-label">Store / Shop Name *</label>
                  <div class="input-wrap">
                    <i class="fa-solid fa-shop input-icon"></i>
                    <input type="text" class="form-input" name="shop_name" placeholder="My Fashion Store" value="{{ old('shop_name', $data['shop_name'] ?: ucfirst($data['username'])) }}" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Email Address *</label>
                  <div class="input-wrap">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" class="form-input" name="email" placeholder="owner@maturenature.in" value="{{ old('email', $data['email']) }}" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Phone Number *</label>
                  <div class="input-wrap">
                    <i class="fa-solid fa-phone input-icon"></i>
                    <input type="text" class="form-input" name="phone" placeholder="9876543210" value="{{ old('phone', $data['phone']) }}" required>
                  </div>
                  <input type="hidden" name="country_code" value="{{ $data['country_code'] }}">
                </div>

                <div class="form-group">
                  <label class="form-label">City *</label>
                  <div class="input-wrap">
                    <i class="fa-solid fa-city input-icon"></i>
                    <input type="text" class="form-input" name="city" placeholder="Mumbai" value="{{ old('city', 'Mumbai') }}" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">State / Province</label>
                  <div class="input-wrap">
                    <i class="fa-solid fa-map-location-dot input-icon"></i>
                    <input type="text" class="form-input" name="district" placeholder="Maharashtra" value="{{ old('district', 'Maharashtra') }}">
                  </div>
                </div>
              </div>
            </div>

            <!-- Card 2: Payment Gateway Selection -->
            <div class="co-card">
              <div class="co-card-header">
                <div class="co-card-icon">
                  <i class="fa-solid fa-credit-card"></i>
                </div>
                <h2>Payment Method</h2>
              </div>

              <div class="payment-grid">
                <label class="payment-option">
                  <input type="radio" name="payment_method" value="Razorpay" checked>
                  <div class="payment-box">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Razorpay / UPI</span>
                  </div>
                </label>

                <label class="payment-option">
                  <input type="radio" name="payment_method" value="Stripe">
                  <div class="payment-box">
                    <i class="fa-brands fa-stripe"></i>
                    <span>Credit / Debit Card</span>
                  </div>
                </label>

                <label class="payment-option">
                  <input type="radio" name="payment_method" value="Paypal">
                  <div class="payment-box">
                    <i class="fa-brands fa-paypal"></i>
                    <span>PayPal Express</span>
                  </div>
                </label>

                <label class="payment-option">
                  <input type="radio" name="payment_method" value="Offline">
                  <div class="payment-box">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Bank Transfer</span>
                  </div>
                </label>
              </div>
            </div>

          </div>

          <!-- Right Side: Order Summary -->
          <div class="co-right">
            <div class="co-summary">
              
              <div class="summary-title">
                <i class="fa-solid fa-cart-shopping"></i> Order Summary
              </div>

              <div class="plan-badge">
                <span class="label">Selected Subscription</span>
                <span class="name">{{ $data['package']->name }}</span>
                <span class="price">
                  ₹{{ number_format($data['package']->price_monthly, 2) }} / Month
                </span>
              </div>

              <div class="summary-row">
                <div class="key"><i class="fa-solid fa-circle-check"></i> Activated Product</div>
                <div class="val">LaunchShop Engine</div>
              </div>

              <div class="summary-row">
                <div class="key"><i class="fa-solid fa-server"></i> Database Setup</div>
                <div class="val">Automated SaaS Provision</div>
              </div>

              <div class="summary-row">
                <div class="key"><i class="fa-solid fa-bolt"></i> Launch Speed</div>
                <div class="val">Instant Activation</div>
              </div>

              <div class="total-row">
                <div class="label">Total Due Now</div>
                <div class="amount">₹{{ number_format($data['package']->price_monthly, 2) }}</div>
              </div>

              <button type="submit" class="btn-submit">
                <i class="fa-solid fa-rocket"></i> Place Order & Launch Website
              </button>

              <div class="trust-list">
                <div class="trust-item"><i class="fa-solid fa-shield-halved"></i> Automated single-click agency dashboard setup</div>
                <div class="trust-item"><i class="fa-solid fa-check"></i> Includes free sub-domain setup</div>
                <div class="trust-item"><i class="fa-solid fa-headset"></i> Dedicated support via Mature Nature SaaS</div>
              </div>

            </div>
          </div>

        </div>
      </form>

    </div>
  </section>

</body>
</html>
