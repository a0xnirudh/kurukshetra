FROM phusion/baseimage:latest

CMD ["/sbin/my_init"]

RUN export LANG=C.UTF-8 && add-apt-repository -y ppa:ondrej/php && apt-get update && apt-get install -y apache2 php5.6 wget

RUN mkdir /etc/service/apache2

RUN echo '#!/bin/bash\nexec apache2ctl -k start -X' > /etc/service/apache2/run

RUN chmod a+x /etc/service/apache2/run

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN wget https://phar.phpunit.de/phpunit-4.8.1.phar --no-check-certificate

RUN chmod +x phpunit-4.8.1.phar

RUN mv phpunit-4.8.1.phar /usr/local/bin/phpunit
