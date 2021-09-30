# OpenSSL SSL certificates

You can generate self-signed SSL certificates for local development using OpenSSL using the command below. Browsers won't fully trust these certificates, but they will be fully functional after you added an exception.

**Note**: Replace `myproject.test` in the following command with your local virtual hostname
**Note**: You will need to change the path to your host machine's `openssl.cnf` file in the command. This differs based on your host machine's OS and configuration, but these are common locations:
    - Linux: `/usr/lib/ssl/openssl.cnf` or `/usr/local/ssl/openssl.cnf`
    - Mac OS: `/System/Library/OpenSSL/openssl.cnf`

```
openssl req -newkey rsa:2048 -x509 -nodes -keyout services/ssl/myproject.test.selfsigned-private.key -new -out services/ssl/myproject.test.selfsigned-public.crt -subj /CN=myproject.test -reqexts SAN -extensions SAN -config <(cat /usr/lib/ssl/openssl.cnf <(printf '[SAN]\nsubjectAltName=DNS:myproject.test')) -sha256 -days 3650
```
