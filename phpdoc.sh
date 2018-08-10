#!/usr/bin/env bash
#
# Copyright (C) 2009-2018 OpenEstate.org
#

PHPDOC="phpDocumentor.phar"
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

rm -Rf "$DIR/phpdoc"
rm -Rf "$DIR/phpdoc-cache"

"$PHPDOC" --force --quiet \
  --target="$DIR/phpdoc" \
  --directory="$DIR/src/include" \
  --cache-folder="$DIR/phpdoc-cache" \
  --title="OpenEstate-PHP-Export"

rm -Rf "$DIR/phpdoc-cache"
