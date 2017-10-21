#!/usr/bin/env sh

isCommand() {
  for cmd in \
    "about" \
    "list" \
    "tweets:delete"
  do
    if [ -z "${cmd#"$1"}" ]; then
      return 0
    fi
  done

  return 1
}

# check if the first argument passed in looks like a flag
if [ "$(printf %c "$1")" = '-' ]; then
  set -- /usr/local/bin/dumb-init -- php tweet "$@"
# check if the first argument passed in is composer
elif [ "$1" = 'php' ]; then
  set -- /usr/local/bin/dumb-init -- "$@"
# check if the first argument passed in matches a known command
elif isCommand "$1"; then
  set -- /usr/local/bin/dumb-init -- php tweet "$@"
fi

exec "$@"