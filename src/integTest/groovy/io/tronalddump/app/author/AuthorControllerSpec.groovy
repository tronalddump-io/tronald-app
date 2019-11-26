package io.tronalddump.app.author

import io.tronalddump.app.exception.EntityNotFoundException
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.boot.test.context.SpringBootTest
import org.springframework.http.MediaType
import spock.lang.Specification
import spock.lang.Subject

@SpringBootTest
class AuthorControllerSpec extends Specification {

    @Autowired
    @Subject
    AuthorController controller

    def 'should find "AuthorEntity" by id'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def id = 'wVE8Y7BoRKCBkxs1JkqAvw'

        when:
        def response = controller.findById(acceptHeader, id)

        then:
        response.content.authorId == id
        response.content.name == 'Donald Trump'
    }

    def 'should report an error if "AuthorEntity" does not exist'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def id = 'does-not-exist'

        when:
        controller.findById(acceptHeader, id)

        then:
        def exception = thrown(EntityNotFoundException)
        exception.message == 'Author with id "does-not-exist" not found.'
    }
}
