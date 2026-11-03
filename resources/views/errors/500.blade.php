<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We Are Working On It | System Recovery - SaaSReselling</title>

  <!-- Google Fonts & FontAwesome -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary-color: #ff3d00;
      --primary-gradient: linear-gradient(135deg, #ff3d00 0%, #ff6e40 100%);
      --accent-color: #6366f1;
      --bg-dark: #090d16;
      --card-bg: rgba(255, 255, 255, 0.98);
      --text-dark: #0f172a;
      --text-muted: #64748b;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: radial-gradient(circle at 50% 0%, #1e1b4b 0%, #0f172a 65%, #090d16 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      color: #ffffff;
      overflow-x: hidden;
      position: relative;
    }

    .glow-orb-1 {
      position: absolute;
      width: 440px;
      height: 440px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255, 61, 0, 0.22) 0%, rgba(0, 0, 0, 0) 70%);
      top: -120px;
      left: -100px;
      animation: floatOrb 8s infinite alternate ease-in-out;
      pointer-events: none;
    }

    .glow-orb-2 {
      position: absolute;
      width: 480px;
      height: 480px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
      bottom: -140px;
      right: -100px;
      animation: floatOrb 10s infinite alternate ease-in-out;
      pointer-events: none;
    }

    @keyframes floatOrb {
      0% { transform: translate(0, 0) scale(1); }
      100% { transform: translate(35px, 25px) scale(1.08); }
    }

    .error-card {
      background: var(--card-bg);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 28px;
      padding: 48px 40px;
      max-width: 580px;
      width: 100%;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
      position: relative;
      z-index: 10;
      color: var(--text-dark);
      text-align: center;
      animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(24px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #fef3c7;
      color: #92400e;
      font-weight: 800;
      font-size: 12.5px;
      letter-spacing: .04em;
      text-transform: uppercase;
      padding: 8px 20px;
      border-radius: 9999px;
      border: 1px solid #fde68a;
      margin-bottom: 24px;
      box-shadow: 0 2px 8px rgba(217, 119, 6, 0.08);
    }

    .status-badge i {
      color: #d97706;
      animation: spin 3s linear infinite;
    }

    @keyframes spin {
      100% { transform: rotate(360deg); }
    }

    .illustration-wrap {
      position: relative;
      margin: 0 auto 24px;
      width: 130px;
      height: 130px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .illustration-circle {
      width: 120px;
      height: 120px;
      border-radius: 32px;
      background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
      border: 2px solid #c7d2fe;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      box-shadow: 0 12px 30px rgba(99, 102, 241, 0.15);
    }

    .illustration-circle i.main-icon {
      font-size: 50px;
      color: #4f46e5;
    }

    .floating-chip {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #0f172a;
      color: #ffffff;
      font-family: 'Outfit', sans-serif;
      font-size: 13px;
      font-weight: 900;
      padding: 4px 10px;
      border-radius: 999px;
      border: 2px solid #ffffff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .pulsing-dot {
      position: absolute;
      bottom: -4px;
      left: -4px;
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: #10b981;
      border: 3px solid #ffffff;
      box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
      animation: pulseDot 2s infinite;
    }

    @keyframes pulseDot {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.15); opacity: 0.8; }
    }

    h1.heading {
      font-family: 'Outfit', sans-serif;
      font-size: clamp(24px, 3.8vw, 32px);
      font-weight: 900;
      color: #0f172a;
      margin-bottom: 12px;
      letter-spacing: -0.5px;
      line-height: 1.25;
    }

    p.subtitle {
      font-size: 15px;
      color: var(--text-muted);
      line-height: 1.65;
      margin-bottom: 28px;
      max-width: 480px;
      margin-left: auto;
      margin-right: auto;
      font-weight: 500;
    }

    .progress-wrap {
      background: #f1f5f9;
      border-radius: 9999px;
      height: 7px;
      overflow: hidden;
      margin-bottom: 30px;
      position: relative;
    }

    .progress-bar-fill {
      background: linear-gradient(90deg, #ff3d00 0%, #6366f1 100%);
      height: 100%;
      width: 70%;
      border-radius: 9999px;
      animation: pulseBar 3s infinite alternate ease-in-out;
    }

    @keyframes pulseBar {
      0% { width: 50%; }
      100% { width: 90%; }
    }

    .action-btn-group {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .btn-primary-custom {
      background: var(--primary-gradient);
      color: #ffffff;
      font-weight: 800;
      font-size: 14px;
      padding: 13px 28px;
      border-radius: 14px;
      text-decoration: none;
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      gap: 9px;
      border: none;
      box-shadow: 0 8px 22px rgba(255, 61, 0, 0.3);
    }

    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 28px rgba(255, 61, 0, 0.4);
      color: #ffffff;
    }

    .btn-secondary-custom {
      background: #f8fafc;
      color: #334155;
      font-weight: 700;
      font-size: 14px;
      padding: 13px 22px;
      border-radius: 14px;
      text-decoration: none;
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border: 1.5px solid #e2e8f0;
      cursor: pointer;
    }

    .btn-secondary-custom:hover {
      background: #e2e8f0;
      color: #0f172a;
      transform: translateY(-2px);
    }

    .footer-note {
      font-size: 12px;
      font-weight: 600;
      color: #94a3b8;
      margin-top: 28px;
      margin-bottom: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    @media (max-width: 576px) {
      .error-card {
        padding: 36px 22px;
      }
      .action-btn-group {
        flex-direction: column;
        width: 100%;
      }
      .btn-primary-custom, .btn-secondary-custom {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>
<body>

  <!-- Background Orbs -->
  <div class="glow-orb-1"></div>
  <div class="glow-orb-2"></div>

  <div class="error-card">

    <!-- Status Badge -->
    <div class="status-badge">
      <i class="fa-solid fa-gear"></i>
      <span>We Are Working On It • System Recovery</span>
    </div>

    <!-- Illustration Box -->
    <div class="illustration-wrap">
      <div class="illustration-circle">
        <i class="fa-solid fa-screwdriver-wrench main-icon"></i>
      </div>
      <div class="floating-chip">500</div>
      <div class="pulsing-dot" title="Self-Healing System Active"></div>
    </div>

    <!-- Heading & Text -->
    <h1 class="heading">We Are Working On It!</h1>
    <p class="subtitle">
      Sorry for the interruption. We encountered a momentary hiccup while processing your request. Our automated systems and engineers are restoring service now.
    </p>

    <!-- Progress Indicator -->
    <div class="progress-wrap">
      <div class="progress-bar-fill"></div>
    </div>

    <!-- Action Buttons -->
    <div class="action-btn-group">
      <a href="{{ url('/') }}" class="btn-primary-custom">
        <i class="fa-solid fa-house"></i> Go Back to Home
      </a>
      <button onclick="window.location.reload();" class="btn-secondary-custom">
        <i class="fa-solid fa-rotate-right"></i> Refresh Page
      </button>
      <a href="https://wa.me/916374913298?text=Hi%20SaaSReselling,%20I%20noticed%20an%20issue%20on%20the%20website" target="_blank" class="btn-secondary-custom">
        <i class="fab fa-whatsapp text-green-500"></i> Support
      </a>
    </div>

    <p class="footer-note">
      <i class="fa-solid fa-shield-halved" style="color: #10b981;"></i> SaaSReselling Cloud Protection • 99.9% Uptime Active
    </p>

  </div>

</body>
</html>
