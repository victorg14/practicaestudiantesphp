FROM debian:12

RUN apt update && apt install apache2 php libapache2-mod-php nano php-pgsql curl -y 

RUN a2enmod rewrite headers expires 

WORKDIR /var/www/html

EXPOSE 80 

CMD ["apache2ctl","-D", "FOREGROUND"]