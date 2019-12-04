package io.tronalddump.app.slack

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
        slackService.authorizeUri().toUriString() == 'https://slack.com/oauth/authorize/?client_id=11111111111.222222222222&redirect_uri=http://localhost:8080/connect/slack&scope=commands'
    }
}
