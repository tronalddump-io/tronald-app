package io.tronalddump.app.tag

import io.tronalddump.app.BaseSpecification
import io.tronalddump.app.exception.EntityNotFoundException
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.boot.test.context.SpringBootTest
import org.springframework.http.MediaType
import spock.lang.Subject

@SpringBootTest
class TagControllerSpec extends BaseSpecification {

    @Autowired
    @Subject
    TagController controller

    def 'should find "TagEntity" by value'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def value = 'Hillary Clinton'

        when:
        def response = controller.findByValue(acceptHeader, value)

        then:
        response.createdAt != null
        response.value == value
        response.updatedAt != null
    }

    def 'should report an error if "TagEntity" does not exist'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def value = 'does-not-exist'

        when:
        controller.findByValue(acceptHeader, value)

        then:
        def exception = thrown(EntityNotFoundException)
        exception.message == 'Tag with value "does-not-exist" not found.'
    }

    def 'should find "PageModel" for all tags'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE

        when:
        def response = controller.findAll(acceptHeader)

        then:
        response.count == 28
        response.embedded != null
        response.total == 28
    }
}
