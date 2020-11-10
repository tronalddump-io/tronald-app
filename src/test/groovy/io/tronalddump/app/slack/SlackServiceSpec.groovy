package io.tronalddump.app.slack

import org.springframework.http.*
import org.springframework.util.LinkedMultiValueMap
import org.springframework.web.client.RestTemplate
import spock.lang.Specification
import spock.lang.Subject

class SlackServiceSpec extends Specification {

    def clientId = '11111111111.222222222222'
    def clientSecret = 'my-super-client-secret'
    def redirectUrl = 'http://localhost:8080/connect/slack'
    def restTemplate = Mock(RestTemplate)

    @Subject
    SlackService slackService = new SlackService(
            clientId,
            clientSecret,
            redirectUrl,
            restTemplate
    )

    def 'should return the authorize uri'() {
        expect:
        slackService.authorizeUri().toUriString() == 'https://slack.com/oauth/v2/authorize/?client_id=11111111111.222222222222&redirect_uri=http://localhost:8080/connect/slack&scope=commands'
    }

    def 'should return an access token'() {
        given:
        def code = 'my-client-code'

        when:
        def accessToken = new AccessToken()
        def headers = new HttpHeaders()
        headers.add(HttpHeaders.ACCEPT, MediaType.APPLICATION_JSON_VALUE)
        headers.add(HttpHeaders.CONTENT_TYPE, MediaType.APPLICATION_FORM_URLENCODED_VALUE)

        def map = new LinkedMultiValueMap<>()
        map.add("client_id", clientId)
        map.add("client_secret", clientSecret)
        map.add("code", code)
        map.add("redirect_uri", redirectUrl)

        1 * restTemplate.exchange(
                "https://slack.com/api/oauth.access",
                HttpMethod.POST,
                new HttpEntity(map, headers),
                AccessToken.class
        ) >> new ResponseEntity(accessToken, HttpStatus.OK)

        then:
        slackService.requestAccessToken(code) == accessToken
    }
}
