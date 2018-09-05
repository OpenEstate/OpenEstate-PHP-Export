#!/usr/bin/env bash
#
# Copyright (C) 2009-2018 OpenEstate.org
#

XGETTEXT="xgettext"
XGETTEXT_PARAMS="--package-version=2.0-dev --msgid-bugs-address=i18n@openestate.org --join-existing --from-code=UTF-8 --language=PHP"

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$DIR/src"


#
# Extract global translations.
#

rm -f "../i18n/template.pot"
touch "../i18n/template.pot"
find . \
    -name "*.php" \
    -not -path "./data/*" \
    -not -path "./include/TrueBV/*" \
    -not -path "./include/Gettext/*" \
    -not -path "./include/PHPMailer/*" \
    -not -path "./themes/*" \
    -exec "$XGETTEXT" --package-name="OpenEstate-PHP-Export" ${XGETTEXT_PARAMS} -o "../i18n/template.pot" {} \;


#
# Extract separate translations for each theme.
#

for i in $(ls -d themes/*/); do
    themePath=${i%%/}
    themeName=$(basename ${themePath})
    #echo $themePath
    #echo $themeName

    mkdir -p "../i18n/$themeName"
    rm -f "../i18n/$themeName/template.pot"
    touch "../i18n/$themeName/template.pot"
    find "./$themePath/" \
        -name "*.php" \
        -exec "$XGETTEXT" --package-name="OpenEstate-PHP-Export ($themeName theme)" ${XGETTEXT_PARAMS} -o "../i18n/$themeName/template.pot" {} \;
done
