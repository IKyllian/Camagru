FROM debian:buster

WORKDIR /

RUN apt-get update \
	&& apt-get install -y nginx sendmail

COPY nginx/nginx.conf /etc/nginx/conf.d/default.conf

COPY ./srcs/ /var/www/html/

EXPOSE 3000

CMD ["nginx", "-g", "daemon off;"]