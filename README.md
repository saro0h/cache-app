
## Without any strategy of cache

=> see commit: http://bit.ly/1wqwvbe)

![capture d ecran 2014-10-19 a 21 37 04](https://cloud.githubusercontent.com/assets/667519/4694433/db6f5dc4-57c8-11e4-92c3-1381eaa6d527.png)

----------------
----------------

## With the strategy of cache ``Cache-Control`` (private cache for client browser) with a ``max-age`` of 2 minutes (120 seconds)

- At the first refresh: ![capture d ecran 2014-10-19 a 21 56 02](https://cloud.githubusercontent.com/assets/667519/4694476/42202a20-57ca-11e4-90c2-108c42d90ced.png)
**Notice the "private" mention.**

- Calling the page again (don't do a force refresh, otherwise, your browser will bypass its cache): ![capture d ecran 2014-10-19 a 21 56 35](https://cloud.githubusercontent.com/assets/667519/4694489/d00f34ca-57ca-11e4-80bd-692e0ac3c3cf.png)
**Notice that the server is not hit at all.**

=>  see commit: http://bit.ly/1prET40

----------------
----------------

## With the strategy of cache ``Cache-Control`` (public cache for Reverse Proxy / Gateway cache) with a ``s-maxage`` of 1 minute (60 seconds)

- At the first refresh: ![capture d ecran 2014-10-19 a 22 10 31](https://cloud.githubusercontent.com/assets/667519/4694544/8c2f97f6-57cd-11e4-8b9b-7b16f8a225a8.png)

- Calling the page again (**60 seconds after the first call**): ![capture d ecran 2014-10-19 a 22 13 13](https://cloud.githubusercontent.com/assets/667519/4694546/9ee7e07e-57cd-11e4-834a-c467f265a332.png)

=> see commit: http://bit.ly/1vQHz2C 

----------------
----------------

## With an esi

- At the first refresh: ![capture d ecran 2014-10-19 a 22 29 37](https://cloud.githubusercontent.com/assets/667519/4694625/379acaaa-57d0-11e4-9f4c-805e617b7ae6.png)
We can see that both the page ``/`` and the fragment have a stale cache.

- At the second refresh (less than 20 seconds after the first one):
![capture d ecran 2014-10-19 a 22 30 02](https://cloud.githubusercontent.com/assets/667519/4694643/a928fb10-57d0-11e4-8e75-5ed8f77f80ce.png)
We can see that both the page ``/`` and the fragment have a fresh cache (no need to regenerate the response).

- A the third refresh (35 seconds after the first refresh): ![capture d ecran 2014-10-19 a 22 35 17](https://cloud.githubusercontent.com/assets/667519/4694653/2780bfe8-57d1-11e4-8f02-596f554a0a1f.png)
We can see that only the cached fragment is stale, time to regenerate a new response.

**Nota Bene**: You can take a look at the Symfony Profiler to notice the second request for the ESI (``_fragmentxxxx`` 
![capture d ecran 2014-10-19 a 22 32 39](https://cloud.githubusercontent.com/assets/667519/4694690/72df2f78-57d2-11e4-9b08-c291160feb98.png)).

=> see commit: http://bit.ly/1wial91

Now you can have fun with all of that !

----------------
----------------

You may have notice that you can access the fragment directly from its url. Not that good uh?

Let's secure it with this, in the file ``security.yml`` :
``
  access_control:
    - { path: ^/_fragment, roles: IS_AUTHENTICATED_ANONYMOUSLY, ip: 127.0.0.1 }
    - { path: ^/_fragment, roles: ROLE_ESI }
``

Basically, if the client ip is 127.0.0.1 (the ip of your reverse proxy cache), the fragment is accessible, that way your RPC can get the missing fragment to replace the ``<esi:include src="xxx">`` properly, otherwise, it is accessible by the user having the role ``ROLE_ESI`` (which nobody has so it's not accessible).
