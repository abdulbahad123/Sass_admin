-- Clean SQL template for Website Builder agency dynamic database provisioning
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;

-- Base Table 1: admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admins` (`id`, `role_id`, `username`, `email`, `first_name`, `last_name`, `image`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Admin1@Launchshop', 'admin@example.com', 'Launchshop', 'Admin', NULL, '$2y$10$gcG9UIs4OvLNlxKQ9UWNyeX4XfF8hw0yhDsOK3usRNSaD.4sCfQrG', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Base Table 2: languages
CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT 0,
  `rtl` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `rtl`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Base Table 3: basic_settings
CREATE TABLE IF NOT EXISTS `basic_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `language_id` int(11) DEFAULT NULL,
  `website_title` varchar(255) DEFAULT 'Website Builder',
  `base_color` varchar(30) DEFAULT '6366f1',
  `base_color_2` varchar(255) DEFAULT '8b5cf6',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `basic_settings` (`id`, `language_id`, `website_title`, `base_color`, `base_color_2`, `created_at`, `updated_at`) VALUES
(1, 1, 'Website Builder', '6366f1', '8b5cf6', NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Base Table 4: basic_extendeds
CREATE TABLE IF NOT EXISTS `basic_extendeds` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `language_id` int(11) DEFAULT NULL,
  `timezone` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `basic_extendeds` (`id`, `language_id`, `timezone`, `created_at`, `updated_at`) VALUES
(1, 1, 'Asia/Kolkata', NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Base Table 5: socials
CREATE TABLE IF NOT EXISTS `socials` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `icon` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `serial_number` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Base Table 6: users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Base Table 7: packages
CREATE TABLE IF NOT EXISTS `packages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 1: wb_landing_settings
CREATE TABLE IF NOT EXISTS `wb_landing_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hero_badge` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '⚡ No-coding required',
  `hero_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Build Your Website in Just Few Minutes',
  `hero_subtitle` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_primary_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Get Started Free',
  `cta_primary_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#pricing',
  `cta_secondary_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'View Templates',
  `cta_secondary_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#templates',
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6366f1',
  `secondary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#8b5cf6',
  `trust_badges` json DEFAULT NULL,
  `features_data` json DEFAULT NULL,
  `process_data` json DEFAULT NULL,
  `testimonials_data` json DEFAULT NULL,
  `faq_data` json DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hello@websitebuilder.com',
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '+1 (800) 123-4567',
  `contact_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_css` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `wb_landing_settings` (`id`, `hero_badge`, `hero_title`, `hero_subtitle`, `cta_primary_text`, `cta_primary_url`, `cta_secondary_text`, `cta_secondary_url`, `primary_color`, `secondary_color`, `trust_badges`, `features_data`, `process_data`, `contact_email`, `contact_phone`, `footer_text`, `created_at`, `updated_at`) VALUES
(1, '⚡ No-coding required', 'Build Your Website in Just Few Minutes', 'Create beautiful, professional websites in minutes with our intuitive drag-and-drop builder and AI-powered features.', 'Get Started Free', '#pricing', 'View Templates', '#templates', '#6366f1', '#8b5cf6', '[{"icon": "shield-check", "text": "No Technical Skills Required"}, {"icon": "zap", "text": "Instant Setup"}, {"icon": "layers", "text": "10k+ Business Templates"}]', '[{"icon": "smartphone", "desc": "Looks perfect on every screen size.", "title": "Mobile Optimized"}, {"icon": "search", "desc": "Built to rank high on Google search.", "title": "SEO Ready"}, {"icon": "globe", "desc": "Connect your custom .com domain instantly.", "title": "Custom Domain"}, {"icon": "zap", "desc": "Lightning-fast load times globally.", "title": "Fast Hosting"}, {"icon": "lock", "desc": "Free security certificate included.", "title": "Secure SSL"}, {"icon": "bar-chart-3", "desc": "Track your visitors and traffic easily.", "title": "Analytics"}, {"icon": "sparkles", "desc": "Regenerate content anytime with AI.", "title": "AI Page Rewriter"}, {"icon": "award", "desc": "Create & manage websites under your own brand.", "title": "Client-Ready White Label"}]', '[{"desc": "Select from our gallery of professionally designed templates.", "step": "01", "title": "Choose a Template"}, {"desc": "Use our visual editor to update text, images, and colors.", "step": "02", "title": "Customize Content"}, {"desc": "Connect your custom domain and go live with a single click.", "step": "03", "title": "Publish to World"}]', 'hello@websitebuilder.com', '+1 (800) 123-4567', 'The easiest way to build professional websites. No coding required.', NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Table 2: wb_templates
CREATE TABLE IF NOT EXISTS `wb_templates` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Portfolio',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'images/hero-section.png',
  `demo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_free` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wb_templates_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `wb_templates` (`id`, `name`, `slug`, `category`, `description`, `price`, `is_free`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Business Classic', 'business-classic', 'Agency', 'Professional business website template with clean design.', 0.00, 1, 1, 1, NOW(), NOW()),
(2, 'Startup Launch', 'startup-launch', 'Startup', 'Modern startup template with problem-solution structure.', 49.00, 0, 1, 2, NOW(), NOW()),
(3, 'Creative Agency', 'creative-agency', 'Portfolio', 'Bold and creative template for digital agencies and studios.', 27.00, 0, 1, 3, NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Table 3: wb_packages
CREATE TABLE IF NOT EXISTS `wb_packages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monthly_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `yearly_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_websites` int(11) NOT NULL DEFAULT 1,
  `storage_limit_mb` int(11) NOT NULL DEFAULT 5000,
  `custom_domain_allowed` tinyint(1) NOT NULL DEFAULT 1,
  `white_label_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `ai_tools_allowed` tinyint(1) NOT NULL DEFAULT 1,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `features_list` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wb_packages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `wb_packages` (`id`, `name`, `slug`, `monthly_price`, `yearly_price`, `max_websites`, `storage_limit_mb`, `is_popular`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Starter', 'starter', 9.00, 90.00, 1, 5000, 0, 1, NOW(), NOW()),
(2, 'Pro', 'pro', 19.00, 190.00, 10, 50000, 1, 1, NOW(), NOW()),
(3, 'Business', 'business', 39.00, 390.00, 100, 500000, 0, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Table 4: wb_customers
CREATE TABLE IF NOT EXISTS `wb_customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subdomain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `sso_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sso_token_expires_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wb_customers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 5: wb_staff
CREATE TABLE IF NOT EXISTS `wb_staff` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Support Agent',
  `permissions` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wb_staff_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 6: wb_pages
CREATE TABLE IF NOT EXISTS `wb_pages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_home` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 7: wb_sections
CREATE TABLE IF NOT EXISTS `wb_sections` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hero',
  `content` json DEFAULT NULL,
  `styles` json DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 8: wb_agency_settings
CREATE TABLE IF NOT EXISTS `wb_agency_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DesignAGENCY',
  `site_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `top_announcement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'We help businesses grow with creative digital solutions.',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info@designagency.com',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '+1 (234) 567-890',
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_badge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Creative Digital Solutions',
  `hero_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_subtitle` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'assets/website_builder/Templates/Digital_agency/hero_banner.png',
  `primary_btn_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Get Started',
  `primary_btn_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#contact',
  `secondary_btn_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'View Our Work',
  `secondary_btn_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#portfolio',
  `stats_data` json DEFAULT NULL,
  `services_data` json DEFAULT NULL,
  `portfolio_data` json DEFAULT NULL,
  `testimonials_data` json DEFAULT NULL,
  `about_hero_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_hero_subtitle` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `story_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `story_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mission_vision_data` json DEFAULT NULL,
  `team_members_data` json DEFAULT NULL,
  `contact_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_subtitle` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `faqs_data` json DEFAULT NULL,
  `social_links` json DEFAULT NULL,
  `footer_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_quick_links` json DEFAULT NULL,
  `footer_legal_links` json DEFAULT NULL,
  `custom_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_domain_status` tinyint(4) NOT NULL DEFAULT 0,
  `blogs_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 9: wb_agency_inquiries
CREATE TABLE IF NOT EXISTS `wb_agency_inquiries` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 10: wb_template_purchases
CREATE TABLE IF NOT EXISTS `wb_template_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'digital_agency',
  `template_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Digital Agency',
  `razorpay_payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razorpay_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razorpay_signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 499.00,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
