#!/usr/bin/env bash
#
# Copyright (C) 2009-2018 OpenEstate.org
#

MSGFMT="msgfmt"
MSGFMT_PARAMS=""

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
#cd "$DIR/src"


#
# Compile global translations.
#

rm -f "$DIR/src/locale/*.mo"
for i in $(ls "$DIR/i18n"); do
    path="$DIR/i18n/$i"
    if [ ! -f $path ]; then
        continue
    fi

    suffix="${path##*.}"
    if [ "$suffix" != "po" ]; then
        continue
    fi

    lang="$(basename ${path%%.*})"
    #echo "$lang"
    mkdir -p "$DIR/src/locale"
    "$MSGFMT" -o "$DIR/src/locale/$lang.mo" --language="$lang" ${MSGFMT_PARAMS} "$path"
done


#
# Compile separate translations for each theme.
#

for i in $(ls -d ${DIR}/i18n/*/); do
    themePath=${i%%/}
    themeName=$(basename ${themePath})

    rm -f "$DIR/src/themes/$themeName/locale/*.mo"
    for j in $(ls "$themePath"); do
        path="$themePath/$j"
        #echo "$path"
        if [ ! -f $path ]; then
            continue
        fi

        suffix="${path##*.}"
        if [ "$suffix" != "po" ]; then
            continue
        fi

        lang="$(basename ${path%%.*})"
        #echo "$lang"
        mkdir -p "$DIR/src/themes/$themeName/locale"
        "$MSGFMT" -o "$DIR/src/themes/$themeName/locale/$lang.mo" --language="$lang" ${MSGFMT_PARAMS} "$path"
    done
done
