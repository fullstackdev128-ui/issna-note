# ISSNA Notes — Gestion Académique IFPSCID

📖 **Documentation complète → voir [DOCUMENTATION.md](DOCUMENTATION.md)**

## À propos du projet
ISSNA Notes est une application web robuste conçue pour la gestion des notes et des résultats académiques selon le système LMD. Elle permet la saisie des notes, le calcul automatique des moyennes (semestrielles et annuelles) et la génération de relevés de notes PDF officiels.

## État actuel du projet (Roadmap)
- [x] **Phase 1-4** : Architecture, Référentiel LMD, Gestion Étudiants.
- [x] **Phase 5-6** : Saisie des notes, Calculateur MGP/GPA (INSES).
- [x] **Phase 7-8** : Résultats annuels, Génération PDF bilingue.
- [x] **Phase 9** : Optimisations SQL (Index), Tests Unitaires, Cache Production.
- [ ] **Prochaine étape** : Déploiement final sur Hostinger.

## Installation Rapide
1. Clonez le projet.
2. `composer install`.
3. Configurez votre `.env`.
4. `php artisan migrate --seed`.
5. `php artisan serve`.

---
*Projet développé pour l'ISSNA Douala, Cameroun.*
