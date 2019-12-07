package io.tronalddump.app.slack

import org.springframework.http.HttpStatus
import spock.lang.Specification
import spock.lang.Subject

class SlackControllerSpec extends Specification {

    @Subject
    SlackController subject
    SlackService slackService = Mock()

    def setup() {
        subject = new SlackController(slackService)
    }

    def 'should return a ModelAndView with an error'() {
        given:
        def code = ''
        def error = 'Oops'

        when:
        1 * slackService.authorizeUri()

        then:
        def response = subject.connect(code, error)
        response.model.authorizeUri == null
        response.status == HttpStatus.UNAUTHORIZED
        response.viewName == 'slack/error'
    }

    def 'should return a ModelAndView with an access token'() {
        given:
        def code = 'secret-code'
        def error = ''

        when:
        1 * slackService.requestAccessToken(code)

        then:
        def response = subject.connect(code, error)
        response.model.accessToken == null
        response.viewName == 'slack/success'
    }

    def 'should return a ModelAndView and an authorizeUri'() {
        given:
        def code = ''
        def error = ''

        when:
        1 * slackService.authorizeUri()

        then:
        def response = subject.connect(code, error)
        response.model.authorizeUri == null
        response.viewName == 'slack/connect'
    }
}
