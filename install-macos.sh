#!/usr/bin/env bash
# Install dependencies
brew install bison re2c gmp libsodium imagemagick pkg-config libgd libiconv oniguruma libzip openssl
# Install PHP
PHP_CONFIGURE_OPTIONS="--with-openssl=$(brew --prefix openssl) --with-iconv=$(brew --prefix libiconv)" PHP_WITHOUT_PEAR=yes asdf install

