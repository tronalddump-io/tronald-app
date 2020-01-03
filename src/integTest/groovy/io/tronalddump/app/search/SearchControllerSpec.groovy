package io.tronalddump.app.search

import io.tronalddump.app.BaseSpecification
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.http.MediaType
import org.springframework.web.server.ResponseStatusException
import spock.lang.Subject

class SearchControllerSpec extends BaseSpecification {

    @Autowired
    @Subject
    SearchController controller

    void 'should find "PageModel" by query'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def pageNumber = 0
        def query = 'Hillary'
        def tag = ''

        when:
        def response = controller.quote(acceptHeader, query, tag, pageNumber)

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

    void 'should find "PageModel" by tag'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def pageNumber = 0
        def query = ''
        def tag = 'Hillary Clinton'

        when:
        def response = controller.quote(acceptHeader, query, tag, pageNumber)

        then:
        response.count == 10
        response.embedded != null
        response.links != null
        response.total == 40

        response.getLink("self").get().getHref() == 'http://localhost/search/quote?tag=Hillary Clinton&page=0'
        response.getLink("first").get().getHref() == 'http://localhost/search/quote?tag=Hillary Clinton&page=0'
        response.getLink("prev").get().getHref() == 'http://localhost/search/quote?tag=Hillary Clinton&page=0'
        response.getLink("next").get().getHref() == 'http://localhost/search/quote?tag=Hillary Clinton&page=1'
        response.getLink("last").get().getHref() == 'http://localhost/search/quote?tag=Hillary Clinton&page=4'
    }

    void 'should report an error if no "tag" or "query" parameter was provided'() {
        given:
        def acceptHeader = MediaType.APPLICATION_JSON_VALUE
        def pageNumber = 0
        def query = ''
        def tag = ''

        when:
        controller.quote(acceptHeader, query, tag, pageNumber)

        then:
        def exception = thrown(ResponseStatusException)
        exception.message == '400 BAD_REQUEST "Either a query or tag parameter must be provided"'
    }
}
