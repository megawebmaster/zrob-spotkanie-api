#!/usr/bin/env bash
# Install PHP dependencies
brew install bison re2c gmp libsodium imagemagick pkg-config libgd libiconv oniguruma libzip openssl
# Install app dependencies
brew install libpq
# Install PHP
PHP_CONFIGURE_OPTIONS="--with-openssl=$(brew --prefix openssl) --with-iconv=$(brew --prefix libiconv)" asdf install

