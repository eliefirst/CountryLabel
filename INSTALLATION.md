# Installation Guide - ElielWeb_CountryLabel

## 🔴 Fixing "Class not found" Error

If you get this error:
```
Class "ElielWeb\CountryLabel\Plugin\Directory\Model\CountryPlugin" not found
```

Follow this guide step-by-step.

---

## Prerequisites

- Magento 2.4.6+ installed
- PHP 8.1+
- SSH/terminal access to your server
- File system permissions (ability to write to app/code/)

---

## Installation Steps

### Method 1: Manual Installation (Recommended)

#### Step 1: Copy Module Files

```bash
# From your local machine or Git repository
# Copy the entire CountryLabel folder to your Magento installation

# SSH into your server
cd /path/to/your/magento

# Create the directory structure
mkdir -p app/code/Elie

# Copy the module (adjust path to where you have the module)
cp -r /path/to/CountryLabel app/code/ElielWeb/

# Verify the files are in place
ls -la app/code/ElielWeb/CountryLabel/
```

**Expected directory structure:**
```
app/code/ElielWeb/CountryLabel/
├── Plugin/
│   └── Directory/
│       └── Model/
│           └── CountryPlugin.php
├── etc/
│   ├── module.xml
│   └── di.xml
├── i18n/
│   ├── en_US.csv
│   └── fr_FR.csv
├── composer.json
├── registration.php
├── README.md
├── HYVA_COMPATIBILITY.md
└── INSTALLATION.md (this file)
```

#### Step 2: Set Correct Permissions

```bash
# From Magento root directory
cd /path/to/your/magento

# Set ownership (replace www-data with your web server user)
chown -R www-data:www-data app/code/ElielWeb/CountryLabel/

# Set directory permissions
find app/code/ElielWeb/CountryLabel/ -type d -exec chmod 755 {} \;

# Set file permissions
find app/code/ElielWeb/CountryLabel/ -type f -exec chmod 644 {} \;
```

#### Step 3: Enable the Module

```bash
# From Magento root directory
cd /path/to/your/magento

# Enable the module
php bin/magento module:enable ElielWeb_CountryLabel

# Verify it's enabled
php bin/magento module:status ElielWeb_CountryLabel
# Should show: "Module is enabled"
```

#### Step 4: Run Magento Setup Commands (CRITICAL!)

**This is the most important step to fix "Class not found":**

```bash
# IMPORTANT: Run these commands in this exact order

# 1. Setup upgrade (registers the module)
php bin/magento setup:upgrade

# 2. Compile dependency injection (generates plugin factories)
php bin/magento setup:di:compile

# 3. Deploy static content (if needed)
php bin/magento setup:static-content:deploy -f en_US fr_FR

# 4. Clear all caches
php bin/magento cache:clean
php bin/magento cache:flush

# 5. Reindex (optional but recommended)
php bin/magento indexer:reindex
```

**Why each command is needed:**

- `setup:upgrade`: Registers module in database, creates schema if needed
- `setup:di:compile`: **CRITICAL** - Generates the plugin class factories. Without this, you get "Class not found"
- `setup:static-content:deploy`: Deploys static assets (translations)
- `cache:flush`: Clears all caches to ensure new code is loaded

#### Step 5: Verify Installation

```bash
# Check module status
php bin/magento module:status ElielWeb_CountryLabel

# Check if plugin is registered
php bin/magento setup:di:compile-multi-tenant 2>&1 | grep CountryPlugin

# Expected: No errors

# Check Magento logs
tail -f var/log/system.log
tail -f var/log/exception.log
```

---

### Method 2: Composer Installation (For Production)

If you want to install via Composer:

#### Step 1: Add to composer.json

```bash
cd /path/to/your/magento

# Add local repository
composer config repositories.elie-country-label path app/code/ElielWeb/CountryLabel

# Require the module
composer require elie/module-country-label:@dev

# Or for specific version
composer require elie/module-country-label:^1.0.1
```

#### Step 2: Follow Steps 3-5 from Method 1

Same as above (enable module, run setup commands, verify).

---

## Troubleshooting "Class not found"

### Issue 1: Still getting "Class not found" after setup:di:compile

**Possible causes:**

1. **Module not in correct directory**
   ```bash
   # Verify path exists
   ls -la app/code/ElielWeb/CountryLabel/Plugin/Directory/Model/CountryPlugin.php
   # If file not found, your module is in the wrong place
   ```

2. **registration.php not loaded**
   ```bash
   # Check if registration.php is readable
   cat app/code/ElielWeb/CountryLabel/registration.php

   # Verify it contains:
   # \Magento\Framework\Component\ComponentRegistrar::register(
   #     \Magento\Framework\Component\ComponentRegistrar::MODULE,
   #     'ElielWeb_CountryLabel',
   #     __DIR__
   # );
   ```

3. **DI compilation failed silently**
   ```bash
   # Remove generated files and recompile
   rm -rf generated/code/*
   rm -rf generated/metadata/*
   php bin/magento setup:di:compile

   # Watch for errors during compilation
   ```

