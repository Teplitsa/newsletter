#!/usr/bin/env bash

rsync -avzr --exclude='.DS_Store' ./wp-content/themes/mailer/issues-themes/teplitsa  ./wp-content/uploads/wysija/themes/
rsync -avz  ./wp-content/themes/mailer/issues-themes/email_template.html  ./wp-content/plugins/wysija-newsletters/tools/templates/newsletter/email/email_template.html