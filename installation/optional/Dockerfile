FROM phusion/baseimage:latest

CMD ["/sbin/my_init"]

RUN rm -rf /var/lib/apt/lists/* && apt-get clean

RUN export LANG=C.UTF-8 && add-apt-repository -y ppa:ondrej/php && apt-get update && apt-get install -y apache2 php7.2 php7.2-mbstring wget python3-pip

RUN mkdir /etc/service/apache2

RUN echo '#!/bin/bash\nexec apache2ctl -k start -X' > /etc/service/apache2/run

RUN chmod a+x /etc/service/apache2/run

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN wget https://phar.phpunit.de/phpunit-7.1.4.phar --no-check-certificate

RUN chmod +x phpunit-7.1.4.phar

RUN mv phpunit-7.1.4.phar /usr/local/bin/phpunit

RUN useradd -ms /bin/bash kurukshetra -G www-data

RUN chgrp www-data /var/www/html

RUN chmod g+rwx  /var/www/html

# Installing python requirements

RUN pip3 install flask