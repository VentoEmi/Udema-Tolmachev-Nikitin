FROM mysql:8

RUN echo "[mysqld]" >> /etc/mysql/my.cnf
RUN echo "default-authentication-plugin=mysql_native_password" >> /etc/mysql/my.cnf
RUN echo "bind-address=0.0.0.0" >> /etc/mysql/my.cnf
RUN chown -R mysql:mysql /docker-entrypoint-initdb.d/