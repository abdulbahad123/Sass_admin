<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Store Launched Successfully — Mature Nature SaaS</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary: #ff5a2c;
      --dark: #0e1b3d;
      --success: #10b981;
      --text: #1e293b;
      --muted: #64748b;
      --bg: #f8fafc;
      --white: #ffffff;
      --border: #e2e8f0;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .mn-header {
      background: var(--dark);
      padding: 20px 0;
      color: #fff;
      text-align: center;
    }
    .mn-logo {
      font-size: 22px;
      font-weight: 900;
      color: #fff;
      text-decoration: none;
    }
    .mn-logo span { color: var(--primary); }

    .success-container {
      max-width: 680px;
      margin: 60px auto;
      padding: 0 20px;
      flex: 1;
    }

    .success-card {
      background: var(--white);
      border-radius: 20px;
      border: 1px solid var(--border);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      padding: 48px;
      text-align: center;
    }

    .icon-box {
      width: 80px;
      height: 80px;
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      margin: 0 auto 24px;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
      70% { box-shadow: 0 0 0 16px rgba(16, 185, 129, 0); }
      100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    h1 {
      font-size: 28px;
      font-weight: 900;
      color: var(--dark);
      margin-bottom: 12px;
    }

    p.subtitle {
      font-size: 15px;
      color: var(--muted);
      margin-bottom: 32px;
    }

    .details-box {
      background: #f8fafc;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px;
      text-align: left;
      margin-bottom: 32px;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px dashed var(--border);
      font-size: 14px;
    }
    .detail-item:last-child { border-bottom: none; }
    .detail-item .label { color: var(--muted); font-weight: 600; }
    .detail-item .value { color: var(--dark); font-weight: 800; }

    .btn-group {
      display: flex;
      gap: 16px;
      justify-content: center;
    }

    .btn {
      padding: 16px 28px;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 800;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }

    .btn-primary {
      background: var(--primary);
      color: #fff;
    }
    .btn-primary:hover {
      background: #e0451a;
      transform: translateY(-2px);
    }

    .btn-outline {
      background: #fff;
      color: var(--dark);
      border: 1.5px solid var(--border);
    }
    .btn-outline:hover {
      background: #f1f5f9;
    }
  </style>
</head>
<body>

  <header class="mn-header">
    <a href="/" class="mn-logo"><i class="fa-solid fa-rocket"></i> Mature Nature <span>SaaS</span></a>
  </header>

  <div class="success-container">
    <div class="success-card">
      <div class="icon-box">
        <i class="fa-solid fa-check"></i>
      </div>

      <h1>Store Launched Successfully!</h1>
      <p class="subtitle">Congratulations! Your website database and subscription have been provisioned safely on Mature Nature SaaS.</p>

      <div class="details-box">
        <div class="detail-item">
          <span class="label">Store Name</span>
          <span class="value">{{ $agency->name ?? 'My Store' }}</span>
        </div>
        <div class="detail-item">
          <span class="label">Store URL</span>
          <span class="value" style="color: var(--primary);">
            {{ session('checkout_store_url', 'https://maturenature.in') }}
          </span>
        </div>
        <div class="detail-item">
          <span class="label">Owner Email</span>
          <span class="value">{{ $agency->email ?? auth()->user()->email ?? '-' }}</span>
        </div>
        <div class="detail-item">
          <span class="label">Subscription Status</span>
          <span class="value" style="color: var(--success);"><i class="fa-solid fa-circle-check"></i> Active</span>
        </div>
      </div>

      <div class="btn-group">
        <a href="{{ route('whitelabel.dashboard') }}" class="btn btn-primary">
          <i class="fa-solid fa-gauge-high"></i> Go to Admin Dashboard
        </a>
        <a href="{{ session('checkout_store_url', '/') }}" target="_blank" class="btn btn-outline">
          <i class="fa-solid fa-arrow-up-right-from-square"></i> Visit Storefront
        </a>
      </div>
    </div>
  </div>

</body>
</html>
