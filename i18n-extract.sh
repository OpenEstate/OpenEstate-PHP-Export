#!/usr/bin/env bash
#
# Copyright (C) 2009-2018 OpenEstate.org
#

XGETTEXT="xgettext"
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd "$DIR/src"
rm -f "./locale/template.pot"
touch "./locale/template.pot"
find . -name "*.php" \
  -not -path "./data/*" \
  -not -path "./include/TrueBV/*" \
  -not -path "./include/Gettext/*" \
  -not -path "./include/PHPMailer/*" \
  -exec "$XGETTEXT" --join-existing --from-code="UTF-8" -o "./locale/template.pot" {} \;
