FROM debian:buster

WORKDIR /

RUN rm /var/lib/mysql/ib_logfile0 /var/lib/mysql/ib_logfile1 /var/lib/aria_log_control

CMD ["service", "mysql", "restart"]