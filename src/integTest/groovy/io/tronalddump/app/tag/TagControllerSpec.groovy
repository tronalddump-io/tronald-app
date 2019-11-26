package io.tronalddump.app.tag

import io.tronalddump.app.exception.EntityNotFoundException
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.boot.test.context.SpringBootTest
import org.springframework.http.MediaType
import spock.lang.Specification
import spock.lang.Subject

import java.sql.Timestamp

@SpringBootTest
class TagControllerSpec extends Specification {

    @Autowired
    @Subject
    TagController controller

    def 'should find "TagEntity" by id'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def id = 'N6ETSqCxTRKs0LNvU3m-0a'

        when:
        def response = controller.findById(acceptHeader, id)

        then:
        response.content.createdAt == Timestamp.valueOf('2019-11-24 08:58:53.220419')
        response.content.tagId == id
        response.content.value == 'Hillary Clinton'
        response.content.updatedAt == Timestamp.valueOf('2019-11-24 08:58:53.220419')
    }

    def 'should report an error if "QuoteEntity" does not exist'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def id = 'does-not-exist'

        when:
        controller.findById(acceptHeader, id)

        then:
        def exception = thrown(EntityNotFoundException)
        exception.message == 'Tag with id "does-not-exist" not found.'
    }
}
