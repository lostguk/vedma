FROM nginx:alpine

# Создаем непривилегированного пользователя
ARG MAC_ENV=false
RUN if [ "$MAC_ENV" = "false" ]; then \
    addgroup -g 1000 -S appgroup && \
    adduser -u 1000 -D -S -G appgroup appuser; \
fi

# Устанавливаем дополнительные пакеты для безопасности
RUN apk add --no-cache curl

# Рабочая директория проекта
WORKDIR /var/www/html

# Удаляем дефолтную конфигурацию
RUN rm /etc/nginx/conf.d/default.conf && \
    rm /etc/nginx/nginx.conf

# Создаем директории для логов и pid с правильными правами
RUN if [ "$MAC_ENV" = "false" ]; then \
    mkdir -p /var/log/nginx /var/cache/nginx /var/run && \
    chown -R appuser:appgroup /var/log/nginx /var/cache/nginx /var/run && \
    chmod -R 755 /var/log/nginx /var/cache/nginx /var/run; \
  else \
    mkdir -p /var/log/nginx /var/cache/nginx /var/run && \
    chmod -R 755 /var/log/nginx /var/cache/nginx /var/run; \
  fi

# Копируем базовую конфигурацию nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Устанавливаем права на конфигурацию только для dev/prod
RUN if [ "$MAC_ENV" = "false" ]; then \
    chown appuser:appgroup /etc/nginx/nginx.conf; \
  fi

# Expose порты
EXPOSE 80 443

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# --- USER только для dev/prod ---
ARG MAC_ENV=false
RUN if [ "$MAC_ENV" = "false" ]; then \
  echo 'USER appuser' >> /tmp/userfile; \
fi

# Запуск nginx
CMD ["nginx", "-g", "daemon off;"]
