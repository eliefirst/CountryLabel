# Elie_CountryLabel - Module Magento 2

## 🚨 IMPORTANT - Lisez ceci en premier !

**Si vous rencontrez l'erreur :**
```
Class "Elie\CountryLabel\Plugin\Directory\Model\CountryPlugin" not found
```

👉 **[Consultez le guide d'installation détaillé INSTALLATION.md](INSTALLATION.md)**

**Solution rapide :**
```bash
php bin/magento setup:di:compile
php bin/magento cache:flush
```

---

## 📋 Description

Module Magento 2 pour remplacer automatiquement "Taiwan" par "Taiwan China Region" dans toute la plateforme.

**Version:** 1.0.1 ⚡ **Critical Hyva Fix**
**Compatible:** Magento 2.4.6+ | PHP 8.1, 8.2, 8.3, 8.4 | **Hyva Theme ✅**

---
 
## ✨ Fonctionnalités
 
### Override automatique
 
- ✅ **Frontend** : Checkout, pages produits, compte client
- ✅ **Admin** : Commandes, expéditions, factures, avoirs
- ✅ **PDF** : Tous documents générés
- ✅ **Exports** : CSV, XML
- ✅ **API REST/SOAP** : Toutes réponses
- ✅ **Emails** : Confirmations, notifications
 
### Approche hybride (Plugin + i18n)
 
1. **Plugin PHP** : Intercepte `Country::getName()`
2. **Traductions i18n** : Fichiers CSV (en_US, fr_FR)
 
**Résultat :** Couverture à 100%
 
---
 
## 📦 Installation

### ⚠️ Guide d'installation complet

**Pour une installation sans erreur "Class not found" :**
👉 **[Consultez INSTALLATION.md pour le guide complet](INSTALLATION.md)**

### Installation rapide (résumé)

```bash
# 1. Copier le module
cp -r CountryLabel /path/to/magento/app/code/Elie/

# 2. Activer et installer
php bin/magento module:enable Elie_CountryLabel
php bin/magento setup:upgrade
php bin/magento setup:di:compile  # CRITIQUE - évite "Class not found"
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
```

**⚠️ La commande `setup:di:compile` est OBLIGATOIRE** sinon vous aurez l'erreur "Class not found"

### ⚡ Migration v1.0.0 → v1.0.1 (Hyva Fix)

Si vous utilisez déjà v1.0.0 et rencontrez des problèmes avec Hyva :

```bash
# 1. Mettre à jour les fichiers du module
cd /path/to/magento/app/code/Elie/CountryLabel
git pull origin main

# OU copier manuellement les nouveaux fichiers

# 2. Recompiler (important!)
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush

# 3. Tester Hyva - devrait fonctionner maintenant!
```

---
 
## 🎯 Mapping par défaut
 
| Code | Original | Nouveau |
|------|----------|---------|
| TW | Taiwan | **Taiwan China Region** |
 
---
 
## 🔧 Personnalisation
 
### Ajouter d'autres pays
 
Éditer `Plugin/Directory/Model/CountryPlugin.php` :
 
```php
private array $countryLabelMapping = [
    'TW' => 'Taiwan China Region',
    'US' => 'United States of America',
    'GB' => 'United Kingdom',
];
```
 
### Ajouter des traductions
 
Créer `i18n/es_ES.csv` :
```csv
"Taiwan","Taiwan China Region"
```
 
---
 
## 🧪 Tests
 
### Frontend
1. Checkout avec adresse Taiwan
2. Vérifier : "Taiwan China Region" ✅
 
### Admin
1. Sales → Orders → Commande avec Taiwan
2. Vérifier dans l'adresse ✅
 
### PDF
1. Générer une facture PDF
2. Vérifier le nom du pays ✅
 
### API
```bash
curl -X GET "/rest/V1/directory/countries/TW"
# Résultat attendu : "Taiwan China Region"
```
 
---
 
## 🔍 Débogage
 
```bash
# Module activé ?
php bin/magento module:status Elie_CountryLabel
 
# Vider caches
php bin/magento cache:flush
 
# Recompiler
php bin/magento setup:di:compile
```
 
---
 
## 📊 Compatibilité

- ✅ Magento 2.4.6+
- ✅ PHP 8.1 - 8.4
- ✅ Tous modules tiers utilisant `Country` standard
- ✅ **Hyva Theme** - Compatible à 100% (voir [HYVA_COMPATIBILITY.md](HYVA_COMPATIBILITY.md))
- ✅ Luma Theme
- ✅ Tous thèmes personnalisés

---
 
## ⚠️ Notes
 
### Scope du changement
- ✅ Modifie l'affichage uniquement
- ❌ Ne modifie pas le code pays (reste "TW")
- ❌ Ne modifie pas les données en BDD
 
### Performance
- ✅ Impact minimal (plugin léger)
- ✅ Pas de requêtes SQL supplémentaires
 
---
 
## 🏗️ Architecture
 
```
CountryLabel/
├── Plugin/Directory/Model/
│   └── CountryPlugin.php       (Logique principale)
├── etc/
│   ├── module.xml              (Déclaration module)
│   └── di.xml                  (Plugin declaration)
├── i18n/
│   ├── en_US.csv               (Traductions EN)
│   └── fr_FR.csv               (Traductions FR)
├── composer.json
└── registration.php
```
 
---
 
## 📝 Changelog

### v1.0.1 (2025-12-01) ⚡ **CRITICAL HYVA FIX**
- 🔴 **FIX MAJEUR:** Résout l'erreur `Class "CountryPlugin" not found`
- 🔴 **FIX MAJEUR:** Résout le crash frontend avec Hyva Theme
- ✅ Suppression du plugin `afterLoadByCode()` (causait conflit GraphQL avec Hyva)
- ✅ Amélioration du plugin `afterGetName()` avec type checking robuste
- ✅ Ajout de validation pour éviter les erreurs avec objets Country non initialisés
- ✅ Ajout de `minimum-stability` dans composer.json
- ✅ Création du guide INSTALLATION.md avec troubleshooting complet
- ✅ Compatible Hyva Theme (testé et validé)
- ✅ Compatible Luma Theme (pas de régression)
- ✅ Meilleure gestion des edge cases

**⚠️ Si vous utilisez v1.0.0 avec Hyva :** Mettez à jour immédiatement !
**⚠️ Si vous avez "Class not found" :** Exécutez `php bin/magento setup:di:compile`

### v1.0.0 (2024-11-27)
- ✅ Première version
- ✅ Plugin sur `Country::getName()` et `Country::loadByCode()`
- ✅ Traductions i18n
- ✅ Mapping Taiwan → Taiwan China Region
- ✅ Compatible Magento 2.4.8-p3 / PHP 8.4
- ❌ **Incompatible Hyva Theme** (corrigé en v1.0.1)
 
---
 
## 📄 License
 
Proprietary - Usage interne uniquement
 
---
 
## 👥 Auteur
 
**Elie** - Développement initial
 
🎉 **Taiwan → Taiwan China Region**
