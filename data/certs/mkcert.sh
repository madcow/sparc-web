#!/bin/bash

ROOTCA=sparc-ca
DOMAIN=sparc.local
CAKEY=$ROOTCA.key
CACRT=$ROOTCA.crt
CRT=$DOMAIN.crt
KEY=$DOMAIN.key
CSR=$DOMAIN.csr

openssl genrsa -out $CAKEY 2048

openssl req -new -x509 -days 365 -key $CAKEY \
	-subj "/C=DE/O=Madcow Software/CN=Madcow Root CA" \
	-out $CACRT

openssl req -newkey rsa:2048 -nodes -keyout $KEY \
	-subj "/C=DE/O=Madcow Software/CN=*.sparc.local" \
	-out $CSR

openssl x509 \
	-req -extfile <(printf "subjectAltName=DNS:*.$DOMAIN,DNS:$DOMAIN") \
	-days 365 -in $CSR -CA $CACRT -CAkey $CAKEY -CAcreateserial -out $CRT

sudo cp $CACRT /usr/local/share/ca-certificates
sudo update-ca-certificates

sudo cp $CRT /etc/ssl/certs/ 
sudo cp $KEY /etc/ssl/private/
sudo service apache2 restart
