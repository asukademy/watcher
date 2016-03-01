# Simular Watcher

## Cron Setting

```
# Watcher
0 12 * * 7  php /var/www/html/simular/watcher/cli/console queue create &> /dev/null
*/3 * * * * php /var/www/html/simular/watcher/cli/console queue dequeue &> /dev/null
```
