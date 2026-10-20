<?php

// 1. Fix public_landing.blade.php CTAs
$publicLandingFile = 'd:/xamp/htdocs/Sass_admin/resources/views/whitelabel/website/public_landing.blade.php';
if (file_exists($publicLandingFile)) {
    $content = file_get_contents($publicLandingFile);
    
    // Replace the cta_url variables with WhatsApp links
    $content = str_replace(
        '{{ $agency->cta_url ?? \'/login\' }}', 
        'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business', 
        $content
    );
    $content = str_replace(
        '{{ $agencyGet(\'cta_url\') ?? \'/login\' }}', 
        'https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business', 
        $content
    );
    // There might also be hardcoded /login for some buttons.
    $content = str_replace(
        'href="/login"', 
        'href="https://wa.me/916374913298?text=I%20am%20interested%20on%20that%20business"', 
        $content
    );

    file_put_contents($publicLandingFile, $content);
    echo "Fixed public_landing CTAs.\n";
}

// 2. Fix landing/index.blade.php footer social icons color
$indexLandingFile = 'd:/xamp/htdocs/Sass_admin/resources/views/landing/index.blade.php';
if (file_exists($indexLandingFile)) {
    $content = file_get_contents($indexLandingFile);
    
    $content = str_replace(
        'dark:text-slate-400 text-xs transition-colors"><i class="fab',
        'dark:text-white text-xs transition-colors"><i class="fab',
        $content
    );
    
    file_put_contents($indexLandingFile, $content);
    echo "Fixed index.blade.php footer icons.\n";
}

// 3. Launchshop Dev OTP Fixes
$wbFrontendFile = 'd:/xamp/htdocs/launchshop_dev/app/Http/Controllers/WebsiteBuilder/FrontendController.php';
if (file_exists($wbFrontendFile)) {
    $content = file_get_contents($wbFrontendFile);
    $content = str_replace('! (Test Master OTP: 123456)', '!', $content);
    file_put_contents($wbFrontendFile, $content);
    echo "Fixed FrontendController OTP msg.\n";
}

$wbCheckoutFile = 'd:/xamp/htdocs/launchshop_dev/resources/views/website_builder/front/checkout.blade.php';
if (file_exists($wbCheckoutFile)) {
    $content = file_get_contents($wbCheckoutFile);
    $content = str_replace(
        "var otpText = data.otp ? ' <span class=\"badge bg-dark text-white ms-1 px-2 py-1 fs-6\">OTP: ' + data.otp + '</span>' : '';",
        "var otpText = '';",
        $content
    );
    file_put_contents($wbCheckoutFile, $content);
    echo "Fixed checkout.blade.php OTP display.\n";
}

$lsStepFile = 'd:/xamp/htdocs/launchshop_dev/resources/views/front/step.blade.php';
if (file_exists($lsStepFile)) {
    $content = file_get_contents($lsStepFile);
    
    // Remove the OTP banner HTML completely
    $bannerPattern = '/<!-- OTP Code Display Banner \(shown after OTP is sent\) -->\s*<div id="otp-display-banner".*?<\/div>/is';
    $content = preg_replace($bannerPattern, '', $content);
    
    // Remove JS that fills the OTP code and shows the banner
    $content = preg_replace("/\\$('#otp-display-code')\\.text\\(.*?\\);/i", "", $content);
    $content = preg_replace("/\\$('#otp-display-banner')\\.removeClass\\('d-none'\\);/i", "", $content);
    $content = preg_replace("/\\$('#otp_code')\\.val\\([^\\)]*res\\.otp[^\\)]*\\);/i", "", $content);
    $content = preg_replace("/\\$('#otp_code')\\.val\\([^\\)]*data\\.otp[^\\)]*\\);/i", "", $content);
    
    file_put_contents($lsStepFile, $content);
    echo "Fixed step.blade.php OTP banner and autofill.\n";
}

echo "All fixes applied.\n";
