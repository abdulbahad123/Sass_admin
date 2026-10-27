<!DOCTYPE html>
<html lang="en" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - SaaSReselling</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            orange: '#ff3d00',
                            navy: '#080E24',
                            dark: '#0B0F19'
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-gradient { background: linear-gradient(135deg, #ff3d00 0%, #ff6e40 100%); }
    </style>
</head>
<body class="bg-[#080E24] text-slate-200 antialiased min-h-screen flex flex-col justify-between">

    <!-- TOP HEADER NAVBAR (Same as Main Landing Page) -->
    <header class="sticky top-0 z-50 bg-[#080E24]/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-3 text-white font-extrabold text-xl font-heading">
                <div class="w-10 h-10 rounded-xl btn-gradient flex items-center justify-center text-white text-base font-bold shadow-md shadow-orange-500/20">
                    <i class="fas fa-layer-group"></i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-white">SaaSReselling</span>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center space-x-8 text-xs font-bold text-slate-300">
                <a href="{{ url('/') }}#hero" class="hover:text-[#ff3d00] transition-colors">Home</a>
                <a href="{{ url('/') }}#about" class="hover:text-[#ff3d00] transition-colors">About</a>
                <a href="{{ url('/') }}#products" class="hover:text-[#ff3d00] transition-colors">Products</a>
                <a href="{{ url('/') }}#pricing" class="hover:text-[#ff3d00] transition-colors">Pricing</a>
                <a href="{{ url('/') }}#faq" class="hover:text-[#ff3d00] transition-colors">FAQs</a>
            </nav>

            <!-- CTA Button -->
            <div class="flex items-center space-x-4">
                <a href="{{ url('/') }}#hero" class="px-5 py-2.5 rounded-full btn-gradient text-white text-xs font-bold shadow-lg shadow-orange-500/25 hover:scale-105 transition-all">
                    <span>Back to Home</span>
                    <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN LEGAL CONTENT CONTAINER -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        
        <!-- Document Card -->
        <div class="bg-[#0B0F19] border border-slate-800 rounded-3xl p-6 sm:p-12 shadow-2xl relative overflow-hidden">
            <!-- Top Gradient Accent -->
            <div class="absolute top-0 left-0 right-0 h-1.5 btn-gradient"></div>

            <!-- Page Title Header -->
            <div class="border-b border-slate-800 pb-6 mb-8">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-orange-500/15 text-[#ff3d00] border border-orange-500/30">
                    Legal Policy
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight font-heading mt-3">
                    {{ $title }}
                </h1>
                <p class="text-xs text-slate-400 mt-2">
                    Last updated: October 27, 2026 • Effective Immediately
                </p>
            </div>

            <!-- Policy Text Body -->
            <div class="space-y-6 text-xs sm:text-sm text-slate-300 leading-relaxed">
                @if($type === 'privacy')
                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">1. Introduction</h2>
                        <p>Welcome to <strong>SaaSReselling</strong> ("we", "our", or "us"). We are committed to protecting your personal information and your right to privacy. This Privacy Policy describes how we collect, use, store, and share your personal data when you use our website at <code>saasreselling.com</code>, partner portal, and white-label SaaS services.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">2. Information We Collect</h2>
                        <p>When you register as an agency partner, book a demo, or interact with our platform, we collect information that you voluntarily provide to us, including:</p>
                        <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-400">
                            <li>Contact information (such as your full name, email address, and WhatsApp/phone number).</li>
                            <li>Agency and business credentials (business name, domain preferences, and business type).</li>
                            <li>Billing and payment information processed through secure, PCI-compliant third-party gateways.</li>
                            <li>Technical logs, IP addresses, browser types, and usage diagnostics.</li>
                        </ul>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">3. How We Use Your Data</h2>
                        <p>We process your information to provide, maintain, and improve our multi-tenant SaaS services, including: provisioning white-label agency portals, sending service announcements, technical support, preventing fraudulent activities, and complying with applicable legal obligations.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">4. Data Security & Storage</h2>
                        <p>We implement industry-standard technical and organizational security measures, including HTTPS encryption, isolated tenant databases, automated backups, and restricted employee access to safeguard your data against unauthorized access, loss, or alteration.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">5. Contact Us</h2>
                        <p>If you have any questions or requests regarding your personal data or this Privacy Policy, please contact our privacy compliance team at <strong>{{ $data['lp_contact_email'] ?? 'support@saasreselling.com' }}</strong>.</p>
                    </section>

                @elseif($type === 'terms')
                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">1. Acceptance of Terms</h2>
                        <p>By accessing or using the <strong>SaaSReselling</strong> platform, subscribing to a reseller tier, or deploying white-label tenant instances, you agree to be bound by these Terms and Conditions. If you do not agree, you must discontinue using our services immediately.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">2. White-Label License & Usage Rights</h2>
                        <p>Subject to compliance with these Terms and timely payment of your subscription fees, SaaSReselling grants you a revocable, non-exclusive, non-transferable license to rebrand and resell access to our integrated SaaS software suite (including Ecombuilder, Website Builder, and WhatsApp Automation) to your end-clients.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">3. Agency Responsibilities & Content</h2>
                        <p>As a reseller, you are solely responsible for managing your end-client accounts, customer support, and complying with all local laws and privacy regulations. You agree not to use the platform for any unlawful activities, spam dispatch, malicious software distribution, or infringing content.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">4. Subscription Fees & Renewals</h2>
                        <p>Reseller subscriptions are billed on a recurring monthly or yearly basis in accordance with the pricing plan selected during checkout. Failure to settle recurring invoices may result in temporary suspension or termination of tenant instances.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">5. Limitation of Liability</h2>
                        <p>To the maximum extent permitted by applicable law, SaaSReselling shall not be liable for any indirect, incidental, punitive, or consequential damages resulting from downtime, data loss, or agency commercial operations.</p>
                    </section>

                @elseif($type === 'refund')
                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">1. Subscription Cancellation</h2>
                        <p>You can cancel your <strong>SaaSReselling</strong> white-label partner subscription at any time directly through your Super Admin dashboard or by submitting a ticket to <strong>{{ $data['lp_contact_email'] ?? 'support@saasreselling.com' }}</strong>. Upon cancellation, your subscription remains active until the end of your current paid billing period.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">2. Refund Eligibility</h2>
                        <p>Because SaaSReselling immediately provisions dedicated cloud infrastructure, dynamic databases, white-label licenses, and tenant domains upon registration, monthly subscription fees are generally non-refundable once the billing cycle begins.</p>
                        <p>However, if you experience technical failure preventing access to the core platform and our engineering team is unable to resolve it within 7 business days, you may qualify for a prorated refund.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">3. Processing of Approved Refunds</h2>
                        <p>Approved refunds are processed to the original payment method within 5 to 7 business days depending on your banking provider.</p>
                    </section>

                @elseif($type === 'shipping')
                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">1. Digital Delivery Policy</h2>
                        <p><strong>SaaSReselling</strong> provides software-as-a-service (SaaS) and white-label cloud software solutions. We do not manufacture, package, or ship any physical goods or tangible merchandise.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">2. Service Provisioning Timeframes</h2>
                        <p>Upon successful payment confirmation:</p>
                        <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-400">
                            <li>Your partner account and super admin credentials are dispatched instantly via email and registered WhatsApp.</li>
                            <li>Automated cloud database provisioning completes within <strong>1 to 5 minutes</strong>.</li>
                            <li>Custom domain SSL verification and DNS mapping typically propagate within <strong>2 to 24 hours</strong> depending on your registrar.</li>
                        </ul>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">3. Need Help With Delivery?</h2>
                        <p>If you have not received your account onboarding access or credentials within 15 minutes of checkout, please contact our provisioning team at <strong>{{ $data['lp_contact_email'] ?? 'support@saasreselling.com' }}</strong>.</p>
                    </section>
                @endif
            </div>

            <!-- Back Link -->
            <div class="mt-10 pt-6 border-t border-slate-800 flex justify-between items-center text-xs">
                <a href="{{ url('/') }}" class="text-[#ff3d00] hover:underline font-bold flex items-center gap-1.5">
                    <i class="fas fa-arrow-left"></i>
                    <span>Return to SaaSReselling Home</span>
                </a>
                <span class="text-slate-500">© {{ date('Y') }} SaaSReselling. All rights reserved.</span>
            </div>
        </div>

    </main>

    <!-- FOOTER (Same Clean Landing Page Footer) -->
    <footer class="bg-[#0B0F19] text-slate-400 text-xs py-8 border-t border-slate-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>© {{ date('Y') }} SaaSReselling. All rights reserved.</p>
            <div class="flex items-center space-x-4">
                <a href="{{ url('/privacy-policy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                <span>•</span>
                <a href="{{ url('/terms-conditions') }}" class="hover:text-white transition-colors">Terms & Conditions</a>
                <span>•</span>
                <a href="{{ url('/refund-policy') }}" class="hover:text-white transition-colors">Refund Policy</a>
                <span>•</span>
                <a href="{{ url('/shipping-policy') }}" class="hover:text-white transition-colors">Shipping Policy</a>
            </div>
        </div>
    </footer>

</body>
</html>