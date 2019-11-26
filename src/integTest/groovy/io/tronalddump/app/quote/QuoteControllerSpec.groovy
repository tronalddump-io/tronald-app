package io.tronalddump.app.quote

import io.tronalddump.app.exception.EntityNotFoundException
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.boot.test.context.SpringBootTest
import org.springframework.http.MediaType
import spock.lang.Specification
import spock.lang.Subject

import java.sql.Timestamp

@SpringBootTest
class QuoteControllerSpec extends Specification {

    @Autowired
    @Subject
    QuoteController controller

    def 'should find "QuoteEntity" by id'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def id = 'G8SKzX6lRaWPc7E3s__HrA'

        when:
        def response = controller.findById(acceptHeader, id)

        then:
        response.content.appearedAt == Timestamp.valueOf('2015-12-18 18:25:35.000000')
        response.content.author.name == 'Donald Trump'
        response.content.quoteId == id
        response.content.createdAt == Timestamp.valueOf('2019-11-22 19:08:28.930308')
        response.content.source.quoteSourceId == 'RVlMuL1VT1-q8t9j9z0Y5w'
        response.content.tags.stream().map({ it.value }).collect() == ['Jeb Bush']
        response.content.value == 'I have an idea for Jeb Bush whose campaign is a disaster. Try using your last name & don’t be ashamed of it!'
    }

    def 'should report an error if "QuoteEntity" does not exist'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def id = 'does-not-exist'

        when:
        controller.findById(acceptHeader, id)

        then:
        def exception = thrown(EntityNotFoundException)
        exception.message == 'Quote with id "does-not-exist" not found.'
    }
}