4. **PHP namespace/class mismatch**
   ```bash
   # Verify the class file has correct namespace
   head -20 app/code/ElielWeb/CountryLabel/Plugin/Directory/Model/CountryPlugin.php

   # Should show:
   # namespace ElielWeb\CountryLabel\Plugin\Directory\Model;
   # class CountryPlugin
   ```

### Issue 2: Module enabled but plugin not working

```bash
# Check di.xml is loaded
grep -r "elie_country_label_override" var/

# If nothing found, di.xml might not be loaded
# Solution: Clear config cache
php bin/magento cache:clean config
php bin/magento setup:di:compile
```

### Issue 3: Works in CLI but not in web browser

**Cause:** Generated code exists for CLI but not for web

**Solution:**
```bash
# Deploy for all areas
php bin/magento setup:di:compile

# Or specifically:
php bin/magento setup:di:compile --area=frontend
php bin/magento setup:di:compile --area=adminhtml

# Clear FPC
php bin/magento cache:clean full_page
```

### Issue 4: Hyva Theme still crashes

If you followed all steps above and Hyva still doesn't work:

```bash
# 1. Verify you have v1.0.1+ (not v1.0.0)
cat app/code/ElielWeb/CountryLabel/composer.json | grep version
# Should show: "version": "1.0.1"

# 2. Check if afterLoadByCode exists (it should NOT in v1.0.1)
grep -n "afterLoadByCode" app/code/ElielWeb/CountryLabel/Plugin/Directory/Model/CountryPlugin.php
# Should return: no results

# 3. Clear Hyva cache
rm -rf pub/static/frontend/Hyva/*
php bin/magento setup:static-content:deploy -f --theme=Hyva/theme
php bin/magento cache:flush
```

---

## Testing the Installation

### Test 1: Check Module is Loaded

```bash
php bin/magento module:status ElielWeb_CountryLabel
# Expected: "ElielWeb_CountryLabel" in "List of enabled modules"
```

### Test 2: Check Plugin is Registered

```bash
# Search for plugin in generated code
find generated/code -name "*CountryPlugin*"
# Expected: Should find generated interceptor files
```

### Test 3: Test via CLI

```bash
# Create a test script
cat > test_country.php << 'EOF'
<?php
require 'app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();

$country = $objectManager->create(\Magento\Directory\Model\Country::class);
$country->loadByCode('TW');

echo "Country Code: " . $country->getCountryId() . "\n";
echo "Country Name: " . $country->getName() . "\n";
echo "Expected: Taiwan China Region\n";
EOF

php test_country.php
# Expected output: "Country Name: Taiwan China Region"
```

### Test 4: Test in Frontend (Hyva)

1. Go to checkout page
2. Add billing/shipping address
3. Select country dropdown
4. Look for "Taiwan China Region" (not "Taiwan")

### Test 5: Check Logs

```bash
# No errors should appear
tail -100 var/log/exception.log | grep -i country
tail -100 var/log/system.log | grep -i "elie\|country"
```

---

## Production Deployment Checklist

- [ ] Module files copied to `app/code/ElielWeb/CountryLabel/`
- [ ] File permissions set correctly (755 for dirs, 644 for files)
- [ ] `php bin/magento module:enable ElielWeb_CountryLabel` executed
- [ ] `php bin/magento setup:upgrade` executed
- [ ] `php bin/magento setup:di:compile` executed (MOST IMPORTANT!)
- [ ] `php bin/magento setup:static-content:deploy` executed
- [ ] All caches cleared
- [ ] Tested in staging environment first
- [ ] Verified "Taiwan China Region" appears in checkout
- [ ] Checked logs for errors
- [ ] Hyva frontend loads without crashes

---

## Rollback Instructions

If something goes wrong:

```bash
# 1. Disable the module
php bin/magento module:disable ElielWeb_CountryLabel

# 2. Run setup upgrade
php bin/magento setup:upgrade

# 3. Recompile
php bin/magento setup:di:compile

# 4. Clear caches
php bin/magento cache:flush

# 5. (Optional) Remove files
rm -rf app/code/ElielWeb/CountryLabel/
```

---

## Support

If you still encounter issues after following this guide:

1. Check Magento logs: `var/log/exception.log` and `var/log/system.log`
2. Check web server error logs (Apache/Nginx)
3. Verify Magento version compatibility (2.4.6+ required)
4. Verify PHP version (8.1+ required)
5. Check file system permissions

**Common issues resolved:**
- ✅ Class not found → Run `setup:di:compile`
- ✅ Hyva crash → Update to v1.0.1
- ✅ Plugin not working → Clear config cache
- ✅ Works in CLI not web → Deploy static content

---

## Version Info

- **Module Version:** 1.0.1
- **Magento Compatibility:** 2.4.6+
- **PHP Compatibility:** 8.1, 8.2, 8.3, 8.4
- **Hyva Compatibility:** ✅ Yes (v1.0.1+)
- **Luma Compatibility:** ✅ Yes
