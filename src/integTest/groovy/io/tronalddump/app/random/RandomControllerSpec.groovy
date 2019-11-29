package io.tronalddump.app.random

import org.springframework.beans.factory.annotation.Autowired
import org.springframework.boot.test.context.SpringBootTest
import org.springframework.http.MediaType
import spock.lang.Specification
import spock.lang.Subject
import spock.lang.Unroll

@SpringBootTest
class RandomControllerSpec extends Specification {

    @Autowired
    @Subject
    RandomController controller

    @Unroll
    def 'should return a random "QuoteModel"'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE

        when:
        def response = controller.random(acceptHeader)

        then:
        response.appearedAt != null
        response.createdAt != null
        response.updatedAt != null
        response.quoteId != null
        response.tags.get(0) != null
        response.value != null
    }
}
