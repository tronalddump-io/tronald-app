[![CircleCI](https://circleci.com/gh/tronalddump-io/tronald-app/tree/master.svg?style=svg)](https://circleci.com/gh/tronalddump-io/tronald-app/tree/master)
[![Donate to this project using patreon.com](https://img.shields.io/badge/patreon-donate-yellow.svg)](https://www.patreon.com/matchilling)

# TRONALDDUMP.IO

Api & web archive for the dumbest things Donald Trump has ever said.

## Usage

```shell
# Retrieve a random quote
$ curl --request GET \
       --url 'https://api.tronalddump.io/random/quote' \
       --header 'Accept: application/hal+json'
```

Example quote response:
```json
{
  "appeared_at": "2015-07-28T16:33:50.000Z",
  "created_at": "2019-12-13T16:59:09.774Z",
  "quote_id": "FA4GQR8WSWqy-96ZWa6aKQ",
  "tags": [
    "President Obama"
  ],
  "updated_at": "2019-12-13T17:26:27.045Z",
  "value": "My Fox News int. with Sean Hannity on Obama being all talk & no action & making America Great Again! http://t.co/RqP6MNnR7h",
  "_embedded": {
    "author": [
      {
        "author_id": "wVE8Y7BoRKCBkxs1JkqAvw",
        "bio": null,
        "created_at": "2019-12-13T16:43:24.728Z",
        "name": "Donald Trump",
        "slug": "donald-trump",
        "updated_at": "2019-12-13T16:43:24.728Z",
        "_links": {
          "self": {
            "href": "https://api.tronalddump.io/author/wVE8Y7BoRKCBkxs1JkqAvw"
          }
        }
      }
    ],
    "source": [
      {
        "created_at": "2019-12-13T16:53:40.290Z",
        "filename": null,
        "quote_source_id": "fPp2is_VRE-XVQlsMfzuMw",
        "remarks": null,
        "updated_at": "2019-12-13T16:53:40.290Z",
        "url": "https://twitter.com/realDonaldTrump/status/626068052891836416",
        "_links": {
          "self": {
            "href": "https://api.tronalddump.io/quote-source/fPp2is_VRE-XVQlsMfzuMw"
          }
        }
      }
    ]
  },
  "_links": {
    "self": {
      "href": "https://api.tronalddump.io/quote/FA4GQR8WSWqy-96ZWa6aKQ"
    }
  }
}
```

The application supports HAL through JSON, a simple format for a consistent and
easy way to hyperlink between resources which improves discoverability. Watch
out for the `_links` property within the response body and follow the embedded
links.

For more examples check the [OpenApi documentation](https://api.tronalddump.io/documentation)
and have a look at the [Postman collection](./postman/io.tronalddump.postman_collection.json
and read the [docs](https://docs.tronalddump.io/).

## License

This distribution is covered by the **GNU GENERAL PUBLIC LICENSE**, Version 3, 29 June 2007.

## Support & Contact

Having trouble with this repository? Check out the documentation at the repository's site or contact m@matchilling.com and we’ll help you sort it out.

Happy Coding

:v: