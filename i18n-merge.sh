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

MSGMERGE="msgmerge"
MSGMERGE_PARAMS="--update"

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$DIR/i18n"


#
# Merge global translations.
#

find . \
    -maxdepth 1 \
    -type f \
    -name "*.po" \
    -exec "$MSGMERGE" ${MSGMERGE_PARAMS} {} ./template.pot \;


#
# Merge separate translations for each theme.
#

for i in $(ls -d */); do
    themePath=${i%%/}
    themeName=$(basename ${themePath})
    #echo $themePath
    #echo $themeName

    find "$themePath" \
        -maxdepth 1 \
        -type f \
        -name "*.po" \
        -exec "$MSGMERGE" ${MSGMERGE_PARAMS} {} "$themePath/template.pot" \;
done
