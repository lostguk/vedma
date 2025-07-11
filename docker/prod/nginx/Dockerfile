FROM nginx:1.26-alpine

# Remove default config shipped with the base image
RUN rm /etc/nginx/conf.d/default.conf

# Copy hardened configuration
COPY docker/prod/nginx/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]