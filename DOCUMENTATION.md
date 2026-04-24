# DOCUMENTATION TECHNIQUE · ISSNA Notes

## 1. PRÉSENTATION DU PROJET
- **Contexte** : Application de gestion des notes développée pour l'**ISSNA** (Institut Supérieur des Sciences de la Nutrition et de l'Alimentation) en partenariat avec l'**IFPSCID**, Douala, Cameroun.
- **Objectif** : Digitaliser le processus complet de gestion académique : du référentiel LMD à la génération des relevés de notes PDF bilingues.
- **Périmètre V1** :
    - Gestion du référentiel (Filières, Spécialités, UE, EC).
    - Gestion des étudiants et campus.
    - Saisie sécurisée des notes (CC, SN, RP).
    - Calcul automatique des moyennes semestrielles et annuelles.
    - Génération de relevés de notes officiels.
- **Structure** : L'application gère deux campus et deux rôles (Super Admin et Admin Scolarité), centralisant toutes les données dans une interface unique.

## 2. STACK TECHNIQUE
- **Backend** : Laravel 11 (PHP 8.2), Eloquent ORM.
- **Frontend** : Blade Templates, TailwindCSS (CDN), Alpine.js (CDN).
- **PDF** : `barryvdh/laravel-dompdf` (moteur DomPDF).
- **Authentification** : Laravel Sanctum (Stateful session web).
- **Base de données** : MySQL 8 (Charset : `utf8mb4_unicode_ci`).
- **Outils** : Composer (PHP), Artisan (CLI), Git.
- **Justification** : Laravel 11 offre une structure moderne et sécurisée. L'usage des CDN (Tailwind/Alpine) permet une légèreté maximale sans étape de compilation complexe en production.

## 3. ARBRE DU PROJET
```text
C:.
|   .env
|   artisan
|   composer.json
|   DOCUMENTATION.md
|   README.md
|   
+---app
|   +---Http
|   |   +---Controllers
|   |   |   +---Admin
|   |   |   |       AnneeAcademiqueController.php
|   |   |   |       ElementConstitutifController.php
|   |   |   |       EtudiantController.php
|   |   |   |       FiliereController.php
|   |   |   |       NoteController.php
|   |   |   |       ReleveController.php
|   |   |   |       ResultatAnnuelController.php
|   |   |   |       ResultatSemestreController.php
|   |   |   |       SpecialiteController.php
|   |   |   |       UniteEnseignementController.php
|   |   |   |       UtilisateurController.php
|   |   |   \---Auth
|   |   |           LoginController.php
|   |   +---Middleware
|   |   |       CheckRole.php
|   +---Models
|   |       AnneeAcademique.php
|   |       Campus.php
|   |       ElementConstitutif.php
|   |       Etudiant.php
|   |       Filiere.php
|   |       Note.php
|   |       ResultatAnnuel.php
|   |       ResultatSemestre.php
|   |       Specialite.php
|   |       UniteEnseignement.php
|   |       User.php
|   \---Services
|           MatriculeGeneratorService.php
|           NoteCalculatorService.php
|           ResultatAnnuelService.php
|           
+---database
|   +---migrations
|   \---seeders
|           DatabaseSeeder.php
|           UserSeeder.php
|           ...
+---resources
|   +---views
|   |   +---admin
|   |   +---auth
|   |   +---notes
|   |   +---releves
|   |   \---resultats
+---routes
|       web.php
```

## 4. MODÈLE DE DONNÉES (10 Tables)
1. **campus** : Centres physiques (Douala, etc.).
2. **filieres** : Grands domaines (Sciences de la Santé, etc.).
3. **specialites** : Parcours spécifiques (Sciences Infirmières, etc.).
4. **unite_enseignements (UE)** : Regroupement de matières par semestre.
5. **element_constitutifs (EC)** : Matières individuelles avec crédits.
6. **annee_academiques** : Sessions annuelles (2023-2024, etc.).
7. **users** : Utilisateurs (Super Admin, Admin).
8. **etudiants** : Profils complets des apprenants.
9. **notes** : Valeurs CC/SN/RP avec traçabilité.
10. **resultat_semestres** & **resultat_annuels** : Tables de synthèse calculées.

**Diagramme de relations** :
`Filiere 1--N Specialite 1--N UE 1--N EC 1--N Note N--1 Etudiant`

