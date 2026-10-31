#!/bin/sh
# HTTP smoke test. Usage: build/smoke.sh http://127.0.0.1:8080
# Works against `composer run:prod` and the production Docker image.
set -eu

base="${1:?usage: smoke.sh BASE_URL}"
failed=0

expect() {
    want="$1"
    shift
    # `|| true` keeps a dead server (curl exit 7) from aborting before the FAIL line.
    got=$(curl -s -o /dev/null -w '%{http_code}' "$@" || true)
    if [ "$got" != "$want" ]; then
        echo "FAIL: expected $want, got $got: $*"
        failed=1
    fi
}

expect 200 "$base/"
expect 200 "$base/ping"
expect 200 -I "$base/ping"
expect 200 -X POST -H 'Content-Type: application/json' -d '{"name":"Phel"}' "$base/greet"
expect 200 "$base/greet/Phel"
expect 400 "$base/greet/%FF"
expect 200 "$base/css/style.css"
expect 404 "$base/nope"
expect 405 -X DELETE "$base/ping"

if ! curl -s -I "$base/nope" | grep -qi '^server: '; then
    echo "FAIL: 404 response has no Server header"
    failed=1
fi

if [ "$failed" -ne 0 ]; then
    exit 1
fi
echo "smoke ok: $base"
