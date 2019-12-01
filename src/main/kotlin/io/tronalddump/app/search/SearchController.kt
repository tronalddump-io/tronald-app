package io.tronalddump.app.search

import io.tronalddump.app.Url
import io.tronalddump.app.quote.QuoteEntity
import io.tronalddump.app.quote.QuoteRepository
import org.springframework.data.domain.Page
import org.springframework.data.domain.PageRequest
import org.springframework.data.domain.Pageable
import org.springframework.hateoas.MediaTypes
import org.springframework.hateoas.server.mvc.WebMvcLinkBuilder
import org.springframework.hateoas.server.mvc.WebMvcLinkBuilder.linkTo
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.*


@RequestMapping(value = [Url.SEARCH])
@RestController
class SearchController(
        private val assembler: PageModelAssembler,
        private val repository: QuoteRepository
) {

    @ResponseBody
    @RequestMapping(
            headers = [
                "${HttpHeaders.ACCEPT}=${MediaType.APPLICATION_JSON_VALUE}",
                "${HttpHeaders.ACCEPT}=${MediaTypes.HAL_JSON_VALUE}"
            ],
            method = [RequestMethod.GET],
            produces = [MediaType.APPLICATION_JSON_VALUE],
            value = ["/quote"]
    )
    fun quote(
            @RequestHeader(HttpHeaders.ACCEPT) acceptHeader: String,
            @RequestParam("query") query: String,
            @RequestParam(value = "page", defaultValue = "0") pageNumber: Int
    ): PageModel {
        val page: Pageable = PageRequest.of(pageNumber, 10)
        val result: Page<QuoteEntity> = repository.findByValueContaining(query, page)
        val model: PageModel = assembler.toModel(result)

        val linkBuilder: WebMvcLinkBuilder = linkTo(this::class.java)
        model.add(linkBuilder.slash("quote/?query=${query}&page=${pageNumber}").withSelfRel())
        model.add(linkBuilder.slash("quote/?query=${query}&page=${page.first().pageNumber}").withRel("first"))
        model.add(linkBuilder.slash("quote/?query=${query}&page=${page.previousOrFirst().pageNumber}").withRel("prev"))
        model.add(linkBuilder.slash("quote/?query=${query}&page=${page.next().pageNumber}").withRel("next"))
        model.add(linkBuilder.slash("quote/?query=${query}&page=${result.totalPages}").withRel("last"))

        return model
    }
}
