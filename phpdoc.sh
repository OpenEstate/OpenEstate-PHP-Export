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

PROJECT="OpenEstate-PHP-Export"
VERSION="1.7-SNAPSHOT"
PHPDOC="phpDocumentor.phar"
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

rm -Rf "$DIR/phpdoc"
rm -Rf "$DIR/phpdoc-cache"

"$PHPDOC" --force --quiet \
  --target="$DIR/phpdoc" \
  --directory="$DIR/src/include" \
  --cache-folder="$DIR/phpdoc-cache" \
  --title="$PROJECT $VERSION API"

rm -Rf "$DIR/phpdoc-cache"
