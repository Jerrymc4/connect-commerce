#!/bin/bash
echo "Restarting Laravel Valet and clearing PHP cache..."
valet restart
php -r "if(function_exists('opcache_reset')) { opcache_reset(); echo \"OPcache reset successfully.\n\"; } else { echo \"OPcache not enabled.\n\"; }"
echo "Done! Now try debugging again."
echo "Access your Xdebug test at: http://connect-commerce.test/xdebug-info.php?XDEBUG_SESSION=VSCODE" 