#!/usr/bin/env bash
chmod -R 777 node_modules && \
touch package-lock.json && \
chmod 777 package-lock.json && \
touch public/mix-manifest.json && \
chmod 777 public/mix-manifest.json && \
touch public/js/app.js
chmod -R 777 public/js/*