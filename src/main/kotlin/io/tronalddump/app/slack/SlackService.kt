package io.tronalddump.app.slack

import org.springframework.beans.factory.annotation.Autowired
import org.springframework.beans.factory.annotation.Value
import org.springframework.http.*
import org.springframework.stereotype.Service
import org.springframework.util.LinkedMultiValueMap
import org.springframework.util.MultiValueMap
import org.springframework.web.client.RestTemplate
import org.springframework.web.util.UriComponents
import org.springframework.web.util.UriComponentsBuilder


@Service
class SlackService(
        @Value("\${slack.oauth.client_id}")
        private val clientId: String,

        @Value("\${slack.oauth.client_secret}")
        private val clientSecret: String,

        @Value("\${slack.oauth.redirect_uri}")
        private val redirectUrl: String,

        @Autowired
        private val restTemplate: RestTemplate
) {

    fun authorizeUri(): UriComponents {
        //@see  https://api.slack.com/docs/oauth-scopes
        val urlQueryParams: MultiValueMap<String, String> = LinkedMultiValueMap()
        urlQueryParams["client_id"] = clientId
        urlQueryParams["redirect_uri"] = redirectUrl
        urlQueryParams["scope"] = "commands"

        return UriComponentsBuilder
                .newInstance()
                .scheme("https")
                .host("slack.com")
                .path("/oauth/v2/authorize/")
                .queryParams(urlQueryParams)
                .build()
                .encode()
    }

    fun requestAccessToken(code: String): AccessToken? {
        val headers = HttpHeaders()
        headers.add(HttpHeaders.ACCEPT, MediaType.APPLICATION_JSON_VALUE)
        headers.add(HttpHeaders.CONTENT_TYPE, MediaType.APPLICATION_FORM_URLENCODED_VALUE)

        val map: MultiValueMap<String, String> = LinkedMultiValueMap()
        map.add("client_id", clientId)
        map.add("client_secret", clientSecret)
        map.add("code", code)
        map.add("redirect_uri", redirectUrl)

        val responseEntity: ResponseEntity<AccessToken> = restTemplate.exchange(
                "https://slack.com/api/oauth.v2.access",
                HttpMethod.POST,
                HttpEntity(map, headers),
                AccessToken::class.java
        )

        return responseEntity.body
    }
}

