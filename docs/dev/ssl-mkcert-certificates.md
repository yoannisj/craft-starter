# Mkcert SSL certificates

If you have [mkcert](https://mkcert.dev) installed on your host machine, you can create SSL certificates for local development that are recognized by your local browser using the command below:

**Note**: These certificates will not be trusted by all browsers on all platforms. [Learn more](https://github.com/FiloSottile/mkcert#supported-root-stores)
**Note**: Replace `myproject.test` in the following command with your local virtual hostname

```
mkcert -key-file services/ssl/myproject.test.mkcert-public.pem -cert-file services/ssl/myproject.test.mkcert-private.pem "*.myproject.test" myproject.test localhost 127.0.0.1 ::1
```
