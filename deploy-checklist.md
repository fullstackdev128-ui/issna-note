# Checklist Déploiement ISSNA Notes 
  
## Avant upload 
- [x] php artisan test → tous les tests passent 
- [ ] APP_DEBUG=false dans .env 
- [x] php artisan config:cache 
- [x] php artisan route:cache   
- [x] php artisan view:cache 
- [ ] composer install --optimize-autoloader --no-dev 
  
## Sur Hostinger 
- [ ] PHP 8.2 activé 
- [ ] MySQL 8 créé (nom DB, user, password) 
- [ ] Uploader tous les fichiers SAUF : node_modules, .env, storage/logs 
- [ ] Créer .env sur le serveur (copier .env.production et remplir) 
- [ ] php artisan key:generate 
- [ ] php artisan migrate --force 
- [ ] php artisan storage:link 
- [ ] Copier images logos dans public/images/ 
- [ ] Vérifier permissions : storage/ et bootstrap/cache/ → chmod 775 
- [ ] Pointer document root vers /public 
  
## Vérifications post-déploiement 
- [ ] `https://notes.issna.cm`  → page login visible 
- [ ] Connexion super_admin fonctionne 
- [ ] Générer 1 relevé PDF → logos visibles 
- [ ] Créer 1 étudiant test → supprimer après 
