package io.tronalddump.app.search

import io.tronalddump.app.BaseSpecification
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.http.MediaType
import spock.lang.Subject

class SearchControllerSpec extends BaseSpecification {

    @Autowired
    @Subject
    SearchController controller

    def 'should find "PageModel" by query'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def pageNumber = 0
        def query = 'Hillary'

        when:
        def response = controller.quote(acceptHeader, query, pageNumber)

        then:
        response.count == 10
        response.embedded != null
        response.links != null
        response.total == 35

        response.getLink("self").get().getHref() == 'http://localhost/search/quote?query=Hillary&page=0'
        response.getLink("first").get().getHref() == 'http://localhost/search/quote?query=Hillary&page=0'
        response.getLink("prev").get().getHref() == 'http://localhost/search/quote?query=Hillary&page=0'
        response.getLink("next").get().getHref() == 'http://localhost/search/quote?query=Hillary&page=1'
        response.getLink("last").get().getHref() == 'http://localhost/search/quote?query=Hillary&page=4'
    }
}
