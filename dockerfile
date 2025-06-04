# 1. On utilise l'image officielle PHP avec Apache intégrée
FROM php:8.2-apache

# 2. Copier tous les fichiers de ton projet dans le dossier web d'Apache
COPY . /var/www/html/

# 3. Donner les bons droits (optionnel mais recommandé)
RUN chown -R www-data:www-data /var/www/html

# 4. Exposer le port 80 (HTTP)
EXPOSE 80

# 5. Commande par défaut pour lancer Apache en mode foreground
CMD ["apache2-foreground"]
