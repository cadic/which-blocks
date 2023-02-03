#!/bin/bash
npm run env run tests-wordpress "chmod -c ugo+w /var/www/html"
npm run env run tests-cli "wp rewrite structure '/%postname%/' --hard"

npm run env run tests-cli "wp post delete --force 1 2 3"
npm run env run tests-cli "wp import ./which-blocks.xml --authors=create"
