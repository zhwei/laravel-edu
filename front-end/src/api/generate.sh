#!/bin/bash

cd "$(dirname "$0")"
openapi-generator generate -i http://127.0.0.1:8000/ -g typescript-axios -o .
