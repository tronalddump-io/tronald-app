package io.tronalddump.app.quote_source

import io.tronalddump.app.exception.EntityNotFoundException
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.boot.test.context.SpringBootTest
import org.springframework.http.MediaType
import spock.lang.Specification
import spock.lang.Subject

import java.sql.Timestamp

@SpringBootTest
class QuoteSourceControllerSpec extends Specification {

    @Autowired
    @Subject
    QuoteSourceController controller

    def 'should find "QuoteSourceEntity" by id'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def id = 'C7OOYDFtQeu63LTszdKpGQ'

        when:
        def response = controller.findById(acceptHeader, id)

        then:
        response.createdAt != null
        response.filename == null
        response.quoteSourceId == id
        response.remarks == null
        response.updatedAt != null
        response.url == 'http://twitter.com/realDonaldTrump/status/689429603614502913'
    }

    def 'should report an error if "QuoteSourceEntity" does not exist'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def id = 'does-not-exist'

        when:
        controller.findById(acceptHeader, id)

        then:
        def exception = thrown(EntityNotFoundException)
        exception.message == 'QuoteSource with id "does-not-exist" not found.'
    }
}