## 5. RÔLES ET PERMISSIONS
| Fonctionnalité | Super Admin | Admin Scolarité |
| :--- | :---: | :---: |
| Gestion Utilisateurs | ✅ | ❌ |
| Config Référentiel | ✅ | ❌ |
| Création Étudiants | ✅ | ✅ |
| Saisie des Notes | ✅ | ✅ |
| Modification Note (Motif) | ✅ | ❌ |
| Calcul Résultats | ✅ | ✅ |
| Génération Relevés | ✅ | ✅ |

**Traçabilité** : Toute modification de note par un Super Admin requiert un motif obligatoire et enregistre l'auteur et la date.

## 6. RÉFÉRENTIEL ACADÉMIQUE
- **Structure LMD** : 30 crédits par semestre.
- **Niveaux** : 1 (Niveau 1) à 5 (Master 2).
- **Semestres** : Toujours notés S1 et S2 (relatifs à l'année d'étude).
- **Matricule** : `[AA][CODE2][SEQ4]` (ex: `24SI0001` pour 2024, Sciences Infirmières).

## 7. MOTEUR DE CALCUL
Calculs gérés par `NoteCalculatorService` :
1. **Note Finale EC** : `(CC * 0.4) + (SN * 0.6)`. Si Rattrapage (RP) présent, `Note = RP` (100%).
2. **Moyenne UE** : Moyenne pondérée par les crédits des EC.
3. **Validation UE** : `Moyenne ≥ 10/20` ET `Aucune note EC < 8/20`.
4. **Moyenne Semestrielle** : Moyenne arithmétique des moyennes d'UE.
5. **Grille MGP (INSES)** :
    - 18-20 : A+ (4.0) - Excellent
    - 16-17.99 : A (3.7) - Très Bien
    - 14-15.99 : B+ (3.3) - Bien
    - 12-13.99 : B (3.0) - Bien
    - 11-11.99 : C+ (2.3) - Assez Bien
    - 10-10.99 : C (2.0) - Passable

## 8. WORKFLOW PAR MODULE
- **Étudiants** : Création avec matricule auto-généré via `MatriculeGeneratorService`.
- **Saisie Notes** : Saisie par grille. Le RP est bloqué si aucune note SN n'est présente.
- **Résultats** : Preview dynamique avant validation en base pour verrouillage.
- **Relevés PDF** : Utilise DomPDF pour un rendu bilingue (FR/EN) professionnel avec logos.

## 9. AUTHENTIFICATION ET SÉCURITÉ
- Middleware `CheckRole` protège les accès.
- Vérification du statut `actif` de l'utilisateur.
- Journalisation des dernières connexions (`last_login`).

## 10. ROUTES PRINCIPALES
| Méthode | URI | Controller@méthode |
| :--- | :--- | :--- |
| GET | `/login` | LoginController@showForm |
| GET | `/dashboard` | DashboardController@index |
| GET | `/notes/saisie` | NoteController@saisie |
| POST | `/resultats/valider` | ResultatSemestreController@valider |
| POST | `/releves/generer` | ReleveController@generer |

## 11. SEEDERS
- **UserSeeder** : `admin@issna.cm` / `Issna@2026!` (Super Admin).
- **DatabaseSeeder** : Initialise tout le référentiel LMD par défaut.

## 12. DÉPLOIEMENT
- **Local** : `php artisan serve`.
- **Production** : Configurer `.env.production`, générer `APP_KEY`, et pointer le DocumentRoot vers `/public`.

## 13. GUIDE DÉVELOPPEUR
- **Prerequisites** : PHP 8.2+, Composer.
- **Installation** : `composer install`, `php artisan migrate --seed`.
- **Ajout Spécialité** : Via Menu Référentiel (Super Admin).

## 14. ROADMAP
- **V2** : Portail étudiant (consultation), Module enseignant.
- **V3** : Statistiques avancées, Export Excel, API Mobile.

## 15. DÉCISIONS TECHNIQUES
| Décision | Choix | Justification |
| :--- | :--- | :--- |
| Pas de Breeze/Jetstream | Custom Auth | Contrôle total sur les rôles et la logique de session. |
| Tailwind CDN | Sans Build | Facilité de maintenance sur des serveurs partagés type Hostinger. |
