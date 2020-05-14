package io.tronalddump.app.slack

import io.tronalddump.app.quote.QuoteEntity
import io.tronalddump.app.quote.QuoteRepository
import org.springframework.data.domain.PageImpl
import org.springframework.data.domain.PageRequest
import org.springframework.http.HttpStatus
import spock.lang.Specification
import spock.lang.Subject
import spock.lang.Unroll

class SlackControllerSpec extends Specification {

    @Subject
    SlackController subject
    QuoteRepository quoteRepository = Mock()
    SlackService slackService = Mock()

    def setup() {
        subject = new SlackController(quoteRepository, slackService)
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

    @Unroll
    def "should return a SlackCommandResponse for command '#command"() {
        given:
        SlackCommandRequest request = new SlackCommandRequest(text: command)

        when:
        quoteRepository.randomQuote() >> Optional.of(
                new QuoteEntity(quoteId: "dGV8JJVAQQ-ElmiYH1_h8g", value: "A random quote")
        )

        then:
        def response = subject.command(request)
        response instanceof SlackCommandResponse == true
        response.attachments == attachments
        response.mrkdwn == mrkdwn
        response.responseType == type
        response.text == text

        where:
        command || text                    || type                         || mrkdwn || attachments
        ''      || null                    || SlackResponseType.IN_CHANNEL || true   || [new SlackCommandResponseAttachment("A random quote", ["text"], "A random quote", "[permalink]", "https://www.tronalddump.io/quote/dGV8JJVAQQ-ElmiYH1_h8g")]
        'help'  || '*Available commands:*' || SlackResponseType.EPHEMERAL  || true   || [new SlackCommandResponseAttachment(null, ["text"], "Type `/tronald` to get a random quote.", "Get random Tronald trash", null), new SlackCommandResponseAttachment(null, ["text"], "Type `/tronald ? {search_term}` to search The Tronald's dump.", "Free text search", null), new SlackCommandResponseAttachment(null, ["text"], "Type `/tronald help` to display this help context.", "Help", null)]
    }

    def "should return a SlackCommandResponse for a search command if there are no results"() {
        given:
        SlackCommandRequest request = new SlackCommandRequest(text: "? poop")

        when:
        quoteRepository.findByValueIgnoreCaseContaining(*_) >> new PageImpl<QuoteEntity>([])

        then:
        def response = subject.command(request)
        response.attachments == []
        response.mrkdwn == true
        response.responseType == SlackResponseType.EPHEMERAL
        response.text == "Your search for *`poop`* did not match any quote ¯\\_(ツ)_/¯. Make sure that all words are spelled correctly. Try different keywords. Try more general keywords."
    }

    def "should return a SlackCommandResponse for a search command - first page"() {
        given:
        SlackCommandRequest request = new SlackCommandRequest(text: "? Hillary")

        when:
        quoteRepository.findByValueIgnoreCaseContaining(*_) >> new PageImpl<QuoteEntity>(
                [
                        new QuoteEntity(quoteId: "dGV8JJVAQQ-ElmiYH1_h8g", value: "A random quote"),
                        new QuoteEntity(quoteId: "dGV8JJVAQQ-ElmiYH1_h8g", value: "A random quote"),
                        new QuoteEntity(quoteId: "dGV8JJVAQQ-ElmiYH1_h8g", value: "A random quote"),
                        new QuoteEntity(quoteId: "dGV8JJVAQQ-ElmiYH1_h8g", value: "A random quote"),
                        new QuoteEntity(quoteId: "dGV8JJVAQQ-ElmiYH1_h8g", value: "A random quote")
                ],
                PageRequest.of(0, 5),
                6L
        )

        then:
        def response = subject.command(request)
        response.attachments == [
                new SlackCommandResponseAttachment("A random quote", ["text"], "A random quote", "(1)", "https://www.tronalddump.io/quote/dGV8JJVAQQ-ElmiYH1_h8g"),
                new SlackCommandResponseAttachment("A random quote", ["text"], "A random quote", "(2)", "https://www.tronalddump.io/quote/dGV8JJVAQQ-ElmiYH1_h8g"),
                new SlackCommandResponseAttachment("A random quote", ["text"], "A random quote", "(3)", "https://www.tronalddump.io/quote/dGV8JJVAQQ-ElmiYH1_h8g"),
                new SlackCommandResponseAttachment("A random quote", ["text"], "A random quote", "(4)", "https://www.tronalddump.io/quote/dGV8JJVAQQ-ElmiYH1_h8g"),
                new SlackCommandResponseAttachment("A random quote", ["text"], "A random quote", "(5)", "https://www.tronalddump.io/quote/dGV8JJVAQQ-ElmiYH1_h8g")
        ]
        response.mrkdwn == true
        response.responseType == SlackResponseType.EPHEMERAL
        response.text == "*Search results 5 of 6*. Type `/tronald ? Hillary --page 2` to see more results."
    }

    def "should return a SlackCommandResponse for a search command - second page"() {
        given:
        SlackCommandRequest request = new SlackCommandRequest(text: "? Hillary --page 2")

        when:
        quoteRepository.findByValueIgnoreCaseContaining(*_) >> new PageImpl<QuoteEntity>([new QuoteEntity(quoteId: "dGV8JJVAQQ-ElmiYH1_h8g", value: "A random quote")], PageRequest.of(1, 5), 6L)

        then:
        def response = subject.command(request)
        response.attachments == [new SlackCommandResponseAttachment("A random quote", ["text"], "A random quote", "(6)", "https://www.tronalddump.io/quote/dGV8JJVAQQ-ElmiYH1_h8g")]
        response.mrkdwn == true
        response.responseType == SlackResponseType.EPHEMERAL
        response.text == "*Search results 6 of 6*."
    }
}
