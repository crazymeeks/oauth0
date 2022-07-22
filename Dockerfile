FROM ubuntu:20.04

RUN apt -y --fix-missing update && \
# Install apache, PHP, and supplimentary programs, openssh-server, curl, and lynx-cur
apt -y install software-properties-common && \
LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php && \
apt -y update && apt-cache pkgnames | grep php7.2 && apt -y update && apt -y install nginx php7.2 php7.2-fpm \ 
&& apt -y update && apt -y install php7.2-cli php7.2-gd php7.2-intl php7.2-common php7.2-mysql php7.2-curl curl \
php7.2-dom zip unzip php7.2-xml \
php7.2-zip php7.2-mbstring \
php7.2-json php7.2-opcache php7.2-dev php7.2-sqlite3 php7.2-xdebug git vim iputils-ping \
&& apt-cache search php7.2 && apt -y update && \
curl -sS https://getcomposer.org/installer -o composer-setup.php && \
curl -s https://getcomposer.org/installer | php && \
mv composer.phar /usr/local/bin/composer && \
apt install sqlite3 libsqlite3-dev

RUN rm /etc/nginx/sites-enabled/default
RUN rm /etc/nginx/sites-available/default
# RUN ln -s /etc/nginx/sites-available/vhost-nginx.conf /etc/nginx/sites-enabled/vhost
# Set php version to use
RUN update-alternatives --set php /usr/bin/php7.2

WORKDIR /var/www/web


EXPOSE 80
EXPOSE 3000
EXPOSE 443

CMD service php7.2-fpm start && nginx -g "daemon off;"