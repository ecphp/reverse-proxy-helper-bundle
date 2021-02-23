# EC Reverse Proxy Bundle

At European Commission, many applications are behind a reverse proxy server.

If the reverse proxy configuration is not properly set, Symfony applications
will generate invalid URIs because they will use their internal server URIs
instead of the one given by the reverse proxy.

For some reason, the reverse proxy services at European Commission doesn't send
all the necessary headers to let Symfony generate proper URIs.

This bundle will make sure that the URIs are properly generated by providing the
missing headers and set the appropriate trusted proxies configuration.

## Installation

Edit `composer.json` and make sure to have:

```json
  "minimum-stability": "dev",
  "prefer-stable": true,
  "repositories": [
      {
          "type": "git",
          "url": "https://citnet.tech.ec.europa.eu/CITnet/stash/scm/ecphp/ec-reverse-proxy-bundle.git"
      }
  ]
```

Then do:

```shell
    composer require "ecphp/ec-reverse-proxy-bundle:dev-master"
```

Then, in your Symfony application, create a new configuration file `ec-reverse-proxy.yaml` in `config/packages`:

```yaml
    ec_reverse_proxy:
        base_url: https://webgate.ec.europa.eu:12345/app1/
```

You may also move this file in a specific environment if needed, and modulate the configuration based on the
environment.

You can also use environment variables as well:

Update your configuration file as such to use an environment variable `REVERSE_PROXY_URL`:

```yaml
    ec_reverse_proxy:
        base_url: '%env(resolve:REVERSE_PROXY_URL)%'
```

## What does this do?

This bundle ensures that proper headers are set for HTTP requests behind a reverse proxy.

Setting a trusted proxy and proper headers allows for correct URL generation, redirecting,
session handling and logging in Symfony when behind a reverse proxy.

This is useful if your web servers sit behind a load balancer (*Nginx, HAProxy, Envoy, ELB/ALB, etc*),
HTTP cache (*CloudFlare, Squid, Varnish, etc*), or other intermediary (*reverse*) proxy.

## How does this work?

Applications behind a reverse proxy typically read some HTTP headers such as `X-Forwarded-*`, (`X-Forwarded-For`, `X-Forwarded-Proto`, `X-Forwared-Host`, `X-Forwarded-Port`, `X-Forwarded-Prefix`)
to know about the real end-client making an HTTP request.

> If those headers were not set, then the application code would think every
> incoming HTTP request would be from the proxy.

The Symfony HTTP base classes have a concept of a "trusted proxy", where those `X-Forwarded-*`
headers will only be used if the source IP address of the request is known.

This package creates an easier interface to that option. You can set the IP addresses of the proxies
(that the application would see, so it may be a private network IP address), and the Symfony HTTP classes will know to
use the `X-Forwarded-*` headers if an HTTP request containing those headers was from the trusted proxy.

## Why does this matter?

A very common load balancing approach is to send `https://` requests to a load balancer, but send `http://` requests to the application servers behind the load balancer.

For example, you may send a request in your browser to `https://example.org`. The load balancer, in turn, might send requests to an application server at `http://192.168.1.23`.

What if that server returns a redirect, or generates an asset url? The users's browser would get back a redirect or HTML that includes `http://192.168.1.23` in it, which is clearly wrong.

What happens is that the application thinks its hostname is `192.168.1.23` and the schema is `http://`. It doesn't know that the end client used `https://example.org` for its web request.

So the application needs to know to read the `X-Forwarded` headers to get the correct request details
(schema `https://`, host `example.org`).

Symfony is able to reads those headers, but only if the trusted proxy configuration is correctly set to "trust" the load balancer/reverse proxy.

## Open Source Contributions

* symfony/framework-bundle: [#40281][http pr 40281]


[http pr 40281]: https://github.com/symfony/symfony/pull/40281
