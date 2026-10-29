<!DOCTYPE html>
<html lang="en" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - SaaSReselling</title>
    
    <!-- Meta SEO Tags -->
    <meta name="description" content="{{ $title }} for SaaSReselling - All-in-One White Label SaaS Platform.">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}">

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
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'sans-serif']
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
<body class="bg-white dark:bg-[#0B0F19] text-slate-700 dark:text-slate-200 antialiased min-h-screen flex flex-col justify-between">

    <!-- TOP ANNOUNCEMENT BAR (Exact from Main Landing Page) -->
    @if(!empty($data['lp_announcement_text']))
    <div class="bg-gradient-to-r from-slate-900 via-orange-950 to-slate-900 text-white text-[11px] sm:text-xs py-2 px-4 text-center font-medium tracking-wide flex items-center justify-center gap-2">
        <span class="bg-orange-500/30 text-orange-200 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">{{ $data['lp_announcement_badge'] }}</span>
        <span>{{ $data['lp_announcement_text'] }}</span>
    </div>
    @endif

    <!-- STICKY NAVBAR (Exact from Main Landing Page) -->
    <header class="sticky top-0 z-50 bg-white/95 dark:bg-[#0B0F19]/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-700/80 transition-all duration-300">
        <div class="max-w-[1340px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 sm:h-24">
                <!-- Logo (Increased Size & Dual Dark/White Mode Support) -->
                @php
                    $lightLogo = !empty($data['lp_header_logo']) ? $data['lp_header_logo'] : null;
                    $darkLogo  = !empty($data['lp_header_logo_dark']) ? $data['lp_header_logo_dark'] : $lightLogo;
                @endphp
                <a href="{{ url('/') }}" class="flex items-center space-x-3 py-1">
                    @if($lightLogo || $darkLogo)
                        @if($lightLogo)
                            <img src="{{ asset($lightLogo) }}" alt="Logo" class="h-14 sm:h-16 lg:h-20 max-h-[72px] w-auto object-contain transition-all {{ $darkLogo ? 'dark:hidden' : '' }}">
                        @endif
                        @if($darkLogo)
                            <img src="{{ asset($darkLogo) }}" alt="Logo Dark" class="h-14 sm:h-16 lg:h-20 max-h-[72px] w-auto object-contain transition-all hidden dark:block">
                        @endif
                    @else
                        <div class="flex items-center space-x-2.5">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl btn-gradient flex items-center justify-center text-white font-bold text-2xl shadow-md shadow-orange-500/20">
                                <i class="fas fa-layer-group text-xl sm:text-2xl"></i>
                            </div>
                            <span class="font-heading font-extrabold text-2xl sm:text-3xl tracking-tight text-slate-900 dark:text-white">SaaSReselling</span>
                        </div>
                    @endif
                </a>

                <!-- Desktop Menu Navigation -->
                <nav class="hidden lg:flex items-center space-x-8 text-sm font-semibold text-slate-600 dark:text-slate-300 relative">
                    <a href="{{ url('/') }}#hero" class="hover:text-[#ff3d00] transition-colors py-6">Home</a>
                    <a href="{{ url('/') }}#about" class="hover:text-[#ff3d00] transition-colors py-6">Solutions</a>
                    <a href="{{ url('/') }}#products" class="hover:text-[#ff3d00] transition-colors py-6">Products</a>
                    <a href="{{ url('/') }}#pricing" class="hover:text-[#ff3d00] transition-colors py-6">Pricing</a>
                    <a href="{{ url('/') }}#faq" class="hover:text-[#ff3d00] transition-colors py-6">FAQs</a>
                </nav>

                <!-- Right Side Actions & CTA -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="hidden sm:flex items-center space-x-3">
                        <a href="{{ url('/') }}#hero" class="px-5 py-2.5 rounded-full btn-gradient text-white text-xs sm:text-sm font-bold shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 hover:scale-[1.02] transition-all flex items-center space-x-2">
                            <span>Return to Home</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <!-- Theme Toggle -->
                    <button onclick="document.documentElement.classList.toggle('dark')" class="p-2 sm:p-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <i class="fas fa-moon dark:hidden text-lg"></i>
                        <i class="fas fa-sun hidden dark:block text-lg text-amber-400"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN LEGAL DOCUMENT CONTAINER -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        
        <div class="bg-[#080E24] border border-slate-800 rounded-3xl p-6 sm:p-12 shadow-2xl relative overflow-hidden text-slate-300">
            <!-- Top Gradient Accent -->
            <div class="absolute top-0 left-0 right-0 h-1.5 btn-gradient"></div>

            <!-- Page Title Header -->
            <div class="border-b border-slate-800 pb-6 mb-8">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-orange-500/15 text-[#ff3d00] border border-orange-500/30">
                    Official Policy Document
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight font-heading mt-3">
                    {{ $title }}
                </h1>
                <p class="text-xs text-slate-400 mt-2">
                    Platform: <strong class="text-orange-400">SaaSReselling (saasreselling.com)</strong> • Effective Date: October 2026
                </p>
            </div>

            <!-- Policy Text Body -->
            <div class="space-y-6 text-xs sm:text-sm leading-relaxed text-slate-300">
                                @if($type === 'privacy')
                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">Privacy Policy</h2>
                        <p>At <strong>SaaSReselling</strong> (accessible at <a href="https://saasreselling.com/" target="_blank" class="text-orange-400 underline">https://saasreselling.com/</a>), we respect your privacy and are committed to protecting the personal information you share with us. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.</p>
                        <p>By accessing or using our website and services, you agree to the terms of this Privacy Policy.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">1. Information We Collect</h2>
                        <p>We may collect the following types of information:</p>
                        <h3 class="text-sm font-semibold text-slate-200">1.1 Personal Information</h3>
                        <p>When you register, contact us, or make a purchase, we may collect: Name, Email address, Phone number, Business name, and Billing/payment-related details (payment verification only; we do not store card details).</p>
                        <h3 class="text-sm font-semibold text-slate-200">1.2 Non-Personal Information</h3>
                        <p>IP address, Browser type and version, Device information, Pages visited, time spent on site, and Referring URLs for security, analytics, and optimization.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">2. How We Use Your Information</h2>
                        <ul class="list-disc list-inside space-y-1 text-slate-400">
                            <li>Provide and deliver our products and services</li>
                            <li>Verify payments, including manual transactions (UPI/bank transfers)</li>
                            <li>Communicate regarding inquiries, orders, and support requests</li>
                            <li>Improve website functionality and user experience</li>
                            <li>Send important updates, service-related messages, or promotional communication (you may opt out anytime)</li>
                            <li>Prevent fraud, abuse, or unauthorized access</li>
                        </ul>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">3. Manual Payments & Data Usage</h2>
                        <p>If you choose to make a manual payment, we may collect payment reference details or screenshots solely for verification purposes. This information is used only to confirm payment, not shared publicly, and retained only as long as required for accounting or legal purposes.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">4. Cookies & Tracking Technologies</h2>
                        <p>We use cookies and similar technologies to understand user behavior, improve site performance, and track marketing effectiveness. You can disable cookies through your browser settings.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">5. Sharing of Information</h2>
                        <p>We do not sell, trade, or rent your personal information to third parties. We share information only with trusted service providers strictly for service delivery, to comply with legal obligations, or to protect our rights.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">6. Data Security & Retention</h2>
                        <p>We implement industry-standard security measures to protect your information and retain it only as long as necessary to fulfill service delivery, comply with law, and resolve disputes.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">7. Your Rights & Changes</h2>
                        <p>You have the right to access, update, or request deletion of your data and opt out of marketing. We may update this policy periodically; continued use implies acceptance.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">8. Contact Us</h2>
                        <p>Website: <a href="https://saasreselling.com/" target="_blank" class="text-orange-400">https://saasreselling.com/</a><br>Phone / WhatsApp: <strong class="text-white">+91 63749 13298</strong><br>Email: <strong class="text-orange-400">support@saasreselling.com</strong></p>
                    </section>

                @elseif($type === 'terms')
                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">Terms and Conditions</h2>
                        <p>Welcome to <strong>SaaSReselling</strong> (accessible at <a href="https://saasreselling.com/" target="_blank" class="text-orange-400 underline">https://saasreselling.com/</a>, including related sub-pages such as “White Label Solutions”). These Terms and Conditions govern your access to and use of our website, products, and services.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">1. About Our Services</h2>
                        <p>SaaSReselling provides digital products, software tools, white-label SaaS solutions, website services, reseller programs, and related digital services.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">2. Eligibility & Payments</h2>
                        <p>You must be at least 18 years old. We accept online payment gateways and manual transactions (UPI/Bank transfer). All payments are solely for purchasing SaaSReselling's own products and reseller memberships. SaaSReselling does not hold or split payments between resellers and their end customers.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">3. No Refund & No Cancellation Policy</h2>
                        <p>All sales are final and non-refundable, including manual and online payments. Once payment is completed and access is provided, no refunds or chargebacks will be entertained.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">4. Source Code & Script Policy</h2>
                        <p>All software is provided as managed white-label solutions. Source code, scripts, and backend infrastructure remain hosted on our cloud VPS servers and are owned and controlled by us. We do not sell or transfer source code under any circumstances.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">5. Contact Information</h2>
                        <p>Website: <a href="https://saasreselling.com/" target="_blank" class="text-orange-400">https://saasreselling.com/</a><br>Phone / WhatsApp: <strong class="text-white">+91 63749 13298</strong><br>Email: <strong class="text-orange-400">support@saasreselling.com</strong></p>
                    </section>

                @elseif($type === 'refund')
                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">Refund Policy</h2>
                        <p>Thank you for choosing <strong>SaaSReselling</strong>. We strive to deliver the highest quality digital products, services, and reseller solutions.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">1. No Refunds / All Sales Are Final</h2>
                        <p>All purchases made through <a href="https://saasreselling.com/" target="_blank" class="text-orange-400">https://saasreselling.com/</a> or manual payments are final and non-refundable. Once purchase is completed and digital access or services begin, we do not offer returns, cancellations, or exchanges.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">2. Why We Do Not Offer Refunds</h2>
                        <p>Because products and services involve instant digital provisioning, server resources, and custom setup, digital content cannot be returned once access is delivered.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">3. Contact Us</h2>
                        <p>Website: <a href="https://saasreselling.com/" target="_blank" class="text-orange-400">https://saasreselling.com/</a><br>Phone / WhatsApp: <strong class="text-white">+91 63749 13298</strong><br>Email: <strong class="text-orange-400">support@saasreselling.com</strong></p>
                    </section>

                @elseif($type === 'shipping')
                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">1. Digital Delivery Policy</h2>
                        <p><strong>SaaSReselling</strong> delivers cloud-hosted software-as-a-service (SaaS) applications and white-label digital tools. We do not manufacture, ship, or deliver any tangible or physical products.</p>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">2. Service Provisioning Timeframes</h2>
                        <p>Upon receipt of payment confirmation:</p>
                        <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-400">
                            <li>Super Admin access credentials and account onboarding details are dispatched immediately via email.</li>
                            <li>Automated cloud database setup completes within <strong>1 to 5 minutes</strong>.</li>
                            <li>Custom domain mapping and SSL issuance propagate within <strong>2 to 24 hours</strong> depending on your DNS host.</li>
                        </ul>
                    </section>

                    <section class="space-y-3">
                        <h2 class="text-base sm:text-lg font-bold text-white font-heading">3. Delivery Support</h2>
                        <p>For any provisioning assistance, reach our operations team at <strong class="text-orange-400">support@saasreselling.com</strong>.</p>
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

    <!-- FOOTER (Exact from Main Landing Page) -->
    <footer class="bg-white dark:bg-[#0B0F19] text-slate-600 dark:text-slate-300 py-12 border-t border-slate-200 dark:border-slate-700/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-12 gap-6 sm:gap-8 pb-10 border-b border-slate-200 dark:border-slate-700/80">
                
                <!-- Col 1: Logo & Socials -->
                <div class="col-span-2 lg:col-span-3 space-y-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2.5 text-slate-900 dark:text-white font-heading font-bold text-xl">
                        @if($lightLogo || $darkLogo)
                            @if($lightLogo)
                                <img src="{{ asset($lightLogo) }}" alt="Logo" class="h-12 sm:h-14 lg:h-16 max-h-[60px] w-auto object-contain transition-all {{ $darkLogo ? 'dark:hidden' : '' }}">
                            @endif
                            @if($darkLogo)
                                <img src="{{ asset($darkLogo) }}" alt="Logo Dark" class="h-12 sm:h-14 lg:h-16 max-h-[60px] w-auto object-contain transition-all hidden dark:block">
                            @endif
                        @else
                            <div class="w-11 h-11 rounded-xl btn-gradient flex items-center justify-center text-white text-base font-bold shadow-sm">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">SaaSReselling</span>
                        @endif
                    </a>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs leading-relaxed">
                        {{ $data['lp_footer_desc'] ?? 'Your technology partner in building profitable white-label SaaS businesses.' }}
                    </p>
                    <div class="flex items-center space-x-2.5 pt-1">
                        @if(!empty($data['lp_fb_url']))<a href="{{ $data['lp_fb_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-facebook-f"></i></a>@endif
                        @if(!empty($data['lp_tw_url']))<a href="{{ $data['lp_tw_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-twitter"></i></a>@endif
                        @if(!empty($data['lp_li_url']))<a href="{{ $data['lp_li_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-linkedin-in"></i></a>@endif
                        @if(!empty($data['lp_yt_url']))<a href="{{ $data['lp_yt_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-discord"></i></a>@endif
                        @if(!empty($data['lp_ig_url']))<a href="{{ $data['lp_ig_url'] }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#1e293b] hover:text-[#ff3d00] flex items-center justify-center text-slate-500 dark:text-white text-xs transition-colors"><i class="fab fa-instagram"></i></a>@endif
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="col-span-1 lg:col-span-2 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Quick Links</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <li><a href="{{ url('/') }}#hero" class="hover:text-[#ff3d00] transition-colors">Home</a></li>
                        <li><a href="{{ url('/') }}#about" class="hover:text-[#ff3d00] transition-colors">Solutions</a></li>
                        <li><a href="{{ url('/') }}#pricing" class="hover:text-[#ff3d00] transition-colors">Pricing</a></li>
                        <li><a href="{{ url('/') }}#products" class="hover:text-[#ff3d00] transition-colors">Resources</a></li>
                        <li><a href="{{ url('/') }}#faq" class="hover:text-[#ff3d00] transition-colors">FAQs</a></li>
                    </ul>
                </div>

                <!-- Col 3: Our Products -->
                <div class="col-span-1 lg:col-span-3 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Our Products</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <li><a href="https://launchshop.saasreselling.com" target="_blank" class="hover:text-[#ff3d00] transition-colors">Ecombuilder</a></li>
                        <li><a href="https://websitebuilder.saasreselling.com" target="_blank" class="hover:text-[#ff3d00] transition-colors">Website Builder</a></li>
                        <li><a href="https://whatsappbulk.saasreselling.com" target="_blank" class="hover:text-[#ff3d00] transition-colors">WhatsApp Bulk Automation</a></li>
                    </ul>
                </div>

                <!-- Col 4: Policies (Task 2 & 3: Matching saasreselling.in) -->
                <div class="col-span-2 lg:col-span-4 space-y-3">
                    <h5 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Policies</h5>
                    <ul class="space-y-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <li><a href="{{ url('/privacy-policy') }}" class="hover:text-[#ff3d00] transition-colors {{ $type === 'privacy' ? 'text-orange-500 font-bold' : '' }}">Privacy Policy</a></li>
                        <li><a href="{{ url('/terms-conditions') }}" class="hover:text-[#ff3d00] transition-colors {{ $type === 'terms' ? 'text-orange-500 font-bold' : '' }}">Terms &amp; Conditions</a></li>
                        <li><a href="{{ url('/refund-policy') }}" class="hover:text-[#ff3d00] transition-colors {{ $type === 'refund' ? 'text-orange-500 font-bold' : '' }}">Refund Policy</a></li>
                        <li><a href="{{ url('/') }}#contact" class="hover:text-[#ff3d00] transition-colors">Contact Us</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright: Clean Only (Task 1) -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-center text-xs text-slate-500 dark:text-slate-400 font-medium">
                <p>© {{ date('Y') }} saasreselling. All rights reserved.</p>
            </div>

        </div>
    </footer>

</body>
</html>