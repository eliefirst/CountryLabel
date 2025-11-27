# Elie_CountryLabel - Module Magento 2
 
## 📋 Description
 
Module Magento 2 pour remplacer automatiquement "Taiwan" par "Taiwan China Region" dans toute la plateforme.
 
**Version:** 1.0.0
**Compatible:** Magento 2.4.8-p3 | PHP 8.1, 8.2, 8.3, 8.4
 
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
 
```bash
# 1. Copier le module
cp -r CountryLabel /path/to/magento/app/code/Elie/
 
# 2. Activer
php bin/magento module:enable Elie_CountryLabel
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
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
 
### v1.0.0 (2024-11-27)
- ✅ Première version
- ✅ Plugin sur `Country::getName()`
- ✅ Traductions i18n
- ✅ Mapping Taiwan → Taiwan China Region
- ✅ Compatible Magento 2.4.8-p3 / PHP 8.4
 
---
 
## 📄 License
 
Proprietary - Usage interne uniquement
 
---
 
## 👥 Auteur
 
**Elie** - Développement initial
 
🎉 **Taiwan → Taiwan China Region**
