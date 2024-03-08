#! /bin/sh

echo "Creating network file"
cat <<EOF >$1 
network={
	ssid="$2"
	psk="$3"
	}
EOF
