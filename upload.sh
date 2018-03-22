#!/usr/bin/env bash

rsync -avP *.php templates img css js src iclub:staging.intensity.club/wp-content/plugins/instructors
