#!/usr/bin/env bash
#
# Copyright 2009-2018 OpenEstate.org.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#      http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
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
