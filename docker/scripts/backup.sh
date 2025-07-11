#!/bin/sh

# Backup script for PostgreSQL database
# Runs daily via cron

set -e

# Configuration
BACKUP_DIR="/backups"
DB_HOST="postgres"
DB_PORT="5432"
DB_NAME="${DB_DATABASE}"
DB_USER="${DB_USERNAME}"
RETENTION_DAYS=7

# Create backup directory if not exists
mkdir -p "$BACKUP_DIR"

# Generate backup filename with timestamp
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/backup_${DB_NAME}_${TIMESTAMP}.sql.gz"

# Create backup
echo "Starting backup at $(date)"
pg_dump -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" --no-owner --no-acl | gzip > "$BACKUP_FILE"

# Check if backup was successful
if [ $? -eq 0 ]; then
    echo "Backup completed successfully: $BACKUP_FILE"
    echo "Size: $(du -h "$BACKUP_FILE" | cut -f1)"
    
    # Remove old backups
    echo "Removing backups older than $RETENTION_DAYS days"
    find "$BACKUP_DIR" -name "backup_${DB_NAME}_*.sql.gz" -type f -mtime +$RETENTION_DAYS -delete
    
    # List remaining backups
    echo "Current backups:"
    ls -lh "$BACKUP_DIR"/backup_${DB_NAME}_*.sql.gz 2>/dev/null || echo "No backups found"
else
    echo "Backup failed!" >&2
    exit 1
fi

# Add cron job (run once at container start)
if [ ! -f /var/spool/cron/crontabs/root ]; then
    echo "0 2 * * * /backup.sh > /proc/1/fd/1 2>/proc/1/fd/2" | crontab -
    echo "Cron job added for daily backup at 2 AM"
fi